<?php

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
        'precioadicional' => (float) $b->boleta_evento_precioadicional,
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
        $precioUnit = (float) $boleta->boleta_evento_precioadicional;
      } elseif ($evento->evento_tipo === 'reservayboleteria') {
        $precioUnit = (float) $boleta->boleta_evento_precio + (float) $boleta->boleta_evento_precioadicional;
      } else {
        $precioUnit = (float) $boleta->boleta_evento_precio;
      }

      $totalCalculado += $precioUnit * $cantidad;
      $cantidadTotal += $cantidad;
      $resumen[] = ['boleta' => $boleta, 'cantidad' => $cantidad, 'precioUnit' => $precioUnit];
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
        $valido = !($promo->tipo === 'uso-unico' && $promo->usado == '1');
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

    // Todo OK — preparar pago
    $this->setLayout('blanco');

    $descripcion = 'Compra ' . $cantidadTotal . ' boleta(s) — ' . html_entity_decode($evento->evento_nombre);

    $payment = Epayco::getInstance()->get();
    $payment['amount'] = $totalFinal;

    $this->_view->payment = $payment;
    $this->_view->nombre = $nombre;
    $this->_view->correo = $email;
    $this->_view->cedula = $documento;
    $this->_view->vendedor = $vendedor;
    $this->_view->evento = $evento;
    $this->_view->descripcion = $descripcion;
    $this->_view->resumen = $resumen;
    $this->_view->cantidadTotal = $cantidadTotal;
    $this->_view->totalFinal = $totalFinal;
    $this->_view->promoId = $promoId;
    $this->_view->codigo = $codigo;
    // idcompra se asignará cuando existan las tablas de compra
    $this->_view->idcompra = 0;
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
}
