<?php
$designFourBgColor = $contenido->contenido_fondo_color ? $contenido->contenido_fondo_color : $colorfondo;
$designFourBorder = $contenido->contenido_borde == '1' ? '2px solid #13436B' : 'none';
$designFourRadius = $contenido->contenido_borde == '1' ? '20px' : '0';
$designFourPadding = $contenido->contenido_borde == '1' ? '0' : 'initial';
$designFourOverflow = $contenido->contenido_borde == '1' ? 'hidden' : 'visible';
?>
<style>
	.design-four-dyn-<?php echo $contenido->contenido_id; ?> {
		background-color:
			<?php echo $designFourBgColor; ?>
		;
		border:
			<?php echo $designFourBorder; ?>
		;
		border-radius:
			<?php echo $designFourRadius; ?>
		;
		padding:
			<?php echo $designFourPadding; ?>
		;
		overflow:
			<?php echo $designFourOverflow; ?>
		;
	}

	.design-four-desc-padded {
		padding: 10px;
	}
</style>

<div
	class="caja-contenido-simple p-0 design-four four-<?php echo $contenido->contenido_id ?> design-four-dyn-<?php echo $contenido->contenido_id; ?>">

	<a <?php if ($contenido->contenido_enlace_abrir == 1) { ?> target="_blank" rel="noopener noreferrer" <?php } ?>
		href='<?php echo $contenido->contenido_enlace; ?>'>
		<?php if ($contenido->contenido_imagen) { ?>
			<div class="imagen-contenido">
				<div>

					<img src="/images/<?php echo $contenido->contenido_imagen; ?>">
				</div>
			</div>
		<?php } ?>
		<?php if ($contenido->contenido_titulo_ver == 1) { ?>
			<h2><?php echo $contenido->contenido_titulo; ?></h2>
		<?php } ?>

		<div>
			<div class="descripcion <?php if ($contenido->contenido_borde == '1') { ?>design-four-desc-padded<?php } ?>">
				<?php echo $contenido->contenido_descripcion; ?>
			</div>
	</a>
	<?php if ($contenido->contenido_archivo) { ?>
		<div align="center" class="archivo">
			<a href="/files/<?php echo $contenido->contenido_archivo ?>" target="blank" rel="noopener noreferrer">Descargar
				Archivo <i class="fas fa-download"></i></a>
		</div>
	<?php } ?>
	<?php if ($contenido->contenido_enlace && $contenido->contenido_id == '186') { ?>
		<div>
			<a href="<?php echo $contenido->contenido_enlace; ?>" <?php if ($contenido->contenido_enlace_abrir == 1) { ?>
					target="_blank" rel="noopener noreferrer" <?php } ?> class="btn btn-block btn-vermas">
				<?php if ($contenido->contenido_vermas) { ?> 		<?php echo $contenido->contenido_vermas; ?> 	<?php } else { ?>Ver
					Más<?php } ?></a>
		</div>
	<?php } ?>
</div>
</div>