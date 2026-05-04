<pre>
	<?php //print_r($this->campos); 
  ?>
</pre>
<?php $nombrevariable = "Tables_in_" . $this->namedatabase; ?>
<br>
<div class="p-5 pt-0">
  <div class="container-fluid">
    <form action="/generator/" method="post">
      <div class="row">
        <div class="col-9">
          <select class="form-control" name="table" style="text-transform: uppercase;">
            <option>Seleccione...</option>
            <?php foreach ($this->tablas as $key => $tabla) : ?>
              <option value="<?php echo $tabla->$nombrevariable ?>" <?php if ($tabla->$nombrevariable == $this->table) : ?> selected<?php endif ?>><?php echo $tabla->$nombrevariable ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="col-3">
          <button type="submit" class="btn btn-block btn-info text-white w-100">Crear Administrador</button>
        </div>
      </div>
    </form>

    <div class="mt-3">
      <?php if ($this->table != '') { ?>
        <div class="form-gen-bx border rounded p-3">
          <h3 style="text-transform: uppercase;">Tabla <?php echo $this->table; ?></h3>
          <form method="post" action="/generator/index/crear">
            <input type="hidden" name="table" value="<?php echo $this->table; ?>">
            <div class="row">
              <div class="col-3">
                <label>Nombre Controlador</label>
                <input type="text" name="controlador" class="form-control letras" required>
              </div>
              <div class="col-3">
                <label>Ruta del Modelo</label>
                <input type="text" name="ruta" class="form-control" value="administracion" required>
              </div>
              <div class="col-3">
                <label>Titulo Listar</label>
                <input type="text" name="titulo_listado" class="form-control" required>
              </div>
              <div class="col-3">
                <label>Titulo Editar/ Crear</label>
                <input type="text" name="titulo_edicion" class="form-control" required>
              </div>
            </div>
            <br>
            <h4>Parametrización de La tabla</h4>
            <div style="padding: 20px;border-top: 1px #CCCCCC solid;">
              <div class="row text-center">
                <div class="col-2"><strong>Nombre Campo</strong></div>
                <div class="col-1"><strong>Requerido</strong></div>
                <div class="col-2"><strong>Titulo del Campo</strong></div>
                <div class="col-2"><strong>Tipo de Campo</strong></div>
                <div class="col-1"><strong>Validaciones</strong></div>
                <div class="col-1"><strong>Listado</strong></div>
                <div class="col-2"><strong>Ancho</strong></div>
                <div class="col-1"><strong>orden</strong></div>
  
              </div>
              <br>
              <?php $contador = 1; ?>
              <?php foreach ($this->campos as $key => $campo) : ?>
                <?php if ($campo->Key != 'PRI' && $campo->Field != 'orden') { ?>
                  <div class="form-group">
                    <div class="row align-items-center rounded py-2 gen-row">
                      <div class="col-2"><?php echo $campo->Field ?></div>
                      <div class="col-1">
                        <select name="requerido_<?php echo $campo->Field ?>" class="form-control">
                          <option value="2">No</option>
                          <option value="1">Si</option>
                        </select>
                      </div>
                      <div class="col-2">
                        <input type="text" class="form-control" placeholder="titulo del campo" name="titulo_<?php echo $campo->Field ?>" value="<?php echo $campo->Field ?>" required>
                      </div>
                      <div class="col-2">
                        <select name="tipo_<?php echo $campo->Field ?>" id="tipo_<?php echo $campo->Field ?>" class="form-control" required onchange="changeselecttipo('<?php echo $campo->Field ?>');">
                          <option value="">Seleccione</option>
                          <?php foreach ($this->tipos as $key => $tipo) : ?>
                            <option value="<?php echo $key ?>"><?php echo $tipo; ?></option>
                          <?php endforeach ?>
                        </select>
                        <div id="dependiente_<?php echo $campo->Field ?>" style="display:none; padding-top: 20px;">
                          <div class="form-group">
                            <label>Tabla Dependiente</label>
                            <select name="tabla_dependiente_<?php echo $campo->Field ?>" id="tabla_dependiente_<?php echo $campo->Field ?>" class="form-control" onchange="selectDepend('<?php echo $campo->Field ?>')">
                              <option>Seleccione...</option>
                              <?php foreach ($this->tablas as $key => $tabla) : ?>
                                <option value="<?php echo $tabla->$nombrevariable ?>"><?php echo $tabla->$nombrevariable ?></option>
                              <?php endforeach ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Value</label>
                            <select name="value_dependiente_<?php echo $campo->Field ?>" id="value_dependiente_<?php echo $campo->Field ?>" class="form-control">
                              <option></option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Label</label>
                            <select name="label_dependiente_<?php echo $campo->Field ?>" id="label_dependiente_<?php echo $campo->Field ?>" class="form-control">
                              <option></option>
                            </select>
                          </div>
                        </div>
                        <div id="oculto_<?php echo $campo->Field ?>" style="display:none;">
                          <div class="form-group">
                            <label>Parametro</label>
                            <select name="oculto_tipo_<?php echo $campo->Field ?>" class="form-control">
                              <option value="1">Valor</option>
                              <option value="2">URL o Formulario</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Valor o Nombre Variable</label>
                            <input type="text" class="form-control" name="oculto_<?php echo $campo->Field ?>">
                          </div>
                          <div>
                            <input type="checkbox" name="oculto_filtro_<?php echo $campo->Field ?>" value="1"> <label>Filtrar con este Dato</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-1">
                        <select name="campo_validacion_<?php echo $campo->Field ?>" class="form-control">
                          <option selected disabled value="">Seleccione</option>
                          <option value="1">Texto</option>
                          <option value="2">Numero</option>
                          <option value="3">Email</option>
                          <option value="4">Fecha</option>
                          <option value="5">URL</option>
                        </select>
                      </div>
                      <div class="col-1">
                        <select name="en_listado_<?php echo $campo->Field ?>" class="form-control">
                          <option value="2">No</option>
                          <option value="1">Si</option>
                        </select>
                      </div>
                      <div class="col-2">
                        <select name="ancho_<?php echo $campo->Field ?>" class="form-control">
                          <?php for ($i = 12; $i > 0; $i--) { ?>
                            <option value="col-<?php echo $i;  ?>">col-<?php echo $i;  ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="col-1">
                        <input type="number" class="form-control" min="0" value="<?php echo $contador; ?>" name="orden_<?php echo $campo->Field ?>" required>
                      </div>
                    </div>
                  </div>
                  <?php $contador++; ?>
                <?php } ?>
              <?php endforeach ?>
              <div class="col-12 d-flex justify-content-center mt-3">
                <button class="btn btn-info w-100 text-white" type="submit">Crear Administrador</button>
              </div>
            </div>
          </form>
        </div>
      <?php } else { ?>
        <div class="alert alert-info text-center">
          Seleccione una tabla para generar el administrador
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(".letras").keypress(function(key) {
      window.console.log(key.charCode)
      if ((key.charCode < 97 || key.charCode > 122) //letras mayusculas
        &&
        (key.charCode < 65 || key.charCode > 90) //letras minusculas
      )
        return false;
    });
  });

  function changeselecttipo(id) {
    var valor = $('#tipo_' + id).val();
    console.log(valor);
    if (parseInt(valor) == 9) {
      $("#dependiente_" + id).show();
    } else {
      $("#dependiente_" + id).hide();
    }
    if (parseInt(valor) == 7) {
      $("#oculto_" + id).show();
    } else {
      $("#oculto_" + id).hide();
    }
  }

  function selectDepend(id) {
    $("#value_dependiente_" + id).empty();
    $("#label_dependiente_" + id).empty();
    var table = $("#tabla_dependiente_" + id).val();
    $.get("/generator/index/getdatatable?table=" + table + "&campo=" + id, {}, function(res) {
      for (var i in res.data) {
        var option = "<option value='" + res.data[i] + "'>" + res.data[i] + "</option>";
        $("#value_dependiente_" + res.campo).append(option);
        $("#label_dependiente_" + res.campo).append(option);
      }
    });
  }
</script>

<style>
  .gen-row:hover{
    background-color: #0dcaf030;
  }
</style>