<?php
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
  'December' => 'diciembre',
];

$compra = $this->infoVenta->compra;
$evento = $this->infoVenta->evento;
$sede = $this->infoVenta->sede;
$detalle = $this->infoVenta->detalle;

$fechaCompra = new DateTime($compra->boleta_compra_fecha);
$dia = $fechaCompra->format('d');
$mes = $meses[$fechaCompra->format('F')];
$anio = $fechaCompra->format('Y');

$pedidoId = $compra->boleta_compra_id;
$nombreEvento = $evento->evento_nombre;
$nombre = $compra->boleta_compra_nombre;
$email = $compra->boleta_compra_email;
$documento = $compra->boleta_compra_documento;
$totalCompra = (int) $compra->boleta_compra_total;
$entidad = $compra->boleta_compra_entidad ?: '—';
$lugar = $sede ? $sede->sede_nombre : '';
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
                    <div style="background-color:#fff541;padding-top:20px;padding-bottom:20px" width="100%">
                      <p style="margin:0">
                        <img src="https://www.galeriacafelibro.com.co/skins/page/images/logogaleria.png"
                          alt="Galería Café Libro"
                          style="border:none;display:inline-block;height:150px;outline:none;text-decoration:none;vertical-align:middle;max-width:100%">
                      </p>
                    </div>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                      style="background-color:#fff;border:1px solid #dedede;border-radius:3px" bgcolor="#fff">
                      <tbody>
                        <tr>
                          <td align="center" valign="top">
                            <!-- Encabezado -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                              style="background-color:#dc1979;color:#fff;font-weight:bold;border-radius:3px 3px 0 0"
                              bgcolor="#dc1979">
                              <tbody>
                                <tr>
                                  <td style="padding:36px 48px;display:block">
                                    <h1
                                      style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;color:#fff">
                                      Gracias por tu <span>compra</span>
                                    </h1>
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
                                            <div
                                              style="color:#636363;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">

                                              <p style="margin:0 0 16px">Hola, <?= ($nombre) ?></p>
                                              <p style="margin:0 0 16px">Hemos terminado de procesar tu compra.</p>

                                              <h2
                                                style="color:#dc1979;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;margin:0 0 18px">
                                                Compra #<?= $pedidoId ?> &nbsp;—&nbsp; <?= "$dia de $mes de $anio" ?>
                                              </h2>

                                              <!-- Tabla de detalle de compra -->
                                              <div style="margin-bottom:40px">
                                                <table cellspacing="0" cellpadding="6" border="1"
                                                  style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                  width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Evento</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Tipo</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Cant.</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Precio unit.</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Subtotal</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <?php foreach ($detalle as $item): ?>
                                                      <tr>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;word-wrap:break-word">
                                                          <?= ($nombreEvento) ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          <?= ($item->detalle_boleta_nombre) ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          <?= (int) $item->detalle_cantidad ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          $<?= number_format((float) $item->detalle_precio_unit) ?>
                                                          <?php if ((float) $item->detalle_precio_reserva > 0): ?>
                                                            + $<?= number_format((float) $item->detalle_precio_reserva) ?>
                                                            serv.
                                                          <?php endif; ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          $<?= number_format((float) $item->detalle_subtotal) ?>
                                                        </td>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                  </tbody>
                                                  <tfoot>
                                                    <tr>
                                                      <th colspan="4"
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Método de pago:
                                                      </th>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        <?= ($entidad) ?>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <th colspan="4"
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;border-top-width:4px">
                                                        Total:
                                                      </th>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;border-top-width:4px">
                                                        $<?= number_format($totalCompra) ?>
                                                      </td>
                                                    </tr>
                                                  </tfoot>
                                                </table>
                                              </div>

                                              <!-- Datos de facturación -->
                                              <table cellspacing="0" cellpadding="0" border="0"
                                                style="width:100%;vertical-align:top;margin-bottom:40px" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td valign="top" width="50%"
                                                      style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0">
                                                      <h2
                                                        style="color:#dc1979;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;margin:0 0 18px">
                                                        Datos de facturación
                                                      </h2>
                                                      <address
                                                        style="padding:12px;color:#636363;border:1px solid #e5e5e5;font-style:normal">
                                                        <?= ($nombre) ?><br>
                                                        CC. <?= ($documento) ?><br>
                                                        <a href="mailto:<?= ($email) ?>"
                                                          target="_blank"><?= ($email) ?></a>
                                                        <?php if ($lugar): ?>
                                                          <br><?= ($lugar) ?>
                                                        <?php endif; ?>
                                                      </address>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>

                                              <!-- Boletas / tickets -->
                                              <div style="margin-bottom:40px">
                                                <h2
                                                  style="color:#dc1979;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;margin:0 0 18px">
                                                  Boletas
                                                </h2>
                                                <table cellspacing="0" cellpadding="6" border="1"
                                                  style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                  width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Evento</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Tipo</th>
                                                      <th
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                        Ticket</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <?php foreach ($this->tickets as $ticket): ?>
                                                      <tr>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;word-wrap:break-word">
                                                          <?= ($nombreEvento) ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          <?= ($ticket['boleta_tipo'] ?? 'Boleta') ?>
                                                        </td>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left">
                                                          <?php
                                                          $ruta = PDFS_PATH . "ticket_" . $ticket['ticket_uid'] . ".pdf";
                                                          if (file_exists($ruta)) {
                                                            $rutaEncriptada = urlencode(encryptString($ruta));
                                                            ?>
                                                            <a href="<?= URL_PROJECT ?>/page/eventos/leerpdf/?token=<?= $rutaEncriptada ?>"
                                                              target="_blank">
                                                              <?= $ticket['ticket_uid'] ?>
                                                            </a>
                                                          <?php } else { ?>
                                                            <?= $ticket['ticket_uid'] ?>
                                                          <?php } ?>
                                                        </td>
                                                      </tr>
                                                    <?php endforeach; ?>
                                                  </tbody>
                                                </table>
                                              </div>

                                              <p style="margin:0 0 16px">Gracias por tu <span>compra</span>.</p>
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

                    <!-- Footer -->
                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                      <tbody>
                        <tr>
                          <td colspan="2" valign="middle"
                            style="border-radius:6px;border:0;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0;color:#3c3c3c">
                            <p style="margin:0 0 16px">Copyright &copy; <?= date('Y') ?> Galer&iacute;a Caf&eacute;
                              Libro.</p>
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
</div>