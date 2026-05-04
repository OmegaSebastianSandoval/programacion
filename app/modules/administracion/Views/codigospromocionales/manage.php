<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<div class="alert alert-info my-3" role="alert">
		<strong><i class="fas fa-info-circle"></i> Información sobre los códigos promocionales:</strong>
		<ul class="mb-0 mt-1">
			<li><strong>Valor:</strong> monto fijo que se descuenta del total de la compra (ej: $5.000).</li>
			<li><strong>Porcentaje:</strong> porcentaje que se descuenta del total de la compra (ej: 10%).</li>
			<li><strong>Evento:</strong> si no se selecciona, el código aplica para todos los eventos; si se selecciona uno,
				solo funciona para ese evento.</li>
			<li><strong>Fecha máxima de uso:</strong> si se selecciona un evento, se usará la fecha del evento como máxima
				(puede reducirse, pero no superarla); si no se selecciona evento y se coloca fecha máxima, esa será la vigencia;
				si no se coloca ninguna, el código no tiene límite de fecha.</li>
			<li><strong>Tipo "Varios usos":</strong> habilita el campo <em>Cantidad máxima de usos</em>; déjelo vacío para
				usos ilimitados.</li>
		</ul>
	</div>
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>"
		data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-12 col-lg-1 form-group">
					<label class="control-label">Activo</label>
					<input type="checkbox" name="activo" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'activo') == 1) {
						echo "checked";
					} ?>></input>
					<div class="help-block with-errors"></div>
				</div>

				<div class="col-12 col-lg-2 form-group">
					<label for="codigo" class="control-label">C&oacute;digo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->codigo; ?>" name="codigo" id="codigo" class="form-control"
							required>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label class="control-label">Tipo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="tipo" id="tipo" required>
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_tipo as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "tipo") == $key) {
									echo "selected";
								} ?>
									value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group" id="wrap-cantidad-usos" style="display:none;">
					<label for="cantidad_usos_maxima" class="control-label">Cantidad m&aacute;x. de usos</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-hashtag"></i></span>
						</div>
						<input type="number" min="1" value="<?= $this->content->cantidad_usos_maxima; ?>"
							name="cantidad_usos_maxima" id="cantidad_usos_maxima" class="form-control" placeholder="Ilimitado">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label for="valor_display" class="control-label">Valor</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="hidden" name="valor" id="valor" value="<?= $this->content->valor; ?>">
						<input type="text" id="valor_display" class="form-control" autocomplete="off"
							value="<?= $this->content->valor ? number_format((float)$this->content->valor, 0, ',', '.') : ''; ?>"
							placeholder="0">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-2 form-group">
					<label for="porcentaje" class="control-label">Porcentaje</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="number" min="1" max="100" value="<?= $this->content->porcentaje; ?>" name="porcentaje" id="porcentaje"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label class="control-label">Evento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono  "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="evento" id="evento">
							<option value="">Seleccione...</option>
							<?php foreach ($this->list_evento as $key => $value) { ?>
								<option <?php if ($this->getObjectVariable($this->content, "evento") == $key) {
									echo "selected";
								} ?>
									value="<?php echo $key; ?>" /> <?= $value; ?></option>
							<?php } ?>
						</select>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="fecha" class="control-label">Fecha m&aacute;xima de uso</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="datetime-local" value="<?= $this->content->fecha; ?>" name="fecha" id="fecha"
							class="form-control" min="<?= date('Y-m-d\TH:i') ?>">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="fecha_uso" class="control-label">Fecha uso</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="datetime-local" value="<?= $this->content->fecha_uso; ?>" name="fecha_uso" id="fecha_uso"
							class="form-control" readonly>
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-1 form-group">
					<label class="control-label">Usado</label>
					<input type="checkbox" readonly name="usado" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'usado') == 1) {
						echo "checked";
					} ?> readonly></input>
					<div class="help-block with-errors"></div>
				</div>

			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>" class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>
<script>
	(function () {
		const valorHidden  = document.getElementById('valor');
		const valorDisplay = document.getElementById('valor_display');

		function formatCOP(raw) {
			const num = parseInt(raw.replace(/\D/g, ''), 10);
			return isNaN(num) ? '' : num.toLocaleString('es-CO');
		}

		valorDisplay.addEventListener('input', function () {
			const clean = this.value.replace(/\D/g, '');
			const cursor = this.selectionStart - (this.value.length - clean.length);
			this.value = clean ? parseInt(clean, 10).toLocaleString('es-CO') : '';
			valorHidden.value = clean || '0';
		});

		valorDisplay.closest('form').addEventListener('submit', function () {
			valorHidden.value = valorDisplay.value.replace(/\D/g, '') || '0';
		});

		const tipoSelect = document.getElementById('tipo');
		const wrapCantidad = document.getElementById('wrap-cantidad-usos');
		const cantidadInput = document.getElementById('cantidad_usos_maxima');
		const eventoSelect = document.getElementById('evento');
		const fechaInput = document.getElementById('fecha');
		const fechaRoute = '<?php echo $this->route; ?>/getfechaevento';
		const today = '<?= date('Y-m-d\TH:i') ?>';

		// Fecha mínima siempre es hoy
		fechaInput.min = today;

		function toggleCantidadUsos () {
			const esVarios = tipoSelect.value === 'varios-usos';
			wrapCantidad.style.display = esVarios ? '' : 'none';
			cantidadInput.disabled = !esVarios;
			if (!esVarios) cantidadInput.value = '';
		}

		function fetchFechaEvento (eventoId) {
			if (!eventoId) {
				// Sin evento: quitar máximo, solo mantener mínimo de hoy
				fechaInput.max = '';
				return;
			}
			fetch(fechaRoute + '?evento_id=' + encodeURIComponent(eventoId))
				.then(r => r.json())
				.then(data => {
					if (data.evento_fecha) {
						// Convertir "YYYY-MM-DD HH:MM:SS" a "YYYY-MM-DDTHH:MM"
						const maxVal = data.evento_fecha.replace(' ', 'T').substring(0, 16);
						fechaInput.max = maxVal;
						// Si la fecha actual del campo supera el máximo o está vacía, la ajustamos
						if (!fechaInput.value || fechaInput.value > maxVal) {
							fechaInput.value = maxVal;
						}
					} else {
						fechaInput.max = '';
					}
				});
		}

		tipoSelect.addEventListener('change', toggleCantidadUsos);
		eventoSelect.addEventListener('change', function () {
			fetchFechaEvento(this.value);
		});

		// Inicializar estado al cargar
		toggleCantidadUsos();
		if (eventoSelect.value) {
			fetchFechaEvento(eventoSelect.value);
		}
	})();
</script>