<?php

/**
 *
 */

class Administracion_mainController extends Controllers_Abstract
{
	protected $namepages;
	/**
	 * Valor por defecto para el boton activo en el panel (evita warnings si no lo define el hijo)
	 * @var int
	 */
	protected $botonpanel = 0;

	/**
	 * Modelo principal que usan muchos controladores (declarado aquí para evitar warnings)
	 * @var mixed
	 */
	protected $mainModel;

	/**
	 * Nombre de la variable donde se guardan los filtros para el controlador
	 * @var string
	 */
	protected $namefilter = '';

	/**
	 * Ruta base del controlador
	 * @var string
	 */
	protected $route = '';

	/**
	 * Cantidad de registros por página por defecto
	 * @var int
	 */
	protected $pages = 20;

	/**
	 * Nombre de la variable que guarda la página actual en la paginación
	 * @var string
	 */
	protected $namepageactual;

	/**
	 * Sección CSRF por defecto (puede sobrescribirse en los hijos)
	 * @var string
	 */
	protected $_csrf_section = '';



	public function init()
	{
		$this->_view->botonpanel = $this->botonpanel;
		$this->setLayout('administracion_panel');
		$botoneralateral = $this->_view->getRoutPHP('modules/administracion/Views/partials/botoneralateral.php');
		$this->getLayout()->setData("panel_botones", $botoneralateral);
		$botonerasuperior = $this->_view->getRoutPHP('modules/administracion/Views/partials/botonerasuperior.php');
		$this->getLayout()->setData("panel_header", $botonerasuperior);
		if ((Session::getInstance()->get("kt_login_id") <= 0 || Session::getInstance()->get("kt_login_id", "") == '')) {
			header('Location: /administracion/');
			exit;
		}

		$fingerprint = md5($_SERVER['HTTP_USER_AGENT']);
		$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$pathSegments = explode('/', trim($urlPath, '/'));
		$basePrefix = '/' . (isset($pathSegments[0]) ? $pathSegments[0] : '') . '/';
		$urlFingerprint = md5($basePrefix);
		if (
			Session::getInstance()->get("kt_login_fingerprint") !== $fingerprint ||
			Session::getInstance()->get("kt_login_url_fingerprint") !== $urlFingerprint
		) {
			session_destroy();
			header('Location: /administracion/?error_session=1');
			exit;
		}

		$inactivo = 9000000;
		if (Session::getInstance()->get('tiempo') != '') {
			$vida_session = time() - Session::getInstance()->get('tiempo');
			if ($vida_session > $inactivo) {
				session_destroy();
				header('Location: /administracion/?inactividad==1');
				exit;
			}
		}
		Session::getInstance()->set("tiempo", time());
	}

	public function changepageAction()
	{
		Session::getInstance()->set($this->namepages, $this->_getSanitizedParam("pages"));
	}

	public function orderAction()
	{
		$this->setLayout('blanco');
		$csrf = $this->_getSanitizedParam("csrf");
		if (Session::getInstance()->get('csrf')[$this->_csrf_section] == $csrf) {
			$id1 = $this->_getSanitizedParam("id1");
			$id2 = $this->_getSanitizedParam("id2");
			if (isset($id1) && $id1 > 0 && isset($id2) && $id2 > 0) {
				$content1 = $this->mainModel->getById($id1);
				$content2 = $this->mainModel->getById($id2);
				if (isset($content1) && isset($content2)) {
					$order1 = $content1->orden;
					$order2 = $content2->orden;
					$this->mainModel->changeOrder($order2, $id1);
					$this->mainModel->changeOrder($order1, $id2);
				}
			}
		}
	}

	public function deleteimageAction()
	{
		$this->setLayout('blanco');
		header('Content-Type:application/json');
		$campo = $this->_getSanitizedParam("campo");
		$id = $this->_getSanitizedParam("id");
		$csrf = $this->_getSanitizedParam("csrf");
		$elimino = 0;
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->$campo != '') {
				$modelUploadImage = new Core_Model_Upload_Image();
				$this->mainModel->editField($id, $campo, '');
				$modelUploadImage->delete($content->$campo);
				$elimino = 1;
			}
		}
		echo json_encode(array('elimino' => $elimino));
	}
	public function deletearchivoAction()
	{
		$this->setLayout('blanco');
		header('Content-Type:application/json');
		$campo = $this->_getSanitizedParam("campo");
		$id = $this->_getSanitizedParam("id");
		$csrf = $this->_getSanitizedParam("csrf");
		$elimino = 0;
		if (Session::getInstance()->get('csrf')[$this->_getSanitizedParam("csrf_section")] == $csrf) {
			$id = $this->_getSanitizedParam("id");
			$content = $this->mainModel->getById($id);
			if ($content->$campo != '') {
				$modelUploadDocument = new Core_Model_Upload_Document();
				$this->mainModel->editField($id, $campo, '');
				$modelUploadDocument->delete($content->$campo);
				$elimino = 1;
			}
		}
		echo json_encode(array('elimino' => $elimino));
	}

	public function uploadeditorimageAction()
	{
		$this->setLayout('blanco');
		header('Content-Type:application/json');

		$csrf = $this->_getSanitizedParam("csrf");
		$csrfSection = $this->_getSanitizedParam("csrf_section");

		if (!$csrfSection || Session::getInstance()->get('csrf')[$csrfSection] != $csrf) {
			http_response_code(403);
			echo json_encode(array('error' => array('message' => 'Token CSRF invalido.')));
			return;
		}

		if (!isset($_FILES['upload']) || !is_uploaded_file($_FILES['upload']['tmp_name'])) {
			http_response_code(400);
			echo json_encode(array('error' => array('message' => 'No se recibio ningun archivo.')));
			return;
		}

		$file = $_FILES['upload'];
		if ((int) $file['error'] !== UPLOAD_ERR_OK) {
			http_response_code(400);
			echo json_encode(array('error' => array('message' => 'Error al subir el archivo.')));
			return;
		}

		$maxSize = 5 * 1024 * 1024;
		if ((int) $file['size'] <= 0 || (int) $file['size'] > $maxSize) {
			http_response_code(400);
			echo json_encode(array('error' => array('message' => 'La imagen supera el tamano permitido (5MB).')));
			return;
		}

		$mime = '';
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			if ($finfo) {
				$mime = finfo_file($finfo, $file['tmp_name']);
				finfo_close($finfo);
			}
		}

		$allowed = array(
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
			'image/gif' => 'gif',
			'image/webp' => 'webp'
		);

		if (!isset($allowed[$mime])) {
			http_response_code(400);
			echo json_encode(array('error' => array('message' => 'Formato de imagen no permitido.')));
			return;
		}

		$imgInfo = @getimagesize($file['tmp_name']);
		if ($imgInfo === false) {
			http_response_code(400);
			echo json_encode(array('error' => array('message' => 'El archivo no es una imagen valida.')));
			return;
		}

		$uploadPath = PUBLIC_PATH . 'upload/';
		if (!file_exists($uploadPath)) {
			mkdir($uploadPath, 0777, true);
		}

		$random = bin2hex(random_bytes(16));
		$fileName = 'editor_' . $random . '.' . $allowed[$mime];
		$targetPath = $uploadPath . $fileName;

		if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
			http_response_code(500);
			echo json_encode(array('error' => array('message' => 'No fue posible guardar la imagen.')));
			return;
		}

		echo json_encode(array('url' => '/upload/' . $fileName));
	}

}