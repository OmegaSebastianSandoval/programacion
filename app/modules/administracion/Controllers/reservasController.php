<?php
/**
* Controlador de Reservas que permite la  creacion, edicion  y eliminacion de los Reservas del Sistema
*/
class Administracion_reservasController extends Administracion_mainController
{
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
		$this->namepages ="pages_reservas";
		$this->namepageactual ="page_actual_reservas";
		$this->_view->route = $this->route;
		if(Session::getInstance()->get($this->namepages)){
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
     * Genera la Informacion necesaria para editar o crear un  Reserva  y muestra su formulario
     *
     * @return void.
     */
	public function manageAction()
	{
		$this->_view->route = $this->route;
		$this->_csrf_section = "manage_reservas_".date("YmdHis");
		$this->_csrf->generateCode($this->_csrf_section);
		$this->_view->csrf_section = $this->_csrf_section;
		$this->_view->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		$id = $this->_getSanitizedParam("id");
		if ($id > 0) {
			$content = $this->mainModel->getById($id);
			if($content->reserva_id){
				$this->_view->content = $content;
				$this->_view->routeform = $this->route."/update";
				$title = "Actualizar Reserva";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}else{
				$this->_view->routeform = $this->route."/insert";
				$title = "Crear Reserva";
				$this->getLayout()->setTitle($title);
				$this->_view->titlesection = $title;
			}
		} else {
			$this->_view->routeform = $this->route."/insert";
			$title = "Crear Reserva";
			$this->getLayout()->setTitle($title);
			$this->_view->titlesection = $title;
		}
	}

	/**
     * Inserta la informacion de un Reserva  y redirecciona al listado de Reservas.
     *
     * @return void.
     */
	public function insertAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {	
			$data = $this->getData();
			$id = $this->mainModel->insert($data);
			
			$data['reserva_id']= $id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'CREAR RESERVA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y Actualiza la informacion de un Reserva  y redirecciona al listado de Reservas.
     *
     * @return void.
     */
	public function updateAction(){
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf ) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->reserva_id) {
				$data = $this->getData();
					$this->mainModel->update($data,$id);
			}
			$data['reserva_id']=$id;
			$data['log_log'] = print_r($data,true);
			$data['log_tipo'] = 'EDITAR RESERVA';
			$logModel = new Administracion_Model_DbTable_Log();
			$logModel->insert($data);}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe un identificador  y elimina un Reserva  y redirecciona al listado de Reservas.
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
					$data['log_tipo'] = 'BORRAR RESERVA';
					$logModel = new Administracion_Model_DbTable_Log();
					$logModel->insert($data); }
			}
		}
		header('Location: '.$this->route.''.'');
	}

	/**
     * Recibe la informacion del formulario y la retorna en forma de array para la edicion y creacion de Reservas.
     *
     * @return array con toda la informacion recibida del formulario.
     */
	private function getData()
	{
		$data = array();
		if($this->_getSanitizedParam("reserva_compra_id") == '' ) {
			$data['reserva_compra_id'] = '0';
		} else {
			$data['reserva_compra_id'] = $this->_getSanitizedParam("reserva_compra_id");
		}
		if($this->_getSanitizedParam("reserva_evento_id_fk") == '' ) {
			$data['reserva_evento_id_fk'] = '0';
		} else {
			$data['reserva_evento_id_fk'] = $this->_getSanitizedParam("reserva_evento_id_fk");
		}
		if($this->_getSanitizedParam("reserva_evento") == '' ) {
			$data['reserva_evento'] = '0';
		} else {
			$data['reserva_evento'] = $this->_getSanitizedParam("reserva_evento");
		}
		$data['reserva_tipo_origen'] = $this->_getSanitizedParam("reserva_tipo_origen");
		if($this->_getSanitizedParam("reserva_cantidad_personas") == '' ) {
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
        if (Session::getInstance()->get($this->namefilter)!="") {
            $filters =(object)Session::getInstance()->get($this->namefilter);
            if ($filters->reserva_compra_id != '') {
                $filtros = $filtros." AND reserva_compra_id LIKE '%".$filters->reserva_compra_id."%'";
            }
            if ($filters->reserva_evento_id_fk != '') {
                $filtros = $filtros." AND reserva_evento_id_fk LIKE '%".$filters->reserva_evento_id_fk."%'";
            }
            if ($filters->reserva_evento != '') {
                $filtros = $filtros." AND reserva_evento LIKE '%".$filters->reserva_evento."%'";
            }
            if ($filters->reserva_tipo_origen != '') {
                $filtros = $filtros." AND reserva_tipo_origen LIKE '%".$filters->reserva_tipo_origen."%'";
            }
            if ($filters->reserva_cantidad_personas != '') {
                $filtros = $filtros." AND reserva_cantidad_personas LIKE '%".$filters->reserva_cantidad_personas."%'";
            }
            if ($filters->reserva_total != '') {
                $filtros = $filtros." AND reserva_total LIKE '%".$filters->reserva_total."%'";
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
					$parramsfilter['reserva_compra_id'] =  $this->_getSanitizedParam("reserva_compra_id");
					$parramsfilter['reserva_evento_id_fk'] =  $this->_getSanitizedParam("reserva_evento_id_fk");
					$parramsfilter['reserva_evento'] =  $this->_getSanitizedParam("reserva_evento");
					$parramsfilter['reserva_tipo_origen'] =  $this->_getSanitizedParam("reserva_tipo_origen");
					$parramsfilter['reserva_cantidad_personas'] =  $this->_getSanitizedParam("reserva_cantidad_personas");
					$parramsfilter['reserva_total'] =  $this->_getSanitizedParam("reserva_total");Session::getInstance()->set($this->namefilter, $parramsfilter);
        }
        if ($this->_getSanitizedParam("cleanfilter") == 1) {
            Session::getInstance()->set($this->namefilter, '');
            Session::getInstance()->set($this->namepageactual,1);
        }
    }
}