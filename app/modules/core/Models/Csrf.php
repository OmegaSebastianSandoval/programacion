<?php
/**
 *
 */

class Core_Model_Csrf
{
     public function __construct($section)
     {
          $this->generateCode($section);
     }

     public function deleteCode($section)
     {
          $csrf = Session::getInstance()->get('csrf');
          if ($csrf != '' && $csrf[$section]) {
               unset($csrf[$section]);
               Session::getInstance()->set('csrf', $csrf);
          }
     }

     public function generateCode($section)
     {
          $csrf = Session::getInstance()->get('csrf');
          if (!is_array($csrf)) {
               $csrf = array();
          }
          $csrf[$section] = $this->getRandomCode(20);
          Session::getInstance()->set('csrf', $csrf);
     }


     public function getRandomCode($length)
     {
          // random_bytes() es criptográficamente seguro (reemplaza rand())
          return bin2hex(random_bytes((int) ceil($length / 2)));
     }
}