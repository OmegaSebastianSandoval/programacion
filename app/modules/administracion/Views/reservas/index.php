<?php
$activeEventoId = $this->getObjectVariable($this->filters, 'reserva_evento_id_fk');
$activeOrigen = $this->getObjectVariable($this->filters, 'reserva_tipo_origen');
$activeEstado = $this->getObjectVariable($this->filters, 'reserva_estado');
?>
<h1 class="titulo-principal"><i class="fas fa-calendar-check"></i> <?php echo $this->titlesection; ?></h1>
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
						<select class="form-control select2-eventos" name="reserva_evento_id_fk">
							<option value="">— Todos los eventos —</option>
							<?php foreach ($this->eventos as $ev): ?>
								<option value="<?= $ev->evento_id ?>" <?= $activeEventoId == $ev->evento_id ? 'selected' : '' ?>>
									<?= ($ev->evento_nombre) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</div>

				<!-- Origen -->
				<div class="col-2">
					<label>Origen</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-tag"></i></span>
						</div>
						<select class="form-control" name="reserva_tipo_origen">
							<option value="">— Todos —</option>
							<option value="solo_reserva" <?= $activeOrigen == 'solo_reserva' ? 'selected' : '' ?>>Solo Reserva</option>
							<option value="con_boletas" <?= $activeOrigen == 'con_boletas' ? 'selected' : '' ?>>Con Boletas</option>
							<option value="boleteria_auto" <?= $activeOrigen == 'boleteria_auto' ? 'selected' : '' ?>>Boletería Auto
							</option>
						</select>
					</label>
				</div>

				<!-- Estado -->
				<div class="col-2">
					<label>Estado</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-circle"></i></span>
						</div>
						<select class="form-control" name="reserva_estado">
							<option value="">— Todos —</option>
							<option value="pendiente" <?= $activeEstado == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
							<option value="confirmada" <?= $activeEstado == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
							<option value="cancelada" <?= $activeEstado == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
							<option value="completada" <?= $activeEstado == 'completada' ? 'selected' : '' ?>>Completada</option>
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
					<div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Reservas</div>
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
						<td>Origen</td>
						<td class="text-center">Personas</td>
						<td class="text-end">Total</td>
						<td>Estado</td>
						<td width="60"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content): ?>
						<?php
						$id = $content->reserva_id;
						$estado = strtolower($content->reserva_estado ?? '');
						$spillClass = in_array($estado, ['confirmada', 'pagada', 'completada', 'pendiente', 'cancelada'])
							? 'spill-' . $estado
							: 'spill-default';

						$origenLabel = match ($content->reserva_tipo_origen) {
							'solo_reserva' => 'Solo Reserva',
							'con_boletas' => 'Con Boletas',
							'boleteria_auto' => 'Boletería Auto',
							default => $content->reserva_tipo_origen,
						};
						?>
						<tr>
							<td class="text-muted" style="font-size:12px;">#<?= $id ?></td>
							<td style="font-size:13px;"><?= $content->reserva_fecha_creacion ?></td>
							<td><?= ($content->reserva_nombre ?? '') ?></td>
							<td style="font-size:12px;color:var(--text-3);"><?= ($content->reserva_email ?? '') ?></td>
							<td style="font-size:13px;"><?= ($content->reserva_evento ?? '') ?></td>
							<td><span style="font-size:12px;color:var(--text-3);"><?= $origenLabel ?></span></td>
							<td class="text-center">
								<?= $content->reserva_cantidad_personas > 0 ? $content->reserva_cantidad_personas : '—' ?></td>
							<td class="text-end">
								<?php if ($content->reserva_total > 0): ?>
									<span style="font-weight:600;">$ <?= number_format($content->reserva_total, 0, ',', '.') ?></span>
								<?php else: ?>
									<span style="color:var(--text-5);">—</span>
								<?php endif; ?>
							</td>
							<td>
								<span class="spill <?= $spillClass ?>">
									<span class="spill-dot"></span>
									<?= ucfirst($estado ?: 'sin estado') ?>
								</span>
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