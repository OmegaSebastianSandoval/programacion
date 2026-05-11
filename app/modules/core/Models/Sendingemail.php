<?php

/**
 * Modelo del modulo Core que se encarga de  enviar todos los correos nesesarios del sistema.
 */
class Core_Model_Sendingemail
{
  /**
   * Intancia de la calse emmail
   * @var class
   */
  protected $email;

  protected $_view;

  public function __construct($view)
  {
    $this->email = new Core_Model_Mail();
    $this->_view = $view;
  }


  public function forgotpassword($user)
  {
    if ($user) {
      $code = [];
      $code['user'] = $user->user_id;
      $code['code'] = $user->code;
      $codeEmail = base64_encode(json_encode($code));
      $this->_view->url = "http://" . $_SERVER['HTTP_HOST'] . "/administracion/index/changepassword?code=" . $codeEmail;
      $this->_view->host = "http://" . $_SERVER['HTTP_HOST'] . "/";
      $this->_view->nombre = $user->user_names . " " . $user->user_lastnames;
      $this->_view->usuario = $user->user_user;
      $this->email->getMail()->addAddress($user->user_email, $user->user_names . " " . $user->user_lastnames);
      $content = $this->_view->getRoutPHP('/../app/modules/core/Views/templatesemail/forgotpassword.php');
      $this->email->getMail()->Subject = "Recuperación de Contraseña Gestor de Contenidos";
      $this->email->getMail()->msgHTML($content);
      $this->email->getMail()->AltBody = $content;
      if ($this->email->sed() == true) {
        return true;
      } else {
        return false;
      }
    }
  }
  public function sendMailContact($data, $mail)
  {
    $this->_view->data = $data;
    $this->email->getMail()->addAddress($mail, "");
    $content = $this->_view->getRoutPHP('/../app/modules/core/Views/templatesemail/mailContact.php');
    $this->email->getMail()->Subject = '';
    $this->email->getMail()->msgHTML($content);
    $this->email->getMail()->AltBody = $content;
    // $this->email->getMail()->addBCC($informacion->info_pagina_correo_oculto);
    if ($this->email->sed() == true) {
      return 1;
    } else {
      return 2;
    }
  }

  public function enviarCorreoReserva($reservacion, $evento, $sede, $reservaEvento)
  {
    $this->_view->reservacion = $reservacion;
    $this->_view->evento = $evento;
    $this->_view->sede = $sede;
    $this->_view->reservaEvento = $reservaEvento;

    $informacionModel = new Page_Model_DbTable_Informacion();
    $informacion = $informacionModel->getList("", "orden ASC")[0];
    $correo = $informacion->info_pagina_correos_contacto;
    $email = $reservacion->reserva_email;
    $nombre = $reservacion->reserva_nombre;

    if (APPLICATION_ENV == 'production') {
      $this->email->getMail()->addBCC($correo, "Reserva Galeria Cafe Libro");
      $this->email->getMail()->addAddress($email, $nombre);
    }
    $this->email->getMail()->addBCC("desarrollo8@omegawebsystems.com", "Reserva Galeria Cafe Libro");

    $content = $this->_view->getRoutPHP('/../app/modules/core/Views/templatesemail/correo_reserva.php');
    $this->email->getMail()->Subject = "Confirmación de Reserva — Galería Café Libro";
    $this->email->getMail()->msgHTML($content);
    $this->email->getMail()->AltBody = $content;
    if ($this->email->sed() == true) {
      return 1;
    } else {
      return 2;
    }
  }

  public function generarCorreoBoleteria($infoVenta, $qrsGenerados)
  {

    $this->_view->tickets = $qrsGenerados;
    $this->_view->infoVenta = $infoVenta;
    $informacionModel = new Page_Model_DbTable_Informacion();
    $informacion = $informacionModel->getList("", "orden ASC")[0];
    $correo = $informacion->info_pagina_correos_contacto;
    $email = $infoVenta->boleta_compra_email;
    if (APPLICATION_ENV == 'production') {
      $this->email->getMail()->addBCC($correo, "Confirmación Galeria Cafe Libro");

      $this->email->getMail()->addAddress($email, "Confirmación Galería Cafe Libro");
    }
    $this->email->getMail()->addBCC("desarrollo8@omegawebsystems.com", "Confirmación Galeria Cafe Libro");

    $content = $this->_view->getRoutPHP('/../app/modules/core/Views/templatesemail/generarcorreo.php');
    $this->email->getMail()->Subject = "Envío Boleteria Galería Cafe Libro";
    $this->email->getMail()->msgHTML($content);
    $this->email->getMail()->AltBody = $content;
    if ($this->email->sed() == true) {
      return 1;
    } else {
      return 2;
    }
  }
}
