<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class Page_eventoController extends Page_mainController
{
  public function detalleAction()
  {
    $id = $this->_getSanitizedParam("id");

    $eventoModel = new Administracion_Model_DbTable_Eventos();
    $evento = $eventoModel->getById($id);

    if (!$evento || !$evento->evento_id) {
      header('Location: /');
      exit;
    }

    $fechaActual = date('Y-m-d H:i:s');
    $boletaModel = new Administracion_Model_DbTable_Boletaevento();
    $boletasRaw = $boletaModel->getList("boleta_evento_evento = '$id' AND boleta_evento_fechalimite >= '$fechaActual'", "boleta_evento_precio ASC");

    $boletatipoModel = new Administracion_Model_DbTable_Boletatipo();
    $tiposRaw = $boletatipoModel->getList();
    $tipoMap = [];
    foreach ($tiposRaw as $t) {
      $tipoMap[$t->boleta_tipo_id] = $t->boleta_tipo_nombre;
    }

    $boletasData = [];
    foreach ($boletasRaw as $b) {
      $cantidad = (int) $b->boleta_evento_cantidad;
      $vendidas = (int) ($b->boleta_evento_cantidad_vendidas ?? 0);
      $disponibles = max(0, $cantidad - $vendidas);
      $boletasData[] = [
        'id' => (int) $b->boleta_evento_id,
        'tipo_nombre' => isset($tipoMap[$b->boleta_evento_tipo]) ? $tipoMap[$b->boleta_evento_tipo] : 'Boleta',
        'saldo' => (int) $b->boleta_evento_saldo,
        'disponibles' => $disponibles,
        'precio' => (float) $b->boleta_evento_precio,
        'precioadicional' => (float) $b->boleta_evento_precioreserva,
        'fechalimite' => $b->boleta_evento_fechalimite,
      ];
    }

    $sedeModel = new Administracion_Model_DbTable_Sedes();
    $sede = $sedeModel->getById($evento->evento_lugar);

    $vendedor = $this->_getSanitizedParam("vendedor");

    $this->_view->evento = $evento;
    $this->_view->sede = $sede;
    $this->_view->vendedor = $vendedor;
    $this->_view->boletasJson = json_encode($boletasData, JSON_UNESCAPED_UNICODE);

    $title = $evento->evento_nombre;
    $this->getLayout()->setTitle($title);
  }

  public function validarpromoAction()
  {
    $this->setLayout('blanco');
    header('Content-Type: application/json');

    $codigo = $this->_getSanitizedParam('codigo');
    $eventoId = (int) $this->_getSanitizedParam('evento_id');

    if (!$codigo || !$eventoId) {
      echo json_encode(['ok' => false, 'mensaje' => 'Datos incompletos.']);
      return;
    }

    $promoModel = new Administracion_Model_DbTable_Codigospromocionales();
    $resultados = $promoModel->getList(
      "codigo = '$codigo' AND activo = '1' AND (evento = '0' OR evento = '$eventoId')"
    );

    if (empty($resultados)) {
      echo json_encode(['ok' => false, 'mensaje' => 'Código no válido o no aplica para este evento.']);
      return;
    }

    $promo = $resultados[0];

    if ($promo->tipo === 'uso-unico') {
      if ($promo->usado == '1') {
        echo json_encode(['ok' => false, 'mensaje' => 'Este código ya fue utilizado.']);
        return;
      }
    } elseif ($promo->tipo === 'varios-usos') {
      $cantidadUsos = count($resultados) ?? 0;
      if ($promo->cantidad_usos_maxima !== null && (int) $cantidadUsos >= (int) $promo->cantidad_usos_maxima) {
        echo json_encode(['ok' => false, 'mensaje' => 'Este código ha alcanzado su límite de usos.']);
        return;
      }
    }

    echo json_encode([
      'ok' => true,
      'tipo' => $promo->tipo,
      'valor' => (float) $promo->valor,
      'porcentaje' => (float) $promo->porcentaje,
      'id' => (int) $promo->id,
      'mensaje' => 'Código aplicado correctamente.',
    ]);
  }

  public function generarpagoAction()
  {
    $this->setLayout('blanco');

    $eventoId = (int) $this->_getSanitizedParam('evento_id');
    $nombre = $this->_getSanitizedParam('nombre');
    $documento = $this->_getSanitizedParam('documento');
    $fechanac = $this->_getSanitizedParam('fechanacimiento');
    $email = $this->_getSanitizedParam('email');
    $vendedor = $this->_getSanitizedParam('vendedor');
    $codigo = $this->_getSanitizedParam('codigo');
    $totalFront = (float) $this->_getSanitizedParam('total');

    // boletas viene como JSON — se lee raw para no romper la estructura
    $boletasRaw = isset($_POST['boletas']) ? $_POST['boletas'] : '[]';
    $boletasSel = json_decode($boletasRaw, true);

    $redirectError = function ($msg) use ($eventoId) {
      $base = $eventoId ? '/page/evento/detalle?id=' . $eventoId : '/';
      header('Location: ' . $base . '&error=' . urlencode($msg));
      exit;
    };

    if (!$eventoId || !$nombre || !$documento || !$email || !$fechanac) {
      $redirectError('Completa todos los datos del comprador.');
    }

    if (!is_array($boletasSel) || empty($boletasSel)) {
      $redirectError('Selecciona al menos una boleta.');
    }

    $eventosModel = new Administracion_Model_DbTable_Eventos();
    $evento = $eventosModel->getById($eventoId);
    if (!$evento) {
      $redirectError('Evento no encontrado.');
    }

    $boletaeventoModel = new Administracion_Model_DbTable_Boletaevento();
    $totalCalculado = 0;
    $resumen = [];
    $cantidadTotal = 0;

    foreach ($boletasSel as $sel) {
      $bId = (int) ($sel['id'] ?? 0);
      $cantidad = (int) ($sel['cantidad'] ?? 0);
      if ($bId <= 0 || $cantidad <= 0)
        continue;
      if ($cantidad > 20) {
        $redirectError('Máximo 20 boletas por tipo.');
      }

      $boleta = $boletaeventoModel->getById($bId);
      if (!$boleta || (int) $boleta->boleta_evento_evento !== $eventoId) {
        $redirectError('Una de las boletas seleccionadas no es válida.');
      }

      $disponibles = (int) $boleta->boleta_evento_cantidad - (int) ($boleta->boleta_evento_cantidad_vendidas ?? 0);
      if ($cantidad > $disponibles) {
        $redirectError('Solo quedan ' . $disponibles . ' unidades disponibles de esa boleta.');
      }

      if ($evento->evento_tipo === 'reserva') {
        $precioUnit = (float) $boleta->boleta_evento_precioreserva;
        $precioReserva = 0;
      } elseif ($evento->evento_tipo === 'reservayboleteria') {
        $precioUnit = (float) $boleta->boleta_evento_precio;
        $precioReserva = (float) $boleta->boleta_evento_precioreserva;
      } else {
        $precioUnit = (float) $boleta->boleta_evento_precio;
        $precioReserva = 0;
      }

      $totalCalculado += ($precioUnit + $precioReserva) * $cantidad;
      $cantidadTotal += $cantidad;
      $resumen[] = ['boleta' => $boleta, 'cantidad' => $cantidad, 'precioUnit' => $precioUnit, 'precioReserva' => $precioReserva];
    }

    if (empty($resumen)) {
      $redirectError('Selecciona al menos una boleta.');
    }

    // Aplicar código promocional
    $descuento = 0;
    $promoId = null;
    if ($codigo) {
      $promoModel = new Administracion_Model_DbTable_Codigospromocionales();
      $promos = $promoModel->getList(
        "codigo = '$codigo' AND activo = '1' AND (evento = '0' OR evento = '$eventoId')"
      );
      if (!empty($promos)) {
        $promo = $promos[0];
        $valido = true;
        if ($promo->tipo === 'uso-unico') {
          if ($promo->usado == '1') {
            $valido = false;
          }
        } elseif ($promo->tipo === 'varios-usos') {
          $cantidadUsos = count($promos);
          if ($promo->cantidad_usos_maxima !== null && $cantidadUsos >= (int) $promo->cantidad_usos_maxima) {
            $valido = false;
          }
        }
        if ($valido) {
          $descuento = ((float) $promo->porcentaje > 0)
            ? round($totalCalculado * (float) $promo->porcentaje / 100)
            : (float) $promo->valor;
          $promoId = (int) $promo->id;
        }
      }
    }

    $totalFinal = max(0, $totalCalculado - $descuento);

    // Validar que el total del front coincida (tolerancia $1)
    if (abs($totalFinal - $totalFront) > 1) {
      $redirectError('El monto enviado no coincide con el calculado. Recarga la página e intenta de nuevo.');
    }


    $comprasModel = new Administracion_Model_DbTable_Compras();
    $dataCompra = [];
    $dataCompra['boleta_compra_evento'] = $eventoId;
    $dataCompra['boleta_compra_documento'] = $documento;
    $dataCompra['boleta_compra_nombre'] = $nombre;
    $dataCompra['boleta_compra_email'] = $email;
    $dataCompra['boleta_compra_fechanacimiento'] = $fechanac;
    $dataCompra['boleta_compra_fecha'] = date('Y-m-d H:i:s');
    $dataCompra['boleta_compra_codigo'] = $codigo;
    $dataCompra['boleta_compra_total'] = $totalFinal;
    $dataCompra['boleta_compra_vendedor'] = $vendedor;
    $idCompra = $comprasModel->insert($dataCompra);


    foreach ($resumen as $item) {
      $detalleData = [];
      $detalleData['detalle_compra'] = $idCompra;
      $detalleData['detalle_boleta'] = $item['boleta']->boleta_evento_id;
      $detalleData['detalle_cantidad'] = $item['cantidad'];
      $detalleData['detalle_precio_unit'] = $item['precioUnit'];
      $detalleData['detalle_precio_reserva'] = $item['precioReserva'];
      $detalleData['detalle_subtotal'] = ($item['precioUnit'] + $item['precioReserva']) * $item['cantidad'];

      $compradetalleModel = new Administracion_Model_DbTable_Compradetalle();
      $compradetalleModel->insert($detalleData);
    }

    $descripcion = 'Compra ' . $cantidadTotal . ' boleta(s) — ' . $evento->evento_nombre;
    $epayco = Epayco::getInstance()->get();
    $apiBase = rtrim($epayco['url_apify'], '/');

    // 1. Autenticación Apify
    $authHeader = 'Basic ' . base64_encode($epayco['PUBLIC_KEY'] . ':' . $epayco['PRIVATE_KEY']);
    $ch = curl_init($apiBase . '/login');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . $authHeader],
      CURLOPT_POSTFIELDS => '{}',
      CURLOPT_TIMEOUT => 15,
    ]);
    $loginResp = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $jwtToken = $loginResp['token'] ?? null;
    if (!$jwtToken) {
      $redirectError('Error al conectar con la pasarela de pago. Intenta de nuevo.');
    }

    // 2. Crear sesión de pago
    $sessionBody = json_encode([
      'checkout_version' => '2',
      'name' => 'Galería Café Libro',
      'currency' => 'COP',
      'amount' => $totalFinal,
      'description' => $descripcion,
      'lang' => 'ES',
      'invoice' => (string) $idCompra,
      'country' => 'CO',
      'response' => $epayco['responseUrl'],
      'confirmation' => $epayco['confirmationUrl'],
      'billing' => [
        'email' => $email,
        'name' => $nombre,
        'address' => '',
        'typeDoc' => 'CC',
        'numberDoc' => $documento,
        'callingCode' => '+57',
        'mobilePhone' => '',
      ],
      'extras' => [
        'extra1' => (string) $idCompra,
        'extra2' => (string) $cantidadTotal,
        'extra3' => $promoId ? (string) $promoId : '',
        'extra4' => $codigo ?? '',
      ],
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init($apiBase . '/payment/session/create');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $jwtToken],
      CURLOPT_POSTFIELDS => $sessionBody,
      CURLOPT_TIMEOUT => 15,
    ]);
    $sessionResp = json_decode(curl_exec($ch), true);
    curl_close($ch);

    $sessionId = $sessionResp['data']['sessionId'] ?? null;
    if (!$sessionId) {
      $redirectError('No se pudo iniciar la sesión de pago. Intenta de nuevo.');
    }

    $this->_view->sessionId = $sessionId;
    $this->_view->epaycoTest = true; // cambiar a false en producción
  }

  public function states()
  {
    $array = array();
    $array['1'] = 'Aceptada';
    $array['2'] = 'Rechazada';
    $array['3'] = 'Pendiente';
    $array['4'] = 'Fallida';
    $array['6'] = 'Reversada';
    $array['7'] = 'Retenida';
    $array['8'] = 'Iniciada';
    $array['9'] = 'Caducada';
    $array['10'] = 'Abandonada';
    $array['11'] = 'Cancelada';
    return $array;
  }

  public function leerpdfAction()
  {
    $this->setLayout('blanco');
    if (isset($_GET['token'])) {
      $token = $this->_getSanitizedParam("token");

      $ruta = $this->decryptString($token);

      if ($ruta && file_exists($ruta)) {
        header("Content-Type: application/pdf");
        readfile($ruta);
        exit;
      } else {
        echo "❌ Archivo no encontrado o token inválido.";
        exit;
      }
    } else {
      echo "❌ Token no proporcionado.";
      exit;
    }
  }
  function decryptString($encryptedString, $key = 'omegagaleria2025')
  {
    $data = base64_decode($encryptedString);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
  }

  public function respuestaAction()
  {
    $ref = $this->_getSanitizedParam('ref_payco');

    $response = json_decode(file_get_contents('https://secure.epayco.co/validation/v1/reference/' . $ref));
    $data = array();
    $data['log_log'] = print_r($response, true);
    $data['log_tipo'] = 'EPAYCO RESPUESTA';
    $logModel = new Administracion_Model_DbTable_Log();
    //$logModel->insert($data);

    $this->_view->response = $response->data;
    $this->_view->list_states = $this->states();
  }

  public function respuesta2Action()
  {
    $this->setLayout('blanco');

    $inputJSON = file_get_contents('php://input');
    $response = json_decode($inputJSON, true);

    $data = array();
    $data['log_log'] = preg_replace('/[^\x09\x0A\x0D\x20-\x7E]+/', '', print_r($response, true));
    $data['log_tipo'] = 'EPAYCO RESPUESTA';
    $logModel = new Administracion_Model_DbTable_Log();
    $logModel->insert($data);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'mensaje' => 'Datos recibidos']);
  }
  public function confirmacionAction()
  {
    ini_set("memory_limit", "-1");


    $this->setLayout('blanco');
    http_response_code(200);
    header("HTTP/1.1 200 OK");
    header('Status: 200');

    $epayco = Epayco::getInstance()->get();
    $p_cust_id_cliente = $epayco['P_CUST_ID_CLIENTE'];
    $p_key = $epayco['P_KEY'];

    $x_ref_payco = $_REQUEST['x_ref_payco'] ?? '';
    $x_transaction_id = $_REQUEST['x_transaction_id'] ?? '';
    $x_amount = $_REQUEST['x_amount'] ?? '';
    $x_currency_code = $_REQUEST['x_currency_code'] ?? '';
    $x_signature = $_REQUEST['x_signature'] ?? '';

    $data2 = array();
    $data2['log_tipo'] = 'EPAYCO CONFIRMACION INICIO';

    $signature = hash('sha256', $p_cust_id_cliente . '^' . $p_key . '^' . $x_ref_payco . '^' . $x_transaction_id . '^' . $x_amount . '^' . $x_currency_code);
    $comprasModel = new Administracion_Model_DbTable_Compras;
    $boletaseventoModel = new Administracion_Model_DbTable_Boletaevento();
    $compraDetalleModel = new Administracion_Model_DbTable_Compradetalle();
    //Validamos la firma
    if ($x_signature == $signature) {

      $x_cod_response = $_REQUEST['x_cod_response'];
      switch ((int) $x_cod_response) {
        case 1:
          # code transacción aceptada
          $data2['log_tipo'] = 'EPAYCO CONFIRMACION ACEPTADA';


          $idCompra = (int) ($_REQUEST['x_extra1'] ?? 0);

          $respuesta = $_REQUEST['x_transaction_state'] ?? '';
          $estado = $_REQUEST['x_cod_transaction_state'] ?? '';
          $estadoTx = $_REQUEST['x_transaction_state'] ?? '';
          $franquicia = $_REQUEST['x_bank_name'] ?? '';

          if ($_REQUEST['x_cod_transaction_state'] == 1) {


            $compraExistente = $comprasModel->getById($idCompra);
            $yaConfirmada = $compraExistente && $compraExistente->boleta_compra_validacion === '1';

            $comprasModel->updateConfirmacion($respuesta, $estado, $estadoTx, $idCompra, $franquicia);

            if (!$yaConfirmada) {
              $boletasCompra = $compraDetalleModel->getList("detalle_compra = '$idCompra'");
              foreach ($boletasCompra as $boletacompra) {
                $boleta = $boletaseventoModel->getById($boletacompra->detalle_boleta);

                $cantidadComprada = (int) $boletacompra->detalle_cantidad;
                $cantidadVendida = (int) ($boleta->boleta_evento_cantidad_vendidas ?? 0);
                $boletaseventoModel->editField($boleta->boleta_evento_id, "boleta_evento_cantidad_vendidas", $cantidadVendida + $cantidadComprada);
              }

              $codigo = $_REQUEST['x_extra4'] ?? '';
              if ($codigo !== '') {
                $codigoModel = new Administracion_Model_DbTable_Codigospromocionales();
                $lista = $codigoModel->getList(" codigo='$codigo' ", "");
                if (!empty($lista)) {
                  $codigo_id = $lista[0]->id;
                  $codigoModel->editField($codigo_id, "usado", "1");
                  $codigoModel->editField($codigo_id, "fecha_uso", date("Y-m-d H:i:s"));
                }
              }
            }
          }

          //echo "transacción aceptada";
          break;
        case 2:
          # code transacción rechazada
          $data2['log_tipo'] = 'EPAYCO CONFIRMACION RECHAZADA';

          $id = (int) ($_REQUEST['x_extra1'] ?? 0);
          $respuesta = $_REQUEST['x_transaction_state'] ?? '';
          $estado = $_REQUEST['x_cod_transaction_state'] ?? '';
          $estadoTx = $_REQUEST['x_transaction_state'] ?? '';
          $franquicia = $_REQUEST['x_bank_name'] ?? '';

          $comprasModel->updateConfirmacion($respuesta, $estado, $estadoTx, $id, $franquicia);

          //echo "transacción rechazada";
          break;
        case 3:
          # code transacción pendiente
          $data2['log_tipo'] = 'EPAYCO CONFIRMACION PENDIENTE';

          $id = (int) ($_REQUEST['x_extra1'] ?? 0);
          $respuesta = $_REQUEST['x_transaction_state'] ?? '';
          $estado = $_REQUEST['x_cod_transaction_state'] ?? '';
          $estadoTx = $_REQUEST['x_transaction_state'] ?? '';
          $franquicia = $_REQUEST['x_bank_name'] ?? '';

          $comprasModel->updateConfirmacion($respuesta, $estado, $estadoTx, $id, $franquicia);

          //echo "transacción pendiente";
          break;
        case 4:
          # code transacción fallida
          $data2['log_tipo'] = 'EPAYCO CONFIRMACION FALLIDA';

          $id = (int) ($_REQUEST['x_extra1'] ?? 0);
          $respuesta = $_REQUEST['x_transaction_state'] ?? '';
          $estado = $_REQUEST['x_cod_transaction_state'] ?? '';
          $estadoTx = $_REQUEST['x_transaction_state'] ?? '';
          $franquicia = $_REQUEST['x_bank_name'] ?? '';

          $comprasModel->updateConfirmacion($respuesta, $estado, $estadoTx, $id, $franquicia);

          //echo "transacción fallida";
          break;
        case 6:
          # transacción reversada — deshacer lo hecho en case 1
          $data2['log_tipo'] = 'EPAYCO CONFIRMACION REVERSADA';

          $idCompra = (int) ($_REQUEST['x_extra1'] ?? 0);
          $respuesta = $_REQUEST['x_transaction_state'] ?? '';
          $estado = $_REQUEST['x_cod_transaction_state'] ?? '';
          $estadoTx = $_REQUEST['x_transaction_state'] ?? '';
          $franquicia = $_REQUEST['x_bank_name'] ?? '';

          $compraExistente = $comprasModel->getById($idCompra);
          $estabaConfirmada = $compraExistente && $compraExistente->boleta_compra_validacion === '1';

          $comprasModel->updateConfirmacion($respuesta, $estado, $estadoTx, $idCompra, $franquicia);

          if ($estabaConfirmada) {
            $boletasCompra = $compraDetalleModel->getList("detalle_compra = '$idCompra'");
            foreach ($boletasCompra as $boletacompra) {
              $boleta = $boletaseventoModel->getById($boletacompra->detalle_boleta);
              $cantidadComprada = (int) $boletacompra->detalle_cantidad;
              $cantidadVendida = (int) ($boleta->boleta_evento_cantidad_vendidas ?? 0);
              $nuevaCantidad = max(0, $cantidadVendida - $cantidadComprada);
              $boletaseventoModel->editField($boleta->boleta_evento_id, "boleta_evento_cantidad_vendidas", $nuevaCantidad);
            }

            $codigo = $_REQUEST['x_extra4'] ?? '';
            if ($codigo !== '') {
              $codigoModel = new Administracion_Model_DbTable_Codigospromocionales();
              $lista = $codigoModel->getList(" codigo='$codigo' ", "");
              if (!empty($lista)) {
                $codigo_id = $lista[0]->id;
                $codigoModel->editField($codigo_id, "usado", "0");
                $codigoModel->editField($codigo_id, "fecha_uso", "NULL");
              }
            }
          }

          break;
      }
    } else {
      $data2['log_tipo'] = 'EPAYCO Firma no válida';
      echo ("Firma no válida");
    }


    $data2['log_log'] = preg_replace('/[^\x09\x0A\x0D\x20-\x7E]+/', '', print_r($_REQUEST, true));
    $logModel = new Administracion_Model_DbTable_Log();
    $logModel->insert($data2);
  }

  public function generarpdfs($infoVenta, $ticket)
	{
		$this->setLayout('blanco');
		$this->_view->ticket = $ticket;
		$this->_view->infoVenta = $infoVenta;

		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);
		$options->set('isPhpEnabled', false);
		$options->set('defaultFont', 'helvetica');

		$dompdf = new Dompdf($options);

		$content  = $this->_view->getRoutPHP('modules/page/Views/template/generarpdf.php');

		if ($infoVenta->programacion_bono == 1) {
			$terminos = $this->_view->getRoutPHP('modules/page/Views/template/terminosbonos.php');
		} else {
			$terminos = $this->_view->getRoutPHP('modules/page/Views/template/terminos.php');
		}

		$html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
			<style>
				body { margin: 0; padding: 0; font-family: helvetica, sans-serif; font-size: 12px; }
				.page-break { page-break-before: always; }
			</style>
		</head><body>'
			. $content
			. '<div class="page-break">' . $terminos . '</div>'
		. '</body></html>';

		$dompdf->loadHtml($html);
		$dompdf->setPaper('letter', 'landscape');
		$dompdf->render();

		ob_clean();
		$name = PDFS_PATH . "ticket_{$ticket->ticket_uid}.pdf";
		file_put_contents($name, $dompdf->output());
	}
}

// https://rdbd9vcd-8043.use2.devtunnels.ms/page/evento/respuesta?ref_payco=b55e48c797674450e476881e