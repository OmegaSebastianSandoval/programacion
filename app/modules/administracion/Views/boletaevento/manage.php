<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>"
		data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->boleta_evento_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->boleta_evento_id; ?>" />
			<?php } ?>
			<?php
			$aforoMaximo = $this->boleta_evento_aforomaximo;
			$cantidadActual = $this->boleta_evento_cantidadactual;
			$saldo = $this->boleta_evento_saldo;
			$cantidadPropia = $this->content->boleta_evento_cantidad ? (int) $this->content->boleta_evento_cantidad : 0;
			$maxPermitido = $saldo + $cantidadPropia;
			$porcentaje = $aforoMaximo > 0 ? round(($cantidadActual / $aforoMaximo) * 100) : 0;
			?>
			<?php
			$colorClass = $porcentaje >= 90 ? 'danger' : ($porcentaje >= 70 ? 'warning' : 'success');
			$colorHex = $porcentaje >= 90 ? '#dc3545' : ($porcentaje >= 70 ? '#fd7e14' : '#198754');
			$bgLight = $porcentaje >= 90 ? '#fff5f5' : ($porcentaje >= 70 ? '#fff8f0' : '#f0faf4');
			?>
			<div class="mb-4 p-3 rounded-3 border"
				style="background:<?php echo $bgLight; ?>; border-color:<?php echo $colorHex; ?>20 !important;">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<span class="fw-semibold text-secondary"
						style="font-size:.8rem; letter-spacing:.05em; text-transform:uppercase;">Aforo del evento</span>
					<div class="d-flex align-items-center gap-2">
						<span class="fw-bold" style="font-size:1.1rem; color:<?php echo $colorHex; ?>;">
							<?php echo number_format($cantidadActual); ?>
							<span class="text-muted fw-normal" style="font-size:.9rem;">/
								<?php echo number_format($aforoMaximo); ?></span>
						</span>
						<span class="badge rounded-pill"
							style="background:<?php echo $colorHex; ?>; font-size:.75rem; padding:.35em .75em;">
							<?php echo $saldo <= 0 ? 'Agotado' : '+' . number_format($saldo) . ' disp.'; ?>
						</span>
					</div>
				</div>
				<div class="progress" style="height:10px; border-radius:99px; background:#e9ecef;">
					<div class="progress-bar" role="progressbar"
						style="width:<?php echo $porcentaje; ?>%; background:<?php echo $colorHex; ?>; border-radius:99px; transition:width .6s ease;"
						aria-valuenow="<?php echo $cantidadActual; ?>" aria-valuemin="0"
						aria-valuemax="<?php echo $aforoMaximo; ?>">
					</div>
				</div>
				<div class="d-flex justify-content-between mt-1" style="font-size:.72rem; color:#adb5bd;">
					<span>0</span>
					<span><?php echo $porcentaje; ?>% ocupado</span>
					<span><?php echo number_format($aforoMaximo); ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-12  col-lg-3 form-group">
					<label class="control-label">Tipo boleta</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="boleta_evento_tipo">
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_boleta_evento_tipo as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "boleta_evento_tipo") == $key) {
									echo "selected";
								} ?> value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12  col-lg-3 form-group">
					<label for="boleta_evento_cantidad" class="control-label">Cantidad</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="number" value="<?= $this->content->boleta_evento_cantidad; ?>" name="boleta_evento_cantidad"
							id="boleta_evento_cantidad" class="form-control" required min="1" max="<?php echo $maxPermitido; ?>"
							oninput="validarCantidadBoleta(this, <?php echo $maxPermitido; ?>)">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<!-- <div class="col-12  col-lg-3 form-group">
					<label for="boleta_evento_saldo" class="control-label">Saldo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_evento_saldo; ?>" name="boleta_evento_saldo"
							id="boleta_evento_saldo" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div> -->
				<input type="hidden" name="boleta_evento_saldo" value="<?php if ($this->content->boleta_evento_saldo) {
					echo $this->content->boleta_evento_saldo;
				} else {
					echo $this->boleta_evento_saldo;
				} ?>">
				<input type="hidden" name="boleta_evento_evento" value="<?php if ($this->content->boleta_evento_evento) {
					echo $this->content->boleta_evento_evento;
				} else {
					echo $this->boleta_evento_evento;
				} ?>">
				<div class="col-12  col-lg-3 form-group">
					<label for="boleta_evento_precio" class="control-label">Precio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_evento_precio; ?>" name="boleta_evento_precio"
							id="boleta_evento_precio" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" name="boleta_evento_precioadicional"
					value="<?php echo $this->content->boleta_evento_precioadicional ?>">
				<div class="col-12  col-lg-3 form-group">
					<label for="boleta_evento_fechalimite" class="control-label">Fecha l&iacute;mite</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" value="<?php if ($this->content->boleta_evento_fechalimite) {
							echo $this->content->boleta_evento_fechalimite;
						} else {
							echo date('Y-m-d');
						} ?>" name="boleta_evento_fechalimite" id="boleta_evento_fechalimite" class="form-control"
							data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<input type="hidden" name="boleta_evento_horalimite"
					value="<?php echo $this->content->boleta_evento_horalimite ?>">
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>?boleta_evento_evento=<?php if ($this->content->boleta_evento_evento) {
					 echo $this->content->boleta_evento_evento;
				 } else {
					 echo $this->boleta_evento_evento;
				 } ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>
<script>
	function validarCantidadBoleta (input, max) {
		var val = parseInt(input.value, 10);
		if (isNaN(val) || val < 1) {
			input.value = 1;
			return;
		}
		var fb = input.closest('.input-group').nextElementSibling;
		if (val > max) {
			input.value = max;
			input.classList.add('is-invalid');
			if (fb) fb.textContent = 'La cantidad no puede superar los ' + max + ' cupos disponibles.';
		} else {
			input.classList.remove('is-invalid');
			if (fb) fb.textContent = '';
		}
	}
</script>