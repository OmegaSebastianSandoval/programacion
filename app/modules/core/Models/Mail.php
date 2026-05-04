<?php

/**
* Modelo del modulo Core que se encarga de inicializar  la clase de envio de correos
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
class Core_Model_Mail
{
    /**
     * classe de  phpmailer
     * @var class
     */
    private $mail;

    /**
     * asigna los valores a la clase e instancia el phpMailer
     */
    public function __construct()
    {
        
        $informacionModel = new Page_Model_DbTable_Informacion();
        $informacion = $informacionModel->getList("","orden ASC")[0];

        $this->mail = new PHPMailer;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Host = $informacion->info_pagina_host;
        $this->mail->Port = $informacion->info_pagina_port;
        $this->mail->SMTPAuth = true;
        $this->mail->Username =$informacion->info_pagina_username;
        $this->mail->Password =$informacion->info_pagina_password;
        $this->mail->setFrom($informacion->info_pagina_correo_remitente,$informacion->info_pagina_nombre_remitente);


    }
    /**
     * retorna la  instancia de email
     * @return class email
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * envia el correo
     * @return bool envia el estado del correo
     */
    public function sed()
    {
        if ($this->mail->send()) {
            return true;
        } else {
            return false;
        }
    }
}