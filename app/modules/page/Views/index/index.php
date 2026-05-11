<div class="banner-principal">
  <?php echo $this->banner; ?>
</div>
<div class="contenido">
  <?php echo $this->contenido; ?>
</div>
<?php if (($this->popUp) && $this->popUp->publicidad_estado == 1) { ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const popUp = document.getElementById("popUp");
      if (popUp) {
        const modal = new bootstrap.Modal(popUp);
        modal.show();
      }
    });
  </script>

  <div class="modal fade" id="popUp" tabindex="-1" aria-labelledby="popUpLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style=" border: none;
    background-color: transparent;">
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"
          style="filter: invert(1);"></button>
        <div class="modal-body">
          <?php if ($this->popUp->publicidad_video != "") { ?>
            <div class="fondo-video-youtube">
              <div class="banner-video-youtube" id="videobanner<?php echo $this->popUp->publicidad_id; ?> "
                data-video="<?php echo $this->id_youtube($this->popUp->publicidad_video); ?>"></div>
            </div>
          <?php } ?>
          <?php if ($this->popUp->publicidad_imagen != "") { ?>
            <?php if ($this->popUp->publicidad_enlace != "") { ?> <a href="<?php echo $this->popUp->publicidad_enlace ?>"
                <?php if ($this->popUp->publicidad_tipo_enlace == 1) {
                  echo "target='_blank' rel='noopener noreferrer'";
                } ?>> <?php } ?><img class="w-100 img-fluid d-none d-md-block img-popUp"
                src="/images/<?php echo $this->popUp->publicidad_imagen ?>"
                alt="Imagen popUp <?= $this->popUp->publicidad_nombre ?>">
              <img class="w-100 img-fluid d-block d-md-none"
                src="/images/<?php echo $this->popUp->publicidad_imagenresponsive ?>"
                alt="Imagen popUp <?= $this->popUp->publicidad_nombre ?>">
              <?php if ($this->popUp->publicidad_enlace != "") { ?>
              </a>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
