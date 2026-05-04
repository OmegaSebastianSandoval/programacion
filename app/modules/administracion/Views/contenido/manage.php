<h1 class="titulo-principal"><i class="fas fa-cogs"></i> <?php echo $this->titlesection; ?></h1>
<div class="container-fluid">
  <form class="text-left" enctype="multipart/form-data" method="post" action="<?php echo $this->routeform;?>"
    data-bs-toggle="validator">
    <div class="content-dashboard">
      <input type="hidden" name="csrf" id="csrf" value="<?php echo $this->csrf ?>">
      <input type="hidden" name="csrf_section" id="csrf_section" value="<?php echo $this->csrf_section ?>">
      <?php if ($this->content->contenido_id) { ?>
      <input type="hidden" name="id" id="id" value="<?= $this->content->contenido_id; ?>" />
      <?php }?>
      <?php 
				if($this->content->contenido_padre) { $padre = $this->content->contenido_padre; } else { $padre = $this->padre; }
				if($this->content->contenido_tipo) { $tipo = $this->content->contenido_tipo; } else { $tipo = $this->tipo; }
				if($this->content->contenido_seccion) { $seccion = $this->content->contenido_seccion; } else { $seccion = $this->seccion; }
			 ?>
      <div class="row g-3 align-items-end form-grid">
        <div class="col-12">
          <h2 class="content-title">
            Tipo de Contenido
          </h2>
        </div>
        <input type="hidden" name="contenido_padre"
          value="<?php if($this->content->contenido_padre) { echo $this->content->contenido_padre; } else { echo $this->padre; }  ?>">
        <div class="col-12 col-md-4 col-lg-2 form-group">
          <label class="control-label">Activar Contenido</label>
  
          <input type="checkbox" name="contenido_estado" value="1" class="form-control switch-form"
            <?php if ($this->getObjectVariable($this->content, 'contenido_estado') == 1) { echo "checked";} ?>></input>
          <div class="help-block with-errors"></div>
        </div>
        <?php if($padre == 0 || $padre == ''){ ?>
        <div class="col-12 col-md-6 col-lg-3 form-group">
          <label class="control-label">Sección</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-cafe "><i class="far fa-list-alt"></i></span>
            </div>
            <select class="form-control" name="contenido_seccion" id="contenido_seccion" required>
              <option value="">Seleccione...</option>
              <?php foreach ($this->list_contenido_seccion AS $key => $value ){?>
              <option
                <?php if($this->getObjectVariable($this->content,"contenido_seccion") == $key ){ echo "selected"; }?>
                value="<?php echo $key; ?>" /> <?= $value; ?></option>
              <?php } ?>
            </select>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <?php } else { ?>
        <input type="hidden" name="contenido_seccion" id="contenido_seccion" value="<?php echo $seccion; ?>">
        <?php } ?>
        <?php if($this->mostrartipos == 1 || $padre == 0 || $padre == '' ){ ?>
        <div class="col-12 col-md-6 col-lg-3 form-group">
          <label class="control-label">Tipo</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-verde "><i class="far fa-list-alt"></i></span>
            </div>


            <select class="form-control" name="contenido_tipo" id="contenido_tipo" required
              onchange="aparecercolumna();">
              <option value="">Seleccione...</option>
              <?php foreach ($this->list_contenido_tipo AS $key => $value ){?>
              <option <?php if($this->getObjectVariable($this->content,"contenido_tipo") == $key ){ echo "selected"; }?>
                value="<?php echo $key; ?>" /> <?= $value; ?></option>
              <?php } ?>
            </select>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <?php } else { ?>
        <input type="hidden" name="contenido_tipo" id="contenido_tipo" value="<?php echo $tipo; ?>">
        <?php } ?>
        <div class="col-12 col-md-6 col-lg-3 form-group">
          <label for="contenido_fecha" class="control-label">Fecha</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-morado "><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input type="text"
              value="<?php if($this->content->contenido_fecha){ echo $this->content->contenido_fecha; } else { echo date('Y-m-d'); } ?>"
              name="contenido_fecha" id="contenido_fecha" class="form-control" data-provide="datepicker"
              data-date-format="yyyy-mm-dd" data-date-language="es" readonly>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div
          class="col-12 col-md-6 col-lg-4 <?php if($this->content->contenido_padre==0){ ?>d-none <?php } ?> form-group si-banner si-seccion no-contenido  si-carrousel no-acordion no-contenido2"
          <?php if( $tipo != 1 && $tipo != 2  && $tipo != 6 ){ ?> style="display: none;" <?php } ?>>
          <label class="control-label">Diseño Columnas</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-verde "><i class="fas fa-arrows-alt-h"></i></span>
            </div>
            <select class="form-control" name="contenido_columna_espacios">
              <option value="">Seleccione...</option>
              <?php foreach ($this->list_contenido_columna_espacios AS $key => $value ){?>
              <option
                <?php if($this->getObjectVariable($this->content,"contenido_columna_espacios") == $key ){ echo "selected"; }?>
                value="<?php echo $key; ?>" /> <?= $value; ?></option>
              <?php } ?>
            </select>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div
          class="col-12 col-md-6 col-lg-4 form-group no-banner no-carrousel no-acordion si-seccion"
          <?php if( $tipo != 2 && $tipo != 4){ ?> style="display: none;" <?php } ?>>
          <label class="control-label ">Alineación</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-verde "><i class="fas fa-align-center"></i></span>
            </div>
            <select class="form-control" name="contenido_columna_alineacion">
              <option value="">Seleccione...</option>
              <?php foreach ($this->list_contenido_columna_alineacion AS $key => $value ){?>
              <option
                <?php if($this->getObjectVariable($this->content,"contenido_columna_alineacion") == $key ){ echo "selected"; }?>
                value="<?php echo $key; ?>" /> <?= $value; ?></option>
              <?php } ?>
            </select>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 mt-4 no-start <?php if($this->content->contenido_padre==0){ ?>d-none <?php } ?> no-colum">
          <h2 class="content-title">
            Diseño
          </h2>
        </div>
        <?php if( ($tipo != 4) || $this->contentpadre->contenido_tipo == 2 ){ ?>
        <div class="col-12 <?php if($this->content->contenido_padre==0){ ?>d-none <?php } ?> form-group no-colum">
          <label for="contenido_columna" class="control-label">Columna</label>
          <div class="row">
            <div class="col-6 col-md-4 col-lg-2">
              <label class="radio-col">
                <input type="radio" value="col-sm-12" <?php if($this->content->contenido_columna == 'col-sm-12'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna12.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
              <label class="radio-col">
                <input type="radio" value="col-sm-6" <?php if($this->content->contenido_columna == 'col-sm-6'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna6.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
              <label class="radio-col">
                <input type="radio" value="col-sm-4" <?php if($this->content->contenido_columna == 'col-sm-4'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna4.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
              <label class="radio-col">
                <input type="radio" value="col-sm-3" <?php if($this->content->contenido_columna == 'col-sm-3'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna3.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-2 no-carrousel2 ">
              <label class="radio-col">
                <input type="radio" value="col-sm-8" <?php if($this->content->contenido_columna == 'col-sm-8'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna8.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-2 no-carrousel2 ">
              <label class="radio-col">
                <input type="radio" value="col-sm-9" <?php if($this->content->contenido_columna == 'col-sm-9'){ ?>
                  checked <?php } ?> name="contenido_columna" id="contenido_columna" class="form-control">
                <span>
                  <img src="/skins/administracion/images/columna9.png">
                </span>
              </label>
            </div>
          </div>
          <div class="help-block with-errors"></div>
        </div>
        <?php } ?>
        <?php if( $tipo == 5 || $tipo == 6 || $this->contentpadre->contenido_tipo == 2  ){ ?>
        <div class="col-12 col-lg-9 form-group no-banner no-seccion si-carrousel no-acordion si-contenido2 "
          <?php if( ($tipo != 2 && $tipo != 4 && $tipo != 5 && $tipo != 6 ) || $tipo == 0 ){ ?> style="display: none;"
          <?php } ?>>
          <label for="contenido_disenio" class="control-label">Diseño del Contenido</label>
          <div class="row">
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio">
                <input type="radio" value="1" <?php if($this->content->contenido_disenio == '1'){ ?> checked <?php } ?>
                  name="contenido_disenio" id="contenido_disenio" class="form-control">
                <span>
                  <img src="/skins/administracion/images/forma1.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio">
                <input type="radio" value="2" <?php if($this->content->contenido_disenio == '2'){ ?> checked <?php } ?>
                  name="contenido_disenio" id="contenido_disenio" class="form-control">
                <span>
                  <img src="/skins/administracion/images/forma2.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio">
                <input type="radio" value="3" <?php if($this->content->contenido_disenio == '3'){ ?> checked <?php } ?>
                  name="contenido_disenio" id="contenido_disenio" class="form-control">
                <span>
                  <img src="/skins/administracion/images/forma3.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio">
                <input type="radio" value="4" <?php if($this->content->contenido_disenio == '4'){ ?> checked <?php } ?>
                  name="contenido_disenio" id="contenido_disenio" class="form-control">
                <span>
                  <img src="/skins/administracion/images/forma4.png">
                </span>
              </label>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio">
                <input type="radio" value="5" <?php if($this->content->contenido_disenio == '5'){ ?> checked <?php } ?>
                  name="contenido_disenio" id="contenido_disenio" class="form-control">
                <span>
                  <img src="/skins/administracion/images/forma4.png">
                </span>
              </label>
            </div>
          </div>

          <div class="row g-2 mt-2">
            <!-- Diseño 7: Hero Card con Overlay -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="7" <?php if($this->content->contenido_disenio == '7'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-hero" aria-hidden="true">
                    <span class="rdth-h-img"></span>
                    <span class="rdth-h-overlay"></span>
                    <span class="rdth-h-text">
                      <span class="rdth-line rdth-line-title"></span>
                      <span class="rdth-line rdth-line-sub"></span>
                      <span class="rdth-pill"></span>
                    </span>
                  </span>
                  <span class="rdth-label">7 · Hero con Overlay</span>
                  <span class="rdth-desc">Imagen full + texto encima</span>
                </span>
              </label>
            </div>
            <!-- Diseño 8: Split Media -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="8" <?php if($this->content->contenido_disenio == '8'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-split" aria-hidden="true">
                    <span class="rdth-s-img"></span>
                    <span class="rdth-s-body">
                      <span class="rdth-s-bar"></span>
                      <span class="rdth-line rdth-line-title"></span>
                      <span class="rdth-line rdth-line-sub"></span>
                      <span class="rdth-line rdth-line-sub rdth-line-short"></span>
                    </span>
                  </span>
                  <span class="rdth-label">8 · Split Media</span>
                  <span class="rdth-desc">Imagen + contenido lado a lado</span>
                </span>
              </label>
            </div>
            <!-- Diseño 9: Blog / Noticia -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="9" <?php if($this->content->contenido_disenio == '9'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-blog" aria-hidden="true">
                    <span class="rdth-b-img">
                      <span class="rdth-b-badge"></span>
                    </span>
                    <span class="rdth-b-body">
                      <span class="rdth-line rdth-line-title"></span>
                      <span class="rdth-line rdth-line-sub"></span>
                      <span class="rdth-line rdth-line-sub rdth-line-short"></span>
                    </span>
                  </span>
                  <span class="rdth-label">9 · Card Blog / Noticia</span>
                  <span class="rdth-desc">Imagen 16:9 + extracto + CTA</span>
                </span>
              </label>
            </div>
            <!-- Diseño 10: Testimonial -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="10" <?php if($this->content->contenido_disenio == '10'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-quote" aria-hidden="true">
                    <span class="rdth-q-mark">&ldquo;</span>
                    <span class="rdth-q-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                    <span class="rdth-line rdth-line-sub"></span>
                    <span class="rdth-line rdth-line-sub rdth-line-short"></span>
                    <span class="rdth-q-author">
                      <span class="rdth-q-avatar"></span>
                      <span class="rdth-line rdth-line-title" style="width:55%"></span>
                    </span>
                  </span>
                  <span class="rdth-label">10 · Testimonial / Quote</span>
                  <span class="rdth-desc">Cita + estrellas + autor</span>
                </span>
              </label>
            </div>
            <!-- Diseño 11: Stat / Contador -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="11" <?php if($this->content->contenido_disenio == '11'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-stat" aria-hidden="true">
                    <span class="rdth-st-icon"></span>
                    <span class="rdth-st-num">500<span style="font-size:.55em">+</span></span>
                    <span class="rdth-line rdth-line-sub" style="width:70%; margin:0 auto"></span>
                  </span>
                  <span class="rdth-label">11 · Número / Estadística</span>
                  <span class="rdth-desc">Contador animado + etiqueta</span>
                </span>
              </label>
            </div>
            <!-- Diseño 12: Feature con barra lateral -->
            <div class="col-6 col-md-4 col-lg-3">
              <label class="radio-disenio-thumb">
                <input type="radio" value="12" <?php if($this->content->contenido_disenio == '12'){ ?> checked <?php } ?>
                  name="contenido_disenio" class="form-control">
                <span class="rdth-box">
                  <span class="rdth-preview rdth-feature" aria-hidden="true">
                    <span class="rdth-f-bar"></span>
                    <span class="rdth-f-inner">
                      <span class="rdth-f-icon"></span>
                      <span class="rdth-f-text">
                        <span class="rdth-line rdth-line-title"></span>
                        <span class="rdth-line rdth-line-sub"></span>
                        <span class="rdth-line rdth-line-sub rdth-line-short"></span>
                      </span>
                    </span>
                  </span>
                  <span class="rdth-label">12 · Feature con Barra</span>
                  <span class="rdth-desc">Barra + ícono + título + texto</span>
                </span>
              </label>
            </div>
          </div>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 col-lg-3 form-group no-banner si-carrousel no-acordion si-contenido2"
          <?php if(isset($tipo) == false || ($tipo != 2 && $tipo != 4  && $tipo != 6 ) ){ ?> style="display: none;"
          <?php } ?>>
          <label class="control-label">Diseño con Borde</label>
          <input type="checkbox" class="switch-form" name="contenido_borde" value="1"
            <?php if ($this->getObjectVariable($this->content, 'contenido_borde') == 1) { echo "checked";} ?>></input>
          <div class="help-block with-errors"></div>
        </div>
        <?php } ?>
        <div class="col-12 mt-4 no-start">
          <h2 class="content-title">
            Información del contenido
          </h2>
        </div>
        <div class="col-12 col-lg-9 form-group no-start">
          <label for="contenido_titulo" class="control-label">Título</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-rojo-claro "><i class="fas fa-pencil-alt"></i></span>
            </div>
            <input type="text" value="<?= $this->content->contenido_titulo; ?>" name="contenido_titulo"
              id="contenido_titulo" class="form-control">
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 col-lg-3 form-group no-banner  si-seccion no-carrousel no-acordion si-contenido2"
          <?php if($tipo == 1 || $tipo == 6 || $tipo == 7  || $tipo == 0 ){ ?> style="display: none;" <?php } ?>>
          <label class="control-label">Mostrar Título</label>
          <input type="checkbox" name="contenido_titulo_ver" value="1" class="form-control switch-form "
            <?php if ($this->getObjectVariable($this->content, 'contenido_titulo_ver') == 1) { echo "checked";} ?>></input>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 form-group no-banner si-seccion si-contenido no-carrousel  no-acordion  si-contenido2"
          <?php if($tipo == 1 || $tipo == 4 || $tipo == 6 || $tipo == 7  || $tipo == 0){ ?> style="display: none;"
          <?php } ?>>
          <label for="contenido_imagen">Imagen</label>
          <input type="file" name="contenido_imagen" id="contenido_imagen" class="form-control  file-image"
            data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
          <div class="help-block with-errors"></div>
          <?php if($this->content->contenido_imagen) { ?>
          <div id="imagen_contenido_imagen">
            <img src="/images/<?= $this->content->contenido_imagen; ?>" class="img-thumbnail thumbnail-administrator" />
            <div><button class="btn btn-danger btn-sm" type="button"
                onclick="eliminarImagen('contenido_imagen','<?php echo $this->route."/deleteimage"; ?>')"><i
                  class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
          </div>
          <?php } ?>
        </div>
        <div class="col-12 col-md-6 col-lg-4 form-group no-banner no-acordion no-carrousel si-seccion"
          <?php if($tipo != 2 && $tipo != 4  ){ ?> style="display: none;" <?php } ?>>
          <label for="contenido_fondo_imagen"><?php if($tipo == 4){ ?>Imagen Banner <?php } else{ ?> Imagen Fondo
            <?php } ?></label>
          <input type="file" name="contenido_fondo_imagen" id="contenido_fondo_imagen" class="form-control  file-image"
            data-buttonName="btn-primary" accept="image/gif, image/jpg, image/jpeg, image/png">
          <div class="help-block with-errors"></div>
          <?php if($this->content->contenido_fondo_imagen) { ?>
          <div id="imagen_contenido_fondo_imagen">
            <img src="/images/<?= $this->content->contenido_fondo_imagen; ?>"
              class="img-thumbnail thumbnail-administrator" />
            <div><button class="btn btn-danger btn-sm" type="button"
                onclick="eliminarImagen('contenido_fondo_imagen','<?php echo $this->route."/deleteimage"; ?>')"><i
                  class="glyphicon glyphicon-remove"></i> Eliminar Imagen</button></div>
          </div>
          <?php } ?>
        </div>
        <div class="col-12 col-md-6 col-lg-4 form-group no-carrousel no-acordion no-carrousel si-seccion" <?php if($tipo != 2){ ?>
          style="display: none;" <?php } ?>>
          <label class="control-label">Tipo Fondo</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-rosado "><i class="far fa-list-alt"></i></span>
            </div>
            <select class="form-control" name="contenido_fondo_imagen_tipo">
              <option value="">Seleccione...</option>
              <?php foreach ($this->list_contenido_fondo_imagen_tipo AS $key => $value ){?>
              <option
                <?php if($this->getObjectVariable($this->content,"contenido_fondo_imagen_tipo") == $key ){ echo "selected"; }?>
                value="<?php echo $key; ?>" /> <?= $value; ?></option>
              <?php } ?>
            </select>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 col-md-6 col-lg-4 form-group no-contenido no-banner si-seccion no-acordion si-carrousel si-contenido2"
          <?php if($tipo != 2 && $tipo!= 4 && $tipo!= 5  && $tipo!= 6 ){ ?> style="display: none;" <?php } ?>>
          <label for="contenido_fondo_color" class="control-label"><?php if($tipo == 4){ ?> Color Caption
            <?php } else{ ?> Color Fondo <?php } ?></label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-azul-claro "><i class="fas fa-pencil-alt"></i></span>
            </div>
            <input type="text" value="<?= $this->content->contenido_fondo_color; ?>" name="contenido_fondo_color"
              id="contenido_fondo_color" class="form-control">
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 form-group no-banner no-carrousel no-seccion no-acordion si-contenido"
          <?php if($tipo != 3){ ?> style="display: none;" <?php } ?>>
          <label for="contenido_introduccion" class="form-label">Introducci&oacute;n</label>
          <textarea name="contenido_introduccion" id="contenido_introduccion" class="form-control tinyeditor"
            rows="10"><?= $this->content->contenido_introduccion; ?></textarea>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 form-group no-banner si-seccion si-contenido si-carrousel si-acordion si-contenido2 no-start"
          <?php if( ($tipo == 1 || $tipo == 0) && $this->contentpadre->contenido_tipo != 2  ){ ?> style="display: none;"
          <?php } ?>>
          <label for="contenido_descripcion" class="form-label">Descripción</label>
          <textarea name="contenido_descripcion" id="contenido_descripcion" class="form-control tinyeditor"
            rows="10"><?= $this->content->contenido_descripcion; ?></textarea>
          <div class="help-block with-errors"></div>
        </div>
      </div>
      <div class="row no-banner si-seccion si-contenido no-acordion no-carrousel si-contenido2"
        <?php if($tipo == 1 || $tipo == 6 || $tipo == 7 || ($tipo == 0 && $this->contentpadre->contenido_tipo == 2  )){ ?>
        style="display: none;" <?php } ?>>
        <div class="col-12 col-lg-6 form-group no-start">
          <label for="contenido_enlace" class="control-label">Enlace</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-verde-claro "><i class="fas fa-pencil-alt"></i></span>
            </div>
            <input type="text" value="<?= $this->content->contenido_enlace; ?>" name="contenido_enlace"
              id="contenido_enlace" class="form-control">
            <div class="input-group-prepend">
              <span class="input-group-text">Abrir en </span>
              <select class="form-control" name="contenido_enlace_abrir">
                <?php foreach ($this->list_contenido_enlace_abrir AS $key => $value ){?>
                <option
                  <?php if($this->getObjectVariable($this->content,"contenido_enlace_abrir") == $key ){ echo "selected"; }?>
                  value="<?php echo $key; ?>" /> <?= $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </label>
          <div class="help-block with-errors"></div>
        </div>
        <div class="col-12 col-lg-6 form-group no-start">
          <label for="contenido_vermas" class="control-label">Texto Ver m&aacute;s</label>
          <label class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text input-icono  fondo-rosado "><i class="fas fa-pencil-alt"></i></span>
            </div>
            <input type="text" value="<?= $this->content->contenido_vermas; ?>" name="contenido_vermas"
              id="contenido_vermas" class="form-control">
          </label>
          <div class="help-block with-errors"></div>
        </div>
      </div>
    </div>
    <div class="botones-acciones">
      <button class="btn btn-guardar" type="submit">Guardar</button>
      <a href="<?php echo $this->route; ?><?php if($padre){ echo "?padre=".$padre; } ?>"
        class="btn btn-cancelar">Cancelar</a>
    </div>
  </form>
</div>

<style>
  body{
    overflow-x: hidden;
  }
  .content-dashboard .form-group {
    margin-bottom: 0.35rem;
  }
  .content-dashboard .form-grid .input-group {
    width: 100%;
  }
  .content-dashboard .form-grid .switch-form {
    margin-top: 0.35rem;
  }
  .content-dashboard .radio-col,
  .content-dashboard .radio-disenio {
    width: 100%;
  }
  label{
    text-transform: lowercase !important;
  }
  label::first-letter{
    text-transform: uppercase !important;
  }

  /* ===== Thumbnails de diseño 7-12 ===== */
  .radio-disenio-thumb {
    display: block;
    cursor: pointer;
    width: 100%;
  }
  .radio-disenio-thumb input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }
  .rdth-box {
    display: flex;
    flex-direction: column;
    gap: 6px;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    transition: border-color .15s ease, box-shadow .15s ease;
  }
  .radio-disenio-thumb input[type="radio"]:checked + .rdth-box {
    border-color: #13436B;
    box-shadow: 0 0 0 3px rgba(19,67,107,.12);
  }
  .radio-disenio-thumb:hover .rdth-box {
    border-color: #13436B;
  }
  .rdth-preview {
    display: block;
    width: 100%;
    height: 80px;
    position: relative;
    overflow: hidden;
    background: #f0f2f5;
    border-radius: 0;
    flex-shrink: 0;
  }
  .rdth-label {
    display: block;
    padding: 6px 10px 2px;
    font-size: .78rem;
    font-weight: 700;
    color: #1f2a37;
    text-transform: none !important;
    line-height: 1.2;
  }
  .rdth-label::first-letter { text-transform: none !important; }
  .rdth-desc {
    display: block;
    padding: 0 10px 8px;
    font-size: .68rem;
    color: #888;
    text-transform: none !important;
    line-height: 1.3;
  }
  .rdth-desc::first-letter { text-transform: none !important; }

  /* Líneas de texto placeholder */
  .rdth-line {
    display: block;
    height: 5px;
    border-radius: 3px;
    background: #d0d5dd;
    margin-bottom: 4px;
  }
  .rdth-line-title { width: 80%; background: #aab; }
  .rdth-line-sub   { width: 90%; }
  .rdth-line-short { width: 55%; }
  .rdth-pill {
    display: inline-block;
    width: 36px;
    height: 8px;
    border-radius: 10px;
    background: rgba(255,255,255,.4);
    border: 1px solid rgba(255,255,255,.6);
    margin-top: 4px;
  }

  /* ---- HERO (7) ---- */
  .rdth-hero {
    background: linear-gradient(to bottom, #5b7fa6 0%, #2d4a6a 100%);
  }
  .rdth-h-img {
    position: absolute; inset: 0;
    background: linear-gradient(135deg, #4a6fa5 0%, #2c3e50 100%);
    opacity: .7;
  }
  .rdth-h-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 65%);
  }
  .rdth-h-text {
    position: absolute; bottom: 8px; left: 10px; right: 10px;
    display: flex; flex-direction: column; gap: 3px;
  }
  .rdth-h-text .rdth-line { background: rgba(255,255,255,.55); }
  .rdth-h-text .rdth-line-title { background: rgba(255,255,255,.9); }

  /* ---- SPLIT (8) ---- */
  .rdth-split {
    display: flex !important;
    background: #fff;
    border-bottom: 1px solid #eee;
  }
  .rdth-s-img {
    flex: 0 0 42%;
    background: linear-gradient(135deg, #7c9cbf, #4a6fa5);
  }
  .rdth-s-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 10px 10px 10px 12px;
    gap: 4px;
  }
  .rdth-s-bar {
    width: 22px; height: 3px;
    border-radius: 2px;
    background: #4f46e5;
    margin-bottom: 5px;
  }

  /* ---- BLOG (9) ---- */
  .rdth-blog {
    display: flex !important;
    flex-direction: column;
    background: #fff;
    border-bottom: 1px solid #eee;
  }
  .rdth-b-img {
    flex: 0 0 42px;
    background: linear-gradient(135deg, #7c9cbf, #4a6fa5);
    position: relative;
  }
  .rdth-b-badge {
    position: absolute; top: 5px; left: 6px;
    width: 28px; height: 7px;
    border-radius: 4px;
    background: #4f46e5;
  }
  .rdth-b-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 8px 10px;
    gap: 3px;
  }

  /* ---- QUOTE (10) ---- */
  .rdth-quote {
    background: #f8f9fc;
    display: flex !important;
    flex-direction: column;
    padding: 8px 10px 6px;
    gap: 4px;
  }
  .rdth-q-mark {
    font-family: Georgia, serif;
    font-size: 2.4rem;
    line-height: .8;
    color: rgba(79,70,229,.18);
    position: absolute;
    top: 2px; right: 8px;
  }
  .rdth-q-stars {
    font-size: .5rem;
    color: #4f46e5;
    letter-spacing: 1px;
    display: block;
  }
  .rdth-q-author {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    padding-top: 5px;
    border-top: 1px solid #e5e7eb;
  }
  .rdth-q-avatar {
    width: 16px; height: 16px;
    border-radius: 50%;
    background: #4f46e5;
    flex-shrink: 0;
  }

  /* ---- STAT (11) ---- */
  .rdth-stat {
    background: #fff;
    display: flex !important;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 8px;
  }
  .rdth-st-icon {
    width: 22px; height: 22px;
    border-radius: 6px;
    background: #4f46e5;
  }
  .rdth-st-num {
    font-size: 1.35rem;
    font-weight: 900;
    color: #4f46e5;
    line-height: 1;
    letter-spacing: -.03em;
  }

  /* ---- FEATURE (12) ---- */
  .rdth-feature {
    display: flex !important;
    background: #fff;
    border-bottom: 1px solid #eee;
  }
  .rdth-f-bar {
    flex: 0 0 4px;
    background: #4f46e5;
    border-radius: 0;
  }
  .rdth-f-inner {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
  }
  .rdth-f-icon {
    width: 24px; height: 24px;
    border-radius: 6px;
    background: #4f46e5;
    flex-shrink: 0;
  }
  .rdth-f-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
</style>