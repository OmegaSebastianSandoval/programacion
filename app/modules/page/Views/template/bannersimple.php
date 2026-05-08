<div class="slider-simple">
	<?php
	$bannersimpleStyles = '';
	foreach ($rescontenido['hijos'] as $key => $resbanner) {
		$banner = $resbanner['detalle'];
		$bannersimpleStyles .= '.content-caption-bg-' . $contenedor->contenido_id . '-' . $key . '{background-color:' . $banner->contenido_fondo_color . ';}';
	}
	?>
	<style>
		<?php echo $bannersimpleStyles; ?>
	</style>
	<div id="carouselsimple<?php echo $contenedor->contenido_id; ?>" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-indicators">
			<?php foreach ($rescontenido['hijos'] as $key => $resbanner) { ?>
				<button type="button" data-bs-target="#carouselsimple<?php echo $contenedor->contenido_id; ?>"
					data-bs-slide-to="<?php echo $key; ?>" <?php if ($key == 0) { ?>class="active" aria-current="true" <?php } ?>
					aria-label="Slide <?php echo ($key + 1); ?>"></button>
			<?php } ?>
		</div>

		<div class="carousel-inner">
			<?php foreach ($rescontenido['hijos'] as $key => $resbanner) { ?>
				<?php
				$banner = $resbanner['detalle'];
				$hasLink = ($banner->contenido_enlace);
				$openBlank = ($banner->contenido_enlace_abrir == 1);
				?>
				<div class="carousel-item <?php if ($key == 0) { ?>active <?php } ?>">
					<?php if ($hasLink) { ?>
						<a href="<?php echo $banner->contenido_enlace; ?>" <?php if ($openBlank) { ?>target="_blank"
								rel="noopener noreferrer" <?php } ?>>
						<?php } ?>
						<img class="d-block w-100" src="/images/<?php echo $banner->contenido_fondo_imagen; ?>"
							alt="<?php echo $banner->publicidad_titulo; ?>">
						<div
							class="carousel-caption d-flex h-100 align-items-center <?php if ($banner->contenido_columna_alineacion == 2) { ?>justify-content-center text-center<?php } else if ($banner->contenido_columna_alineacion == 3) { ?>justify-content-end text-end<?php } else { ?>justify-content-start text-start<?php } ?>">
							<div class="<?php echo $banner->contenido_columna; ?>">
								<div
									class="content-caption content-caption-bg-<?php echo $contenedor->contenido_id; ?>-<?php echo $key; ?>">
									<?php if ($banner->contenido_titulo_ver == 1) { ?>
										<h2><?php echo $banner->contenido_titulo; ?></h2>
									<?php } ?>
									<div><?php echo $banner->contenido_descripcion; ?></div>
								</div>
							</div>
						</div>
						<?php if ($hasLink) { ?>
						</a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
		<button class="carousel-control-prev" type="button"
			data-bs-target="#carouselsimple<?php echo $contenedor->contenido_id; ?>" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button"
			data-bs-target="#carouselsimple<?php echo $contenedor->contenido_id; ?>" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
</div>