<div class="container-fluid">
  <div class="row">
    <div class="col-5">
      <div class="row">
        <div class="col-5 ps-0">
          <div class="div-dashboard center">
            <h2>
              <i class="fa-solid fa-hashtag"></i> Redes Sociales <a href="/administracion/informacion/#redes"><i
                  class="fas fa-marker"></i></a>
            </h2>
            <div align="center">
              <div align="center" class="redes">
                <div>
                  <i class="fa-brands fa-facebook-f"></i>
                </div>
                <?php if ($this->info->info_pagina_facebook) { ?>
                    <div>
                      <i class="fa-solid fa-check"></i>
                    </div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-x-twitter"></i></div>
                <?php if ($this->info->info_pagina_x) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-instagram"></i></div>
                <?php if ($this->info->info_pagina_instagram) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-pinterest-p"></i></div>
                <?php if ($this->info->info_pagina_pinterest) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-youtube"></i></div>
                <?php if ($this->info->info_pagina_youtube) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-linkedin-in"></i></div>
                <?php if ($this->info->info_pagina_linkedin) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
              <div align="center" class="redes">
                <div><i class="fa-brands fa-tiktok"></i></div>
                <?php if ($this->info->info_pagina_tiktok) { ?>
                    <div><i class="fa-solid fa-check"></i></div>
                <?php } else { ?>
                    <div><i class="fa-solid fa-xmark"></i></div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-3" style="padding:0;">
          <div class="div-dashboard">
            <h2>
              <i class="fa-brands fa-fonticons"></i> Favicon <a href="/administracion/informacion/#favicon"><i
                  class="fas fa-marker"></i></a>
            </h2>
            <div align="center" class="redesFavicon">
              <?php if ($this->info->info_pagina_favicon) { ?>
                  <div><img src="../images/<?= $this->info->info_pagina_favicon; ?>" style="width: 72px;max-width: 72px;">
                  </div>
                  <div><i class="fa-solid fa-check"></i></div>
              <?php } else { ?>
                  <div><img class="style_btn_nofavicon" src="/images/noexiste.png"></i></div>

                  <div><i class="fa-solid fa-xmark"></i></div>
              <?php } ?>

            </div>


          </div>
        </div>
        <div class="col-4">
          <div class="div-whatsapp">
            <div>
              <img src="/skins/administracion/images/whatsapp.png">
              <h2>Whatsapp:</h2>
              <p><?php echo $this->info->info_pagina_whatsapp; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-7 pe-0" style="overflow: hidden;">
      <div class="div-dashboard">
        <h2>
          <i class="fa-regular fa-address-book"></i> Información de Contáctenos <a
            href="/administracion/informacion/#contactenos"><i class="fas fa-marker"></i></a>
        </h2>
        <div class="pading-dashboard">
          <div class="row">
            <div class="col-4">
              <div class="contenedor-informacion">
                <div class="icono telefono">
                  <img src="/skins/administracion/images/telefono.png">
                </div>
                <div class="contenido">
                  <h4>Teléfonos:</h4>
                  <div><?php echo $this->info->info_pagina_telefono; ?></div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="contenedor-informacion">
                <div class="icono correo">
                  <img src="/skins/administracion/images/correo.png">
                </div>
                <div class="contenido">
                  <h4>Correo Contacto:</h4>
                  <div><?php echo $this->info->info_pagina_correos_contacto; ?></div>
                </div>
              </div>
            </div>

            <div class="col-4">
              <div class="contenedor-informacion">
                <div class="icono direccion">
                  <img src="/skins/administracion/images/direccion.png">
                </div>
                <div class="contenido">
                  <h4>Dirección:</h4>
                  <div><?php echo $this->info->info_pagina_direccion_contacto; ?></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="div-dashboard h-100">
      <h2>
        <i class="fa-regular fa-envelope"></i> Configuración Envio Correo <a
          href="/administracion/informacion#configcorreo"><i class="fas fa-marker"></i></a>
      </h2>
      <div class="pading-dashboard center" style=" margin-top: 2%;padding-bottom: 6px;">
        <div class="row w-100">
          <div class="col-12" style="padding-left: 0;">
            <table class="table table-bordered table-host w-100">
              <tbody>
                <tr>
                  <th scope="row">Host</th>
                  <td>
                    <span><?= $this->info->info_pagina_host; ?></span>
                  </td>
                  <th scope="row">Correo remitente</th>
                  <td>
                    <span><?= $this->info->info_pagina_correo_remitente; ?></span>
                  </td>
                  <th scope="row">Username</th>
                  <td>
                    <span><?= $this->info->info_pagina_username; ?></span>


                  </td>
                </tr>
                <!--<th scope="row">Password</th>
                <td>
                  <span><?= $this->info->info_pagina_password; ?></span>


                </td>-->
                <tr>
                  <th scope="row">Port</th>
                  <td>
                    <span><?= $this->info->info_pagina_port; ?></span>
                  </td>
                  <th scope="row">Nombre remitente</th>
                  <td>
                    <span><?= $this->info->info_pagina_nombre_remitente; ?></span>
                  </td>
                  <th scope="row">Correo oculto</th>
                  <td>
                    <span><?= $this->info->info_pagina_correo_oculto; ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="div-dashboard">
      <h2>
        <i class="fa-solid fa-link"></i> Enlaces de Interés<a href="/administracion/informacion/#contactenos"><i
            class="fas fa-marker"></i></a>
      </h2>
      <div class="pading-dashboard pt-3">
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
            <div class="text-end"><a class="btn btn-sm btn-success" href="<?php echo $this->route . "\manage"; ?>"> <i
                  class="fas fa-plus-square"></i> Crear Nuevo</a></div>
          </div>
        </div>
        <div class="content-table" style="margin-left:0;margin-right:0;">
          <table class=" table table-striped  table-hover table-administrator text-left">
            <thead>
              <tr>
                <td>Título</td>
                <td>Link</td>
                <td width="100">Orden</td>
                <td width="150"></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($this->lists as $content) { ?>
                  <?php $id = $content->enlaces_id; ?>
                  <tr>
                    <td><?= $content->enlaces_titulo; ?></td>
                    <td><?= $content->enlaces_link; ?></td>
                    <td>
                      <input type="hidden" id="<?= $id; ?>" value="<?= $content->orden; ?>"></input>
                      <button class="up_table btn btn-primary btn-sm"><i class="fas fa-angle-up"></i></button>
                      <button class="down_table btn btn-primary btn-sm"><i class="fas fa-angle-down"></i></button>
                    </td>
                    <td class="text-end">
                      <div>
                        <a class="btn btn-azul btn-sm" href="<?php echo $this->route; ?>/manage?id=<?= $id ?>"
                          data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"><i class="fas fa-pen-alt"></i></a>
                        <?php if ($_SESSION['kt_login_level'] == 1) { ?><span data-bs-toggle="tooltip"
                              data-bs-placement="top" title="Eliminar"><a class="btn btn-rojo btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modal<?= $id ?>"><i class="fas fa-trash-alt"></i></a></span><?php } ?>
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
                                href="/administracion/panel/delete?id=<?= $id ?>&csrf=<?= $this->csrf; ?><?php echo ''; ?>">Eliminar</a>
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
      </div>
    </div>
    <div class="col-12 pe-0">
      <div class="row w-100">
        <div class="col-7 ps-0">
          <div class="div-dashboard">
            <h2>
              <i class="fa-solid fa-globe"></i> Archivo SEO <a href="/administracion/informacion/#seo"><i
                  class="fas fa-marker"></i></a>
            </h2>
            <div class="pading-dashboard">
              <div class="row">
                <div class="col-4">
                  <h5> Descripción: </h5>
                  <div class="contenedor-informacion">
                    <div class="icono-seo descripcion">
                      <img src="/skins/administracion/images/descripcion.png">
                    </div>
                    <div class="contenido">
                      <div><?php echo $this->info->info_pagina_descripcion; ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <h5> Tags: </h5>
                  <div class="contenedor-informacion">
                    <div class="icono-seo tags">
                      <img src="/skins/administracion/images/tags.png">
                    </div>
                    <div class="contenido">
                      <div><?php echo $this->info->info_pagina_tags; ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <h5> Archivos SEO: </h5>
                  <div class="contenedor-informacion">
                    <div class="icono-seo archivos">
                      <img src="/skins/administracion/images/archivos-seo.png">
                    </div>
                    <div class="contenido">
                      <a <?php if ($this->info->info_pagina_robot): ?>href="/robots.txt" target="_blank" <?php endif ?>
                        class="btn btn-block btn-robot <?php if (!$this->info->info_pagina_robot) { ?>disabled <?php } ?>"><i
                          class="fas fa-robot"></i> Robot</a>
                      <br>
                      <a <?php if ($this->info->info_pagina_sitemap) { ?>href="/sitemap.xml" target="_blank" <?php } ?>
                        class="btn btn-block btn-sitemap <?php if (!$this->info->info_pagina_sitemap) { ?>disabled <?php } ?>">
                        <i class="fas fa-sitemap"></i> SiteMap</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-5 pe-0" style="overflow: hidden; margin-right: -15px;">
          <div class="div-dashboard h-100" style="">
            <h2>
              <i class="fa-regular fa-handshake"></i> Política de Manejo de Datos <a
                href="/administracion/informacion/#politicadatos"><i class="fas fa-marker"></i></a>
            </h2>
            <div class="pading-dashboard center">

              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <th>Título</th>
                  </tr>
                  <tr>
                    <td><?= $this->info->info_pagina_titulo_legal; ?></td>
                  </tr>
                  <tr>
                    <th>Descripción</th>
                  </tr>
                  <tr>
                    <td><?= $this->info->info_pagina_descripcion_legal; ?></td>
                  </tr>




                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-5 ps-0" style="overflow: hidden;">
        <div class="div-dashboard" style="height: 100%;">
          <h2>
            <i class="fa-solid fa-code"></i> Scripts Head <a href="/administracion/informacion/#scripts"><i
                class="fas fa-marker"></i></a>
          </h2>
          <div class="pading-dashboard center">
            <div class="contenedor-informacion">
              <div class="icono-seo scripts">
                <img src="/skins/administracion/images/scripts.png">
              </div>
              <div class="contenido">
                <h4>Scripts:</h4>
                <?php echo htmlentities($this->info->info_pagina_scripts); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-7 pe-0">
        <div class="div-dashboard">
          <h2>
            <i class="fa-solid fa-code"></i> Scripts Head <a href="/administracion/informacion/#scripts"><i
                class="fas fa-marker"></i></a>
          </h2>
          <div class="pading-dashboard">
            <div class="contenedor-informacion">
              <div class="icono-seo log">
                <img src="/skins/administracion/images/log-de-usuario.png">
              </div>
              <div class="contenido">
                <h4>Log de usuarios:</h4>
                <table width="100%" class="tabla_log">
                  <tr>
                    <th>Usuario</th>
                    <th>Fecha ingreso</th>
                    <th>Hora ingreso</th>
                    <th><a href="/administracion/log/"><button class="btn-xs btn-azul-claro">Detalle</button></a></th>
                  <tr>
                    <?php foreach ($this->log as $log): ?>
                        <?php $aux = explode(" ", $log->log_fecha); ?>
                      <tr>
                        <td><?php echo $log->log_usuario; ?></td>
                        <td><?php echo $aux[0]; ?></td>
                        <td><?php echo $aux[1]; ?></td>
                        <td><a href="/administracion/log/?log_usuario=<?php echo $log->log_usuario; ?>"><button
                              class="btn-xs btn-verde-claro">Detalle</button></a></td>
                      </tr>
                  <?php endforeach ?>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>
<br><br>
<style>
  .table-bordered th {
    background: #8bc63d;
    text-align: left;
    color: #FFFFFF;
    font-weight: 300;
    vertical-align: inherit;
    font-size: 16px;
    font-weight: 100;
  }

  .table-bordered td {
    vertical-align: inherit;
    font-weight: 100;
  }

  .redesFavicon {
    text-align: center;
    display: block;
    padding-top: 7px;
  }

  .style_btn_nofavicon {
    width: 60px;
    padding-top: 6px;
  }
</style>