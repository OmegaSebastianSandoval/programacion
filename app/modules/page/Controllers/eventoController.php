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

    $boletaModel = new Administracion_Model_DbTable_Boletaevento();
    $boletasRaw = $boletaModel->getList("boleta_evento_evento = '$id'");

    $boletatipoModel = new Administracion_Model_DbTable_Boletatipo();
    $tiposRaw = $boletatipoModel->getList();
    $tipoMap = [];
    foreach ($tiposRaw as $t) {
      $tipoMap[$t->boleta_tipo_id] = $t->boleta_tipo_nombre;
    }

    $boletasData = [];
    foreach ($boletasRaw as $b) {
      $boletasData[] = [
        'id'          => (int)$b->boleta_evento_id,
        'tipo_nombre' => isset($tipoMap[$b->boleta_evento_tipo]) ? $tipoMap[$b->boleta_evento_tipo] : 'Boleta',
        'saldo'       => (int)$b->boleta_evento_saldo,
        'precio'      => (float)$b->boleta_evento_precio,
        'fechalimite' => $b->boleta_evento_fechalimite,
      ];
    }

    $sedeModel = new Administracion_Model_DbTable_Sedes();
    $sede = $sedeModel->getById($evento->evento_lugar);

    $vendedor = $this->_getSanitizedParam("vendedor");

    $this->_view->evento    = $evento;
    $this->_view->sede      = $sede;
    $this->_view->vendedor  = $vendedor;
    $this->_view->boletasJson = json_encode($boletasData, JSON_UNESCAPED_UNICODE);

    $title = $evento->evento_nombre;
    $this->getLayout()->setTitle($title);
  }
}
