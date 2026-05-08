<h1 class="titulo-principal"><i class="fas fa-map-marker-alt"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form action="<?php echo $this->route . '?reserva_evento_evento=' . $this->reserva_evento_evento; ?>" method="post">
		<div class="content-dashboard">
			<div class="row">
				<div class="col-3">
					<label>Tipo de zona</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="reserva_evento_tipo">
							<option value="">Todas</option>
							<?php foreach ($this->list_reserva_evento_tipo as $key => $value): ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'reserva_evento_tipo') == $key) echo 'selected'; ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-3">
					<label>Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono"><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="reserva_evento_nombre"
							value="<?php echo $this->getObjectVariable($this->filters, 'reserva_evento_nombre') ?>">
					</label>
				</div>
				<div class="col-3">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"><i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-3">
					<label>&nbsp;</label>
					<a class="btn btn-block btn-azul-claro" href="<?php echo $this->route; ?>?cleanfilter=1&reserva_evento_evento=<?= $this->reserva_evento_evento ?>">
						<i class="fas fa-eraser"></i> Limpiar Filtro</a>
				</div>
			</div>
		</div>
	</form>
	<div align="center">
		<ul class="pagination justify-content-center">
		<?php
			if ($this->totalpages < 10) {
				$paginainicial = 1; $paginafinal = $this->totalpages;
			} else {
				if ($this->page < 5) { $paginainicial = 1; $paginafinal = 9; }
				else if ($this->page > ($this->totalpages - 4)) { $paginainicial = $this->totalpages - 9; $paginafinal = $this->totalpages; }
				else { $paginainicial = $this->page - 3; $paginafinal = $this->page + 3; }
			}
			$url = $this->route;
			if ($this->totalpages > 1) {
				if ($this->page != 1)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '&reserva_evento_evento=' . $this->reserva_evento_evento . '">&laquo; Anterior</a></li>';
				for ($i = $paginainicial; $i <= $paginafinal; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '&reserva_evento_evento=' . $this->reserva_evento_evento . '">' . $i . '</a></li>';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '&reserva_evento_evento=' . $this->reserva_evento_evento . '">Siguiente &raquo;</a></li>';
			}
		?>
		</ul>
	</div>
	<div class="content-dashboard">
		<div class="franja-paginas">
			<div class="row">
				<div class="col-5">
					<div class="titulo-registro">
						Se encontraron <?php echo $this->register_number; ?> Registros
						<?php if ($this->evento && $this->evento->evento_nombre): ?>
							&mdash; Evento: <strong><?= $this->evento->evento_nombre; ?></strong>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-3 text-end">
					<div class="texto-paginas">Registros por pagina:</div>
				</div>
				<div class="col-1">
					<select class="form-control form-control-sm selectpagination">
						<option value="20" <?php if ($this->pages == 20) echo 'selected'; ?>>20</option>
						<option value="30" <?php if ($this->pages == 30) echo 'selected'; ?>>30</option>
						<option value="50" <?php if ($this->pages == 50) echo 'selected'; ?>>50</option>
						<option value="100" <?php if ($this->pages == 100) echo 'selected'; ?>>100</option>
					</select>
				</div>
				<div class="col-3">
					<div class="text-end d-flex justify-content-end gap-2">
						<a class="btn btn-sm btn-secondary" href="/administracion/eventos">
							<i class="fas fa-arrow-left"></i> Volver a Eventos</a>
						<a class="btn btn-sm btn-success"
							href="<?php echo $this->route . '/manage?reserva_evento_evento=' . $this->reserva_evento_evento; ?>">
							<i class="fas fa-plus-square"></i> Crear Nuevo</a>
					</div>
				</div>
			</div>
		</div>
		<div class="content-table">
			<table class="table table-striped table-hover table-administrator text-left">
				<thead>
					<tr>
						<td>Tipo</td>
						<td>Nombre</td>
						<td>Precio</td>
						<td>Capacidad</td>
						<td>Disponibles</td>
						<td>Vendidas</td>
						<td>Fecha l&iacute;mite</td>
						<td>Activo</td>
						<td width="120"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
					<?php $id = $content->reserva_evento_id; ?>
						<tr>
							<td><?= ($this->list_reserva_evento_tipo[$content->reserva_evento_tipo]) ? $this->list_reserva_evento_tipo[$content->reserva_evento_tipo] : '—'; ?></td>
							<td><?= $content->reserva_evento_nombre; ?></td>
							<td>$<?= number_format((float)$content->reserva_evento_precio, 0, ',', '.'); ?></td>
							<td><?= (int)$content->reserva_evento_capacidad; ?></td>
							<td><?= max(0, (int)$content->reserva_evento_cantidad - (int)$content->reserva_evento_cantidad_vendidas); ?></td>
							<td><?= (int)$content->reserva_evento_cantidad_vendidas; ?></td>
							<td><?= $content->reserva_evento_fechalimite ?: '—'; ?></td>
							<td><?= $content->reserva_evento_activo == 1 ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>'; ?></td>
							<td class="text-end">
								<div>
									<a class="btn btn-azul btn-sm"
										href="<?php echo $this->route; ?>/manage?id=<?= $id ?>&reserva_evento_evento=<?= $this->reserva_evento_evento; ?>"
										data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
										<i class="fas fa-pen-alt"></i></a>
									<a class="btn btn-rojo btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"
										data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar">
										<i class="fas fa-trash-alt"></i></a>
								</div>
								<div class="modal fade text-left" id="modal<?= $id ?>" tabindex="-1" role="dialog">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title">Eliminar Registro</h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">¿Esta seguro de eliminar este registro?</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
												<a class="btn btn-danger"
													href="<?php echo $this->route; ?>/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?>&reserva_evento_evento=<?= $this->reserva_evento_evento; ?>">
													Eliminar</a>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>">
		<input type="hidden" id="page-route" value="<?php echo $this->route; ?>/changepage">
	</div>
</div>
