<?php

if (isset($_GET['cookie_consent']) && $_GET['cookie_consent'] === 'accept') {
  $secureCookie = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
  setcookie('cookie_consent', 'accepted', [
    'expires' => time() + (365 * 24 * 60 * 60),
    'path' => '/',
    'secure' => $secureCookie,
    'httponly' => true,
    'samesite' => 'Lax'
  ]);

  $_COOKIE['cookie_consent'] = 'accepted';

  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }

  $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
  $currentPath = strtok($requestUri, '?');
  $queryParams = $_GET;
  unset($queryParams['cookie_consent']);
  $queryString = http_build_query($queryParams);

  header('Location: ' . $currentPath . (!empty($queryString) ? '?' . $queryString : ''));
  exit;
}

$cookieConsentAccepted = isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] === 'accepted';
if ($cookieConsentAccepted && session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('FRAMEWORK_PATH', ROOT . '../framework' . DS);
define('APP_PATH', ROOT . '../app' . DS);
define('VIEWS_PATH', APP_PATH . 'View' . DS);
define('LAYOUTS_PATH', APP_PATH . 'layout' . DS);
define('IMAGE_PATH', APP_PATH . "../public/images/");
define('FILE_PATH', APP_PATH . "../public/files/");
define('PUBLIC_PATH', APP_PATH . "../public/");
define('PDFS_PATH', APP_PATH . "../public/pdfs/");

date_default_timezone_set('America/Bogota');

require_once FRAMEWORK_PATH . 'Config/Config.php';
set_include_path(
  implode(
    PATH_SEPARATOR,
    array(
      get_include_path(),
      FRAMEWORK_PATH
    )
  )
);

function framework_autoload($classname)
{
  $ruta = explode('_', $classname);
  if (substr(end($ruta), -10) == 'Controller') {
    $file = strtolower($ruta[0]) . '/Controllers/' . $ruta[1] . '.php';
    if (file_exists(APP_PATH . 'modules/' . $file)) {
      require_once(APP_PATH . 'modules/' . $file);
    }
  } else if (isset($ruta[1]) && $ruta[1] == 'Model') {
    $file = strtolower($ruta[0]) . "/Models/";
    unset($ruta[0]);
    unset($ruta[1]);
    $file = $file . implode("/", $ruta) . '.php';
    if (file_exists(APP_PATH . 'modules/' . $file)) {
      require_once(APP_PATH . 'modules/' . $file);
    }
  } else {
    $file = implode("/", $ruta) . '.php';
    if (file_exists(APP_PATH . '../framework/' . $file)) {
      require_once($file);
    }
  }
}
spl_autoload_register('framework_autoload');

include(APP_PATH . '/../vendor/autoload.php');
$env = "development";
if (strpos($_SERVER['HTTP_HOST'], "xovis.omegasolucionesweb.com") !== false) {
  $env = "staging";
} else if (strpos($_SERVER['HTTP_HOST'], "...") !== false) {
  $env = "production";
}
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $env);

if (!headers_sent()) {
  $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
  $csp = [
    "default-src 'self' https: data: blob:",
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:",
    "style-src 'self' 'unsafe-inline' https:",
    "img-src 'self' data: blob: https:",
    "font-src 'self' data: https:",
    "connect-src 'self' https:",
    "frame-src 'self' https:",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
    "frame-ancestors 'self'"
  ];

  if ($isHttps) {
    $csp[] = 'upgrade-insecure-requests';
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
  }

  header('Content-Security-Policy: ' . implode('; ', $csp));
  header('X-Frame-Options: SAMEORIGIN');
  header('X-Content-Type-Options: nosniff');
  header('Referrer-Policy: strict-origin-when-cross-origin');
  header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

error_reporting(0);
ini_set('display_errors', 0);

if (isset($_GET['debug']) && $_GET['debug'] == "1") {
  error_reporting(E_ALL);

}
ini_set('display_errors', 1);
if (!file_exists(IMAGE_PATH)) {
  mkdir(IMAGE_PATH, 0777, true);
}

if (!file_exists(FILE_PATH)) {
  mkdir(FILE_PATH, 0777, true);
}

// Mostrar una pantalla amigable cuando ocurre un error fatal
register_shutdown_function(function () {
  $error = error_get_last();
  if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
    if (ob_get_length()) {
      @ob_end_clean();
    }
    http_response_code(500);
    $bg = '#ffecec';
    $title = 'Error fatal';

    // En producción no mostramos detalles a menos que se solicite con ?debug=1
    $showDetails = (defined('APPLICATION_ENV') && APPLICATION_ENV !== 'production') || (isset($_GET['debug']) && $_GET['debug'] == '1');

    if ($showDetails) {
      $message = $error['message'];
      $file = $error['file'];
      $line = (int) $error['line'];
      $details = "<p style='font-family:monospace'>En " . htmlspecialchars($file) . " en la línea $line</p>";
    } else {
      $message = 'Ha ocurrido un error interno. Intente nuevamente más tarde.';
      $details = '';
    }


    // Incluir plantilla de error (más moderna) si existe
    $errorTemplate = APP_PATH . 'errors' . DIRECTORY_SEPARATOR . 'fatal.php';
    if (file_exists($errorTemplate)) {
      include $errorTemplate;
    } else {
      // Fallback simple
      echo "<!doctype html><html><head><meta charset='utf-8'><title>$title</title></head><body style='background:$bg;color:#000;padding:30px;font-family:Arial,Helvetica,sans-serif;'><h1 style='margin-top:0;'>$title</h1><p>$message</p>$details</body></html>";
    }
    exit(1);
  }
});
