<?php
/**
 * Controlador de Reservaevento que permite la  creacion, edicion  y eliminacion de los Reservas evento del Sistema
 */
class Administracion_reservaeventoController extends Administracion_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos Reservas evento
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
	protected $_csrf_section = "administracion_reservaevento";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador reservaevento .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Reservaevento();
		$this->namefilter = "parametersfilterreservaevento";
		$this->route = "/administracion/reservaevento";
		$this->namepages = "pages_reservaevento";
		$this->namepageactual = "page_actual_reservaevento";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  Reservas evento con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de Reservas evento";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "";
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
		$this->_view->list_reserva_evento_tipo = $this->getReservaeventotipo();
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		$this->_view->reserva_evento_evento = $reserva_evento_evento;
		$eventoModel = new Administracion_Model_DbTable_Eventos();
		$evento = $eventoModel->getById($reserva_evento_evento);
		$this->_view->evento = $evento;
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  Reserva  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_reservaevento_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_reserva_evento_tipo = $this->getReservaeventotipo();
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		$this->_view->reserva_evento_evento = $reserva_evento_evento;

		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->reserva_evento_id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar Zona de Reserva";
				$reserva_evento_evento = $content->reserva_evento_evento;
				$this->_view->reserva_evento_evento = $reserva_evento_evento;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear Zona de Reserva";
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear Zona de Reserva";
		}
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;

		$eventoModel = new Administracion_Model_DbTable_Eventos();
		$evento = $eventoModel->getById($reserva_evento_evento);
		$this->_view->evento = $evento;

		$boletaEventoModel = new Administracion_Model_DbTable_Boletaevento();
		$boletasModel = new Administracion_Model_DbTable_Boletatipo();
		$list_boletas = $boletaEventoModel->getList(" boleta_evento_evento = '$reserva_evento_evento' ");
		foreach ($list_boletas as $b) {
			$boleta = $boletasModel->getById($b->boleta_evento_tipo);
			$b->boleta_evento_boleta_nombre = $boleta->boleta_tipo_nombre;
		}
		$this->_view->list_boletas = $list_boletas;
	
		$reservasExistentes = $this->mainModel->getList(" reserva_evento_evento = '$reserva_evento_evento' ");
		$cuposUsados = 0;
		foreach ($reservasExistentes as $r) {
			$cuposUsados += (int) $r->reserva_evento_cantidad;
		}
		$this->_view->reserva_cupos_usados = $cuposUsados;
	}

	/**
	 * Inserta la informacion de un Reserva  y redirecciona al listado de Reservas evento.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$id = $this->mainModel->insert($data);
			$data['reserva_evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR ZONA RESERVA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '?reserva_evento_evento=' . $reserva_evento_evento);
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un Reserva  y redirecciona al listado de Reservas evento.
	 *
	 * @return void.
	 */
	public function updateAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->reserva_evento_id) {
				$data = $this->getData();
				$this->mainModel->update($data, $id);
			}
			$data['reserva_evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR ZONA RESERVA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '?reserva_evento_evento=' . $reserva_evento_evento);
	}

	/**
	 * Recibe un identificador  y elimina un Reserva  y redirecciona al listado de Reservas evento.
	 *
	 * @return void.
	 */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$reserva_evento_evento = $content->reserva_evento_evento;
					$this->mainModel->deleteRegister($id);
					$data = (array) $content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR ZONA RESERVA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '?reserva_evento_evento=' . $reserva_evento_evento);
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Reservaevento.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		if ($this->_getSanitizedParam("reserva_evento_evento") == '') {
			$data['reserva_evento_evento'] = '0';
		} else {
			$data['reserva_evento_evento'] = $this->_getSanitizedParam("reserva_evento_evento");
		}
		if ($this->_getSanitizedParam("reserva_evento_tipo") == '') {
			$data['reserva_evento_tipo'] = '0';
		} else {
			$data['reserva_evento_tipo'] = $this->_getSanitizedParam("reserva_evento_tipo");
		}
		$data['reserva_evento_nombre'] = $this->_getSanitizedParam("reserva_evento_nombre");
		$data['reserva_evento_precio'] = $this->_getSanitizedParam("reserva_evento_precio");
		if ($this->_getSanitizedParam("reserva_evento_capacidad") == '') {
			$data['reserva_evento_capacidad'] = '0';
		} else {
			$data['reserva_evento_capacidad'] = $this->_getSanitizedParam("reserva_evento_capacidad");
		}
		if ($this->_getSanitizedParam("reserva_evento_cantidad") == '') {
			$data['reserva_evento_cantidad'] = '0';
		} else {
			$data['reserva_evento_cantidad'] = $this->_getSanitizedParam("reserva_evento_cantidad");
		}
		if ($this->_getSanitizedParam("reserva_evento_cantidad_vendidas") == '') {
			$data['reserva_evento_cantidad_vendidas'] = '0';
		} else {
			$data['reserva_evento_cantidad_vendidas'] = $this->_getSanitizedParam("reserva_evento_cantidad_vendidas");
		}
		if ($this->_getSanitizedParam("reserva_evento_boleta_req") == '') {
			$data['reserva_evento_boleta_req'] = '0';
		} else {
			$data['reserva_evento_boleta_req'] = $this->_getSanitizedParam("reserva_evento_boleta_req");
		}
		if ($this->_getSanitizedParam("reserva_evento_boletas_x_reserva") == '') {
			$data['reserva_evento_boletas_x_reserva'] = '0';
		} else {
			$data['reserva_evento_boletas_x_reserva'] = $this->_getSanitizedParam("reserva_evento_boletas_x_reserva");
		}
		$data['reserva_evento_fechalimite'] = $this->_getSanitizedParam("reserva_evento_fechalimite");
		if ($this->_getSanitizedParam("reserva_evento_activo") == '') {
			$data['reserva_evento_activo'] = '0';
		} else {
			$data['reserva_evento_activo'] = $this->_getSanitizedParam("reserva_evento_activo");
		}
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
		$reserva_evento_evento = $this->_getSanitizedParam("reserva_evento_evento");
		$filtros .= " AND reserva_evento_evento = '$reserva_evento_evento' ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if ($filters->reserva_evento_tipo != '') {
				$filtros .= " AND reserva_evento_tipo = '" . $filters->reserva_evento_tipo . "'";
			}
			if ($filters->reserva_evento_nombre != '') {
				$filtros .= " AND reserva_evento_nombre LIKE '%" . $filters->reserva_evento_nombre . "%'";
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
			$parramsfilter['reserva_evento_tipo'] = $this->_getSanitizedParam("reserva_evento_tipo");
			$parramsfilter['reserva_evento_nombre'] = $this->_getSanitizedParam("reserva_evento_nombre");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}

	private function getReservaeventotipo()
	{
		$modelData = new Administracion_Model_DbTable_Reservatipo();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $value) {
			$array[$value->reserva_tipo_id] = $value->reserva_tipo_nombre;
		}
		return $array;
	}
}