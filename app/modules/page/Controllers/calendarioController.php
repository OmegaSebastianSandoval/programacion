<?php

class Page_calendarioController extends Page_mainController
{

  public function indexAction()
  {
    $sedesModel = new Administracion_Model_DbTable_Sedes();
    $sedesRaw = $sedesModel->getList("sede_estado='1'", "sede_id ASC");

    $sedesForJs = [];
    foreach ($sedesRaw as $sede) {
      $sedesForJs[] = [
        'id'        => (int) $sede->sede_id,
        'nombre'    => $sede->sede_nombre,
        'direccion' => $sede->sede_direccion,
        'color'     => $sede->sede_color ?: '#888888',
      ];
    }

    $eventosModel = new Administracion_Model_DbTable_Eventos();
    $hoy = date('Y-m-d') . ' 00:00:00';
    $eventosRaw = $eventosModel->getList(
      "evento_activo='1' AND evento_estado='activo' AND evento_fecha >= '$hoy'",
      "evento_fecha ASC"
    );

    $boletasEventosModel = new Administracion_Model_DbTable_Boletaevento();
    $fechaActual = date('Y-m-d H:i:s');
    $eventosPorFecha = [];

    foreach ($eventosRaw as $ev) {
      $dateKey  = substr($ev->evento_fecha, 0, 10);
      $timeStr  = substr($ev->evento_fecha, 11, 5);
      $eventoId = $ev->evento_id;
      $boletas  = $boletasEventosModel->getList(
        "boleta_evento_evento = '$eventoId' AND boleta_evento_fechalimite >= '$fechaActual'", ""
      );

      $eventosPorFecha[$dateKey][] = [
        'id'           => (int) $eventoId,
        'nombre'       => $ev->evento_nombre,
        'descripcion'  => $ev->evento_descripcion,
        'imagen'       => $ev->evento_imagen,
        'fecha'        => $dateKey,
        'hora'         => $timeStr,
        'lugar'        => (int) $ev->evento_lugar,
        'costo'        => (int) $ev->evento_costo,
        'aforomaximo'  => (int) $ev->evento_aforomaximo,
        'tipo'         => $ev->evento_tipo ?? '',
        'tiene_boletas' => !empty($boletas),
      ];
    }

    $this->_view->eventosJson = json_encode($eventosPorFecha, JSON_UNESCAPED_UNICODE);
    $this->_view->sedesJson   = json_encode($sedesForJs, JSON_UNESCAPED_UNICODE);
  }
}
