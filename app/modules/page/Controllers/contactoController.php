<?php

class Page_contactoController extends Page_mainController
{
  public function indexAction()
  {
    $this->_view->banner = $this->template->bannerInternas(2);

  }
}