<?php
$designThreeBgColor = $contenido->contenido_fondo_color ? $contenido->contenido_fondo_color : $colorfondo;
$designThreeBorder = $contenido->contenido_borde == '1' ? '2px solid #13436B' : 'none';
$designThreeRadius = $contenido->contenido_borde == '1' ? '20px' : '0';
$designThreePadding = $contenido->contenido_borde == '1' ? '0' : 'initial';
$designThreeOverflow = $contenido->contenido_borde == '1' ? 'hidden' : 'visible';
?>
<style>
  .design-three-dyn-<?php echo $contenido->contenido_id; ?> {
    background-color:
      <?php echo $designThreeBgColor; ?>
    ;
    border:
      <?php echo $designThreeBorder; ?>
    ;
    border-radius:
      <?php echo $designThreeRadius; ?>
    ;
    padding:
      <?php echo $designThreePadding; ?>
    ;
    overflow:
      <?php echo $designThreeOverflow; ?>
    ;
  }

  .design-three-desc-padded {
    padding: 10px;
  }
</style>

<div
  class="caja-contenido-simple design-three three-<?php echo $contenido->contenido_id; ?> design-three-dyn-<?php echo $contenido->contenido_id; ?>">

  <?php if ($contenido->contenido_titulo_ver == 1) { ?>
    <h2><?php echo $contenido->contenido_titulo; ?></h2>
  <?php } ?>
  <?php if ($contenido->contenido_imagen) { ?>
    <div class="imagen-contenido">
      <div><img src="/images/<?php echo $contenido->contenido_imagen; ?>"></div>
    </div>
    <?php if ($contenido->contenido_borde != '1') { ?>
      </br>
    <?php } ?>
  <?php } ?>
  <div>
    <div
      class="descripcion <?php if ($contenido->contenido_borde == '1' && $contenido->contenido_descripcion) { ?>design-three-desc-padded<?php } ?>">
      <?php echo $contenido->contenido_descripcion; ?>
    </div>
    <?php if ($contenido->contenido_enlace) { ?>
      <div>
        <a href="<?php $contenido->contenido_enlace; ?>" <?php if ($contenido->contenido_enlace_abrir == 1) { ?>
            target="_blank" rel="noopener noreferrer" <?php } ?> class="btn btn-block btn-vermas">
          <?php if ($contenido->contenido_vermas) { ?>     <?php echo $contenido->contenido_vermas; ?>   <?php } else { ?>Ver
            Más<?php } ?>
        </a>
      </div>
    <?php } ?>
  </div>
</div>