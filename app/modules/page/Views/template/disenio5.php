<?php
$designFiveBorder = $contenido->contenido_borde == '1' ? '2px solid #13436B' : 'none';
$designFiveRadius = $contenido->contenido_borde == '1' ? '20px' : '0';
$designFiveOverflow = $contenido->contenido_borde == '1' ? 'hidden' : 'visible';
?>
<style>
    .design-five-border-<?php echo $contenido->contenido_id; ?> {
        border:
            <?php echo $designFiveBorder; ?>
        ;
        border-radius:
            <?php echo $designFiveRadius; ?>
        ;
        overflow:
            <?php echo $designFiveOverflow; ?>
        ;
    }

    .design-five-bg-<?php echo $contenido->contenido_id; ?> {
        background: url(/images/<?php echo $contenido->contenido_fondo_imagen; ?>);
        background-color:
            <?php echo $contenido->contenido_fondo_color; ?>
        ;
    }
</style>

<div
    class="padding-crediciti design-five five-<?php echo $contenido->contenido_id; ?> design-five-border-<?php echo $contenido->contenido_id; ?>">
    <div class="crediciti p-3 design-five-bg-<?php echo $contenido->contenido_id; ?>">
        <?php if ($contenido->contenido_imagen) { ?>
            <div><img src="/images/<?php echo $contenido->contenido_imagen; ?>"></div>
        <?php } ?>
        <div class="fondo-gris">
            <?php if ($contenido->contenido_titulo_ver == 1) { ?>
                <div>
                    <h2><?php echo $contenido->contenido_titulo; ?></h2>
                </div>
            <?php } ?>
            <div class="descripcion"><?php echo $contenido->contenido_descripcion; ?></div>
            <?php if ($contenido->contenido_enlace) { ?>
                <div class="boton-crediciti">
                    <a href="<?php echo $contenido->contenido_enlace; ?>" <?php if ($contenido->contenido_enlace_abrir == 1) { ?> target="_blank" rel="noopener noreferrer" <?php } ?> class="btn btn-vermas">
                        <?php if ($contenido->contenido_vermas) { ?>         <?php echo $contenido->contenido_vermas; ?>
                        <?php } else { ?>Ver
                            Más<?php } ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>