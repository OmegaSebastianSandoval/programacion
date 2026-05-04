<?php
/**
* Controlador de Sedes que permite la  creacion, edicion  y eliminacion de los sedes del Sistema
*/
class Administracion_sedesController extends Administracion_mainController
{
  public $botonpanel = 5;
	/**
	 * $mainModel  instancia del modelo de  base de datos sedes
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
	protected $_csrf_section = "administracion_sedes";

	/**
	 * $namepages nombre de la pvariable en la cual se va a guardar  el numero de seccion en la paginacion del controlador
	 * @var string
	 */
	protected $namepages;



	/**
     * Inicializa las variables principales del controlador sedes .
     *
     * @return void.
     */
	public function init()
	{
		$this->mainModel = new Administracion_Model_DbTable_Sedes();
		$this->namefilter = "parametersfiltersedes";
		$this->route = "/administracion/sedes";
		$this->namepages ="pages_sedes";
		$this->namepageactual ="page_actual_sedes";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
			$this->pages = Session::getInstance()->get($this->namepages);
		} else {
			$this->pages = 20;
		}
		parent::init();
	}


	/**
     * Recibe la informacion y  muestra un listado de  sedes con sus respectivos filtros.
     *
     * @return void.
     */
	public function indexAction()
	{
		$title = "Administración de sedes";
		$this->getLayout()->setTitle($title);
		$this->_view->titlesection = $title;
		$this->filters();
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$filters =(object)Session::getInstance()->get($this->namefilter);
        $this->_view->filters = $filters;
		$filters = $this->getFilter();
		$order = "orden ASC";
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
     * Genera la Informacion necesaria para editar o crear un  sede  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_sedes_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->sede_id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar sede";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear sede";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear sede";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un sede  y redirecciona al listado de sedes.
     *
     * @return void.
     */
	public function insertAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {	
			$data = $this->getData();
			$uploadImage =  new Core_Model_Upload_Image();
			if($_FILES['sede_imagen']['name'] != ''){
				$data['sede_imagen'] = $uploadImage->upload("sede_imagen");
			}
			$uploadDocument =  new Core_Model_Upload_Document();
			if($_FILES['sede_carta']['name'] != ''){
				$data['sede_carta'] = $uploadDocument->upload("sede_carta");
			}
			$id = $this->mainModel->insert($data);
			$this->mainModel->changeOrder($id,$id);
			$data['sede_id']= $id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'CREAR SEDE';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un sede  y redirecciona al listado de sedes.
     *
     * @return void.
     */
	public function updateAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->sede_id) {
				$data = $this->getData();
				$uploadImage =  new Core_Model_Upload_Image();
				if($_FILES['sede_imagen']['name'] != ''){
					if($content->sede_imagen){
						$uploadImage->delete($content->sede_imagen);
					}
					$data['sede_imagen'] = $uploadImage->upload("sede_imagen");
				} else {
					$data['sede_imagen'] = $content->sede_imagen;
				}
				$uploadDocument =  new Core_Model_Upload_Document();
				if($_FILES['sede_carta']['name'] != ''){
					if($content->sede_carta){
						$uploadDocument->delete($content->sede_carta);
					}
					$data['sede_carta'] = $uploadDocument->upload("sede_carta");
				} else {
					$data['sede_carta'] = $content->sede_carta;
				}
				$this->mainModel->update($data,$id);
			}
			$data['sede_id']=$id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'EDITAR SEDE';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un sede  y redirecciona al listado de sedes.
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
					$uploadImage =  new Core_Model_Upload_Image();
					if (isset($content->sede_imagen) && $content->sede_imagen != '') {
						$uploadImage->delete($content->sede_imagen);
					}
					$uploadDocument =  new Core_Model_Upload_Document();
					if (isset($content->sede_carta) && $content->sede_carta != '') {
						$uploadDocument->delete($content->sede_carta);
					}
					$this->mainModel->deleteRegister($id);$data = (array)$content;
					$data['log_log'] = print_r($data,true);
					$data['log_tipo'] = 'BORRAR SEDE';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Sedes.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		$data['sede_nombre'] = $this->_getSanitizedParam("sede_nombre");
		$data['sede_telefono'] = $this->_getSanitizedParam("sede_telefono");
		$data['sede_direccion'] = $this->_getSanitizedParam("sede_direccion");
		$data['sede_imagen'] = "";
		$data['sede_descripcion'] = $this->_getSanitizedParamHtml("sede_descripcion");
		if($this->_getSanitizedParam("sede_estado") == '' ) {
			$data['sede_estado'] = '0';
		} else {
			$data['sede_estado'] = $this->_getSanitizedParam("sede_estado");
		}
		if($this->_getSanitizedParam("sede_aforo") == '' ) {
			$data['sede_aforo'] = '0';
		} else {
			$data['sede_aforo'] = $this->_getSanitizedParam("sede_aforo");
		}
		$data['sede_carta'] = "";
		$data['sede_color'] = $this->_getSanitizedParam("sede_color");
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
            if ($filters->sede_nombre != '') {
                $filtros = $filtros." AND sede_nombre LIKE '%".$filters->sede_nombre."%'";
            }
            if ($filters->sede_telefono != '') {
                $filtros = $filtros." AND sede_telefono LIKE '%".$filters->sede_telefono."%'";
            }
            if ($filters->sede_imagen != '') {
                $filtros = $filtros." AND sede_imagen LIKE '%".$filters->sede_imagen."%'";
            }
            if ($filters->sede_estado != '') {
                $filtros = $filtros." AND sede_estado LIKE '%".$filters->sede_estado."%'";
            }
            if ($filters->sede_aforo != '') {
                $filtros = $filtros." AND sede_aforo LIKE '%".$filters->sede_aforo."%'";
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
        if ($this->getRequest()->isPost()== true) {
        	Session::getInstance()->set($this->namepageactual,1);
            $parramsfilter = array();
					$parramsfilter['sede_nombre'] =  $this->_getSanitizedParam("sede_nombre");
					$parramsfilter['sede_telefono'] =  $this->_getSanitizedParam("sede_telefono");
					$parramsfilter['sede_imagen'] =  $this->_getSanitizedParam("sede_imagen");
					$parramsfilter['sede_estado'] =  $this->_getSanitizedParam("sede_estado");
					$parramsfilter['sede_aforo'] =  $this->_getSanitizedParam("sede_aforo");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}