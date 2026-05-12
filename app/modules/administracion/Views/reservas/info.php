<?php
$r = $this->reserva;
$compra = $this->compra;
$detalles = $this->detalles;
$evento = $this->evento;
$sede = $this->sede;

$estado = $r->reserva_estado ?? '';
$estadoColor = '#8898aa';
$estadoLabel = $estado ?: 'sin estado';
if (in_array(strtolower($estado), ['confirmada', 'pagada', 'aprobada'])) {
	$estadoColor = '#38a169';
} elseif (strtolower($estado) == 'pendiente') {
	$estadoColor = '#d97706';
} elseif (strtolower($estado) == 'cancelada') {
	$estadoColor = '#e53e3e';
}

$validacionStates = [
	'1' => 'Aceptada', '2' => 'Rechazada', '3' => 'Pendiente',
	'4' => 'Fallida', '6' => 'Reversada', '7' => 'Retenida',
	'8' => 'Iniciada', '9' => 'Caducada', '10' => 'Abandonada',
	'11' => 'Cancelada',
];
$validacionSuccessCodes = ['1'];
$validacionPendingCodes = ['3', '7', '8'];

$valCompra = $compra ? ($compra->boleta_compra_validacion ?? '') : '';
$esSoloReserva = strtolower($r->reserva_tipo_origen ?? '') === 'solo_reserva';

if ($esSoloReserva && ($valCompra === '' || $valCompra === '0' || $valCompra === null)) {
	$valLabel = 'No necesita validación';
	$valColor = '#8898aa';
} elseif (isset($validacionStates[$valCompra])) {
	$valLabel = $validacionStates[$valCompra];
	if (in_array($valCompra, $validacionSuccessCodes)) {
		$valColor = '#38a169';
	} elseif (in_array($valCompra, $validacionPendingCodes)) {
		$valColor = '#d97706';
	} else {
		$valColor = '#e53e3e';
	}
} else {
	$valLabel = $valCompra ?: 'Sin estado';
	$valColor = '#8898aa';
	if (strtolower($valCompra) === 'aprobada') $valColor = '#38a169';
	elseif (strtolower($valCompra) === 'pendiente') $valColor = '#d97706';
	elseif (strtolower($valCompra) === 'rechazada') $valColor = '#e53e3e';
}
?>

<div class="container-fluid">
	<div class="ri-page">

		<a href="<?= $this->route ?>" class="ri-back"><i class="fas fa-arrow-left"></i> Volver al listado</a>

		<!-- ── HERO ── -->
		<div class="ri-hero">
			<div class="ri-hero-left">
				<span class="ri-hero-label">Reserva</span>
				<span class="ri-hero-id">#<?= $r->reserva_id ?></span>
				<?php if ($r->reserva_nombre): ?>
					<span class="ri-hero-nombre"><?= ($r->reserva_nombre) ?></span>
				<?php endif; ?>
			</div>
			<div class="ri-hero-right">
				<span class="ri-estado-pill" style="color:<?= $estadoColor ?>; border-color:<?= $estadoColor ?>44;">
					<span class="ri-estado-dot" style="background:<?= $estadoColor ?>;"></span>
					<?= ($estadoLabel) ?>
				</span>
				<?php if ($r->reserva_total > 0): ?>
					<div>
						<div class="ri-hero-total-label">Total</div>
						<div class="ri-hero-total">$ <?= number_format($r->reserva_total, 0, ',', '.') ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- ── META BAR ── -->
		<div class="ri-meta-row">
			<div class="ri-meta-item">
				<div class="ri-meta-label">Fecha</div>
				<div class="ri-meta-value"><?= $r->reserva_fecha_creacion ?: '—' ?></div>
			</div>
			<?php if ($r->reserva_email): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Email</div>
					<div class="ri-meta-value" title="<?= ($r->reserva_email) ?>">
						<?= ($r->reserva_email) ?></div>
				</div>
			<?php endif; ?>
			<?php if ($r->reserva_tipo_origen): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Origen</div>
					<div class="ri-meta-value"><?= ($r->reserva_tipo_origen) ?></div>
				</div>
			<?php endif; ?>
			<?php if ($r->reserva_cantidad_personas > 0): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">Personas</div>
					<div class="ri-meta-value"><?= $r->reserva_cantidad_personas ?></div>
				</div>
			<?php endif; ?>
			<?php if ($r->reserva_compra_id > 0): ?>
				<div class="ri-meta-item">
					<div class="ri-meta-label">N° Compra</div>
					<div class="ri-meta-value">#<?= $r->reserva_compra_id ?></div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($r->reserva_notas): ?>
			<!-- ── NOTAS ── -->
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon"><i class="fas fa-sticky-note"></i></span>
					<span class="ri-card-title">Notas</span>
				</div>
				<div class="ri-card-body">
					<div class="ri-notas"><?= nl2br(($r->reserva_notas)) ?></div>
				</div>
			</div>
		<?php endif; ?>

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
								<?= $evento->evento_fecha ?>	<?= $evento->evento_hora ? ' · ' . $evento->evento_hora : '' ?></div>
						</div>
						<?php if ($evento->evento_lugar): ?>
							<div class="ri-field">
								<div class="ri-field-label">Sede / Lugar</div>
								<div class="ri-field-value"><?= $sede ? ($sede->sede_nombre) : $evento->evento_lugar ?></div>
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
		<?php elseif ($r->reserva_evento): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon"><i class="fas fa-calendar-alt"></i></span>
					<span class="ri-card-title">Evento</span>
				</div>
				<div class="ri-card-body">
					<div class="ri-fields">
						<div class="ri-field">
							<div class="ri-field-label">Nombre</div>
							<div class="ri-field-value"><?= ($r->reserva_evento) ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- ── COMPRA ── -->
		<?php if ($compra): ?>
			<div class="ri-card">
				<div class="ri-card-header">
					<span class="ri-card-icon" style="background:var(--info-bg);color:var(--info);"><i
							class="fas fa-receipt"></i></span>
					<span class="ri-card-title">Compra #<?= $compra->boleta_compra_id ?></span>
					<span class="ri-card-badge" style="color:<?= $valColor ?>;border-color:<?= $valColor ?>44;">
						<?= ($valLabel) ?>
					</span>
				</div>
				<div class="ri-card-body">
					<div class="ri-fields">
						<div class="ri-field">
							<div class="ri-field-label">Fecha Compra</div>
							<div class="ri-field-value"><?= $compra->boleta_compra_fecha ?: '—' ?></div>
						</div>
						<div class="ri-field">
							<div class="ri-field-label">Nombre</div>
							<div class="ri-field-value">
								<?= $compra->boleta_compra_nombre ? ($compra->boleta_compra_nombre) : '<span class="ri-field-value muted">—</span>' ?>
							</div>
						</div>
						<?php if ($compra->boleta_compra_email): ?>
							<div class="ri-field">
								<div class="ri-field-label">Email</div>
								<div class="ri-field-value"><?= ($compra->boleta_compra_email) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($compra->boleta_compra_telefono): ?>
							<div class="ri-field">
								<div class="ri-field-label">Teléfono</div>
								<div class="ri-field-value"><?= ($compra->boleta_compra_telefono) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($compra->boleta_compra_documento): ?>
							<div class="ri-field">
								<div class="ri-field-label">Documento</div>
								<div class="ri-field-value mono"><?= ($compra->boleta_compra_documento) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($compra->boleta_compra_entidad): ?>
							<div class="ri-field">
								<div class="ri-field-label">Entidad / Franquicia</div>
								<div class="ri-field-value"><?= ($compra->boleta_compra_entidad) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($compra->boleta_compra_codigo): ?>
							<div class="ri-field">
								<div class="ri-field-label">Código</div>
								<div class="ri-field-value mono"><?= ($compra->boleta_compra_codigo) ?></div>
							</div>
						<?php endif; ?>
						<?php if ($compra->boleta_compra_total > 0): ?>
							<div class="ri-field">
								<div class="ri-field-label">Total Compra</div>
								<div class="ri-field-value amount">$ <?= number_format($compra->boleta_compra_total, 0, ',', '.') ?></div>
							</div>
						<?php endif; ?>
					</div>

					<?php if (($detalles)): ?>
						<hr class="ri-divider">
						<div class="ri-items-label">Detalle de ítems</div>
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
								<?php
								$totalItems = 0;
								foreach ($detalles as $det):
									$totalItems += $det->detalle_subtotal;
									?>
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
											<?= $det->detalle_subtotal > 0 ? '$ ' . number_format($det->detalle_subtotal, 0, ',', '.') : '—' ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
							<?php if ($totalItems > 0): ?>
								<tfoot class="ri-table-foot">
									<tr>
										<td colspan="4" class="r"
											style="color:var(--text-3);font-size:11px;letter-spacing:.06em;text-transform:uppercase;">Total ítems
										</td>
										<td class="r" style="color:var(--brand-green-dk);">$ <?= number_format($totalItems, 0, ',', '.') ?></td>
									</tr>
								</tfoot>
							<?php endif; ?>
						</table>
					<?php endif; ?>
				</div>
			</div>

		<?php elseif ($r->reserva_compra_id > 0): ?>
			<div class="ri-card">
				<div class="ri-ref-missing">
					<i class="fas fa-link" style="color:var(--text-5);"></i>
					<span>Referencia a compra <strong>#<?= $r->reserva_compra_id ?></strong> no encontrada en el sistema.</span>
				</div>
			</div>
		<?php endif; ?>

		<div style="margin-top:28px;">
			<a href="<?= $this->route ?>" class="ri-back"><i class="fas fa-arrow-left"></i> Volver al listado</a>
		</div>

	</div>
</div>