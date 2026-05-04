<?php

class Administracion_welcomeController extends Administracion_mainController
{
  public function indexAction()
  {
    if(!Session::getInstance()->get('kt_login_id')){
      header('Location: /administracion/');
    }
  }
}
