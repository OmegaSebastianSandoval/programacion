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

$reservacion = $this->reservacion;
$evento = $this->evento;
$sede = $this->sede;
$reservaEvento = $this->reservaEvento;

$nombre = $reservacion->reserva_nombre;
$email = $reservacion->reserva_email;
$personas = (int) $reservacion->reserva_cantidad_personas;
$total = (float) $reservacion->reserva_total;
$estado = $reservacion->reserva_estado;
$esGratis = $total == 0;

$fechaCreacion = new DateTime($reservacion->reserva_fecha_creacion);
$diaC = $fechaCreacion->format('d');
$mesC = $meses[$fechaCreacion->format('F')];
$anioC = $fechaCreacion->format('Y');

$eventoNombre = $evento ? $evento->evento_nombre : '';
$eventoFecha = '';
if ($evento && $evento->evento_fecha) {
  $fEvento = new DateTime($evento->evento_fecha);
  $eventoFecha = $fEvento->format('d') . ' de ' . ($meses[$fEvento->format('F')] ?? $fEvento->format('F')) . ' de ' . $fEvento->format('Y') . ' — ' . $fEvento->format('H:i');
}
$sedeNombre = $sede ? $sede->sede_nombre : '';
$sedeDireccion = $sede ? $sede->sede_direccion : '';
$tipoReservaNombre = $reservaEvento ? $reservaEvento->reserva_evento_nombre : 'Reserva';

$estadoLabel = match ($estado) {
  'confirmada' => 'Confirmada',
  'pendiente' => 'Pendiente de pago',
  'cancelada' => 'Cancelada',
  default => ucfirst($estado),
};
$estadoColor = ($estado === 'confirmada') ? '#27ae60' : '#e67e22';

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
                          style="border:none;display:inline-block;font-size:14px;font-weight:bold;height:150px;outline:none;text-decoration:none;vertical-align:middle;max-width:100%">
                      </p>
                    </div>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%"
                      style="background-color:#fff;border:1px solid #dedede;border-radius:3px" bgcolor="#fff">
                      <tbody>
                        <tr>
                          <td align="center" valign="top">

                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                              style="background-color:#0b4b3e;color:#fff;font-weight:bold;line-height:100%;vertical-align:middle;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0"
                              bgcolor="#0b4b3e">
                              <tbody>
                                <tr>
                                  <td style="padding:36px 48px;display:block">
                                    <h1
                                      style="font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#fff">
                                      <?= $esGratis ? 'Tu reserva está <span>confirmada</span>' : 'Resumen de tu <span>reserva</span>' ?>
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
                                              style="color:#636363;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left"
                                              align="left">

                                              <p style="margin:0 0 16px">Hola, <?= ($nombre) ?></p>
                                              <p style="margin:0 0 16px">
                                                <?= $esGratis
                                                  ? 'Tu reserva ha sido confirmada exitosamente. A continuación encontrarás el resumen.'
                                                  : 'Hemos recibido tu solicitud de reserva. A continuación encontrarás el resumen.' ?>
                                              </p>

                                              <h2
                                                style="color:#0b4b3e;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                Reserva del <?= "$diaC de $mesC de $anioC" ?>
                                              </h2>

                                              <!-- Detalle del evento -->
                                              <div
                                                style="margin-bottom:32px;padding:16px;background:#f9f9f9;border:1px solid #e5e5e5;border-radius:4px">
                                                <h3
                                                  style="color:#0b4b3e;margin:0 0 12px;font-size:16px;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif">
                                                  <?= ($eventoNombre) ?>
                                                </h3>
                                                <?php if ($eventoFecha): ?>
                                                  <p style="margin:4px 0;font-size:13px">
                                                    <strong>Fecha:</strong> <?= ($eventoFecha) ?>
                                                  </p>
                                                <?php endif; ?>
                                                <?php if ($sedeNombre): ?>
                                                  <p style="margin:4px 0;font-size:13px">
                                                    <strong>Lugar:</strong> <?= ($sedeNombre) ?> - <?= ($sedeDireccion) ?>
                                                  </p>
                                                <?php endif; ?>
                                              </div>

                                              <!-- Tabla de reserva -->
                                              <div style="margin-bottom:40px">
                                                <table cellspacing="0" cellpadding="6" border="1"
                                                  style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                  width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col"
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                        align="left">Tipo de reserva</th>
                                                      <th scope="col"
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                        align="left">Personas</th>
                                                      <th scope="col"
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                        align="left">Total</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    <tr>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word"
                                                        align="left">
                                                        <?= ($tipoReservaNombre) ?>
                                                      </td>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                        align="left">
                                                        <?= $personas ?>
                                                      </td>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                        align="left">
                                                        <?= $esGratis ? 'Gratuita' : '<span>$</span>' . number_format($total) ?>
                                                      </td>
                                                    </tr>
                                                  </tbody>
                                                  <tfoot>
                                                    <tr>
                                                      <th scope="row" colspan="2"
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px"
                                                        align="left">Estado:</th>
                                                      <td
                                                        style="border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-top-width:4px;font-weight:bold;color:<?= $estadoColor ?>"
                                                        align="left">
                                                        <?= $estadoLabel ?>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <th scope="row" colspan="2"
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                        align="left">Total pagado:</th>
                                                      <td
                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif"
                                                        align="left">
                                                        <?= $esGratis ? 'Gratuita' : '<span>$</span>' . number_format($total) ?>
                                                      </td>
                                                    </tr>
                                                  </tfoot>
                                                </table>
                                              </div>

                                              <!-- Datos de facturación -->
                                              <table cellspacing="0" cellpadding="0" border="0"
                                                style="width:100%;vertical-align:top;margin-bottom:40px;padding:0"
                                                width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td valign="top" width="50%"
                                                      style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0"
                                                      align="left">
                                                      <h2
                                                        style="color:#0b4b3e;display:block;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                        Datos del reservante
                                                      </h2>
                                                      <address
                                                        style="padding:12px;color:#636363;border:1px solid #e5e5e5;font-style:normal">
                                                        <?= ($nombre) ?><br>
                                                        <a href="mailto:<?= ($email) ?>"
                                                          target="_blank"><?= ($email) ?></a>
                                                      </address>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>

                                              <p style="margin:0 0 16px">Gracias por tu <span class="il">reserva</span>.
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
                                  <td colspan="2" valign="middle"
                                    style="border-radius:6px;border:0;font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;font-size:12px;line-height:150%;text-align:center;padding:24px 0;color:#3c3c3c"
                                    align="center">
                                    <p style="margin:0 0 16px">Copyright &copy; <?= date('Y') ?> Galer&iacute;a
                                      Caf&eacute; Libro.</p>
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
</div>