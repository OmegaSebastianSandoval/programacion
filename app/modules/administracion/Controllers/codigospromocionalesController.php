<?php
/**
 * Controlador de Codigospromocionales que permite la  creacion, edicion  y eliminacion de los C&oacute;digos Promocionales del Sistema
 */
class Administracion_codigospromocionalesController extends Administracion_mainController
{
  public $botonpanel = 7;
	/**
	 * $mainModel  instancia del modelo de  base de datos C&oacute;digos Promocionales
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
	protected $_csrf_section = "administracion_codigospromocionales";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador codigospromocionales .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Codigospromocionales();
		$this->namefilter = "parametersfiltercodigospromocionales";
		$this->route = "/administracion/codigospromocionales";
		$this->namepages = "pages_codigospromocionales";
		$this->namepageactual = "page_actual_codigospromocionales";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  C&oacute;digos Promocionales con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de C&oacute;digos Promocionales";
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
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->list_evento = $this->getEvento();
	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  C&oacute;digo Promocional  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_codigospromocionales_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_tipo = $this->getTipo();
		$this->_view->list_evento = $this->getEvento();
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar C&oacute;digo Promocional";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear C&oacute;digo Promocional";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear C&oacute;digo Promocional";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
	 * Inserta la informacion de un C&oacute;digo Promocional  y redirecciona al listado de C&oacute;digos Promocionales.
	 *
	 * @return void.
	 */
	public function insertAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$data = $this->getData();
			$id = $this->mainModel->insert($data);

			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR C&OACUTE;DIGO PROMOCIONAL';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un C&oacute;digo Promocional  y redirecciona al listado de C&oacute;digos Promocionales.
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
			if ($content->id) {
				$data = $this->getData();
				$this->mainModel->update($data, $id);
			}
			$data['id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR C&OACUTE;DIGO PROMOCIONAL';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe un identificador  y elimina un C&oacute;digo Promocional  y redirecciona al listado de C&oacute;digos Promocionales.
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
					$this->mainModel->deleteRegister($id);
					$data = (array) $content;
					$data['log_log'] = print_r($data, true);
					$data['log_tipo'] = 'BORRAR C&OACUTE;DIGO PROMOCIONAL';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		header('Location: ' . $this->route . '' . '');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Codigospromocionales.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		$data['codigo'] = $this->_getSanitizedParam("codigo");
		$data['tipo'] = $this->_getSanitizedParam("tipo");
		if ($this->_getSanitizedParam("valor") == '') {
			$data['valor'] = '0';
		} else {
			$data['valor'] = $this->_getSanitizedParam("valor");
		}
		if ($this->_getSanitizedParam("porcentaje") == '') {
			$data['porcentaje'] = '0';
		} else {
			$data['porcentaje'] = $this->_getSanitizedParam("porcentaje");
		}
		if ($this->_getSanitizedParam("evento") == '') {
			$data['evento'] = '0';
		} else {
			$data['evento'] = $this->_getSanitizedParam("evento");
		}
		$data['usado'] = $this->_getSanitizedParam("usado");
		$data['fecha_uso'] = $this->_getSanitizedParam("fecha_uso");
		$data['activo'] = $this->_getSanitizedParam("activo");
		$data['fecha'] = $this->_getSanitizedParam("fecha");
		if ($this->_getSanitizedParam("tipo") === 'varios-usos' && $this->_getSanitizedParam("cantidad_usos_maxima") != '') {
			$data['cantidad_usos_maxima'] = (int) $this->_getSanitizedParam("cantidad_usos_maxima");
		} else {
			$data['cantidad_usos_maxima'] = null;
		}
		$data['cantidad_usos_maxima'] = $this->_getSanitizedParam("cantidad_usos_maxima");

		return $data;
	}

	/**
	 * Retorna la fecha del evento en formato JSON para ser consumido por AJAX.
	 *
	 * @return void.
	 */
	public function getfechaeventoAction()
	{
		$this->setLayout('blanco');
		header('Content-Type: application/json');
		$eventoId = $this->_getSanitizedParam("evento_id");
		$model = new Administracion_Model_DbTable_Dependeventos();
		$evento = $model->getById($eventoId);
		echo json_encode(array('evento_fecha' => $evento ? $evento->evento_fecha : null));
	}

	/**
	 * Genera los valores del campo Tipo.
	 *
	 * @return array cadena con los valores del campo Tipo.
	 */
	private function getTipo()
	{
		$array = array();
		$array['uso-unico'] = 'Uso único';
		$array['varios-usos'] = 'Varios usos';
		return $array;
	}


	/**
	 * Genera los valores del campo Evento.
	 *
	 * @return array cadena con los valores del campo Evento.
	 */
	private function getEvento()
	{
		$modelData = new Administracion_Model_DbTable_Dependeventos();
		$hoy = date("Y-m-d H:i:s");
		$data = $modelData->getList("evento_fecha >= '$hoy' AND evento_activo = 1");
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->evento_id] = $value->evento_nombre;
		}
		return $array;
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
			if ($filters->codigo != '') {
				$filtros = $filtros . " AND codigo LIKE '%" . $filters->codigo . "%'";
			}
			if ($filters->tipo != '') {
				$filtros = $filtros . " AND tipo ='" . $filters->tipo . "'";
			}
			if ($filters->valor != '') {
				$filtros = $filtros . " AND valor LIKE '%" . $filters->valor . "%'";
			}
			if ($filters->porcentaje != '') {
				$filtros = $filtros . " AND porcentaje LIKE '%" . $filters->porcentaje . "%'";
			}
			if ($filters->evento != '') {
				$filtros = $filtros . " AND evento LIKE '%" . $filters->evento . "%'";
			}
			if ($filters->usado != '') {
				$filtros = $filtros . " AND usado LIKE '%" . $filters->usado . "%'";
			}
			if ($filters->fecha_uso != '') {
				$filtros = $filtros . " AND fecha_uso LIKE '%" . $filters->fecha_uso . "%'";
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
			$parramsfilter['codigo'] = $this->_getSanitizedParam("codigo");
			$parramsfilter['tipo'] = $this->_getSanitizedParam("tipo");
			$parramsfilter['valor'] = $this->_getSanitizedParam("valor");
			$parramsfilter['porcentaje'] = $this->_getSanitizedParam("porcentaje");
			$parramsfilter['evento'] = $this->_getSanitizedParam("evento");
			$parramsfilter['usado'] = $this->_getSanitizedParam("usado");
			$parramsfilter['fecha_uso'] = $this->_getSanitizedParam("fecha_uso");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}