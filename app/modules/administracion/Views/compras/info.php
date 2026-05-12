<?php
$c = $this->compra;
$detalles = $this->detalles;
$evento = $this->evento;
$reserva = $this->reserva;
$sede = $this->sede;

$val = strtolower($c->boleta_compra_validacion ?? '');
if ($val == '1')
	$val = 'aprobada';
$valColor = '#8898aa';
$valLabel = $val ?: 'sin estado';
if ($val == 'aprobada') {
	$valColor = '#38a169';
} elseif ($val == 'pendiente') {
	$valColor = '#d97706';
} elseif ($val == 'rechazada') {
	$valColor = '#e53e3e';
}

$entradaValidada = ($c->boleta_compra_validacionentrada ?? 0) == 1;
?>

<div class="container-fluid">
	<div class="ri-page">

		<a href="<?= $this->route ?>" class="ri-back"><i class="fas fa-arrow-left"></i> Volver al listado</a>

		<!-- ── HERO ── -->
		<div class="ri-hero">
			<div class="ri-hero-left">
				<span class="ri-hero-label">Compra</span>
				<span class="ri-hero-id">#<?= $c->boleta_compra_id ?></span>
				<?php if ($c->boleta_compra_nombre): ?>
					<span class="ri-hero-nombre"><?= ($c->boleta_compra_nombre) ?></span>
				<?php endif; ?>
			</div>
			<div class="ri-hero-right">
				<span class="ri-estado-pill" style="color:<?= $valColor ?>; border-color:<?= $valColor ?>44;">
					<span class="ri-estado-dot" style="background:<?= $valColor ?>;"></span>
					<?= ucfirst($valLabel) ?>
				</span>
				<?php if ($c->boleta_compra_total > 0): ?>
					<div>
						<div class="ri-hero-total-label">Total</div>
						<div class="ri-hero-total">$ <?= number_format($c->boleta_compra_total, 0, ',', '.') ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- ── META BAR ── -->
		<div class="ri-meta-row">
			<div class="ri-meta-item">
				<div class="ri-meta-label">Fecha</div>
				<div class="ri-meta-value"><?= $c->boleta_compra_fecha ?: '—' ?></div>
			</div>
			<?php if ($c->boleta_compra_email): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Email</div>
					<div class="ri-meta-value" title="<?= ($c->boleta_compra_email) ?>">
						<?= ($c->boleta_compra_email) ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($c->boleta_compra_telefono): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Teléfono</div>
					<div class="ri-meta-value"><?= ($c->boleta_compra_telefono) ?></div>
				</div>
			<?php endif; ?>
			<?php if ($c->boleta_compra_documento): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Documento</div>
					<div class="ri-meta-value"><?= ($c->boleta_compra_documento) ?></div>
				</div>
			<?php endif; ?>
			<?php if ($c->boleta_compra_entidad): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Entidad</div>
					<div class="ri-meta-value"><?= ($c->boleta_compra_entidad) ?></div>
				</div>
			<?php endif; ?>
			<?php if ($c->boleta_compra_validacion2): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Validación 2</div>
					<div class="ri-meta-value"><?= ($c->boleta_compra_validacion2) ?></div>
				</div>
			<?php endif; ?>
			<div class="ri-meta-item">
				<div class="ri-meta-label">Entrada validada</div>
				<div class="ri-meta-value" style="color:<?= $entradaValidada ? '#38a169' : '#8898aa' ?>;">
					<?= $entradaValidada ? 'Sí' : 'No' ?>
					<?php if ($c->boleta_compra_fechavalidacion): ?>
						<span
							style="font-size:11px;color:var(--text-4);display:block;"><?= $c->boleta_compra_fechavalidacion ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- ── EVENTO ── -->
		<?php if ($evento): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon"><i class="fas fa-calendar-alt"></i></span>
					<span class="ri-card-title">Evento</span>
					<span class="ri-card-badge"
						style="color:var(--info);border-color:var(--info)44;">#<?= $evento->evento_id ?></span>
				</div>
				<div class="ri-card-body">
					<div class="ri-fields">
						<div class="ri-field">
							<div class="ri-field-label">Nombre</div>
							<div class="ri-field-value"><?= ($evento->evento_nombre) ?></div>
						</div>
						<div class="ri-field">
							<div class="ri-field-label">Fecha</div>
							<div class="ri-field-value">
								<?= $evento->evento_fecha ?> 	<?= $evento->evento_hora ? ' · ' . $evento->evento_hora : '' ?>
							</div>
						</div>
						<?php if ($sede): ?>
							<div class="ri-field">
								<div class="ri-field-label">Lugar</div>
								<div class="ri-field-value"><?= ($sede->sede_nombre) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($evento->evento_costo > 0): ?>
							<div class="ri-field">
								<div class="ri-field-label">Costo</div>
								<div class="ri-field-value amount">$ <?= number_format($evento->evento_costo, 0, ',', '.') ?></div>
							</div>
						<?php endif; ?>
						<?php if ($evento->evento_estado): ?>
							<div class="ri-field">
								<div class="ri-field-label">Estado Evento</div>
								<div class="ri-field-value"><?= ($evento->evento_estado) ?></div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- ── COMPRADOR ── -->
		<div class="ri-card">
			<div class="ri-card-header">
				<span class="ri-card-icon"><i class="fas fa-user"></i></span>
				<span class="ri-card-title">Datos del comprador</span>
				<?php if ($c->boleta_compra_codigo): ?>
					<span class="ri-card-badge" style="color:var(--text-3);border-color:var(--border-strong);">
						Cód: <?= ($c->boleta_compra_codigo) ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="ri-card-body">
				<div class="ri-fields">
					<?php if ($c->boleta_compra_nombre): ?>
						<div class="ri-field">
							<div class="ri-field-label">Nombre</div>
							<div class="ri-field-value"><?= ($c->boleta_compra_nombre) ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_email): ?>
						<div class="ri-field">
							<div class="ri-field-label">Email</div>
							<div class="ri-field-value"><?= ($c->boleta_compra_email) ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_telefono): ?>
						<div class="ri-field">
							<div class="ri-field-label">Teléfono</div>
							<div class="ri-field-value"><?= ($c->boleta_compra_telefono) ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_documento): ?>
						<div class="ri-field">
							<div class="ri-field-label">Documento</div>
							<div class="ri-field-value mono"><?= ($c->boleta_compra_documento) ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_fechacedula): ?>
						<div class="ri-field">
							<div class="ri-field-label">Fecha expedición cédula</div>
							<div class="ri-field-value"><?= $c->boleta_compra_fechacedula ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_fechanacimiento): ?>
						<div class="ri-field">
							<div class="ri-field-label">Fecha nacimiento</div>
							<div class="ri-field-value"><?= $c->boleta_compra_fechanacimiento ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_vendedor): ?>
						<div class="ri-field">
							<div class="ri-field-label">Vendedor</div>
							<div class="ri-field-value"><?= ($c->boleta_compra_vendedor) ?></div>
						</div>
					<?php endif; ?>
					<?php if ($c->boleta_compra_tipo): ?>
						<div class="ri-field">
							<div class="ri-field-label">Tipo compra</div>
							<div class="ri-field-value"><?= ucfirst(($c->boleta_compra_tipo)) ?></div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- ── DETALLE DE BOLETAS ── -->
		<?php if (($detalles)): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon" style="background:var(--brand-green-bg);color:var(--brand-green-dk);"><i
							class="fas fa-ticket-alt"></i></span>
					<span class="ri-card-title">Detalle de boletas</span>
				</div>
				<div class="ri-card-body">
					<?php
					$totalItems = 0;
					foreach ($detalles as $det)
						$totalItems += $det->detalle_subtotal;
					?>
					<table class="ri-table">
						<thead>
							<tr>
								<th>Tipo de Boleta</th>
								<th class="c">Cant.</th>
								<th class="r">Precio Unit.</th>
								<th class="r">Precio Reserva</th>
								<th class="r">Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($detalles as $det): ?>
								<tr>
									<td><?= ($det->detalle_boleta_nombre ?: $det->detalle_boleta) ?></td>
									<td class="c"><?= $det->detalle_cantidad ?></td>
									<td class="r">
										<?= $det->detalle_precio_unit > 0 ? '$ ' . number_format($det->detalle_precio_unit, 0, ',', '.') : '—' ?>
									</td>
									<td class="r">
										<?= $det->detalle_precio_reserva > 0 ? '$ ' . number_format($det->detalle_precio_reserva, 0, ',', '.') : '—' ?>
									</td>
									<td class="r strong">
										<?= $det->detalle_subtotal > 0 ? '$ ' . number_format($det->detalle_subtotal, 0, ',', '.') : '—' ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						<?php if ($totalItems > 0): ?>
							<tfoot class="ri-table-foot">
								<tr>
									<td colspan="4" class="r"
										style="color:var(--text-3);font-size:11px;letter-spacing:.06em;text-transform:uppercase;">
										Total ítems
									</td>
									<td class="r" style="color:var(--brand-green-dk);">$ <?= number_format($totalItems, 0, ',', '.') ?></td>
								</tr>
							</tfoot>
						<?php endif; ?>
					</table>
				</div>
			</div>
		<?php endif; ?>

		<!-- ── RESERVA ASOCIADA ── -->
		<?php if ($reserva): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon" style="background:var(--info-bg);color:var(--info);"><i
							class="fas fa-calendar-check"></i></span>
					<span class="ri-card-title">Reserva asociada</span>
					<span class="ri-card-badge"
						style="color:var(--info);border-color:var(--info)44;">#<?= $reserva->reserva_id ?></span>
				</div>
				<div class="ri-card-body">
					<div class="ri-fields">
						<?php if ($reserva->reserva_nombre): ?>
							<div class="ri-field">
								<div class="ri-field-label">Nombre</div>
								<div class="ri-field-value"><?= ($reserva->reserva_nombre) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($reserva->reserva_email): ?>
							<div class="ri-field">
								<div class="ri-field-label">Email</div>
								<div class="ri-field-value"><?= ($reserva->reserva_email) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($reserva->reserva_estado): ?>
							<?php
							$rEstado = strtolower($reserva->reserva_estado);
							$rSpill = in_array($rEstado, ['confirmada', 'pagada', 'completada', 'pendiente', 'cancelada'])
								? 'spill-' . $rEstado : 'spill-default';
							?>
							<div class="ri-field">
								<div class="ri-field-label">Estado reserva</div>
								<div class="ri-field-value">
									<span class="spill <?= $rSpill ?>">
										<span class="spill-dot"></span>
										<?= ucfirst($rEstado) ?>
									</span>
								</div>
							</div>
						<?php endif; ?>
						<?php if ($reserva->reserva_tipo_origen): ?>
							<div class="ri-field">
								<div class="ri-field-label">Origen</div>
								<div class="ri-field-value"><?= ($reserva->reserva_tipo_origen) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($reserva->reserva_cantidad_personas > 0): ?>
							<div class="ri-field">
								<div class="ri-field-label">Personas</div>
								<div class="ri-field-value"><?= $reserva->reserva_cantidad_personas ?></div>
							</div>
						<?php endif; ?>
						<?php if ($reserva->reserva_fecha_creacion): ?>
							<div class="ri-field">
								<div class="ri-field-label">Fecha reserva</div>
								<div class="ri-field-value"><?= $reserva->reserva_fecha_creacion ?></div>
							</div>
						<?php endif; ?>
					</div>
					<?php if ($reserva->reserva_notas): ?>
						<div style="margin-top:16px;">
							<div class="ri-items-label">Notas</div>
							<div class="ri-notas"><?= nl2br(($reserva->reserva_notas)) ?></div>
						</div>
					<?php endif; ?>
					<div style="margin-top:16px;">
						<a class="btn btn-azul btn-sm" href="/administracion/reservas/info?id=<?= $reserva->reserva_id ?>">
							<i class="fas fa-eye"></i> Ver reserva completa
						</a>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- ── CÓDIGO DE RESPUESTA ── -->
		<?php if ($c->boleta_compra_respuesta): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon" style="background:var(--surface-3);color:var(--text-4);"><i
							class="fas fa-code"></i></span>
					<span class="ri-card-title">Respuesta pasarela</span>
				</div>
				<div class="ri-card-body">
					<div class="ri-notas" style="font-family:monospace;font-size:12px;word-break:break-all;">
						<?= ($c->boleta_compra_respuesta) ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div style="margin-top:28px;">
			<a href="<?= $this->route ?>" class="ri-back"><i class="fas fa-arrow-left"></i> Volver al listado</a>
		</div>

	</div>
</div>