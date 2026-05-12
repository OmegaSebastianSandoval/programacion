<?php
/**
 * Controlador de Reservas que permite la  creacion, edicion  y eliminacion de los Reservas del Sistema
 */
class Administracion_reservasController extends Administracion_mainController
{
	public $botonpanel = 10;

	/**
	 * $mainModel  instancia del modelo de  base de datos Reservas
	 * @var modeloContenidos
	 */
	public $mainModel;

	/**
	 * $route  url del controlador base
	 * @var string
	 */
	protected $route;

	/**
	 * $pages cantidad de registros a mostrar por pagina]
	 * @var integer
	 */
	protected $pages;

	/**
	 * $namefilter nombre de la variable a la fual se le van a guardar los filtros
	 * @var string
	 */
	protected $namefilter;

	/**
	 * $_csrf_section  nombre de la variable general csrf  que se va a almacenar en la session
	 * @var string
	 */
	protected $_csrf_section = "administracion_reservas";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador reservas .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Reservas();
		$this->namefilter = "parametersfilterreservas";
		$this->route = "/administracion/reservas";
		$this->namepages = "pages_reservas";
		$this->namepageactual = "page_actual_reservas";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  Reservas con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de Reservas";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "reserva_id DESC";
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
		$this->_view->eventos = $eventoModel->getList('', 'evento_nombre ASC');
	}

	/**
	 * Muestra la informacion detallada de una Reserva (solo lectura).
	 *
	 * @return void.
	 */
	public function infoAction()
	{
		$id = $this->_getSanitizedParam("id");
		if (!($id > 0)) {
			header('Location: ' . $this->route);
			return;
		}
		$reserva = $this->mainModel->getById($id);
		if (!$reserva) {
			header('Location: ' . $this->route);
			return;
		}
		$this->_view->reserva = $reserva;

		$compra = false;
		$detalles = [];
		if ($reserva->reserva_compra_id > 0) {
			$compraModel = new Administracion_Model_DbTable_Compras();
			$compra = $compraModel->getById($reserva->reserva_compra_id);
			$detalleModel = new Administracion_Model_DbTable_Compradetalle();
			$detalles = $detalleModel->getList("detalle_compra = '" . $reserva->reserva_compra_id . "'", "detalle_id ASC");
		}
		$this->_view->compra = $compra;
		$this->_view->detalles = $detalles;

		$evento = false;
		$sede = false;
		if ($reserva->reserva_evento_id_fk > 0) {
			$eventoModel = new Administracion_Model_DbTable_Eventos();
			$evento = $eventoModel->getById($reserva->reserva_evento_id_fk);
			if ($evento && $evento->evento_lugar > 0) {
				$sedeModel = new Administracion_Model_DbTable_Sedes();
				$sede = $sedeModel->getById($evento->evento_lugar);
			}
		}
		$this->_view->evento = $evento;
		$this->_view->sede = $sede;

		$title = "Detalle de Reserva #" . $id;
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->_view->route = $this->route;
	}

	/*
	 * Los métodos manageAction, insertAction, updateAction y deleteAction están
	 * deshabilitados — esta sección es solo de consulta.
	 */

	// public function manageAction() { ... }
	// public function insertAction() { ... }
	// public function updateAction() { ... }
	// public function deleteAction() { ... }

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Reservas.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		if ($this->_getSanitizedParam("reserva_compra_id") == '') {
			$data['reserva_compra_id'] = '0';
		} else {
			$data['reserva_compra_id'] = $this->_getSanitizedParam("reserva_compra_id");
		}
		if ($this->_getSanitizedParam("reserva_evento_id_fk") == '') {
			$data['reserva_evento_id_fk'] = '0';
		} else {
			$data['reserva_evento_id_fk'] = $this->_getSanitizedParam("reserva_evento_id_fk");
		}
		if ($this->_getSanitizedParam("reserva_evento") == '') {
			$data['reserva_evento'] = '0';
		} else {
			$data['reserva_evento'] = $this->_getSanitizedParam("reserva_evento");
		}
		$data['reserva_tipo_origen'] = $this->_getSanitizedParam("reserva_tipo_origen");
		if ($this->_getSanitizedParam("reserva_cantidad_personas") == '') {
			$data['reserva_cantidad_personas'] = '0';
		} else {
			$data['reserva_cantidad_personas'] = $this->_getSanitizedParam("reserva_cantidad_personas");
		}
		$data['reserva_total'] = $this->_getSanitizedParam("reserva_total");
		$data['reserva_estado'] = $this->_getSanitizedParam("reserva_estado");
		$data['reserva_nombre'] = $this->_getSanitizedParam("reserva_nombre");
		$data['reserva_email'] = $this->_getSanitizedParam("reserva_email");
		$data['reserva_fecha_creacion'] = $this->_getSanitizedParam("reserva_fecha_creacion");
		$data['reserva_notas'] = $this->_getSanitizedParam("reserva_notas");
		return $data;
	}
	/**
	 * Genera la consulta con los filtros de este controlador.
	 *
	 * @return array cadena con los filtros que se van a asignar a la base de datos
	 */
	protected function getFilter()
	{
		$filtros = " 1 = 1 ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if (!empty($filters->reserva_evento_id_fk)) {
				$filtros .= " AND reserva_evento_id_fk = '" . $filters->reserva_evento_id_fk . "'";
			}
			if (!empty($filters->reserva_tipo_origen)) {
				$filtros .= " AND reserva_tipo_origen = '" . $filters->reserva_tipo_origen . "'";
			}
			if (!empty($filters->reserva_estado)) {
				$filtros .= " AND reserva_estado = '" . $filters->reserva_estado . "'";
			}
		}
		return $filtros;
	}

	/**
	 * Recibe y asigna los filtros de este controlador
	 *
	 * @return void
	 */
	protected function filters()
	{
		if ($this->getRequest()->isPost() == true) {
			Session::getInstance()->set($this->namepageactual, 1);
			$parramsfilter = array();
			$parramsfilter['reserva_evento_id_fk'] = $this->_getSanitizedParam("reserva_evento_id_fk");
			$parramsfilter['reserva_tipo_origen'] = $this->_getSanitizedParam("reserva_tipo_origen");
			$parramsfilter['reserva_estado'] = $this->_getSanitizedParam("reserva_estado");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}