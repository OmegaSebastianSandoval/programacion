<?php

/**
 *
 */

class Administracion_loginuserController extends Controllers_Abstract
{

	protected $mainModel;
	protected $route;
	protected $_csrf_section = "login_admin";
	public $csrf;

	public function init()
	{
		$this->mainModel = new Core_Model_DbTable_User();
		$this->route = "/administracion/users";
		$this->_view->route = $this->route;
		$this->csrf = Session::getInstance()->get('csrf')[$this->_csrf_section];
		parent::init();
	}

	public function indexAction()
	{
		Session::getInstance()->set("error_login", "");
		$isPost = $this->getRequest()->isPost();
		$user = $this->_getSanitizedParam("user");
		$password = $this->_getSanitizedParam("password");
		$csrf = $this->_getSanitizedParam("csrf");

		if (!($isPost == true && $user && $password && $this->csrf == $csrf)) {
			Session::getInstance()->set("error_login", "Lo sentimos ocurrio un error intente de nuevo.");
			header('Location: /administracion/');
			exit;
		}

		$userModel = new core_Model_DbTable_User();
		if ($userModel->autenticateUser($user, $password) != true) {
			Session::getInstance()->set("error_login", "El usuario o contraseña son incorrectos.");
			header('Location: /administracion/');
			exit;
		}

		$resUser = $userModel->searchUserByUser($user);
		if ($resUser->user_state != 1) {
			Session::getInstance()->set("error_login", "Lo sentimos ocurrio un error intente de nuevo o contacte al administrador.");
			header('Location: /administracion/');
			exit;
		}

		Session::getInstance()->set("kt_login_id", $resUser->user_id);
		Session::getInstance()->set("kt_login_level", $resUser->user_level);
		Session::getInstance()->set("kt_login_user", $resUser->user_user);
		Session::getInstance()->set("kt_login_name", $resUser->user_names . " " . $resUser->user_lastnames);

		session_regenerate_id(true);

		$ua = $_SERVER['HTTP_USER_AGENT'];
		$browserFingerprint = md5($ua);
		Session::getInstance()->set("kt_login_fingerprint", $browserFingerprint);
		$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$pathSegments = explode('/', trim($urlPath, '/'));
		$basePrefix = '/' . (isset($pathSegments[0]) ? $pathSegments[0] : '') . '/';
		$urlFingerprint = md5($basePrefix);
		Session::getInstance()->set("kt_login_url_fingerprint", $urlFingerprint);

		//LOG
		$data['log_tipo'] = "LOGIN";
		$data['log_usuario'] = $resUser->user_user;
		$logModel = new Administracion_Model_DbTable_Log();
		$logModel->insert($data);

		header("Location: /administracion/welcome");
		exit;
	}


	public function forgotpasswordAction()
	{
		$this->setLayout('blanco');
		$this->_csrf_section = "login_admin";
		$modelUser = new Core_Model_DbTable_User();
		$email = $this->_getSanitizedParam("email");

		$filter = " user_email = '" . $email . "' ";
		$user = $modelUser->getList($filter, "")[0];

		if (!$user) {
			Session::getInstance()->set("error_olvido", "Lo sentimos ocurrio un error intente de nuevo.");
			header('Location: /administracion/index/olvido');
			exit;
		}

		$id = $user->user_id;
		$sendingemail = new Core_Model_Sendingemail($this->_view);
		$code = Session::getInstance()->get('csrf')['page_csrf'];
		$modelUser->editCode($id, $code);
		$user = $modelUser->getById($user->user_id);

		if ($sendingemail->forgotpassword($user) != true) {
			Session::getInstance()->set("error_olvido", "Lo sentimos ocurrio un error y no se pudo enviar su mensaje");
			header('Location: /administracion/index/olvido');
			exit;
		}

		Session::getInstance()->set("mensaje_olvido", "Se ha enviado a su correo un mensaje de recuperación de contraseña.");
		Session::getInstance()->set("error_olvido", "");

		header('Location: /administracion/index/olvido');
		exit;
	}

	public function logoutAction()
	{
		//LOG
		$data['log_tipo'] = "LOGOUT";
		$logModel = new Administracion_Model_DbTable_Log();
		$logModel->insert($data);

		Session::getInstance()->set("kt_login_id", "");
		Session::getInstance()->set("kt_login_level", "");
		Session::getInstance()->set("kt_login_user", "");
		Session::getInstance()->set("kt_login_name", "");
		header('Location: /administracion/');
		exit;
	}

}