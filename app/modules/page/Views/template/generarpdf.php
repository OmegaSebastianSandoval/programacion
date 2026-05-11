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

$evento = $this->infoVenta->evento;
$compra = $this->infoVenta->compra;
$sede = $this->infoVenta->sede;
$ticket = $this->ticket;
$tipo = $this->boletaTipo ?? 'Boleta';
$docRoot = rtrim($this->docRoot ?? $_SERVER['DOCUMENT_ROOT'], '/');

$fecha = new DateTime($evento->evento_fecha);
$dia = $fecha->format('d');
$mes = $meses[$fecha->format('F')];
$anio = $fecha->format('Y');
$hora = $fecha->format('H:i');

$pedidoId = $compra->boleta_compra_id;
$nombre = $compra->boleta_compra_nombre;
$lugar = $sede ? $sede->sede_nombre : '';
?>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
  <br>
  <tr>
    <!-- Columna izquierda -->
    <td width="50%" align="center" valign="top">
      <img src="file://<?= $docRoot ?>/images_sales/assets/logogaleria.png" width="140" height="140" alt="Logo" />
      <br><br>
      <img src="file://<?= $docRoot ?>/images/<?= $evento->evento_imagen ?>" alt="<?= $evento->evento_nombre ?>" width="450" height="450"
        style="border:5px solid #dc1979" />
    </td>

    <!-- Columna derecha -->
    <td width="50%" valign="top" align="center">
      <br><br>
      <table border="0" cellpadding="6" cellspacing="0" width="100%">
        <tr>
          <td width="57%">&nbsp;</td>
          <td width="200"
            style="background-color:#ffff00;color:#dc1979;font-size:18px;font-weight:bold;text-align:center;">
            <?= $ticket->ticket_uid ?>
          </td>
        </tr>
      </table>

      <br><br>

      <span style="font-size:14px;font-weight:bold;color:#ffcc00;background:#333;padding:4px 10px;">
        <?= ($tipo) ?>
      </span>
      <br><br>

      <span style="font-size:25px;font-weight:bold;color:#fff">
        <?= ($nombre) ?>
      </span>
      <br><br>

      <span style="font-size:21px;color:#ffcc00;font-weight:500;">
        <?= ($evento->evento_nombre) ?>
      </span>
      <br>

      <span style="font-size:21px;color:#FFF;font-weight:500;">
        <?= "$dia de $mes de $anio" ?> — <?= $hora ?>
      </span>
      <br>

      <?php if ($lugar): ?>
        <span style="font-size:18px;color:#ffcc00;font-weight:500;">
          <?= ($lugar) ?>
        </span>
        <br>
      <?php endif; ?>

      <span style="font-size:16px;color:#aaa;">Pedido #<?= $pedidoId ?></span>
      <br><br>

      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td align="center" style="text-align:center;">
            <img src="file://<?= $docRoot ?>/images_sales/qrs/<?= $ticket->ticket_uid ?>.png" alt="qr" width="250" height="250" />
          </td>
        </tr>
      </table>
      <br><br>

      <div style="font-size:16px;color:#FFF;">
        <?php if ($evento->evento_bono != 1): ?>
          El valor de la boleta no es consumible.<br />
          Aplican términos y condiciones detallados en la publicación del evento.<br />
        <?php endif; ?>
      </div>
    </td>
  </tr>
</table>