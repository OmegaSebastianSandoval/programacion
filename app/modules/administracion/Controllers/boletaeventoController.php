<?php
/**
 * Controlador de Boletaevento que permite la  creacion, edicion  y eliminacion de los Boletas evento del Sistema
 */
class Administracion_boletaeventoController extends Administracion_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos Boletas evento
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
	protected $_csrf_section = "administracion_boletaevento";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
	 * Inicializa las variables principales del controlador boletaevento .
	 *
	 * @return void.
	 */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Boletaevento();
		$this->namefilter = "parametersfilterboletaevento";
		$this->route = "/administracion/boletaevento";
		$this->namepages = "pages_boletaevento";
		$this->namepageactual = "page_actual_boletaevento";
		$this->_view->route = $this->route;
		if (Session::getInstance()->get($this->namepages)) {
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
	 * Recibe la informacion y  muestra un listado de  Boletas evento con sus respectivos filtros.
	 *
	 * @return void.
	 */
	public function indexAction()
	{
		$title = "Administración de Boletas evento";
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
		$this->_view->list_boleta_evento_tipo = $this->getBoletaeventotipo();
		$this->_view->boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");

		$eventoModel = new Administracion_Model_DbTable_Eventos();
		$evento = $eventoModel->getById($this->_getSanitizedParam("boleta_evento_evento"));
		$this->_view->evento = $evento;
		$this->_view->list_evento = $this->getEvento();



	}

	/**
	 * Genera la Informacion necesaria para editar o crear un  Boleta evento  y muestra su formulario
	 *
	 * @return void.
	 */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_boletaevento_" . date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$this->_view->list_boleta_evento_tipo = $this->getBoletaeventotipo();
		$this->_view->boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");
		$this->mostrarAforo($this->_getSanitizedParam("boleta_evento_evento"));

		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if ($content->boleta_evento_id) {
				$this->_view->content = $content;
				$this->_view->routeform = $this->route . "/update";
				$title = "Actualizar Boleta evento";
				$this->mostrarAforo($content->boleta_evento_evento);


			} else {
				$this->_view->routeform = $this->route . "/insert";
				$title = "Crear Boleta evento";
			}
		} else {
			$this->_view->routeform = $this->route . "/insert";
			$title = "Crear Boleta evento";
		}
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
	}

	private function mostrarAforo($eventoId)
	{
		$eventoModel = new Administracion_Model_DbTable_Eventos();
		$evento = $eventoModel->getById($eventoId);
		$this->_view->evento = $evento;

		$aforoMaximo = $evento->evento_aforomaximo;
		$boletasEvento = $this->mainModel->getList(" boleta_evento_evento = '$eventoId' ");
		$cantidadBoletas = 0;
		foreach ($boletasEvento as $value) {
			$cantidadBoletas += $value->boleta_evento_cantidad;
		}

		$this->_view->boleta_evento_saldo = $aforoMaximo - $cantidadBoletas;
		$this->_view->boleta_evento_aforomaximo = $aforoMaximo;
		$this->_view->boleta_evento_cantidadactual = $cantidadBoletas;

	}

	/**
	 * Inserta la informacion de un Boleta evento  y redirecciona al listado de Boletas evento.
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

			$data['boleta_evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'CREAR BOLETA EVENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");
		header('Location: ' . $this->route . '?boleta_evento_evento=' . $boleta_evento_evento . '');
	}

	/**
	 * Recibe un identificador  y Actualiza la informacion de un Boleta evento  y redirecciona al listado de Boletas evento.
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
			if ($content->boleta_evento_id) {
				$data = $this->getData();
				$this->mainModel->update($data, $id);
			}
			$data['boleta_evento_id'] = $id;
			$data['log_log'] = print_r($data, true);
			$data['log_tipo'] = 'EDITAR BOLETA EVENTO';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		$boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");
		header('Location: ' . $this->route . '?boleta_evento_evento=' . $boleta_evento_evento . '');
	}

	/**
	 * Recibe un identificador  y elimina un Boleta evento  y redirecciona al listado de Boletas evento.
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
					$data['log_tipo'] = 'BORRAR BOLETA EVENTO';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data);
				}
			}
		}
		$boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");
		header('Location: ' . $this->route . '?boleta_evento_evento=' . $boleta_evento_evento . '');
	}

	/**
	 * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Boletaevento.
	 *
	 * @return array con toda la informacion recibida del formulario.
	 */
	private function getData()
	{
		$data = array();
		if ($this->_getSanitizedParam("boleta_evento_tipo") == '') {
			$data['boleta_evento_tipo'] = '0';
		} else {
			$data['boleta_evento_tipo'] = $this->_getSanitizedParam("boleta_evento_tipo");
		}
		$data['boleta_evento_cantidad'] = $this->_getSanitizedParam("boleta_evento_cantidad");
		$data['boleta_evento_saldo'] = $this->_getSanitizedParam("boleta_evento_saldo");
		$data['boleta_evento_evento'] = $this->_getSanitizedParamHtml("boleta_evento_evento");
		$data['boleta_evento_precio'] = $this->_getSanitizedParam("boleta_evento_precio");
		$data['boleta_evento_precioadicional'] = $this->_getSanitizedParam("boleta_evento_precioadicional");
		$data['boleta_evento_fechalimite'] = $this->_getSanitizedParam("boleta_evento_fechalimite");
		$data['boleta_evento_horalimite'] = '';
		return $data;
	}

	/**
	 * Genera los valores del campo Tipo evento.
	 *
	 * @return array cadena con los valores del campo Tipo evento.
	 */
	private function getBoletaeventotipo()
	{
		$modelData = new Administracion_Model_DbTable_Boletatipo();

		$data = $modelData->getList();
		$array = array();
		foreach ($data as $key => $value) {
			$array[$value->boleta_tipo_id] = $value->boleta_tipo_nombre;
		}
		return $array;
	}
	private function getEvento()
	{
		$modelData = new Administracion_Model_DbTable_Dependeventos();
		$data = $modelData->getList();
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
		$boleta_evento_evento = $this->_getSanitizedParam("boleta_evento_evento");
		$filtros = $filtros . " AND boleta_evento_evento = '$boleta_evento_evento' ";
		if (Session::getInstance()->get($this->namefilter) != "") {
			$filters = (object) Session::getInstance()->get($this->namefilter);
			if ($filters->boleta_evento_tipo != '') {
				$filtros = $filtros . " AND boleta_evento_tipo ='" . $filters->boleta_evento_tipo . "'";
			}
			if ($filters->boleta_evento_cantidad != '') {
				$filtros = $filtros . " AND boleta_evento_cantidad LIKE '%" . $filters->boleta_evento_cantidad . "%'";
			}
			if ($filters->boleta_evento_evento != '') {
				$filtros = $filtros . " AND boleta_evento_evento LIKE '%" . $filters->boleta_evento_evento . "%'";
			}
			if ($filters->boleta_evento_precio != '') {
				$filtros = $filtros . " AND boleta_evento_precio LIKE '%" . $filters->boleta_evento_precio . "%'";
			}
			if ($filters->boleta_evento_fechalimite != '') {
				$filtros = $filtros . " AND boleta_evento_fechalimite LIKE '%" . $filters->boleta_evento_fechalimite . "%'";
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
			$parramsfilter['boleta_evento_tipo'] = $this->_getSanitizedParam("boleta_evento_tipo");
			$parramsfilter['boleta_evento_cantidad'] = $this->_getSanitizedParam("boleta_evento_cantidad");
			$parramsfilter['boleta_evento_evento'] = $this->_getSanitizedParam("boleta_evento_evento");
			$parramsfilter['boleta_evento_precio'] = $this->_getSanitizedParam("boleta_evento_precio");
			$parramsfilter['boleta_evento_fechalimite'] = $this->_getSanitizedParam("boleta_evento_fechalimite");
			Session::getInstance()->set($this->namefilter, $parramsfilter);
		}
		if ($this->_getSanitizedParam("cleanfilter") == 1) {
			Session::getInstance()->set($this->namefilter, '');
			Session::getInstance()->set($this->namepageactual, 1);
		}
	}
}