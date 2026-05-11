<?php

/* echo "<pre>";
print_r($this->tickets);
echo "</pre>"; */
function encryptString($string, $key = 'omegagaleria2025')
{
  $iv = openssl_random_pseudo_bytes(16);
  $encrypted = openssl_encrypt($string, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
  return base64_encode($iv . $encrypted);
}

$meses = [
  'January' => 'enero',
  'February' => 'febrero',
  'March' => 'marzo',
  'April' => 'abril',
  'May' => 'mayo',
  'June' => 'junio',
  'July' => 'julio',
  'August' => 'agosto',
  'September' => 'septiembre',
  'October' => 'octubre',
  'November' => 'noviembre',
  'December' => 'diciembre'
];

$fecha = new DateTime($this->infoVenta->boleta_compra_fecha);
$dia = $fecha->format('d');
$mes = $meses[$fecha->format('F')];
$anio = $fecha->format('Y');

$pedidoId = $this->infoVenta->boleta_compra_id;
$evento = $this->infoVenta->programacion_nombre;
$cantidad = intval($this->infoVenta->boleta_compra_cantidad);
$servicio = intval($this->infoVenta->boleta_evento_precioadicional);
$precio = intval($this->infoVenta->boleta_evento_precio);

$total = ($cantidad * $precio) + ($cantidad * $servicio);
$totalCompra = intval($this->infoVenta->boleta_compra_total);

$entidad = $this->infoVenta->entidad;
$email = $this->infoVenta->boleta_compra_email;

$tipoBoleta = $this->infoVenta->boleta_tipo_nombre;
$documento = $this->infoVenta->boleta_compra_documento;
$nombre = $this->infoVenta->boleta_compran_nombre;

?>

<div marginwidth="0" marginheight="0" style="background-color:#f7f7f7;padding:0;text-align:center" bgcolor="#f7f7f7">
  <table width="100%" style="background-color:#f7f7f7" bgcolor="#f7f7f7">
    <tbody>
      <tr>
        <td></td>
        <td width="600">
          <div dir="ltr" style="margin:0 auto;padding:70px 0;width:100%;max-width:600px" width="100%">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
              <tbody>
                <tr>
                  <td align="center" valign="top">
                    <div style="background-color:#fff541;padding-top:20px; padding-bottom:20px" width="100%">

                      <p style="margin:0"><img src="https://www.galeriacafelibro.com.co/skins/page/images/logogaleria.png" alt="https://www.galeriacafelibro.com.co" style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:auto;outline:none;text-decoration:none;text-transform:capitalize;vertical-align:middle;max-width:100%;margin-left:0;margin-right:0; height:150px">


                      </p>
                    </div>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff;border:1px solid #dedede;border-radius:3px" bgcolor="#fff">
                      <tbody>
                        <tr>
                          <td align="center" valign="top">

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#dc1979;color:#fff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0" bgcolor="#dc1979">
                              <tbody>
                                <tr>
                                  <td style="padding:36px 48px;display:block">
                                    <h1 style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#fff;background-color:inherit" bgcolor="inherit">Gracias por tu <span>compra</span></h1>
                                  </td>
                                </tr>
                              </tbody>
                            </table>

                          </td>
                        </tr>
                        <tr>
                          <td align="center" valign="top">

                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tbody>
                                <tr>
                                  <td valign="top" style="background-color:#fff" bgcolor="#fff">

                                    <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                      <tbody>
                                        <tr>
                                          <td valign="top" style="padding:48px 48px 32px">
                                            <div style="color:#636363;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left" align="left">

                                              <p style="margin:0 0 16px">Hola, <?= $nombre ?></p>
                                              <p style="margin:0 0 16px">Hemos terminado de procesar tu pedido.</p>

                                              <h2 style="color:#dc1979;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">

                                                Compra #<?= $pedidoId ?> <br><?= "$dia de $mes de $anio"; ?></h2>

                                              <div style="margin-bottom:40px">
                                                <table cellspacing="0" cellpadding="6" border="1" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Producto</th>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Cantidad</th>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Servicio</th>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Precio</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word" align="left">
                                                        <?= $evento ?>
                                                        <!--  <ul style="font-size:small;margin:1em 0 0;padding:0;list-style:none">
                                                          <li style="margin:.5em 0 0;padding:0">
                                                            <strong style="float:left;margin-right:.25em;clear:both">ELIJE EL TIPO DE :</strong>
                                                            <p style="margin:0">En grano</p>
                                                          </li>
                                                        </ul> -->
                                                      </td>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                                        <?= $cantidad ?> </td>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                                        <span><span>$</span><?= $servicio >= 0 ? number_format($servicio) : $servicio   ?></span>
                                                      </td>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                                        <span><span>$</span><?= $precio >= 0 ? number_format($precio) : $precio   ?></span>
                                                      </td>
                                                    </tr>

                                                  </tbody>
                                                  <tfoot>
                                                    <tr>
                                                      <th scope="row" colspan="3" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left">Subtotal:</th>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left"><span><span>$</span><?= $total >= 0 ? number_format($total) : $total   ?></span></td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row" colspan="3" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Método de pago:</th>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left"><?= $entidad ?></td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row" colspan="3" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Total:</th>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left"><span><span>$</span><?= $totalCompra >= 0 ? number_format($totalCompra) : $totalCompra   ?></span></td>
                                                    </tr>
                                                    <!-- <tr>
                                                      <th scope="row" colspan="3" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Nota:</th>
                                                      <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Nota aquí...</td>
                                                    </tr> -->
                                                  </tfoot>
                                                </table>
                                              </div>

                                              <table cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td valign="top" width="50%" style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0" align="left">
                                                      <h2 style="color:#dc1979;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Datos de facturación</h2>

                                                      <address style="padding:12px;color:#636363;border:1px solid #e5e5e5">
                                                        <?= $nombre ?><br> CC.<?= $documento ?><br><a href="mailto:<?= $email ?>" target="_blank"><?= $email ?></a> </address>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                              <div style="margin-bottom:40px">
                                                <h2 style="color:#dc1979;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">

                                                  Boletas</h2>

                                                <table cellspacing="0" cellpadding="6" border="1" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Evento</th>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Tipo</th>
                                                      <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Ticket</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <?php foreach ($this->tickets as $ticket) { ?>
                                                      <tr>
                                                        <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word" align="left">

                                                          <?= $evento ?>

                                                        </td>
                                                        <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                                          <?= $tipoBoleta ?>
                                                        </td>
                                                        <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                                          <?php $ruta = PDFS_PATH . "ticket_" . $ticket["ticket_uid"] . ".pdf" ?>
                                                          <?php if (file_exists($ruta)) {
                                                            $rutaEncriptada = urlencode(encryptString($ruta));
                                                          ?>
                                                            <a href="<?= RUTA_QR ?>/page/programacion/leerpdf/?token=<?= $rutaEncriptada ?>" target="_blank">
                                                              <?= $ticket["ticket_uid"] ?>
                                                            </a>
                                                          <?php }  ?>

                                                        </td>
                                                      </tr>

                                                    <?php } ?>

                                                  </tbody>
                                                </table>
                                              </div>

                                              <p style="margin:0 0 16px">Gracias por tu <span class="il">compra</span>.</p>
                                            </div>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>

                                  </td>
                                </tr>
                              </tbody>
                            </table>

                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="center" valign="top">

                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                      <tbody>
                        <tr>
                          <td valign="top" style="padding:0;border-radius:6px">
                            <table border="0" cellpadding="10" cellspacing="0" width="100%">
                              <tbody>
                                <tr>
                                  <td colspan="2" valign="middle" style="border-radius:6px;border:0;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0;color:#3c3c3c" align="center">
                                    <p style="margin:0 0 16px">Copyright © <?= date('Y') ?> Galer&iacute;a Caf&eacute; Libro.</p>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </td>
        <td></td>
      </tr>
    </tbody>
  </table>
  <div class="yj6qo"></div>
  <div class="adL">
  </div>
</div>