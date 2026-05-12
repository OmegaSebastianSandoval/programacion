<?php
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

$compra = $this->compra;
$evento = $this->evento;
$sede = $this->sede;
$detalle = $this->detalle;
$reserva = $this->reserva;
$epaycoData = $this->epaycoData;

$nombre = $compra->boleta_compra_nombre;
$email = $compra->boleta_compra_email;
$documento = $compra->boleta_compra_documento;
$pedidoId = $compra->boleta_compra_id;
$totalCompra = (float) $compra->boleta_compra_total;
$tipoCompra = $compra->boleta_compra_tipo;
$entidad = $compra->boleta_compra_entidad ?: '—';

$fechaCompra = new DateTime($compra->boleta_compra_fecha);
$diaC = $fechaCompra->format('d');
$mesC = $meses[$fechaCompra->format('F')] ?? $fechaCompra->format('F');
$anioC = $fechaCompra->format('Y');

$eventoNombre = $evento ? $evento->evento_nombre : '';
$eventoFecha = '';
if ($evento && $evento->evento_fecha) {
  $fEv = new DateTime($evento->evento_fecha);
  $eventoFecha = $fEv->format('d') . ' de ' . ($meses[$fEv->format('F')] ?? $fEv->format('F')) . ' de ' . $fEv->format('Y') . ' — ' . $fEv->format('H:i');
}
$sedeNombre = $sede ? $sede->sede_nombre : '';
$sedeDireccion = $sede ? $sede->sede_direccion : '';

$razon = $epaycoData['x_response_reason_text'] ?? '';
$estadoTx = $epaycoData['x_transaction_state'] ?? 'Reversada';
$refPayco = $epaycoData['x_ref_payco'] ?? '';
$monto = $epaycoData['x_amount'] ?? number_format($totalCompra);
$franquicia = $epaycoData['x_bank_name'] ?? $entidad;
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
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                              style="background-color:#b03a2e;color:#fff;font-weight:bold;border-radius:3px 3px 0 0"
                              bgcolor="#b03a2e">
                              <tbody>
                                <tr>
                                  <td style="padding:36px 48px;display:block">
                                    <h1
                                      style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;color:#fff">
                                      Transacción <span>reversada</span>
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
                                              <p style="margin:0 0 16px">
                                                Te informamos que la transacción asociada a tu compra ha sido
                                                <strong>reversada</strong> por la pasarela de pago. El cobro será
                                                revertido en tu medio de pago según los tiempos de tu entidad
                                                financiera.
                                              </p>

                                              <!-- Aviso motivo -->
                                              <?php if ($razon): ?>
                                                <div
                                                  style="margin-bottom:24px;padding:14px 16px;background:#fdf2f2;border-left:4px solid #b03a2e;border-radius:3px">
                                                  <p style="margin:0;font-size:13px;color:#b03a2e">
                                                    <strong>Motivo:</strong> <?= ($razon) ?>
                                                  </p>
                                                </div>
                                              <?php endif; ?>

                                              <h2
                                                style="color:#b03a2e;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;margin:0 0 18px">
                                                Compra #<?= $pedidoId ?> &nbsp;—&nbsp; <?= "$diaC de $mesC de $anioC" ?>
                                              </h2>

                                              <!-- Detalle transacción ePayco -->
                                              <div
                                                style="margin-bottom:32px;padding:16px;background:#f9f9f9;border:1px solid #e5e5e5;border-radius:4px">
                                                <h3
                                                  style="color:#0b4b3e;margin:0 0 12px;font-size:15px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                  Detalle de la transacción
                                                </h3>
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                  <tbody>
                                                    <tr>
                                                      <td style="padding:4px 0;font-size:13px;color:#636363;width:50%">
                                                        <strong>Estado:</strong></td>
                                                      <td style="padding:4px 0;font-size:13px;color:#b03a2e">
                                                        <strong><?= ($estadoTx) ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                      <td style="padding:4px 0;font-size:13px;color:#636363">
                                                        <strong>Monto:</strong></td>
                                                      <td style="padding:4px 0;font-size:13px;color:#636363">
                                                        $<?= is_numeric($monto) ? number_format((float) $monto) : ($monto) ?>
                                                      </td>
                                                    </tr>
                                                    <?php if ($franquicia && $franquicia !== '—'): ?>
                                                      <tr>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <strong>Entidad / Franquicia:</strong></td>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <?= ($franquicia) ?></td>
                                                      </tr>
                                                    <?php endif; ?>
                                                    <?php if ($refPayco): ?>
                                                      <tr>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <strong>Referencia ePayco:</strong></td>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <?= ($refPayco) ?></td>
                                                      </tr>
                                                    <?php endif; ?>
                                                  </tbody>
                                                </table>
                                              </div>

                                              <!-- Evento -->
                                              <?php if ($eventoNombre): ?>
                                                <div
                                                  style="margin-bottom:32px;padding:16px;background:#f9f9f9;border:1px solid #e5e5e5;border-radius:4px">
                                                  <h3
                                                    style="color:#0b4b3e;margin:0 0 12px;font-size:15px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                    <?= ($eventoNombre) ?>
                                                  </h3>
                                                  <?php if ($eventoFecha): ?>
                                                    <p style="margin:4px 0;font-size:13px"><strong>Fecha:</strong>
                                                      <?= ($eventoFecha) ?></p>
                                                  <?php endif; ?>
                                                  <?php if ($sedeNombre): ?>
                                                    <p style="margin:4px 0;font-size:13px"><strong>Lugar:</strong>
                                                      <?= ($sedeNombre) ?> - <?= ($sedeDireccion) ?></p>
                                                  <?php endif; ?>
                                                </div>
                                              <?php endif; ?>

                                              <!-- Boletas -->
                                              <?php if ($detalle && count($detalle) > 0): ?>
                                                <div style="margin-bottom:32px">
                                                  <h3
                                                    style="color:#0b4b3e;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:15px;font-weight:bold;margin:0 0 12px">
                                                    Boletas incluidas
                                                  </h3>
                                                  <table cellspacing="0" cellpadding="6" border="1"
                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                    width="100%">
                                                    <thead>
                                                      <tr>
                                                        <th
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                          Tipo</th>
                                                        <th
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                          Cant.</th>
                                                        <th
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                          Precio unit.</th>
                                                        <th
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                          Subtotal</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php foreach ($detalle as $item): ?>
                                                        <tr>
                                                          <td
                                                            style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                            <?= ($item->detalle_boleta_nombre) ?></td>
                                                          <td
                                                            style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                            <?= (int) $item->detalle_cantidad ?></td>
                                                          <td
                                                            style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                            $<?= number_format((float) $item->detalle_precio_unit) ?>
                                                            <?php if ((float) $item->detalle_precio_reserva > 0): ?>
                                                              + $<?= number_format((float) $item->detalle_precio_reserva) ?>
                                                              serv.
                                                            <?php endif; ?>
                                                          </td>
                                                          <td
                                                            style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left">
                                                            $<?= number_format((float) $item->detalle_subtotal) ?></td>
                                                        </tr>
                                                      <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                      <tr>
                                                        <th colspan="3"
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left;border-top-width:3px">
                                                          Total compra:</th>
                                                        <td
                                                          style="color:#636363;border:1px solid #e5e5e5;padding:10px;text-align:left;border-top-width:3px">
                                                          $<?= number_format($totalCompra) ?></td>
                                                      </tr>
                                                    </tfoot>
                                                  </table>
                                                </div>
                                              <?php endif; ?>

                                              <!-- Reserva -->
                                              <?php if ($reserva && $reserva->reserva_id): ?>
                                                <div
                                                  style="margin-bottom:32px;padding:16px;background:#f9f9f9;border:1px solid #e5e5e5;border-radius:4px">
                                                  <h3
                                                    style="color:#0b4b3e;margin:0 0 12px;font-size:15px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                    Reserva asociada
                                                  </h3>
                                                  <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                    <tbody>
                                                      <tr>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363;width:50%">
                                                          <strong>Tipo:</strong></td>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <?= ($reserva->reserva_tipo_origen) ?></td>
                                                      </tr>
                                                      <tr>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <strong>Personas:</strong></td>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <?= (int) $reserva->reserva_cantidad_personas ?></td>
                                                      </tr>
                                                      <tr>
                                                        <td style="padding:4px 0;font-size:13px;color:#636363">
                                                          <strong>Estado reserva:</strong></td>
                                                        <td style="padding:4px 0;font-size:13px;color:#b03a2e">
                                                          <strong>Cancelada</strong></td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                                </div>
                                              <?php endif; ?>

                                              <!-- Datos del comprador -->
                                              <table cellspacing="0" cellpadding="0" border="0"
                                                style="width:100%;vertical-align:top;margin-bottom:40px" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td valign="top" width="50%"
                                                      style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0">
                                                      <h3
                                                        style="color:#0b4b3e;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:15px;font-weight:bold;margin:0 0 10px">
                                                        Datos del comprador
                                                      </h3>
                                                      <address
                                                        style="padding:12px;color:#636363;border:1px solid #e5e5e5;font-style:normal">
                                                        <?= ($nombre) ?><br>
                                                        CC. <?= ($documento) ?><br>
                                                        <a href="mailto:<?= ($email) ?>"
                                                          target="_blank"><?= ($email) ?></a>
                                                      </address>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>

                                              <p style="margin:0 0 16px">Si tienes preguntas, comunícate con nosotros.
                                              </p>

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

                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                      <tbody>
                        <tr>
                          <td colspan="2" valign="middle"
                            style="border-radius:6px;border:0;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0;color:#3c3c3c"
                            align="center">
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