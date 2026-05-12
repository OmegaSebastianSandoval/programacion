<?php
/**
 * Controlador de Compras — solo consulta (sin crear/editar/eliminar desde aquí).
 */
class Administracion_comprasController extends Administracion_mainController
{
	public $botonpanel = 11;

	public $mainModel;
	protected $route;
	protected $pages;
	protected $namefilter;
	protected $_csrf_section = "administracion_compras";
	protected $namepages;

	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Compras();
		$this->namefilter = "parametersfiltercompras";
		$this->route = "/administracion/compras";
		$this->namepages = "pages_compras";
		$this->namepageactual = "page_actual_compras";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}

	public function indexAction()
	{
		$title = "Administración de Compras";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "boleta_compra_id DESC";
		$list = $this->mainModel->getList($filters, $order);
		$amount = $this->pages;
		$page = $this->_getSanitizedParam("page");
		if (!$page && Session::getInstance()->get($this->namepageactual)) {
			$page = Session::getInstance()->get($this->namepageactual);
			$start = ($page - 1) * $amount;
		} else if (!$page) {
			$start = 0;
			$page = 1;
			Session::getInstance()->set($this->namepageactual, $page);
		} else {
			Session::getInstance()->set($this->namepageactual, $page);
			$start = ($page - 1) * $amount;
		}
		$this->_view->register_number = count($list);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($list) / $amount);
		$this->_view->page = $page;
		$this->_view->lists = $this->mainModel->getListPages($filters, $order, $start, $amount);
		$this->_view->csrf_section = $this->_csrf_section;

		$eventoModel = new Administracion_Model_DbTable_Eventos();
		$eventos = $eventoModel->getList('', 'evento_nombre ASC');
		$this->_view->eventos = $eventos;
		$eventosMap = [];
		foreach ($eventos as $ev) {
			$eventosMap[$ev->evento_id] = $ev->evento_nombre;
		}
		$this->_view->eventosMap = $eventosMap;
	}

	public function infoAction()
	{
		$id = $this->_getSanitizedParam("id");
		if (!($id > 0)) {
			header('Location: ' . $this->route);
			return;
		}
		$compra = $this->mainModel->getById($id);
		if (!$compra) {
			header('Location: ' . $this->route);
			return;
		}
		$this->_view->compra = $compra;

		$detalleModel = new Administracion_Model_DbTable_Compradetalle();
		$detalles = $detalleModel->getList("detalle_compra = '" . $id . "'", "detalle_id ASC");
		$this->_view->detalles = $detalles;

		$evento = false;
		if ($compra->boleta_compra_evento > 0) {
			$eventoModel = new Administracion_Model_DbTable_Eventos();
			$evento = $eventoModel->getById($compra->boleta_compra_evento);
		}
		$this->_view->evento = $evento;

		$reservaModel = new Administracion_Model_DbTable_Reservas();
		$reserva = $reservaModel->getByCompraId($id);
		$this->_view->reserva = $reserva;

		$sede = false;
		if ($evento && $evento->evento_lugar > 0) {
			$sedesModel = new Administracion_Model_DbTable_Sedes();
			$sede = $sedesModel->getById($evento->evento_lugar);
		}
		$this->_view->sede = $sede;

		$title = "Detalle de Compra #" . $id;
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->route = $this->route;
	}

	/*
	 * Métodos de escritura deshabilitados — esta sección es solo de consulta.
	 */
	// public function manageAction() { ... }
	// public function insertAction() { ... }
	// public function updateAction() { ... }
	// public function deleteAction() { ... }

	protected function getFilter()
	{
		$filtros = " 1 = 1 ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if (!empty($filters->boleta_compra_evento)) {
				$filtros .= " AND boleta_compra_evento = '" . $filters->boleta_compra_evento . "'";
			}
			if (!empty($filters->boleta_compra_validacion)) {
				$filtros .= " AND boleta_compra_validacion = '" . $filters->boleta_compra_validacion . "'";
			}
			if (!empty($filters->boleta_compra_tipo)) {
				$filtros .= " AND boleta_compra_tipo = '" . $filters->boleta_compra_tipo . "'";
			}
		}
		return $filtros;
	}

	protected function filters()
	{
		if ($this->getRequest()->isPost() == true) {
			Session::getInstance()->set($this->namepageactual, 1);
			$parramsfilter = array();
			$parramsfilter['boleta_compra_evento'] = $this->_getSanitizedParam("boleta_compra_evento");
			$parramsfilter['boleta_compra_validacion'] = $this->_getSanitizedParam("boleta_compra_validacion");
			$parramsfilter['boleta_compra_tipo'] = $this->_getSanitizedParam("boleta_compra_tipo");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}
