<?php
/**
* Controlador de Compras que permite la  creacion, edicion  y eliminacion de los Compras del Sistema
*/
class Administracion_comprasController extends Administracion_mainController
{
	/**
	 * $mainModel  instancia del modelo de  base de datos Compras
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
	protected $pages ;

	/**
	 * $namefilter nombre de la variable a la fual se le van a guardar los filtros
	 * @var string
	 */
	protected $namefilter;

	/**
	 * $_csrf_section  nombre de la variable general csrf  que se va a almacenar en la session
	 * @var string
	 */
	protected $_csrf_section = "administracion_compras";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador compras .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Compras();
		$this->namefilter = "parametersfiltercompras";
		$this->route = "/administracion/compras";
		$this->namepages ="pages_compras";
		$this->namepageactual ="page_actual_compras";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  Compras con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de Compras";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters =(object)Session::getInstance()->get($this->namefilter);
        $this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "";
		$list = $this->mainModel->getList($filters,$order);
		$amount = $this->pages;
		$page = $this->_getSanitizedParam("page");
		if (!$page && Session::getInstance()->get($this->namepageactual)) {
		   	$page = Session::getInstance()->get($this->namepageactual);
		   	$start = ($page - 1) * $amount;
		} else if(!$page){
			$start = 0;
		   	$page=1;
			Session::getInstance()->set($this->namepageactual,$page);
		} else {
			Session::getInstance()->set($this->namepageactual,$page);
		   	$start = ($page - 1) * $amount;
		}
		$this->_view->register_number = count($list);
		$this->_view->pages = $this->pages;
		$this->_view->totalpages = ceil(count($list)/$amount);
		$this->_view->page = $page;
		$this->_view->lists = $this->mainModel->getListPages($filters,$order,$start,$amount);
		$this->_view->csrf_section = $this->_csrf_section;
	}

	/**
     * Genera la Informacion necesaria para editar o crear un  Compra  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_compras_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->boleta_compra_id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar Compra";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear Compra";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear Compra";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un Compra  y redirecciona al listado de Compras.
     *
     * @return void.
     */
	public function insertAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {	
			$data = $this->getData();
			$id = $this->mainModel->insert($data);
			
			$data['boleta_compra_id']= $id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'CREAR COMPRA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un Compra  y redirecciona al listado de Compras.
     *
     * @return void.
     */
	public function updateAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->boleta_compra_id) {
				$data = $this->getData();
					$this->mainModel->update($data,$id);
			}
			$data['boleta_compra_id']=$id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'EDITAR COMPRA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un Compra  y redirecciona al listado de Compras.
     *
     * @return void.
     */
	public function deleteAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf ) {
			$id =  $this->_getSanitizedParam("id");
			if (isset($id) && $id > 0) {
				$content = $this->mainModel->getById($id);
				if (isset($content)) {
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR COMPRA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Compras.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		$data['boleta_compra_documento'] = $this->_getSanitizedParam("boleta_compra_documento");
		$data['boleta_compra_nombre'] = $this->_getSanitizedParam("boleta_compra_nombre");
		$data['boleta_compra_telefono'] = $this->_getSanitizedParam("boleta_compra_telefono");
		$data['boleta_compra_email'] = $this->_getSanitizedParam("boleta_compra_email");
		$data['boleta_compra_fechacedula'] = $this->_getSanitizedParam("boleta_compra_fechacedula");
		$data['boleta_compra_fechanacimiento'] = $this->_getSanitizedParam("boleta_compra_fechanacimiento");
		$data['boleta_compra_fecha'] = $this->_getSanitizedParam("boleta_compra_fecha");
		$data['boleta_compra_codigo'] = $this->_getSanitizedParam("boleta_compra_codigo");
		$data['boleta_compra_respuesta'] = $this->_getSanitizedParam("boleta_compra_respuesta");
		$data['boleta_compra_validacion'] = $this->_getSanitizedParam("boleta_compra_validacion");
		$data['boleta_compra_validacion2'] = $this->_getSanitizedParam("boleta_compra_validacion2");
		$data['boleta_compra_entidad'] = $this->_getSanitizedParam("boleta_compra_entidad");
		$data['boleta_compra_total'] = $this->_getSanitizedParam("boleta_compra_total");
		$data['boleta_compra_vendedor'] = $this->_getSanitizedParam("boleta_compra_vendedor");
		if($this->_getSanitizedParam("boleta_compra_validacionentrada") == '' ) {
			$data['boleta_compra_validacionentrada'] = '0';
		} else {
			$data['boleta_compra_validacionentrada'] = $this->_getSanitizedParam("boleta_compra_validacionentrada");
		}
		$data['boleta_compra_fechavalidacion'] = $this->_getSanitizedParam("boleta_compra_fechavalidacion");
		$data['boleta_compra_raw'] = $this->_getSanitizedParam("boleta_compra_raw");
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
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
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
        if ($this->getRequest()->isPost()== true) {
        	Session::getInstance()->set($this->namepageactual,1);
            $parramsfilter = array();Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}