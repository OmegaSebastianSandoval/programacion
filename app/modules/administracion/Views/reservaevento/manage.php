<h1 class="titulo-principal"><i class="fas fa-map-marker-alt"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>" data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<input type="hidden" name="reserva_evento_tipo" value="0">
			<input type="hidden" name="reserva_evento_evento" value="<?php echo $this->content->reserva_evento_evento ?: $this->reserva_evento_evento; ?>">
			<?php if ($this->content->reserva_evento_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->reserva_evento_id; ?>" />
			<?php } ?>

			<?php
			$tipoEvento = $this->evento ? $this->evento->evento_tipo : '';
			$aforoMaximo = $this->evento ? (int)$this->evento->evento_aforomaximo : 0;
			$esSoloReserva = $tipoEvento === 'reserva';
			$esReservaYBoleta = $tipoEvento === 'reservayboleteria';
			?>

			<?php if ($this->evento && $this->evento->evento_nombre): ?>
			<div class="rz-evento-header mb-4">
				<div class="rz-evento-nombre"><?= $this->evento->evento_nombre; ?></div>
				<div class="rz-evento-meta">
					<?php if ($this->evento->evento_fecha): ?>
					<span class="rz-evento-meta-item">
						<i class="fas fa-calendar-alt"></i>
						<?= date('d M Y, H:i', strtotime($this->evento->evento_fecha)); ?>
					</span>
					<?php endif; ?>
					<?php if ($aforoMaximo > 0): ?>
					<span class="rz-evento-meta-item">
						<i class="fas fa-users"></i>
						Aforo: <?= number_format($aforoMaximo); ?> personas
					</span>
					<?php endif; ?>
					<span class="rz-evento-tipo-badge rz-tipo-<?= $tipoEvento; ?>">
						<?= $tipoEvento === 'reserva' ? 'Solo Reserva' : ($tipoEvento === 'reservayboleteria' ? 'Boletería + Reserva' : $tipoEvento); ?>
					</span>
				</div>
			</div>
			<?php endif; ?>

			<?php if ($esSoloReserva): ?>
			<div class="rz-guia-box rz-guia-reserva mb-4">
				<div class="rz-guia-titulo"><i class="fas fa-lightbulb"></i> ¿Cómo funciona?</div>
				<p>Cada registro que crees aquí es una <strong>opción de reserva</strong> que verá el usuario en la página del evento. Puedes crear varias (ej: "Entrada general", "Mesa VIP", "Paquete familiar").</p>
				<ul class="rz-guia-lista">
					<li><strong>Nombre:</strong> lo que ve el usuario al elegir. Sé descriptivo.</li>
					<li><strong>Precio:</strong> valor por persona. Coloca <code>0</code> si la reserva es gratuita.</li>
					<li><strong>Capacidad:</strong> cuántas personas caben en cada unidad de esta opción (ej: una mesa de 6 personas → 6). Coloca <code>0</code> si no hay límite por unidad.</li>
					<li><strong>Unidades disponibles:</strong> cuántas de estas opciones hay en total (ej: 10 mesas de 6 personas → 10).</li>
				</ul>
			</div>
			<?php elseif ($esReservaYBoleta): ?>
			<div class="rz-guia-box rz-guia-palco mb-4">
				<div class="rz-guia-titulo"><i class="fas fa-lightbulb"></i> ¿Cómo funciona?</div>
				<p>Cada registro es una <strong>reserva especial</strong> que el usuario puede reservar <em>además</em> de sus boletas. Al seleccionar una reserva, el sistema le exige comprar la cantidad mínima de boletas indicada.</p>
				<ul class="rz-guia-lista">
					<li><strong>Nombre:</strong> nombre visible para el usuario (ej: "Palco Norte", "Mesa 4 personas").</li>
					<li><strong>Precio:</strong> costo fijo de la reserva (independiente de las boletas).</li>
					<li><strong>Capacidad:</strong> personas que caben en la reserva. Informativo para el usuario.</li>
					<li><strong>Unidades disponibles:</strong> cuántas de estas reservas existen.</li>
					<li><strong>Boleta requerida:</strong> tipo de boleta que debe comprar quien reserve esta reserva.</li>
					<li><strong>Boletas obligatorias:</strong> cantidad mínima de esas boletas para poder reservar.</li>
				</ul>
			</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-12 col-lg-8 form-group">
					<label for="reserva_evento_nombre" class="control-label">
						Nombre de la opción <span class="text-danger">*</span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-tag"></i></span>
						</div>
						<input type="text" value="<?= $this->content->reserva_evento_nombre; ?>"
							name="reserva_evento_nombre" id="reserva_evento_nombre" class="form-control" required
							placeholder="<?= $esReservaYBoleta ? 'ej. Palco Norte, Mesa 6 personas...' : 'ej. Entrada general, Mesa VIP, Paquete familiar...'; ?>">
					</label>
					<div class="help-block with-errors"></div>
				</div>

				<div class="col-12 col-lg-4 form-group">
					<label for="reserva_evento_precio_display" class="control-label">
						Precio <?= $esSoloReserva ? 'por persona' : 'de la zona'; ?> <span class="text-danger">*</span>
						<?php if ($esSoloReserva): ?>
						<span class="rz-label-nota">— escribe 0 si es gratis</span>
						<?php endif; ?>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-dollar-sign"></i></span>
						</div>
						<input type="text" id="reserva_evento_precio_display" class="form-control precio-cop"
							data-hidden="reserva_evento_precio"
							value="<?= $this->content->reserva_evento_precio ? number_format((int)$this->content->reserva_evento_precio, 0, ',', '.') : ''; ?>"
							required autocomplete="off" placeholder="0">
					</label>
					<input type="hidden" name="reserva_evento_precio" id="reserva_evento_precio"
						value="<?= $this->content->reserva_evento_precio ?: 0; ?>">
					<div class="help-block with-errors"></div>
				</div>

				<div class="col-12 col-lg-4 form-group">
					<label for="reserva_evento_capacidad" class="control-label">
						Capacidad
						<span class="rz-label-nota">— personas por unidad<?= $esSoloReserva ? ', 0 = sin límite' : ''; ?></span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-users"></i></span>
						</div>
						<input type="number" min="0" value="<?= (int)$this->content->reserva_evento_capacidad; ?>"
							name="reserva_evento_capacidad" id="reserva_evento_capacidad" class="form-control">
					</label>
				</div>

				<div class="col-12 col-lg-4 form-group">
					<label for="reserva_evento_cantidad" class="control-label">
						Unidades disponibles <span class="text-danger">*</span>
						<span class="rz-label-nota">— cuántas de estas opciones hay</span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-hashtag"></i></span>
						</div>
						<input type="number" min="1" value="<?= $this->content->reserva_evento_cantidad ?: 1; ?>"
							name="reserva_evento_cantidad" id="reserva_evento_cantidad" class="form-control" required>
					</label>
					<div class="help-block with-errors"></div>
				</div>

				<?php if ($this->content->reserva_evento_id): ?>
				<div class="col-12 col-lg-4 form-group">
					<label class="control-label">Reservas vendidas</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-ticket-alt"></i></span>
						</div>
						<input type="text" class="form-control bg-light"
							value="<?= (int)$this->content->reserva_evento_cantidad_vendidas; ?>" disabled>
					</label>
					<input type="hidden" name="reserva_evento_cantidad_vendidas" value="<?= (int)$this->content->reserva_evento_cantidad_vendidas; ?>">
				</div>
				<?php else: ?>
					<input type="hidden" name="reserva_evento_cantidad_vendidas" value="0">
				<?php endif; ?>

				<?php if ($esReservaYBoleta): ?>
				<div class="col-12"><hr class="my-3"><p class="fw-semibold text-secondary mb-3" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.06em;">Configuración de boletas requeridas</p></div>

				<div class="col-12 col-lg-6 form-group">
					<label class="control-label">
						Boleta requerida
						<span class="rz-label-nota">— tipo de boleta que debe comprar quien reserve</span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-ticket-alt"></i></span>
						</div>
						<select class="form-control" name="reserva_evento_boleta_req">
							<option value="0">— Sin boleta requerida —</option>
							<?php foreach ($this->list_boletas as $b): ?>
								<?php $disponibles = (int)$b->boleta_evento_cantidad - (int)$b->boleta_evento_cantidad_vendidas; ?>
								<option value="<?= $b->boleta_evento_id; ?>"
									<?php if ($this->getObjectVariable($this->content, 'reserva_evento_boleta_req') == $b->boleta_evento_id) echo 'selected'; ?>>
									Tipo <?= $b->boleta_evento_tipo; ?> — (<?= $b->boleta_evento_boleta_nombre; ?>) $<?= number_format((int)$b->boleta_evento_precio, 0, ',', '.'); ?> (<?= $disponibles; ?> disponibles)
								</option>
							<?php endforeach ?>
						</select>
					</label>
				</div>

				<div class="col-12 col-lg-6 form-group">
					<label for="reserva_evento_boletas_x_reserva" class="control-label">
						Boletas obligatorias por reserva
						<span class="rz-label-nota">— mínimo que debe comprar para poder reservar</span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-hashtag"></i></span>
						</div>
						<input type="number" min="0" value="<?= (int)$this->content->reserva_evento_boletas_x_reserva; ?>"
							name="reserva_evento_boletas_x_reserva" id="reserva_evento_boletas_x_reserva" class="form-control">
					</label>
				</div>
				<?php else: ?>
					<input type="hidden" name="reserva_evento_boleta_req" value="0">
					<input type="hidden" name="reserva_evento_boletas_x_reserva" value="0">
				<?php endif; ?>

				<div class="col-12"><hr class="my-3"><p class="fw-semibold text-secondary mb-3" style="font-size:.8rem; text-transform:uppercase; letter-spacing:.06em;">Disponibilidad</p></div>

				<div class="col-12 col-lg-4 form-group">
					<label for="reserva_evento_fechalimite" class="control-label">
						Fecha límite para reservar
						<span class="rz-label-nota">— opcional</span>
					</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="datetime-local"
							value="<?= $this->content->reserva_evento_fechalimite ?: ''; ?>"
							<?php if ($this->evento && $this->evento->evento_fecha): ?>
							max="<?= date('Y-m-d', strtotime($this->evento->evento_fecha)); ?>T23:59"
							<?php endif; ?>
							name="reserva_evento_fechalimite" id="reserva_evento_fechalimite" class="form-control">
					</label>
				</div>

				<div class="col-12 col-lg-4 form-group">
					<label class="control-label">Mostrar al público</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-eye"></i></span>
						</div>
						<select class="form-control" name="reserva_evento_activo">
							<option value="1" <?php if ($this->content->reserva_evento_activo != '0') echo 'selected'; ?>>Sí — visible en el evento</option>
							<option value="0" <?php if ($this->content->reserva_evento_activo === '0') echo 'selected'; ?>>No — oculto</option>
						</select>
					</label>
				</div>
			</div>
		</div>
		<div class="botones-acciones">
			<button class="btn btn-guardar" type="submit">Guardar</button>
			<a href="<?php echo $this->route; ?>?reserva_evento_evento=<?= $this->content->reserva_evento_evento ?: $this->reserva_evento_evento; ?>"
				class="btn btn-cancelar">Cancelar</a>
		</div>
	</form>
</div>

<style>
.rz-evento-header {
	padding: 1rem 1.25rem;
	border-radius: 10px;
	background: #fff;
	border: 1px solid #e8e8f0;
	display: flex;
	flex-direction: column;
	gap: .4rem;
}
.rz-evento-nombre {
	font-size: 1.1rem;
	font-weight: 700;
	color: #1f2a37;
}
.rz-evento-meta {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: .5rem .9rem;
}
.rz-evento-meta-item {
	font-size: .8rem;
	color: #888;
	display: flex;
	align-items: center;
	gap: .3rem;
}
.rz-evento-tipo-badge {
	font-size: .7rem;
	font-weight: 700;
	letter-spacing: .04em;
	text-transform: uppercase;
	padding: .2rem .6rem;
	border-radius: 50px;
}
.rz-tipo-reserva { background: #ede9fe; color: #6d28d9; }
.rz-tipo-reservayboleteria { background: #fef3c7; color: #92400e; }
.rz-tipo-boleteria { background: #dbeafe; color: #1e40af; }

.rz-guia-box {
	padding: .9rem 1.1rem;
	border-radius: 8px;
	border-left: 3px solid;
	font-size: .85rem;
	color: #444;
}
.rz-guia-reserva {
	background: #f0fdf4;
	border-color: #16a34a;
}
.rz-guia-palco {
	background: #fffbeb;
	border-color: #d97706;
}
.rz-guia-titulo {
	font-weight: 700;
	margin-bottom: .4rem;
	color: #1f2a37;
}
.rz-guia-lista {
	margin: .4rem 0 0 1rem;
	list-style: disc;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: .2rem;
}
.rz-guia-lista li { list-style: disc; }
.rz-guia-box p { margin: 0 0 .3rem; }
.rz-guia-box code {
	background: rgba(0,0,0,.07);
	padding: .05rem .3rem;
	border-radius: 3px;
	font-size: .8rem;
}
.rz-label-nota {
	font-size: .75rem;
	font-weight: 400;
	color: #aaa;
}
</style>

<script>
	function formatearCOP(valor) {
		var num = String(valor).replace(/\D/g, '');
		if (!num) return '';
		return num.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
	}

	document.querySelectorAll('.precio-cop').forEach(function(input) {
		input.addEventListener('input', function() {
			var cursor = this.selectionStart;
			var prevLen = this.value.length;
			var raw = this.value.replace(/\D/g, '');
			var formatted = formatearCOP(raw);
			this.value = formatted;
			var diff = formatted.length - prevLen;
			this.setSelectionRange(cursor + diff, cursor + diff);
			var hidden = document.getElementById(this.getAttribute('data-hidden'));
			if (hidden) hidden.value = raw;
		});
	});

	document.querySelector('form').addEventListener('submit', function() {
		document.querySelectorAll('.precio-cop').forEach(function(input) {
			var hidden = document.getElementById(input.getAttribute('data-hidden'));
			if (hidden) hidden.value = input.value.replace(/\D/g, '');
		});
	});
</script>
