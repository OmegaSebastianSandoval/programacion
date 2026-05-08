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
    $boletasData = [];
    $reservasData = [];

    // Cargar boletas solo para tipos que las usan
    if ($evento->evento_tipo === 'boleteria' || $evento->evento_tipo === 'reservayboleteria') {
      $boletaModel = new Administracion_Model_DbTable_Boletaevento();
      $boletasRaw = $boletaModel->getList("boleta_evento_evento = '$id' AND boleta_evento_fechalimite >= '$fechaActual'", "boleta_evento_precio ASC");

      $boletatipoModel = new Administracion_Model_DbTable_Boletatipo();
      $tiposRaw = $boletatipoModel->getList();
      $tipoMap = [];
      foreach ($tiposRaw as $t) {
        $tipoMap[$t->boleta_tipo_id] = $t->boleta_tipo_nombre;
      }

      foreach ($boletasRaw as $b) {
        $cantidad = (int) $b->boleta_evento_cantidad;
        $vendidas = (int) ($b->boleta_evento_cantidad_vendidas ?? 0);
        $disponibles = max(0, $cantidad - $vendidas);
        $boletasData[] = [
          'id' => (int) $b->boleta_evento_id,
          'tipo_nombre' => $tipoMap[$b->boleta_evento_tipo] ?? 'Boleta',
          'saldo' => (int) $b->boleta_evento_saldo,
          'disponibles' => $disponibles,
          'precio' => (float) $b->boleta_evento_precio,
          'precioadicional' => (float) $b->boleta_evento_precioreserva,
          'fechalimite' => $b->boleta_evento_fechalimite,
        ];
      }
    }

    // Cargar reservas solo para tipos que las usan
    if ($evento->evento_tipo === 'reserva' || $evento->evento_tipo === 'reservayboleteria') {
      $reservaEventoModel = new Administracion_Model_DbTable_Reservaevento();
      $reservasRaw = $reservaEventoModel->getList(
        "reserva_evento_evento = '$id' AND reserva_evento_activo = 1 AND reserva_evento_fechalimite >= '$fechaActual'",
        "reserva_evento_precio ASC"
      );

      foreach ($reservasRaw as $r) {
        $disponibles = (int) $r->reserva_evento_cantidad - (int) ($r->reserva_evento_cantidad_vendidas ?? 0);
        $reservasData[] = [
          'id' => (int) $r->reserva_evento_id,
          'nombre' => $r->reserva_evento_nombre,
          'precio' => (float) $r->reserva_evento_precio,
          'capacidad' => (int) $r->reserva_evento_capacidad,
          'cantidad' => (int) $r->reserva_evento_cantidad,
          'disponibles' => max(0, $disponibles),
          'boleta_req' => (int) $r->reserva_evento_boleta_req,
          'boletas_x_reserva' => (int) $r->reserva_evento_boletas_x_reserva,
        ];
      }
    }

    $sedeModel = new Administracion_Model_DbTable_Sedes();
    $sede = $sedeModel->getById($evento->evento_lugar);

    $vendedor = $this->_getSanitizedParam("vendedor");

    $this->_view->evento = $evento;
    $this->_view->sede = $sede;
    $this->_view->vendedor = $vendedor;
    $this->_view->boletasJson = json_encode($boletasData, JSON_UNESCAPED_UNICODE);
    $this->_view->reservasJson = json_encode($reservasData, JSON_UNESCAPED_UNICODE);

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

    $redirectError = function ($msg) use ($eventoId) {
      $base = $eventoId ? '/page/evento/detalle?id=' . $eventoId : '/';
      header('Location: ' . $base . '&error=' . urlencode($msg));
      exit;
    };

    if (!$eventoId || !$nombre || !$documento || !$email || !$fechanac) {
      $redirectError('Completa todos los datos del comprador.');
    }

    $eventosModel = new Administracion_Model_DbTable_Eventos();
    $evento = $eventosModel->getById($eventoId);
    if (!$evento) {
      $redirectError('Evento no encontrado.');
    }

    $comprasModel = new Administracion_Model_DbTable_Compras();
    $reservasModel = new Administracion_Model_DbTable_Reservas();

    $totalCalculado = 0;
    $cantidadTotal = 0;
    $promoId = null;
    $descuento = 0;
    $idCompra = null;
    $descripcion = '';
    $reservaEventoId = null;

    // ── SOLO RESERVA ──────────────────────────────────────────────────────────
    if ($evento->evento_tipo === 'reserva') {

      $reservaEventoId = (int) $this->_getSanitizedParam('reserva_evento_id');
      $cantidadPersonas = max(1, (int) $this->_getSanitizedParam('cantidad_personas'));

      if (!$reservaEventoId) {
        $redirectError('Selecciona una opción de reserva.');
      }

      $reservaEventoModel = new Administracion_Model_DbTable_Reservaevento();
      $reservaEvento = $reservaEventoModel->getById($reservaEventoId);
      if (!$reservaEvento || (int) $reservaEvento->reserva_evento_evento !== $eventoId) {
        $redirectError('La opción de reserva seleccionada no es válida.');
      }

      $disponibles = (int) $reservaEvento->reserva_evento_cantidad - (int) ($reservaEvento->reserva_evento_cantidad_vendidas ?? 0);
      if ($cantidadPersonas > $disponibles) {
        $redirectError('Solo hay capacidad para ' . $disponibles . ' persona(s) más.');
      }

      $totalCalculado = (float) $reservaEvento->reserva_evento_precio * $cantidadPersonas;
      $cantidadTotal = $cantidadPersonas;
      $descripcion = 'Reserva ' . $cantidadPersonas . ' persona(s) — ' . $evento->evento_nombre;

      $this->aplicarPromo($codigo, $eventoId, $totalCalculado, $descuento, $promoId);
      $totalFinal = max(0, $totalCalculado - $descuento);

      if (abs($totalFinal - $totalFront) > 1) {
        $redirectError('El monto enviado no coincide con el calculado. Recarga la página e intenta de nuevo.');
      }

      $idCompra = $comprasModel->insert($this->buildDataCompra(
        $eventoId,
        $documento,
        $nombre,
        $email,
        $fechanac,
        $codigo,
        $totalFinal,
        $vendedor,
        'reserva'
      ));

      $reservasModel->insert([
        'reserva_compra_id' => $idCompra,
        'reserva_evento_id_fk' => $reservaEventoId,
        'reserva_evento' => $eventoId,
        'reserva_tipo_origen' => 'solo_reserva',
        'reserva_cantidad_personas' => $cantidadPersonas,
        'reserva_total' => $totalFinal,
        'reserva_estado' => ($totalFinal == 0) ? 'confirmada' : 'pendiente',
        'reserva_nombre' => $nombre,
        'reserva_email' => $email,
        'reserva_fecha_creacion' => date('Y-m-d H:i:s'),
        'reserva_notas' => '',
      ]);

      $reservaEventoModel->editField(
        $reservaEventoId,
        'reserva_evento_cantidad_vendidas',
        (int) ($reservaEvento->reserva_evento_cantidad_vendidas ?? 0) + $cantidadPersonas
      );

      if ($totalFinal == 0) {
        header('Location: /page/evento/respuesta?reserva_gratuita=1&compra=' . $idCompra);
        exit;
      }

      // ── SOLO BOLETERÍA ────────────────────────────────────────────────────────
    } elseif ($evento->evento_tipo === 'boleteria') {

      $boletasSel = json_decode(isset($_POST['boletas']) ? $_POST['boletas'] : '[]', true);
      if (!is_array($boletasSel) || empty($boletasSel)) {
        $redirectError('Selecciona al menos una boleta.');
      }

      $resumen = $this->procesarBoletas($boletasSel, $eventoId, $evento, $redirectError, $totalCalculado, $cantidadTotal);

      $this->aplicarPromo($codigo, $eventoId, $totalCalculado, $descuento, $promoId);
      $totalFinal = max(0, $totalCalculado - $descuento);

      if (abs($totalFinal - $totalFront) > 1) {
        $redirectError('El monto enviado no coincide con el calculado. Recarga la página e intenta de nuevo.');
      }

      $idCompra = $comprasModel->insert($this->buildDataCompra(
        $eventoId,
        $documento,
        $nombre,
        $email,
        $fechanac,
        $codigo,
        $totalFinal,
        $vendedor,
        'boleteria'
      ));

      $this->insertarDetalles($resumen, $idCompra);

      // Reserva interna automática para control de ingreso/CRM
      $reservasModel->insert([
        'reserva_compra_id' => $idCompra,
        'reserva_evento_id_fk' => 0,
        'reserva_evento' => $eventoId,
        'reserva_tipo_origen' => 'boleteria_auto',
        'reserva_cantidad_personas' => $cantidadTotal,
        'reserva_total' => 0,
        'reserva_estado' => 'pendiente',
        'reserva_nombre' => $nombre,
        'reserva_email' => $email,
        'reserva_fecha_creacion' => date('Y-m-d H:i:s'),
        'reserva_notas' => '',
      ]);

      $descripcion = 'Compra ' . $cantidadTotal . ' boleta(s) — ' . $evento->evento_nombre;

      // ── BOLETERÍA + RESERVA ───────────────────────────────────────────────────
    } elseif ($evento->evento_tipo === 'reservayboleteria') {

      $boletasSel = json_decode(isset($_POST['boletas']) ? $_POST['boletas'] : '[]', true);
      $reservaEventoId = (int) $this->_getSanitizedParam('reserva_evento_id');

      if (!is_array($boletasSel) || empty($boletasSel)) {
        $redirectError('Selecciona al menos una boleta.');
      }

      $resumen = $this->procesarBoletas($boletasSel, $eventoId, $evento, $redirectError, $totalCalculado, $cantidadTotal);

      $reservaEvento = null;
      if ($reservaEventoId > 0) {
        $reservaEventoModel = new Administracion_Model_DbTable_Reservaevento();
        $reservaEvento = $reservaEventoModel->getById($reservaEventoId);
        if (!$reservaEvento || (int) $reservaEvento->reserva_evento_evento !== $eventoId) {
          $redirectError('Reserva seleccionada no es válida.');
        }

        $disponibles = (int) $reservaEvento->reserva_evento_cantidad - (int) ($reservaEvento->reserva_evento_cantidad_vendidas ?? 0);
        if ($disponibles <= 0) {
          $redirectError('Reserva seleccionada ya no está disponible.');
        }

        // Validar boletas requeridas por el palco
        $boletaReqId = (int) $reservaEvento->reserva_evento_boleta_req;
        $boletasReqCant = (int) $reservaEvento->reserva_evento_boletas_x_reserva;
        if ($boletaReqId > 0 && $boletasReqCant > 0) {
          $cantidadReq = 0;
          foreach ($resumen as $item) {
            if ((int) $item['boleta']->boleta_evento_id === $boletaReqId) {
              $cantidadReq += $item['cantidad'];
            }
          }
          if ($cantidadReq < $boletasReqCant) {
            $redirectError('Para reservar esta reserva debes comprar al menos ' . $boletasReqCant . ' boleta(s) del tipo requerido.');
          }
        }

        $totalCalculado += (float) $reservaEvento->reserva_evento_precio;
      }

      $this->aplicarPromo($codigo, $eventoId, $totalCalculado, $descuento, $promoId);
      $totalFinal = max(0, $totalCalculado - $descuento);

      if (abs($totalFinal - $totalFront) > 1) {
        $redirectError('El monto enviado no coincide con el calculado. Recarga la página e intenta de nuevo.');
      }

      $idCompra = $comprasModel->insert($this->buildDataCompra(
        $eventoId,
        $documento,
        $nombre,
        $email,
        $fechanac,
        $codigo,
        $totalFinal,
        $vendedor,
        'reservayboleteria'
      ));

      $this->insertarDetalles($resumen, $idCompra);

      if ($reservaEvento) {
        $reservasModel->insert([
          'reserva_compra_id' => $idCompra,
          'reserva_evento_id_fk' => $reservaEventoId,
          'reserva_evento' => $eventoId,
          'reserva_tipo_origen' => 'con_boletas',
          'reserva_cantidad_personas' => (int) $reservaEvento->reserva_evento_capacidad,
          'reserva_total' => (float) $reservaEvento->reserva_evento_precio,
          'reserva_estado' => 'pendiente',
          'reserva_nombre' => $nombre,
          'reserva_email' => $email,
          'reserva_fecha_creacion' => date('Y-m-d H:i:s'),
          'reserva_notas' => '',
        ]);
      }

      $descripcion = 'Compra ' . $cantidadTotal . ' boleta(s)' . ($reservaEvento ? ' + palco' : '') . ' — ' . $evento->evento_nombre;

    } else {
      $redirectError('Tipo de evento no reconocido.');
    }

    // ── EPAYCO ────────────────────────────────────────────────────────────────
    $epayco = Epayco::getInstance()->get();
    $apiBase = rtrim($epayco['url_apify'], '/');

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
    $this->_view->epaycoTest = true;
  }

  private function buildDataCompra($eventoId, $documento, $nombre, $email, $fechanac, $codigo, $total, $vendedor, $tipo)
  {
    return [
      'boleta_compra_evento' => $eventoId,
      'boleta_compra_documento' => $documento,
      'boleta_compra_nombre' => $nombre,
      'boleta_compra_email' => $email,
      'boleta_compra_fechanacimiento' => $fechanac,
      'boleta_compra_fecha' => date('Y-m-d H:i:s'),
      'boleta_compra_codigo' => $codigo,
      'boleta_compra_total' => $total,
      'boleta_compra_vendedor' => $vendedor,
      'boleta_compra_tipo' => $tipo,
    ];
  }

  private function aplicarPromo($codigo, $eventoId, $totalCalculado, &$descuento, &$promoId)
  {
    if (!$codigo)
      return;

    $promoModel = new Administracion_Model_DbTable_Codigospromocionales();
    $promos = $promoModel->getList("codigo = '$codigo' AND activo = '1' AND (evento = '0' OR evento = '$eventoId')");
    if (empty($promos))
      return;

    $promo = $promos[0];
    $valido = true;
    if ($promo->tipo === 'uso-unico') {
      $valido = ($promo->usado != '1');
    } elseif ($promo->tipo === 'varios-usos') {
      if ($promo->cantidad_usos_maxima !== null && count($promos) >= (int) $promo->cantidad_usos_maxima) {
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

  private function procesarBoletas($boletasSel, $eventoId, $evento, $redirectError, &$totalCalculado, &$cantidadTotal)
  {
    $boletaeventoModel = new Administracion_Model_DbTable_Boletaevento();
    $resumen = [];

    foreach ($boletasSel as $sel) {
      $bId = (int) ($sel['id'] ?? 0);
      $cantidad = (int) ($sel['cantidad'] ?? 0);
      if ($bId <= 0 || $cantidad <= 0)
        continue;
      if ($cantidad > 20)
        $redirectError('Máximo 20 boletas por tipo.');

      $boleta = $boletaeventoModel->getById($bId);
      if (!$boleta || (int) $boleta->boleta_evento_evento !== $eventoId) {
        $redirectError('Una de las boletas seleccionadas no es válida.');
      }

      $disponibles = (int) $boleta->boleta_evento_cantidad - (int) ($boleta->boleta_evento_cantidad_vendidas ?? 0);
      if ($cantidad > $disponibles) {
        $redirectError('Solo quedan ' . $disponibles . ' unidades disponibles de esa boleta.');
      }

      $precioUnit = (float) $boleta->boleta_evento_precio;
      $precioReserva = ($evento->evento_tipo === 'reservayboleteria') ? (float) $boleta->boleta_evento_precioreserva : 0;

      $totalCalculado += ($precioUnit + $precioReserva) * $cantidad;
      $cantidadTotal += $cantidad;
      $resumen[] = [
        'boleta' => $boleta,
        'cantidad' => $cantidad,
        'precioUnit' => $precioUnit,
        'precioReserva' => $precioReserva,
        'tipo_id' => (int) $boleta->boleta_evento_tipo,
      ];
    }

    if (empty($resumen))
      $redirectError('Selecciona al menos una boleta.');
    return $resumen;
  }

  private function insertarDetalles($resumen, $idCompra)
  {
    $boletatipoModel = new Administracion_Model_DbTable_Boletatipo();
    $tiposRaw = $boletatipoModel->getList();
    $tipoMap = [];
    foreach ($tiposRaw as $t) {
      $tipoMap[(int) $t->boleta_tipo_id] = $t->boleta_tipo_nombre;
    }

    $compradetalleModel = new Administracion_Model_DbTable_Compradetalle();
    foreach ($resumen as $item) {
      $compradetalleModel->insert([
        'detalle_compra' => $idCompra,
        'detalle_boleta' => $item['boleta']->boleta_evento_id,
        'detalle_boleta_nombre' => $tipoMap[$item['tipo_id']] ?? 'Boleta',
        'detalle_cantidad' => $item['cantidad'],
        'detalle_precio_unit' => $item['precioUnit'],
        'detalle_precio_reserva' => $item['precioReserva'],
        'detalle_subtotal' => ($item['precioUnit'] + $item['precioReserva']) * $item['cantidad'],
      ]);
    }
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
    $reservaGratuita = $this->_getSanitizedParam('reserva_gratuita');
    $compraId = (int) $this->_getSanitizedParam('compra');

    if ($reservaGratuita == '1' && $compraId) {
      $comprasModel = new Administracion_Model_DbTable_Compras();
      $compra = $comprasModel->getById($compraId);

      $reservasModel = new Administracion_Model_DbTable_Reservas();
      $reserva = $reservasModel->getByCompraId($compraId);

      $evento = null;
      $sede = null;
      if ($compra && $compra->boleta_compra_evento) {
        $eventoModel = new Administracion_Model_DbTable_Eventos();
        $evento = $eventoModel->getById($compra->boleta_compra_evento);
        if ($evento && $evento->evento_lugar) {
          $sedeModel = new Administracion_Model_DbTable_Sedes();
          $sede = $sedeModel->getById($evento->evento_lugar);
        }
      }

      $this->_view->reservaGratuita = true;
      $this->_view->compra = $compra;
      $this->_view->reserva = $reserva;
      $this->_view->evento = $evento;
      $this->_view->sede = $sede;
      return;
    }

    $ref = $this->_getSanitizedParam('ref_payco');
    if (!$ref) {
      $this->_view->reservaGratuita = false;
      $this->_view->compra = null;
      return;
    }

    $response = json_decode(file_get_contents('https://secure.epayco.co/validation/v1/reference/' . $ref));
    $data = array();
    $data['log_log'] = print_r($response, true);
    $data['log_tipo'] = 'EPAYCO RESPUESTA';
    $logModel = new Administracion_Model_DbTable_Log();
    //$logModel->insert($data);

    $this->_view->reservaGratuita = false;
    $this->_view->compra = null;
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
    $reservasModel = new Administracion_Model_DbTable_Reservas();
    //Validamos la firma
    if ($x_signature == $signature) {

      $idCompraRaw = (int) ($_REQUEST['x_extra1'] ?? 0);
      if ($idCompraRaw) {
        $rawData = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE);
        $comprasModel->updateRaw($idCompraRaw, $rawData);
      }

      $x_cod_response = $_REQUEST['x_cod_response'];
      switch ((int) $x_cod_response) {
        case 1:
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
              // Actualizar boletas vendidas
              $boletasCompra = $compraDetalleModel->getList("detalle_compra = '$idCompra'");
              foreach ($boletasCompra as $boletacompra) {
                $boleta = $boletaseventoModel->getById($boletacompra->detalle_boleta);
                $cantidadComprada = (int) $boletacompra->detalle_cantidad;
                $cantidadVendida = (int) ($boleta->boleta_evento_cantidad_vendidas ?? 0);
                $boletaseventoModel->editField($boleta->boleta_evento_id, "boleta_evento_cantidad_vendidas", $cantidadVendida + $cantidadComprada);
              }

              // Confirmar reserva asociada a esta compra
              $reserva = $reservasModel->getByCompraId($idCompra);
              if ($reserva && $reserva->reserva_id) {
                $reservasModel->editField($reserva->reserva_id, 'reserva_estado', 'confirmada');
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

            // Revertir estado de reserva
            $reserva = $reservasModel->getByCompraId($idCompra);
            if ($reserva && $reserva->reserva_id) {
              $reservasModel->editField($reserva->reserva_id, 'reserva_estado', 'cancelada');
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
  public function enviarboleteriaAction()
  {
    $idCompra = 1;
    $this->setLayout('blanco');
    $comprasModel = new Administracion_Model_DbTable_Compras();
    $ticketsModel = new Administracion_Model_DbTable_Tickets();
    $infoVenta = $this->getVentaInfo($idCompra);
    // echo "<pre>";
    // print_r($infoVenta);
    // echo "</pre>";

    $cantidad = array_reduce($infoVenta->detalle, function ($sum, $item) {
      return $sum + (int) $item->detalle_cantidad;
    }, 0);
    $compraId = $infoVenta->compra->boleta_compra_id;
    $evento = $infoVenta->boleta_compra_evento;

    //validar que ya no se hayan creado los tickets
    $ticketsExisten = $ticketsModel->getList("ticket_compra_id = '$compraId' AND ticket_evento_id = '$evento'");
    if ($ticketsExisten && count($ticketsExisten) == $cantidad) {
      $logModel = new Administracion_Model_DbTable_Log();
      $dataLog = array();
      $dataLog['log_log'] = print_r($ticketsExisten, true);
      $dataLog['log_tipo'] = "QR NO GENERADOS PARA LA COMPRA $compraId PORQUE YA EXISTEN";
      $logModel->insert($dataLog);
      return;
    }
    $fechaEventoFinal = strtotime("$infoVenta->evento_fecha + 1 day");
    $qrsGenerados = [];

    // Iterar desde 1 hasta la cantidad de boletos comprados
    for ($i = 1; $i <= $cantidad; $i++) {

      $dataTicket = [
        "ticket_compra_id" => $compraId,
        "ticket_numero_ticket" => $i,
        "ticket_estado" => 1,
        "ticket_fecha_creacion" => date("Y-m-d H:i:s"),
        "ticket_fecha_expiracion" => date("Y-m-d H:i:s", $fechaEventoFinal)
      ];

      $nextId = $ticketsModel->getNextTicketId();
      $id = $ticketsModel->insert($dataTicket);
      $token = base_convert($id, 10, 36);
      $yearMonth = date("Ym", strtotime($infoVenta->evento_fecha));
      $customUid = "T-{$yearMonth}-" . str_pad($nextId, 7, "0", STR_PAD_LEFT);
      $baseString = "{$compraId}-{$infoVenta->boleta_compra_email}-{$yearMonth}-{$nextId}";
      $token = substr(base_convert(hash('sha256', $baseString), 16, 36), 0, 12);
      $ticketsModel->editField($id, "ticket_uid ", $customUid);
      $ticketsModel->editField($id, "ticket_token", $token);
      $ticketsModel->editField($id, "ticket_evento_id", $infoVenta->boleta_evento_evento);
      $ticket = $ticketsModel->getById($id);

      $qrsGenerados[] = [
        "ticket_id" => $id,
        "ticket_uid" => $customUid,
        "ticket_token" => $token,
        "ticket_numero_ticket" => $i,
        "ticket_fecha_expiracion" => date("Y-m-d H:i:s", $fechaEventoFinal),
        // "rutaQR" => $this->generarQR($customUid, $token),
        "email" => $infoVenta->boleta_compra_email,
        "nombre" => $infoVenta->boleta_compra_nombre,
        "telefono" => $infoVenta->boleta_compra_telefono,
        "estado" => $ticket->ticket_estado,
      ];

      $this->generarpdfs($infoVenta, $ticket);

    }

  }
  public function getVentaInfo($idCompra)
  {
    if (!$idCompra) {
      return null;
    }
    $comprasModel = new Administracion_Model_DbTable_Compras();
    $eventoModel = new Administracion_Model_DbTable_Eventos();
    $compraDetalle = new Administracion_Model_DbTable_Compradetalle();
    $compra = $comprasModel->getById($idCompra);
    $detalleVenta = $compraDetalle->getList("detalle_compra = '{$idCompra}'");
    $evento = $eventoModel->getById($compra->boleta_compra_evento);
    $evento->evento_descripcion = null;
    $evento->evento_titulo_politica = null;
    $evento->evento_descripcion_politica = null;

    return (object) [
      'compra' => $compra,
      'evento' => $evento,
      'detalle' => $detalleVenta,
    ];


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

    $content = $this->_view->getRoutPHP('modules/page/Views/template/generarpdf.php');

    if ($infoVenta->evento_bono == 1) {
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
// {
// header_code:"502",
// body:"Server error: `GET https://rdbd9vcd-8043.use2.devtunnels.ms/page/evento/confirmacion?x_cust_id_cliente=1264217&x_ref_payco=364251404&x_id_factura=1&x_id_invoice=1&x_description=Compra%203%20boleta%28s%29%20%C3%A2%C2%80%C2%94%20Test%20reserva%20y%20boleteria&x_amount=106000&x_amount_country=106000&x_amount_ok=106000&x_tax=0&x_amount_base=0&x_currency_code=COP&x_bank_name=BANCO%20DE%20PRUEBAS&x_cardnumber=457562%2A%2A%2A%2A%2A%2A%2A0326&x_quotas=1&x_respuesta=Rechazada&x_response=Rechazada&x_approval_code=000000&x_transaction_id=364251404&x_fecha_transaccion=2026-05-05%2016%3A36%3A14&x_transaction_date=2026-05-05%2016%3A36%3A14&x_cod_respuesta=2&x_cod_response=2&x_response_reason_text=04-Tarjeta%20restringida%20por%20el%20centro%20de%20autorizaciones&x_errorcode=04&x_cod_transaction_state=2&x_transaction_state=Rechazada&x_franchise=VS&x_payment_method=VS&x_business=Galeria%20Cafe%20Libro%20Club%20Social%20Privado%20SAS&x_customer_doctype=CC&x_customer_document=123123123&x_customer_name=Test&x_customer_lastname=nombre&x_customer_email=test%40test.com&x_customer_phone=3123123121&x_customer_movil=3123123121&x_customer_ind_pais=&x_customer_country=CO&x_customer_city=Bogota&x_customer_address=cll%202%20%2023-33&x_customer_ip=45.173.12.208&x_test_request=FALSE&x_extra1=1&x_extra2=3&x_extra3=1&x_extra4=testing123&x_extra5=&x_extra6=&x_extra7=&x_extra8=&x_extra9=&x_extra10=&x_tax_ico=0&x_payment_date=&x_signature=a02667c50d960df5d06489991833fec9489cf72c6474f4fe4ec259d76d180867&x_transaction_cycle=&is_processable=1&x_manual=1` resulted in a `502 Bad Gateway` response",
// url:"https://rdbd9vcd-8043.use2.devtunnels.ms/page/evento/confirmacion",
// }