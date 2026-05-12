<?php
$activeEvento = $this->getObjectVariable($this->filters, 'boleta_compra_evento');
$activeValidacion = $this->getObjectVariable($this->filters, 'boleta_compra_validacion');
$activeTipo = $this->getObjectVariable($this->filters, 'boleta_compra_tipo');
?>
<h1 class="titulo-principal"><i class="fas fa-receipt"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form action="<?php echo $this->route; ?>" method="post">
		<div class="content-dashboard">
			<div class="row">

				<!-- Evento -->
				<div class="col-4">
					<label>Evento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-calendar-alt"></i></span>
						</div>
						<select class="form-control select2-eventos" name="boleta_compra_evento">
							<option value="">— Todos los eventos —</option>
							<?php foreach ($this->eventos as $ev): ?>
								<option value="<?= $ev->evento_id ?>" <?= $activeEvento == $ev->evento_id ? 'selected' : '' ?>>
									<?= htmlspecialchars($ev->evento_nombre) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

				<!-- Validación -->
				<div class="col-2">
					<label>Validación</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-circle"></i></span>
						</div>
						<select class="form-control" name="boleta_compra_validacion">
							<option value="">— Todos —</option>
							<option value="1" <?= $activeValidacion == '1' ? 'selected' : '' ?>>Aceptada</option>
							<option value="3" <?= $activeValidacion == '3' ? 'selected' : '' ?>>Pendiente</option>
							<option value="8" <?= $activeValidacion == '8' ? 'selected' : '' ?>>Iniciada</option>
							<option value="2" <?= $activeValidacion == '2' ? 'selected' : '' ?>>Rechazada</option>
							<option value="4" <?= $activeValidacion == '4' ? 'selected' : '' ?>>Fallida</option>
							<option value="6" <?= $activeValidacion == '6' ? 'selected' : '' ?>>Reversada</option>
							<option value="7" <?= $activeValidacion == '7' ? 'selected' : '' ?>>Retenida</option>
							<option value="9" <?= $activeValidacion == '9' ? 'selected' : '' ?>>Caducada</option>
							<option value="10" <?= $activeValidacion == '10' ? 'selected' : '' ?>>Abandonada</option>
							<option value="11" <?= $activeValidacion == '11' ? 'selected' : '' ?>>Cancelada</option>
						</select>
					</label>
				</div>

				<!-- Tipo -->
				<div class="col-2">
					<label>Tipo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-tag"></i></span>
						</div>
						<select class="form-control" name="boleta_compra_tipo">
							<option value="">— Todos —</option>
							<option value="boleteria" <?= $activeTipo == 'boleteria' ? 'selected' : '' ?>>Boletería</option>
							<option value="reserva" <?= $activeTipo == 'reserva' ? 'selected' : '' ?>>Reserva</option>
						</select>
					</label>
				</div>

				<div class="col-2">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"><i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-2">
					<label>&nbsp;</label>
					<a class="btn btn-block btn-azul-claro" href="<?php echo $this->route; ?>?cleanfilter=1"><i
							class="fas fa-eraser"></i> Limpiar</a>
				</div>

			</div>
		</div>
	</form>

	<div align="center">
		<ul class="pagination justify-content-center">
			<?php
			if ($this->totalpages < 10) {
				$paginainicial = 1;
				$paginafinal = $this->totalpages;
			} else {
				if ($this->page < 5) {
					$paginainicial = 1;
					$paginafinal = 9;
				} else if ($this->page > ($this->totalpages - 4)) {
					$paginainicial = $this->totalpages - 9;
					$paginafinal = $this->totalpages;
				} else {
					$paginainicial = $this->page - 3;
					$paginafinal = $this->page + 3;
				}
			}
			$url = $this->route;
			if ($this->totalpages > 1) {
				if ($this->page != 1)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
				for ($i = $paginainicial; $i <= $paginafinal; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
			}
			?>
		</ul>
	</div>

	<div class="content-dashboard">
		<div class="franja-paginas">
			<div class="row">
				<div class="col-6">
					<div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Compras</div>
				</div>
				<div class="col-3 text-end">
					<div class="texto-paginas">Registros por página:</div>
				</div>
				<div class="col-1">
					<select class="form-control form-control-sm selectpagination">
						<option value="20" <?php if ($this->pages == 20) {
							echo 'selected';
						} ?>>20</option>
						<option value="30" <?php if ($this->pages == 30) {
							echo 'selected';
						} ?>>30</option>
						<option value="50" <?php if ($this->pages == 50) {
							echo 'selected';
						} ?>>50</option>
						<option value="100" <?php if ($this->pages == 100) {
							echo 'selected';
						} ?>>100</option>
					</select>
				</div>
				<div class="col-2"></div>
			</div>
		</div>
		<div class="content-table">
			<table class="table table-striped table-hover table-administrator text-left">
				<thead>
					<tr>
						<td>#</td>
						<td>Fecha</td>
						<td>Nombre</td>
						<td>Email</td>
						<td>Evento</td>
						<td>Tipo</td>
						<td class="text-end">Total</td>
						<td>Validación</td>
						<td width="60"></td>
					</tr>
				</thead>
				<tbody>
					<?php
					$valStates = [
						'1' => 'Aceptada',
						'2' => 'Rechazada',
						'3' => 'Pendiente',
						'4' => 'Fallida',
						'6' => 'Reversada',
						'7' => 'Retenida',
						'8' => 'Iniciada',
						'9' => 'Caducada',
						'10' => 'Abandonada',
						'11' => 'Cancelada',
					];
					$valSuccess = ['1'];
					$valPending = ['3', '7', '8'];
					?>
					<?php foreach ($this->lists as $content): ?>
						<?php
						$id = $content->boleta_compra_id;
						$valCode = (string) ($content->boleta_compra_validacion ?? '');
						$valLabel = $valStates[$valCode] ?? ($valCode ?: 'Sin estado');
						if (in_array($valCode, $valSuccess)) {
							$spillClass = 'spill-confirmada';
						} elseif (in_array($valCode, $valPending)) {
							$spillClass = 'spill-pendiente';
						} elseif ($valCode !== '') {
							$spillClass = 'spill-cancelada';
						} else {
							$spillClass = 'spill-default';
						}
						$eventoNombre = $this->eventosMap[$content->boleta_compra_evento] ?? '—';
						$tipoLabel = match ($content->boleta_compra_tipo ?? '') {
							'boleteria' => 'Boletería',
							'reserva' => 'Reserva',
							'reservayboleteria' => 'Reserva + Boletería',
							default => $content->boleta_compra_tipo ?: '—',
						};
						$esReservaGratuita = in_array($content->boleta_compra_tipo ?? '', ['reserva', 'reservayboleteria'])
							&& (float)($content->boleta_compra_total ?? 0) == 0
							&& !in_array($valCode, $valSuccess)
							&& !in_array($valCode, $valPending);
						?>
						<tr>
							<td class="text-muted" style="font-size:12px;">#<?= $id ?></td>
							<td style="font-size:13px;"><?= $content->boleta_compra_fecha ?></td>
							<td><?= $content->boleta_compra_nombre ?></td>
							<td style="font-size:12px;color:var(--text-3);"><?= $content->boleta_compra_email ?></td>
							<td style="font-size:13px;"><?= $eventoNombre ?></td>
							<td><span style="font-size:12px;color:var(--text-3);"><?= $tipoLabel ?></span></td>
							<td class="text-end">
								<?php if ($content->boleta_compra_total > 0): ?>
									<span style="font-weight:600;">$ <?= number_format($content->boleta_compra_total, 0, ',', '.') ?></span>
								<?php else: ?>
									<span style="color:var(--text-5);">—</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if ($esReservaGratuita): ?>
									<span class="spill spill-reserva">
										<span class="spill-dot"></span>
										Reserva gratuita
									</span>
								<?php else: ?>
									<span class="spill <?= $spillClass ?>">
										<span class="spill-dot"></span>
										<?= $valLabel ?>
									</span>
								<?php endif; ?>
							</td>
							<td class="text-end">
								<a class="btn btn-azul btn-sm" href="<?php echo $this->route; ?>/info?id=<?= $id ?>"
									data-bs-toggle="tooltip" data-bs-placement="top" title="Ver detalle">
									<i class="fas fa-eye"></i>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>">
		<input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
	</div>

	<div align="center">
		<ul class="pagination justify-content-center">
			<?php
			if ($this->totalpages < 10) {
				$paginainicial = 1;
				$paginafinal = $this->totalpages;
			} else {
				if ($this->page < 5) {
					$paginainicial = 1;
					$paginafinal = 9;
				} else if ($this->page > ($this->totalpages - 4)) {
					$paginainicial = $this->totalpages - 9;
					$paginafinal = $this->totalpages;
				} else {
					$paginainicial = $this->page - 3;
					$paginafinal = $this->page + 3;
				}
			}
			$url = $this->route;
			if ($this->totalpages > 1) {
				if ($this->page != 1)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '"> &laquo; Anterior </a></li>';
				for ($i = $paginainicial; $i <= $paginafinal; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>  ';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '">Siguiente &raquo;</a></li>';
			}
			?>
		</ul>
	</div>
</div>