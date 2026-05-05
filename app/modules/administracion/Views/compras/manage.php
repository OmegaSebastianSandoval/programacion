
<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform;?>"  data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->boleta_compra_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->boleta_compra_id; ?>" />
			<?php }?>
			<div class="row">
				<div class="col-12 form-group">
					<label for="boleta_compra_documento"  class="control-label">boleta_compra_documento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_documento; ?>" name="boleta_compra_documento" id="boleta_compra_documento" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_nombre"  class="control-label">boleta_compra_nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_nombre; ?>" name="boleta_compra_nombre" id="boleta_compra_nombre" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_telefono"  class="control-label">boleta_compra_telefono</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_telefono; ?>" name="boleta_compra_telefono" id="boleta_compra_telefono" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_email"  class="control-label">boleta_compra_email</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_email; ?>" name="boleta_compra_email" id="boleta_compra_email" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_fechacedula"  class="control-label">boleta_compra_fechacedula</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_fechacedula; ?>" name="boleta_compra_fechacedula" id="boleta_compra_fechacedula" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_fechanacimiento"  class="control-label">boleta_compra_fechanacimiento</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_fechanacimiento; ?>" name="boleta_compra_fechanacimiento" id="boleta_compra_fechanacimiento" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_fecha"  class="control-label">boleta_compra_fecha</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->boleta_compra_fecha){ echo $this->content->boleta_compra_fecha; } else { echo date('Y-m-d'); } ?>" name="boleta_compra_fecha" id="boleta_compra_fecha" class="form-control"   data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_codigo"  class="control-label">boleta_compra_codigo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_codigo; ?>" name="boleta_compra_codigo" id="boleta_compra_codigo" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_respuesta"  class="control-label">boleta_compra_respuesta</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_respuesta; ?>" name="boleta_compra_respuesta" id="boleta_compra_respuesta" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_validacion"  class="control-label">boleta_compra_validacion</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_validacion; ?>" name="boleta_compra_validacion" id="boleta_compra_validacion" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_validacion2"  class="control-label">boleta_compra_validacion2</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_validacion2; ?>" name="boleta_compra_validacion2" id="boleta_compra_validacion2" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_entidad"  class="control-label">boleta_compra_entidad</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_entidad; ?>" name="boleta_compra_entidad" id="boleta_compra_entidad" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_total"  class="control-label">boleta_compra_total</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_total; ?>" name="boleta_compra_total" id="boleta_compra_total" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_vendedor"  class="control-label">boleta_compra_vendedor</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_vendedor; ?>" name="boleta_compra_vendedor" id="boleta_compra_vendedor" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_validacionentrada"  class="control-label">boleta_compra_validacionentrada</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_validacionentrada; ?>" name="boleta_compra_validacionentrada" id="boleta_compra_validacionentrada" class="form-control"   >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_fechavalidacion"  class="control-label">boleta_compra_fechavalidacion</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-calendar-alt"></i></span>
						</div>
					<input type="text" value="<?php if($this->content->boleta_compra_fechavalidacion){ echo $this->content->boleta_compra_fechavalidacion; } else { echo date('Y-m-d'); } ?>" name="boleta_compra_fechavalidacion" id="boleta_compra_fechavalidacion" class="form-control"   data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-language="es"  >
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="boleta_compra_raw"  class="control-label">boleta_compra_raw</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono " ><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->boleta_compra_raw; ?>" name="boleta_compra_raw" id="boleta_compra_raw" class="form-control"   >
					</label>
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