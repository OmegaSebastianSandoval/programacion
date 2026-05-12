<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form action="<?php echo $this->route . "?boleta_evento_evento=" . $this->boleta_evento_evento . ""; ?>"
		method="post">
		<div class="content-dashboard">
			<div class="row">
				<div class="col-3">
					<label>Tipo boleta</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="far fa-list-alt"></i></span>
						</div>
						<select class="form-control" name="boleta_evento_tipo">
							<option value="">Todas</option>
							<?php foreach ($this->list_boleta_evento_tipo as $key => $value): ?>
								<option value="<?= $key; ?>" <?php if ($this->getObjectVariable($this->filters, 'boleta_evento_tipo') == $key) {
										echo "selected";
									} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</label>
				</div>
				<div class="col-3">
					<label>Cantidad</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="boleta_evento_cantidad"
							value="<?php echo $this->getObjectVariable($this->filters, 'boleta_evento_cantidad') ?>"></input>
					</label>
				</div>
				<div class="col-3">
					<label>boleta_evento_evento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="boleta_evento_evento"
							value="<?php echo $this->getObjectVariable($this->filters, 'boleta_evento_evento') ?>"></input>
					</label>
				</div>
				<div class="col-3">
					<label>Precio</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="boleta_evento_precio"
							value="<?php echo $this->getObjectVariable($this->filters, 'boleta_evento_precio') ?>"></input>
					</label>
				</div>
				<div class="col-3">
					<label>Fecha l&iacute;mite</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-calendar-alt"></i></span>
						</div>
						<input type="text" class="form-control" name="boleta_evento_fechalimite"
							value="<?php echo $this->getObjectVariable($this->filters, 'boleta_evento_fechalimite') ?>"></input>
					</label>
				</div>
				<div class="col-3">
					<label>&nbsp;</label>
					<button type="submit" class="btn btn-block btn-azul"> <i class="fas fa-filter"></i> Filtrar</button>
				</div>
				<div class="col-3">
					<label>&nbsp;</label>
					<a class="btn btn-block btn-azul-claro " href="<?php echo $this->route; ?>?cleanfilter=1"> <i
							class="fas fa-eraser"></i> Limpiar Filtro</a>
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
					echo '<li class="page-item" ><a class="page-link"  href="' . $url . '?page=' . ($this->page - 1) . '&boleta_evento_evento=' . $this->boleta_evento_evento . '"> &laquo; Anterior </a></li>';
				for ($i = $paginainicial; $i <= $paginafinal; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '&boleta_evento_evento=' . $this->boleta_evento_evento . '">' . $i . '</a></li>  ';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '&boleta_evento_evento=' . $this->boleta_evento_evento . '">Siguiente &raquo;</a></li>';
			}
			?>
		</ul>
	</div>
	<div class="content-dashboard">
		<div class="franja-paginas">
			<div class="row">
				<div class="col-5">
					<div class="titulo-registro">Se encontraron <?php echo $this->register_number; ?> Registros</div>
				</div>
				<div class="col-3 text-end">
					<div class="texto-paginas">Registros por pagina:</div>
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
				<div class="col-3">
					<div class="text-end d-flex justify-content-end gap-2">
						<a class="btn btn-sm btn-secondary" href="/administracion/eventos">
							<i class="fas fa-arrow-left"></i> Volver a Eventos</a>
						<a class="btn btn-sm btn-success"
							href="<?php echo $this->route . "\manage" . "?boleta_evento_evento=" . $this->boleta_evento_evento . ""; ?>">
							<i class="fas fa-plus-square"></i> Crear Nuevo</a>
					</div>
				</div>
			</div>
		</div>
		<div class="content-table">
			<table class=" table table-striped  table-hover table-administrator text-left">
				<thead>
					<tr>
						<td>Tipo boleta</td>
						<td>Cantidad</td>
						<td>Vendidas</td>
						<td>Evento</td>
						<td>Precio</td>
						<td>Precio adicional (Servicio)</td>
						<td>Fecha l&iacute;mite</td>
						<td width="150"></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->lists as $content) { ?>
						<?php $id = $content->boleta_evento_id; ?>
						<tr>
							<td><?= $this->list_boleta_evento_tipo[$content->boleta_evento_tipo]; ?>
							<td><?= $content->boleta_evento_cantidad; ?></td>
							<td><?= (int) $content->boleta_evento_cantidad_vendidas; ?></td>
							<td><?= $this->list_evento[$content->boleta_evento_evento] ?></td>
							<td><?= $content->boleta_evento_precio; ?></td>
							<td><?= $content->boleta_evento_precioadicional; ?></td>
							<td><?= $content->boleta_evento_fechalimite; ?></td>
							<td class="text-end">
								<div>
									<a class="btn btn-azul btn-sm"
										href="<?php echo $this->route; ?>/manage?id=<?= $id ?>&boleta_evento_evento=<?= $this->boleta_evento_evento; ?>"
										data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
									<span data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm"
											data-bs-toggle="modal" data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span>
								</div>
								<!-- Modal -->
								<div class="modal fade text-left" id="modal<?= $id ?>" tabindex="-1" role="dialog"
									aria-labelledby="myModalLabel">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="myModalLabel">Eliminar Registro</h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<div class="">¿Esta seguro de eliminar este registro?</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
												<a class="btn btn-danger"
													href="<?php echo $this->route; ?>/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?><?php echo '' . '&boleta_evento_evento=' . $this->boleta_evento_evento; ?>">Eliminar</a>
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
		<input type="hidden" id="csrf" value="<?php echo $this->csrf ?>"><input type="hidden" id="page-route"
			value="<?php echo $this->route; ?>/changepage">
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
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page - 1) . '&boleta_evento_evento=' . $this->boleta_evento_evento . '"> &laquo; Anterior </a></li>';
				for ($i = $paginainicial; $i <= $paginafinal; $i++) {
					if ($this->page == $i)
						echo '<li class="active page-item"><a class="page-link">' . $this->page . '</a></li>';
					else
						echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '&boleta_evento_evento=' . $this->boleta_evento_evento . '">' . $i . '</a></li>  ';
				}
				if ($this->page != $this->totalpages)
					echo '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($this->page + 1) . '&boleta_evento_evento=' . $this->boleta_evento_evento . '">Siguiente &raquo;</a></li>';
			}
			?>
		</ul>
	</div>
</div>