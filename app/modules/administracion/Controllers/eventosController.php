<?php
/**
 * Controlador de Eventos que permite la  creacion, edicion  y eliminacion de los eventos del Sistema
 */
class Administracion_eventosController extends Administracion_mainController
{
  public $botonpanel = 6;
	/**
	 * $mainModel  instancia del modelo de  base de datos eventos
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
	protected $_csrf_section = "administracion_eventos";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador eventos .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Eventos();
		$this->namefilter = "parametersfiltereventos";
		$this->route = "/administracion/eventos";
		$this->namepages = "pages_eventos";
		$this->namepageactual = "page_actual_eventos";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  eventos con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de eventos";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters = (object) Session::getInstance()->get($this->namefilter);
		$this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "orden ASC";
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
		$this->_view->list_evento_tipo = $this->getEventotipo();
		$this->_view->list_evento_lugar = $this->getEventolugar();
		$this->_view->list_evento_estado = $this->getEventoestado();
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  evento  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_eventos_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_evento_tipo = $this->getEventotipo();
		$this->_view->list_evento_lugar = $this->getEventolugar();
		$this->_view->list_evento_estado = $this->getEventoestado();
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->evento_id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar evento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear evento";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear evento";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un evento  y redirecciona al listado de eventos.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$uploadImage = new Core_Model_Upload_Image();
			if ($_FILES['evento_imagen']['name'] != '') {
				$data['evento_imagen'] = $uploadImage->upload("evento_imagen");
			}
			$id = $this->mainModel->insert($data);
			$this->mainModel->changeOrder($id, $id);
			$data['evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR EVENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un evento  y redirecciona al listado de eventos.
	 *
	 * @return void.
	 */
	public function updateAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->evento_id) {
				$data = $this->getData();
				$uploadImage = new Core_Model_Upload_Image();
				if ($_FILES['evento_imagen']['name'] != '') {
					if ($content->evento_imagen) {
						$uploadImage->delete($content->evento_imagen);
					}
					$data['evento_imagen'] = $uploadImage->upload("evento_imagen");
				} else {
					$data['evento_imagen'] = $content->evento_imagen;
				}
				$this->mainModel->update($data, $id);
			}
			$data['evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR EVENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y elimina un evento  y redirecciona al listado de eventos.
	 *
	 * @return void.
	 */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$uploadImage = new Core_Model_Upload_Image();
					if (isset($content->evento_imagen) && $content->evento_imagen != '') {
						$uploadImage->delete($content->evento_imagen);
					}
					$this->mainModel->deleteRegister($id);
					$data = (array) $content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR EVENTO';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Eventos.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['evento_tipo'] = $this->_getSanitizedParam("evento_tipo");
		$data['evento_nombre'] = $this->_getSanitizedParam("evento_nombre");
		$data['evento_imagen'] = "";
		$data['evento_costo'] = $this->_getSanitizedParam("evento_costo");
		$data['evento_fecha'] = $this->_getSanitizedParam("evento_fecha");
		$data['evento_hora'] = $this->_getSanitizedParam("evento_hora");
		$data['evento_lugar'] = $this->_getSanitizedParam("evento_lugar");
		$data['evento_descripcion'] = $this->_getSanitizedParamHtml("evento_descripcion");
		$data['evento_titulo_politica'] = $this->_getSanitizedParam("evento_titulo_politica");
		$data['evento_descripcion_politica'] = $this->_getSanitizedParamHtml("evento_descripcion_politica");
		if ($this->_getSanitizedParam("evento_bono") == '') {
			$data['evento_bono'] = '0';
		} else {
			$data['evento_bono'] = $this->_getSanitizedParam("evento_bono");
		}
		$data['evento_activo'] = $this->_getSanitizedParam("evento_activo");
		$data['evento_estado'] = $this->resolveEstado($data);
		$data['evento_aforomaximo'] = $this->_getSanitizedParam("evento_aforomaximo");
		$data['evento_porcentaje_pagoinicial'] = $this->_getSanitizedParam("evento_porcentaje_pagoinicial");
		$data['evento_cupo'] = $this->_getSanitizedParam("evento_cupo");
		return $data;
	}

	/**
	 * Determina el estado del evento según su activo y otras reglas de negocio.
	 */
	private function resolveEstado($data)
	{
		if ($data['evento_activo'] == 1) {
			return 'activo';
		}
		return 'inactivo';
	}

	/**
	 * Genera los valores del campo Tipo.
	 *
	 * @return array cadena con los valores del campo Tipo.
	 */
	private function getEventotipo()
	{
		$array = array();
		$array['reserva'] = 'Reserva';
		$array['boleteria'] = 'Boletería';
		$array['reservayboleteria'] = 'Reserva y Boletería';
		return $array;
	}


	/**
	 * Genera los valores del campo Lugar.
	 *
	 * @return array cadena con los valores del campo Lugar.
	 */
	private function getEventolugar()
	{
		$modelData = new Administracion_Model_DbTable_Dependsedes();
		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->sede_id] = $value->sede_nombre;
		}
		return $array;
	}


	/**
	 * Genera los valores del campo Estado.
	 *
	 * @return array cadena con los valores del campo Estado.
	 */
	private function getEventoestado()
	{
		$array = array();
		$array['activo'] = 'Activo';
		$array['inactivo'] = 'Inactivo';
		$array['agotado'] = 'Agotado';
		$array['cancelado'] = 'Cancelado';
		$array['finalizado'] = 'Finalizado';
		return $array;
	}

	/**
	 * Retorna el aforo de una sede en formato JSON para ser consumido por AJAX.
	 *
	 * @return void.
	 */
	public function getaforoAction()
	{
		$this->setLayout('blanco');
		header('Content-Type: application/json');
		$sedeId = $this->_getSanitizedParam("sede_id");
		$model = new Administracion_Model_DbTable_Sedes();
		$sede = $model->getById($sedeId);
		echo json_encode(array('sede_aforo' => $sede ? (int)$sede->sede_aforo : 0));
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
			if ($filters->evento_tipo != '') {
				$filtros = $filtros . " AND evento_tipo ='" . $filters->evento_tipo . "'";
			}
			if ($filters->evento_nombre != '') {
				$filtros = $filtros . " AND evento_nombre LIKE '%" . $filters->evento_nombre . "%'";
			}
			if ($filters->evento_imagen != '') {
				$filtros = $filtros . " AND evento_imagen LIKE '%" . $filters->evento_imagen . "%'";
			}
			if ($filters->evento_costo != '') {
				$filtros = $filtros . " AND evento_costo LIKE '%" . $filters->evento_costo . "%'";
			}
			if ($filters->evento_fecha != '') {
				$filtros = $filtros . " AND evento_fecha LIKE '%" . $filters->evento_fecha . "%'";
			}
			if ($filters->evento_lugar != '') {
				$filtros = $filtros . " AND evento_lugar LIKE '%" . $filters->evento_lugar . "%'";
			}
			if ($filters->evento_bono != '') {
				$filtros = $filtros . " AND evento_bono LIKE '%" . $filters->evento_bono . "%'";
			}
			if ($filters->evento_activo != '') {
				$filtros = $filtros . " AND evento_activo LIKE '%" . $filters->evento_activo . "%'";
			}
			if ($filters->evento_estado != '') {
				$filtros = $filtros . " AND evento_estado ='" . $filters->evento_estado . "'";
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
			$parramsfilter['evento_tipo'] = $this->_getSanitizedParam("evento_tipo");
			$parramsfilter['evento_nombre'] = $this->_getSanitizedParam("evento_nombre");
			$parramsfilter['evento_imagen'] = $this->_getSanitizedParam("evento_imagen");
			$parramsfilter['evento_costo'] = $this->_getSanitizedParam("evento_costo");
			$parramsfilter['evento_fecha'] = $this->_getSanitizedParam("evento_fecha");
			$parramsfilter['evento_lugar'] = $this->_getSanitizedParam("evento_lugar");
			$parramsfilter['evento_bono'] = $this->_getSanitizedParam("evento_bono");
			$parramsfilter['evento_activo'] = $this->_getSanitizedParam("evento_activo");
			$parramsfilter['evento_estado'] = $this->_getSanitizedParam("evento_estado");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}