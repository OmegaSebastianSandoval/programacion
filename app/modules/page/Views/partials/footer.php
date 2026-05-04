<?php
$twitterUrl = '';
if (!empty($this->infopage->info_pagina_twitter)) {
  $twitterUrl = $this->infopage->info_pagina_twitter;
} elseif (!empty($this->infopage->info_pagina_x)) {
  $twitterUrl = $this->infopage->info_pagina_x;
}

$linkedinUrl = '';
if (!empty($this->infopage->info_pagina_linkdn)) {
  $linkedinUrl = $this->infopage->info_pagina_linkdn;
} elseif (!empty($this->infopage->info_pagina_linkedin)) {
  $linkedinUrl = $this->infopage->info_pagina_linkedin;
}
?>

<footer class="auth-footer fixed-bottom">
  <div class="container py-2">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
      <div>
        <p>&copy; <?php echo date('Y') ?> Todos los derechos reservados | Diseñado por
          <a href="https://omegasolucionesweb.com" target="_blank" rel="noopener noreferrer">
            OMEGA SOLUCIONES WEB</a>
        </p>

        <p>info@omegawebsystems.com | 318 642 5229 | 350 708 7228</p>
      </div>

      <div class="footer-social-links d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
        <?php if ($this->infopage->info_pagina_telefono) { ?>
          <?php $telefono = intval(preg_replace('/[^0-9]+/', '', $this->infopage->info_pagina_telefono), 10); ?>
          <a href="tel:<?php echo $telefono; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link social-contact-link">
            <i class="fas fa-phone"></i>
            <span><?php echo $this->infopage->info_pagina_telefono; ?></span>
          </a>
        <?php } ?>
        <?php if ($this->infopage->info_pagina_whatsapp) { ?>
          <?php $whatsapp = intval(preg_replace('/[^0-9]+/', '', $this->infopage->info_pagina_whatsapp), 10); ?>
          <a href="https://api.whatsapp.com/send?phone=<?php echo $whatsapp; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link social-contact-link">
            <i class="fab fa-whatsapp"></i>
            <span><?php echo $this->infopage->info_pagina_whatsapp; ?></span>
          </a>
        <?php } ?>
        <?php if ($this->infopage->info_pagina_facebook) { ?>
          <a href="<?php echo $this->infopage->info_pagina_facebook; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
        <?php } ?>
        <?php if ($twitterUrl) { ?>
          <a href="<?php echo $twitterUrl; ?>" target="_blank" rel="noopener noreferrer" class="red social-link"
            aria-label="X o Twitter">
            <i class="fab fa-twitter"></i>
          </a>
        <?php } ?>
        <?php if ($this->infopage->info_pagina_instagram) { ?>
          <a href="<?php echo $this->infopage->info_pagina_instagram; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
        <?php } ?>
        <?php if ($this->infopage->info_pagina_pinterest) { ?>
          <a href="<?php echo $this->infopage->info_pagina_pinterest; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="Pinterest">
            <i class="fab fa-pinterest-p"></i>
          </a>
        <?php } ?>
        <?php if ($this->infopage->info_pagina_youtube) { ?>
          <a href="<?php echo $this->infopage->info_pagina_youtube; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        <?php } ?>
        <?php if ($linkedinUrl) { ?>
          <a href="<?php echo $linkedinUrl; ?>" target="_blank" rel="noopener noreferrer" class="red social-link"
            aria-label="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
        <?php } ?>
        <?php if (!empty($this->infopage->info_pagina_google)) { ?>
          <a href="<?php echo $this->infopage->info_pagina_google; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="Google Plus">
            <i class="fab fa-google-plus-g"></i>
          </a>
        <?php } ?>
        <?php if (!empty($this->infopage->info_pagina_flickr)) { ?>
          <a href="<?php echo $this->infopage->info_pagina_flickr; ?>" target="_blank" rel="noopener noreferrer"
            class="red social-link" aria-label="Flickr">
            <i class="fab fa-flickr"></i>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>
</footer>