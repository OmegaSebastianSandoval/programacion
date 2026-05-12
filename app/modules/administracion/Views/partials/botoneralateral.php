<ul>
  <?php if (Session::getInstance()->get('kt_login_level') == '1') { ?>
    <li <?php if ($this->botonpanel == 1) { ?>class="activo" <?php } ?>>
      <a href="/administracion/panel">
        <i class="fas fa-info-circle"></i>
        Información página
      </a>
    </li>
  <?php } ?>
  <li <?php if ($this->botonpanel == 2) { ?>class="activo" <?php } ?>>
    <a href="/administracion/publicidad">
      <i class="far fa-images"></i>
      Administrar publicidad
    </a>
  </li>
  <li <?php if ($this->botonpanel == 3) { ?>class="activo" <?php } ?>>
    <a href="/administracion/contenido">
      <i class="fas fa-file-invoice"></i>
      Administrar contenidos
    </a>
  </li>
  <li <?php if ($this->botonpanel == 5) { ?>class="activo" <?php } ?>>
    <a href="/administracion/sedes">
      <i class="fas fa-map-marker-alt"></i>
      Administrar sedes
    </a>
  </li>
  <li <?php if ($this->botonpanel == 6) { ?>class="activo" <?php } ?>>
    <a href="/administracion/eventos">
      <i class="fas fa-calendar-alt"></i>
      Administrar eventos
    </a>
  </li>
  <li <?php if ($this->botonpanel == 7) { ?>class="activo" <?php } ?>>
    <a href="/administracion/codigospromocionales">
      <i class="fas fa-tags"></i>
      Administrar códigos promocionales
    </a>
  </li>
  <li <?php if ($this->botonpanel == 8) { ?>class="activo" <?php } ?>>
    <a href="/administracion/boletatipo">
      <i class="fas fa-ticket-alt"></i>
      Administrar tipos de boletas
    </a>
  </li>
    <li <?php if ($this->botonpanel == 9) { ?>class="activo" <?php } ?>>
    <a href="/administracion/vendedores">
      <i class="fas fa-ticket-alt"></i>
      Administrar vendedores
    </a>
  </li>
      <li <?php if ($this->botonpanel == 10) { ?>class="activo" <?php } ?>>
    <a href="/administracion/reservas">
      <i class="fas fa-calendar-check"></i>
      Administrar reservas
    </a>
  </li>
  <li <?php if ($this->botonpanel == 11) { ?>class="activo" <?php } ?>>
    <a href="/administracion/compras">
      <i class="fas fa-receipt"></i>
      Administrar compras
    </a>
  </li>
  <li <?php if ($this->botonpanel == 12) { ?>class="activo" <?php } ?>>
    <a href="/administracion/metricas">
      <i class="fas fa-chart-line"></i>
      Dashboard de Eventos
    </a>
  </li>
  <?php if (Session::getInstance()->get('kt_login_level') == '1') { ?>
    <li <?php if ($this->botonpanel == 4) { ?>class="activo" <?php } ?>>
      <a href="/administracion/usuario">
        <i class="fas fa-users"></i>
        Administrar usuarios
      </a>
    </li>
  <?php } ?>
</ul>