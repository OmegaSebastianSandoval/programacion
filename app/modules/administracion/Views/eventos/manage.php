<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>"
		data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->evento_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->evento_id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-12 col-lg-1 form-group">
					<label class="control-label">&iquest;Es bono?</label>
					<input type="checkbox" name="evento_bono" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'evento_bono') == 1) {
						echo "checked";
					} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label class="control-label">&iquest;Est&aacute; activo?</label>
					<input type="checkbox" name="evento_activo" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'evento_activo') == 1) {
						echo "checked";
					} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label class="control-label">Tipo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="evento_tipo" required>
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_evento_tipo as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "evento_tipo") == $key) {
									echo "selected";
								} ?>
									value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-6 form-group">
					<label for="evento_nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->evento_nombre; ?>" name="evento_nombre" id="evento_nombre"
							class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="evento_imagen">Imagen</label>
					<input type="file" name="evento_imagen" id="evento_imagen" class="form-control  file-image"
						data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
					<div class="help-block with-errors"></div>
					<?php if ($this->content->evento_imagen) { ?>
						<div id="imagen_evento_imagen">
							<img src="/images/<?= $this->content->evento_imagen; ?>" class="img-thumbnail thumbnail-administrator" />
							<div><button class="btn btn-danger btn-sm" type="button"
									onclick="eliminarImagen('evento_imagen','<?php echo $this->route . "/deleteimage"; ?>')"><i
										class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
						</div>
					<?php } ?>
				</div>
				<?php /* COSTO — ahora viene de otra fuente; campos deshabilitados temporalmente
				<div class="col-12 col-lg-3 form-group">
					<label for="evento_costo_display" class="control-label">Costo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="hidden" name="evento_costo" id="evento_costo" value="<?= $this->content->evento_costo; ?>">
						<input type="text" id="evento_costo_display" class="form-control" required placeholder="$ 0"
							value="<?= $this->content->evento_costo ? '$ ' . number_format((float)$this->content->evento_costo, 0, ',', '.') : ''; ?>">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				*/ ?>
				<div class="col-12 col-lg-3 form-group">
					<label for="evento_fecha" class="control-label">Fecha</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="datetime-local" value="<?= $this->content->evento_fecha; ?>" name="evento_fecha"
							id="evento_fecha" class="form-control" required min="<?= date('Y-m-d\TH:i') ?>">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group d-none">
					<label for="evento_hora" class="control-label">Hora</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->evento_hora; ?>" name="evento_hora" id="evento_hora"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label class="control-label">Lugar</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="evento_lugar" id="evento_lugar" required>
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_evento_lugar as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "evento_lugar") == $key) {
									echo "selected";
								} ?>
									value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>




				<div class="col-12 col-lg-2 form-group">
					<label class="control-label">Estado</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<?php $estadoActual = $this->getObjectVariable($this->content, 'evento_estado') ?: 'activo'; ?>
						<input type="hidden" name="evento_estado" value="<?= $estadoActual ?>">
						<select class="form-control" disabled>
							<?php foreach ($this->list_evento_estado as $key => $value) { ?>
								<option <?php if ($estadoActual == $key) { echo "selected"; } ?>
									value="<?php echo $key; ?>"><?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label for="evento_aforomaximo" class="control-label">Aforo m&aacute;ximo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="number" min="0" value="<?= $this->content->evento_aforomaximo; ?>" name="evento_aforomaximo"
							readonly id="evento_aforomaximo" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label for="evento_porcentaje_pagoinicial" class="control-label">Porcentaje pago inicial</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="number" value="<?= $this->content->evento_porcentaje_pagoinicial; ?>"
							name="evento_porcentaje_pagoinicial" id="evento_porcentaje_pagoinicial" min="0" max="100"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="evento_descripcion" class="form-label">Descripci&oacute;n</label>
					<textarea name="evento_descripcion" id="evento_descripcion" class="form-control tinyeditor"
						rows="10"><?= $this->content->evento_descripcion; ?></textarea>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12  form-group">
					<label for="evento_titulo_politica" class="control-label">T&iacute;tulo pol&iacute;tica</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->evento_titulo_politica; ?>" name="evento_titulo_politica"
							id="evento_titulo_politica" class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12  form-group">
					<label for="evento_descripcion_politica" class="form-label">Pol&iacute;tica</label>
					<textarea name="evento_descripcion_politica" id="evento_descripcion_politica" class="form-control tinyeditor"
						rows="10"><?= $this->content->evento_descripcion_politica; ?></textarea>
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<input type="hidden" name="evento_cupo" id="evento_cupo" value="<?= $this->content->evento_cupo; ?>">
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>
<script>
(function () {
	// const costoHidden = document.getElementById('evento_costo');   // costo viene de otra fuente
	// const costoDisplay = document.getElementById('evento_costo_display');
	const lugarSelect = document.getElementById('evento_lugar');
	const aforoInput = document.getElementById('evento_aforomaximo');
	const afoRoute = '<?php echo $this->route; ?>/getaforo';

	// function formatCOP(value) { ... }           // costo viene de otra fuente
	// costoDisplay.addEventListener('input', ...)
	// costoDisplay.closest('form').addEventListener('submit', ...)

	function fetchAforo(sedeId) {
		if (!sedeId) return;
		fetch(afoRoute + '?sede_id=' + encodeURIComponent(sedeId))
			.then(r => r.json())
			.then(data => {
				const aforo = data.sede_aforo || 0;
				aforoInput.value = aforo;
				aforoInput.max = aforo;
			});
	}

	const activoCheck = document.querySelector('input[name="evento_activo"]');
	const estadoHidden = document.querySelector('input[name="evento_estado"]');
	const estadoSelect = estadoHidden ? estadoHidden.nextElementSibling : null;

	function syncEstado() {
		const estado = activoCheck.checked ? 'activo' : 'inactivo';
		estadoHidden.value = estado;
		if (estadoSelect) estadoSelect.value = estado;
	}

	if (activoCheck) {
		activoCheck.addEventListener('change', syncEstado);
	}

	lugarSelect.addEventListener('change', function () {
		fetchAforo(this.value);
	});

	if (lugarSelect.value) {
		fetchAforo(lugarSelect.value);
	}
})();
</script>