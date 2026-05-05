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
  'December' => 'diciembre'
];

$fecha = new DateTime($this->infoVenta->programacion_fecha);
$dia = $fecha->format('d');
$mes = $meses[$fecha->format('F')];
$anio = $fecha->format('Y');

$pedidoId = $this->infoVenta->boleta_compra_id;
$evento = $this->infoVenta->programacion_nombre;
$cantidad = intval($this->infoVenta->boleta_compra_cantidad);
$servicio = intval($this->infoVenta->boleta_evento_precioadicional);
$precio = intval($this->infoVenta->boleta_evento_precio);

$total = ($cantidad * $precio) + ($cantidad * $servicio);

$entidad = $this->infoVenta->entidad;
$email = $this->infoVenta->boleta_compra_email;
$lugar = $this->infoVenta->programacion_lugar;
$documento = $this->infoVenta->boleta_compra_documento;
$nombre = $this->infoVenta->boleta_compran_nombre;
?>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
  <br>

  <tr>
    <!-- Columna izquierda -->
    <td width="50%" align="center" valign="top">
      <img src="/images_sales/assets/logogaleria.png" width="140" height="140" alt="Logo" />
      <br>
      <br>
      <img src="/images/<?= $this->infoVenta->programacion_imagen; ?>" alt="<?= $this->infoVenta->programacion_nombre; ?>" title="<?= $this->infoVenta->programacion_nombre; ?>" width="450" height="450" style="border:5px solid #dc1979 " />
    </td>

    <!-- Columna derecha -->
    <td width="50%" valign="top" align="center">
      <br>
      <br>
      <table border="0" cellpadding="6" cellspacing="0" width="100%">
        <tr>
          <!-- Celda vacía que ocupa el 100% menos 200px -->
          <td width="57%">&nbsp;</td>

          <!-- Celda con el identificador -->
          <td width="200" style="background-color: #ffff00; color: #dc1979; font-size: 18px; font-weight: bold; text-align: center;">
            <?= $this->ticket->ticket_uid ?>
          </td>
        </tr>
      </table>

      <br>
      <br>
      <br>
      <!-- Información -->
      <span style="font-size: 25px; font-weight: bold; color:#fff"><?= $nombre ?></span>
      <br>
      <br>
      <span style="font-size: 21px; color: #ffcc00; font-weight: 500;"><?= $evento ?></span>
      <br>

      <span style="font-size: 21px; color: #FFF; font-weight: 500;"><?= "$dia de $mes de $anio"; ?></span>
      <br>
      <span style="font-size: 21px;color: #FFF; font-weight: 500;">$<?= $precio >= 0 ? number_format($precio) : $precio   ?> más servicio de $ <?= $servicio >= 0 ? number_format($servicio) : $servicio   ?> (IVA Incluido)</span>
      <br>
      <br>
    
      <table border="0" valign="top" align="center" width="100%">
        <tr>

          <!-- Celda con el identificador -->
          <td width="100%">
          <img src="<?= "/images_sales/qrs/".$this->ticket->ticket_uid.".png" ?>" alt="qr" width="250" height="250" />


          </td>
        </tr>
      </table>
      <br>
      <br>
      <span style="text-align:end; font-size: 18px; color: #ffcc00; font-weight: 500;">
        <!-- Galería Cafe Libro -->
        <?= $lugar ?>
      </span>
      <br>
      <br>
      <div style="font-size: 16px; color: #FFF;">
        <?php if ($this->infoVenta->programacion_bono != 1) { ?>
          El valor de la boleta no es consumible.<br />
          Aplican términos y condiciones detallados en la publicación del evento.<br />
        <?php } ?>
      </div>
    </td>
  </tr>
</table>