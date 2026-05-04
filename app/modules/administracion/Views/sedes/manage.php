<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
	<form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform; ?>"
		data-bs-toggle="validator">
		<div class="content-dashboard">
			<input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
			<input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
			<?php if ($this->content->sede_id) { ?>
				<input type="hidden" name="id" id="id" value="<?= $this->content->sede_id; ?>" />
			<?php } ?>
			<div class="row">
				<div class="col-12 col-lg-1 form-group">
					<label class="control-label">Estado</label>
					<input type="checkbox" name="sede_estado" value="1" class="form-control switch-form " <?php if ($this->getObjectVariable($this->content, 'sede_estado') == 1) {
						echo "checked";
					} ?>></input>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-5 form-group">
					<label for="sede_nombre" class="control-label">Nombre</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->sede_nombre; ?>" name="sede_nombre" id="sede_nombre"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="sede_telefono" class="control-label">Tel&eacute;fono</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->sede_telefono; ?>" name="sede_telefono" id="sede_telefono"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="sede_direccion" class="control-label">Direcci&oacute;n</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->sede_direccion; ?>" name="sede_direccion" id="sede_direccion"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="sede_imagen">Imagen</label>
					<input type="file" name="sede_imagen" id="sede_imagen" class="form-control  file-image"
						data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
					<div class="help-block with-errors"></div>
					<?php if ($this->content->sede_imagen) { ?>
						<div id="imagen_sede_imagen">
							<img src="/images/<?= $this->content->sede_imagen; ?>" class="img-thumbnail thumbnail-administrator" />
							<div><button class="btn btn-danger btn-sm" type="button"
									onclick="eliminarImagen('sede_imagen','<?php echo $this->route . "/deleteimage"; ?>')"><i
										class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
						</div>
					<?php } ?>
				</div>

				<div class="col-12 col-lg-3 form-group">
					<label for="sede_color" class="control-label">Color</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-palette"></i></span>
						</div>
						<input type="text" value="<?= $this->content->sede_color; ?>" name="sede_color" id="sede_color"
							class="form-control colorpicker">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-3 form-group">
					<label for="sede_aforo" class="control-label">Aforo</label>
					<label class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text input-icono "><i class="fas fa-pencil-alt"></i></span>
						</div>
						<input type="text" value="<?= $this->content->sede_aforo; ?>" name="sede_aforo" id="sede_aforo"
							class="form-control">
					</label>
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 col-lg-6 form-group">
					<label for="sede_carta">Carta</label>
					<input type="file" name="sede_carta" id="sede_carta" class="form-control  file-document"
						data-buttonName="btn-primary" onchange="validardocumento('sede_carta');"
						accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf">
					<div class="help-block with-errors"></div>
				</div>
				<div class="col-12 form-group">
					<label for="sede_descripcion" class="form-label">Descripci&oacute;n</label>
					<textarea name="sede_descripcion" id="sede_descripcion" class="form-control tinyeditor"
						rows="10"><?= $this->content->sede_descripcion; ?></textarea>
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