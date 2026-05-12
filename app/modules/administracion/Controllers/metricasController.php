<?php
/**
 * Controlador de Métricas — Dashboard de eventos del día actual.
 * Muestra compras, reservas y disponibilidad de boletas para hoy.
 */
class Administracion_metricasController extends Administracion_mainController
{
    public $botonpanel = 12;

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $title = "Dashboard de Eventos";
        $this->getLayout()->setTitle($title);
        $this->_view->titlesection = $title;

        $hoy = date('Y-m-d');
        $fechaInicio = $hoy . ' 00:00:00';
        $fechaFin = $hoy . ' 23:59:59';

        // Fecha legible en español
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        $diaSemana = $dias[(int) date('w')];
        $diaMes = date('j');
        $mes = $meses[(int) date('n') - 1];
        $anio = date('Y');
        $this->_view->today = "$diaSemana, $diaMes de $mes de $anio";

        $eventoModel = new Administracion_Model_DbTable_Eventos();
        $comprasModel = new Administracion_Model_DbTable_Compras();
        $reservasModel = new Administracion_Model_DbTable_Reservas();
        $boletaEventoModel = new Administracion_Model_DbTable_Boletaevento();
        $sedesModel = new Administracion_Model_DbTable_Sedes();

        // Mapa sede_id => sede_nombre
        $todas_sedes = $sedesModel->getList('', 'sede_id ASC');
        $sede_nombres = [];
        foreach ($todas_sedes as $s) {
            $sede_nombres[$s->sede_id] = $s->sede_nombre;
        }

        // Mapa de nombres de eventos para enriquecer las tablas
        $todos_eventos = $eventoModel->getList('', 'evento_id ASC');
        $evento_nombres = [];
        foreach ($todos_eventos as $e) {
            $evento_nombres[$e->evento_id] = $e->evento_nombre;
        }

        // Eventos de hoy
        $eventos_raw = $eventoModel->getList("evento_fecha BETWEEN '$fechaInicio' AND '$fechaFin'", "evento_hora ASC");
        $eventos_hoy = [];
        foreach ($eventos_raw as $evento) {
            $eid = $evento->evento_id;

            // Boletas configuradas para el evento
            $boletas = $boletaEventoModel->getList("boleta_evento_evento = '$eid'");
            $boletas_total = 0;
            $boletas_saldo = 0;
            foreach ($boletas as $b) {
                $boletas_total += (int) $b->boleta_evento_cantidad;
                $boletas_saldo += (int) $b->boleta_evento_saldo;
            }
            // Proteger contra saldo mayor que el total (dato inconsistente)
            $boletas_saldo = min($boletas_saldo, $boletas_total);
            $evento->boletas_total = $boletas_total;
            $evento->boletas_saldo = $boletas_saldo;
            $evento->boletas_vendidas = $boletas_total - $boletas_saldo;

            // Resolver nombre de sede desde evento_lugar (FK a sedes)
            $evento->sede_nombre = isset($sede_nombres[$evento->evento_lugar])
                ? $sede_nombres[$evento->evento_lugar]
                : $evento->evento_lugar;

            // Reservas de hoy para este evento
            $reservas_ev = $reservasModel->getList(
                "reserva_evento_id_fk = '$eid' AND reserva_fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin'"
            );
            $res_total = 0;
            foreach ($reservas_ev as $r) {
                $res_total += (float) $r->reserva_total;
            }
            $evento->reservas_count = count($reservas_ev);
            $evento->reservas_total = $res_total;

            // Compras de hoy para este evento
            $compras_ev = $comprasModel->getList(
                "boleta_compra_evento = '$eid' AND boleta_compra_fecha BETWEEN '$fechaInicio' AND '$fechaFin'"
            );
            $comp_total = 0;
            foreach ($compras_ev as $c) {
                $comp_total += (float) $c->boleta_compra_total;
            }
            $evento->compras_count = count($compras_ev);
            $evento->compras_total = $comp_total;

            $eventos_hoy[] = $evento;
        }
        $this->_view->eventos_hoy = $eventos_hoy;

        // Todas las compras de hoy
        $compras_hoy = $comprasModel->getList(
            "boleta_compra_fecha BETWEEN '$fechaInicio' AND '$fechaFin'",
            "boleta_compra_id DESC"
        );
        foreach ($compras_hoy as $c) {
            $c->boleta_compra_evento_nombre =
                isset($evento_nombres[$c->boleta_compra_evento])
                ? $evento_nombres[$c->boleta_compra_evento]
                : '—';
        }
        $this->_view->compras_hoy = $compras_hoy;

        // Todas las reservas de hoy
        $reservas_hoy = $reservasModel->getList(
            "reserva_fecha_creacion BETWEEN '$fechaInicio' AND '$fechaFin'",
            "reserva_id DESC"
        );
        foreach ($reservas_hoy as $r) {
            $r->reserva_evento_nombre =
                isset($evento_nombres[$r->reserva_evento_id_fk])
                ? $evento_nombres[$r->reserva_evento_id_fk]
                : '—';
        }
        $this->_view->reservas_hoy = $reservas_hoy;

        // Totales del día
        $total_ventas_hoy = 0;
        $total_reservas_hoy = 0;
        foreach ($compras_hoy as $c) {
            $total_ventas_hoy += (float) $c->boleta_compra_total;
        }
        foreach ($reservas_hoy as $r) {
            $total_reservas_hoy += (float) $r->reserva_total;
        }
        $this->_view->total_ventas_hoy = $total_ventas_hoy;
        $this->_view->total_reservas_hoy = $total_reservas_hoy;
        $this->_view->total_compras_count = count($compras_hoy);
        $this->_view->total_reservas_count = count($reservas_hoy);
    }
}
