<?php
$ev   = $this->evento;
$sede = $this->sede;
$errorMsg = ($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8') : '';

$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

$fechaParts = explode('-', substr($ev->evento_fecha, 0, 10));
$fechaStr = (int) $fechaParts[2] . ' de ' . $meses[(int) $fechaParts[1]] . ' de ' . $fechaParts[0];
$hora = $ev->evento_hora ? substr($ev->evento_hora, 0, 5) : '';

$sedeColor     = ($sede && $sede->sede_color) ? $sede->sede_color : '#0b4b3e';
$r = hexdec(substr($sedeColor, 1, 2));
$g = hexdec(substr($sedeColor, 3, 2));
$b = hexdec(substr($sedeColor, 5, 2));
$sedeTextColor = (($r * 299 + $g * 587 + $b * 114) / 1000 >= 128) ? '#1a1a1a' : '#ffffff';

$boletasArr  = json_decode($this->boletasJson,  true) ?: [];
$reservasArr = json_decode($this->reservasJson, true) ?: [];
$eventoTipo  = $ev->evento_tipo;

// Disponibilidad y precio mínimo según tipo
$hayDisponible = false;
$precioMinimo  = null;

if ($eventoTipo === 'reserva') {
  foreach ($reservasArr as $rItem) {
    if ($rItem['disponibles'] <= 0) continue;
    $hayDisponible = true;
    if ($precioMinimo === null || $rItem['precio'] < $precioMinimo) {
      $precioMinimo = $rItem['precio'];
    }
  }
} else {
  foreach ($boletasArr as $bItem) {
    if ($bItem['disponibles'] <= 0) continue;
    $hayDisponible = true;
    $precioRef = ($eventoTipo === 'reservayboleteria')
      ? ($bItem['precio'] + $bItem['precioadicional'])
      : $bItem['precio'];
    if ($precioMinimo === null || $precioRef < $precioMinimo) {
      $precioMinimo = $precioRef;
    }
  }
}

$btnTexto = ($eventoTipo === 'reserva') ? 'Hacer reserva' : 'Comprar entradas';
?>
<?php if ($errorMsg): ?>
  <div class="ev-alerta-error" id="ev-alerta-error" role="alert">
    <i class="fas fa-exclamation-circle ev-alerta-icon"></i>
    <span class="ev-alerta-msg"><?= $errorMsg ?></span>
    <button class="ev-alerta-close" onclick="this.closest('.ev-alerta-error').remove()" aria-label="Cerrar">&times;</button>
  </div>
<?php endif; ?>

<div class="container">
  <div class="ev-page">

    <!-- ===================== PANEL IZQUIERDO: IMAGEN ===================== -->
    <div class="ev-panel-img">
      <?php if ($ev->evento_imagen): ?>
        <img class="ev-img-main" src="/images/<?= $ev->evento_imagen ?>" alt="<?= $ev->evento_nombre ?>">
      <?php else: ?>
        <div class="ev-img-no-photo"><i class="fas fa-music"></i></div>
      <?php endif; ?>
      <div class="ev-img-overlay"></div>
    </div>

    <!-- ===================== PANEL DERECHO: INFO DEL EVENTO ===================== -->
    <div class="ev-panel-info">
      <div class="ev-info-inner">

        <!-- Badges -->
        <div class="ev-info-badges">
          <span class="ev-badge ev-badge-date">
            <i class="fas fa-calendar-alt"></i> <?= $fechaStr ?>
          </span>
          <?php if ($hora): ?>
            <span class="ev-badge ev-badge-hora--dark">
              <i class="fas fa-clock"></i> <?= $hora ?>
            </span>
          <?php endif; ?>
          <?php if ($sede && $sede->sede_nombre): ?>
            <span class="ev-badge ev-badge-sede" style="background:<?= $sedeColor ?>;color:<?= $sedeTextColor ?>">
              <i class="fas fa-map-marker-alt"></i> <?= $sede->sede_nombre ?>
            </span>
          <?php endif; ?>
        </div>

        <!-- Alerta de tipo de cobro -->
        <?php if ($eventoTipo === 'reserva'): ?>
          <div class="ev-tipo-alerta ev-tipo-alerta--reserva">
            <i class="fas fa-calendar-check"></i>
            <div>
              <strong>Evento con reserva de cupos</strong>
              <span>Selecciona tu opción y el número de personas. El precio se calcula por persona según la opción elegida.</span>
            </div>
          </div>
        <?php elseif ($eventoTipo === 'reservayboleteria'): ?>
          <div class="ev-tipo-alerta ev-tipo-alerta--mixto">
            <i class="fas fa-layer-group"></i>
            <div>
              <strong>Boleta obligatoria — reserva opcional</strong>
              <span>Debes comprar tu boleta de ingreso. Adicionalmente puedes reservar; el valor de la reserva es consumible en el evento.</span>
            </div>
          </div>
        <?php else: ?>
          <div class="ev-tipo-alerta ev-tipo-alerta--boleteria">
            <i class="fas fa-ticket-alt"></i>
            <div>
              <strong>Compra tu boleta de ingreso</strong>
              <span>Pagas el valor total de tu entrada. Recibirás tu boleta electrónica al finalizar la compra.</span>
            </div>
          </div>
        <?php endif; ?>

        <!-- Título -->
        <h1 class="ev-info-title"><?= $ev->evento_nombre ?></h1>

        <!-- Descripción -->
        <?php if ($ev->evento_descripcion): ?>
          <div class="ev-info-desc"><?= $ev->evento_descripcion ?></div>
        <?php endif; ?>

        <!-- Preview disponibilidad -->
        <?php if ($eventoTipo === 'reserva' && $reservasArr): ?>
          <div class="ev-info-boletas-preview">
            <p class="ev-info-section-label"><i class="fas fa-chair me-1"></i> Opciones de reserva</p>
            <div class="ev-info-boletas-list">
              <?php foreach ($reservasArr as $rItem): ?>
                <div class="ev-info-boleta-item<?= $rItem['disponibles'] <= 0 ? ' ev-info-boleta-agotada' : '' ?>">
                  <div class="ev-info-boleta-left">
                    <span class="ev-info-boleta-nombre"><?= $rItem['nombre'] ?></span>
                    <?php if ($rItem['disponibles'] <= 0): ?>
                      <span class="ev-badge-agotada">Agotado</span>
                    <?php else: ?>
                      <span class="ev-info-boleta-saldo"><?= $rItem['disponibles'] ?> cupos disponibles</span>
                    <?php endif; ?>
                  </div>
                  <div class="ev-info-boleta-precios">
                    <?php if ($rItem['precio'] == 0): ?>
                      <span class="ev-info-boleta-precio">Gratis</span>
                    <?php else: ?>
                      <span class="ev-info-boleta-precio">$ <?= number_format($rItem['precio'], 0, ',', '.') ?> / persona</span>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php elseif ($boletasArr): ?>
          <div class="ev-info-boletas-preview">
            <p class="ev-info-section-label"><i class="fas fa-ticket-alt me-1"></i> Boletas disponibles</p>
            <div class="ev-info-boletas-list">
              <?php foreach ($boletasArr as $bItem): ?>
                <div class="ev-info-boleta-item<?= $bItem['disponibles'] <= 0 ? ' ev-info-boleta-agotada' : '' ?>">
                  <div class="ev-info-boleta-left">
                    <span class="ev-info-boleta-nombre"><?= $bItem['tipo_nombre'] ?></span>
                    <?php if ($bItem['disponibles'] <= 0): ?>
                      <span class="ev-badge-agotada">Agotada</span>
                    <?php else: ?>
                      <span class="ev-info-boleta-saldo"><?= $bItem['disponibles'] ?> disponibles</span>
                    <?php endif; ?>
                  </div>
                  <div class="ev-info-boleta-precios">
                    <span class="ev-info-boleta-precio">$ <?= number_format($bItem['precio'], 0, ',', '.') ?></span>
                    <?php if ($eventoTipo === 'reservayboleteria' && $bItem['precioadicional'] > 0): ?>
                      <span class="ev-info-boleta-precio-reserva">+ Reserva $ <?= number_format($bItem['precioadicional'], 0, ',', '.') ?></span>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <?php if ($eventoTipo === 'reservayboleteria' && $reservasArr): ?>
            <div class="ev-info-boletas-preview" style="margin-top:12px">
              <p class="ev-info-section-label"><i class="fas fa-chair me-1"></i> Reservas disponibles</p>
              <div class="ev-tipo-alerta ev-tipo-alerta--mixto" style="margin-bottom:10px;font-size:.82rem;">
                <i class="fas fa-info-circle"></i>
                <div>
                  <strong>El valor de la reserva es consumible</strong>
                  <span>Se descuenta del consumo en el evento. Para acceder a una reserva debes comprar boletas para el evento.</span>
                </div>
              </div>
              <div class="ev-info-boletas-list">
                <?php foreach ($reservasArr as $rItem): ?>
                  <div class="ev-info-boleta-item<?= $rItem['disponibles'] <= 0 ? ' ev-info-boleta-agotada' : '' ?>">
                    <div class="ev-info-boleta-left">
                      <span class="ev-info-boleta-nombre"><?= $rItem['nombre'] ?></span>
                      <?php if ($rItem['capacidad'] > 0): ?>
                        <span class="ev-info-boleta-saldo"><i class="fas fa-users"></i> <?= $rItem['capacidad'] ?> persona<?= $rItem['capacidad'] != 1 ? 's' : '' ?></span>
                      <?php endif; ?>
                      <?php if ($rItem['disponibles'] <= 0): ?>
                        <span class="ev-badge-agotada">Agotado</span>
                      <?php else: ?>
                        <span class="ev-info-boleta-saldo"><?= $rItem['disponibles'] ?> disponibles</span>
                      <?php endif; ?>
                    </div>
                    <div class="ev-info-boleta-precios">
                      <?php if ($rItem['precio'] == 0): ?>
                        <span class="ev-info-boleta-precio">Gratis</span>
                      <?php else: ?>
                        <span class="ev-info-boleta-precio">$ <?= number_format($rItem['precio'], 0, ',', '.') ?></span>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <!-- Políticas -->
        <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
          <button class="ev-politicas-btn" data-bs-toggle="modal" data-bs-target="#modalPoliticas">
            <i class="fas fa-file-alt"></i> Ver políticas del evento
          </button>
        <?php endif; ?>

      </div>

      <!-- CTA sticky al fondo del panel -->
      <?php if (!$hayDisponible): ?>
        <div class="ev-info-cta ev-info-cta--agotado">
          <div class="ev-agotado-icon"><i class="fas fa-ticket-alt"></i></div>
          <div class="ev-agotado-text">
            <span class="ev-agotado-title">Sin disponibilidad</span>
            <span class="ev-agotado-sub"><?= $eventoTipo === 'reserva' ? 'No hay cupos de reserva disponibles' : 'Las boletas para este evento se han agotado' ?></span>
          </div>
        </div>
      <?php else: ?>
        <div class="ev-info-cta">
          <?php if ($precioMinimo !== null): ?>
            <span class="ev-info-cta-desde">
              Desde
              <strong>$ <?= number_format($precioMinimo, 0, ',', '.') ?></strong>
              <?= $eventoTipo === 'reserva' ? '<small>/ persona</small>' : '' ?>
            </span>
          <?php endif; ?>
          <div class="ev-cta-wrap">
            <button class="ev-btn-cta" id="ev-btn-cta"
              data-bs-toggle="modal" data-bs-target="#modalCompra"
              <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): echo 'disabled';
              endif; ?>>
              <i class="fas fa-<?= $eventoTipo === 'reserva' ? 'calendar-check' : 'ticket-alt' ?>"></i>
              <?= $btnTexto ?>
            </button>
            <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
              <p class="ev-cta-hint" id="ev-cta-hint">
                <i class="fas fa-shield-alt"></i>
                Primero debes <button type="button" class="ev-cta-hint-link" data-bs-toggle="modal" data-bs-target="#modalPoliticas">leer y aceptar las políticas</button>
              </p>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- ========== MODAL DE COMPRA ========== -->
<?php if ($hayDisponible): ?>
  <div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ev-modal-compra-dialog">
      <div class="modal-content ev-modal-compra-content">

        <div class="ev-card-header">
          <div class="ev-card-header-info">
            <h2 class="ev-card-header-title">
              <i class="fas fa-<?= $eventoTipo === 'reserva' ? 'calendar-check' : 'ticket-alt' ?> me-2"></i>
              <?= $eventoTipo === 'reserva' ? 'Haz tu reserva' : 'Compra tu entrada' ?>
            </h2>
            <p class="ev-card-header-evento"><?= $ev->evento_nombre ?> — <?= $fechaStr ?></p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="ev-card-header-badge">Seguro</span>
            <button type="button" class="btn-close btn-close-white ms-1" data-bs-dismiss="modal"></button>
          </div>
        </div>

        <div class="modal-body p-0">

          <!-- Datos del comprador -->
          <div class="ev-compra-section">
            <p class="ev-compra-section-title">Datos del comprador</p>
            <div class="ev-form-grid">
              <div class="ev-form-group ev-form-group--full">
                <label>Nombre completo</label>
                <input type="text" class="ev-input" id="comp-nombre" placeholder="Tu nombre completo" required>
              </div>
              <div class="ev-form-group">
                <label>Documento</label>
                <input type="text" class="ev-input" id="comp-documento" placeholder="Número de doc." required>
              </div>
              <div class="ev-form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" class="ev-input" id="comp-nacimiento" required>
              </div>
              <div class="ev-form-group ev-form-group--full">
                <label>Correo electrónico</label>
                <input type="email" class="ev-input" id="comp-email" placeholder="correo@ejemplo.com" required>
              </div>
            </div>
            <input type="hidden" id="comp-vendedor" name="vendedor" value="<?= $this->vendedor ?>">
          </div>

          <?php if ($eventoTipo === 'reserva'): ?>
            <!-- ── SOLO RESERVA: selector de opción + cantidad de personas ── -->
            <div class="ev-compra-section">
              <p class="ev-compra-section-title">Tu reserva</p>
              <div id="ev-reservas-list"></div>
              <div class="ev-personas-counter" id="ev-personas-wrap" style="display:none">
                <div class="ev-personas-counter-label">
                  <i class="fas fa-users"></i>
                  <span>Número de personas</span>
                </div>
                <div class="ev-personas-counter-ctrl">
                  <button class="ev-personas-btn" id="personas-menos" type="button" aria-label="Menos">
                    <svg width="14" height="2" viewBox="0 0 14 2" fill="none"><rect width="14" height="2" rx="1" fill="currentColor"/></svg>
                  </button>
                  <div class="ev-personas-display">
                    <span class="ev-personas-num" id="personas-val">1</span>
                    <span class="ev-personas-unit">persona<span id="personas-plural">s</span></span>
                  </div>
                  <button class="ev-personas-btn ev-personas-btn--plus" id="personas-mas" type="button" aria-label="Más">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><rect x="6" width="2" height="14" rx="1" fill="currentColor"/><rect y="6" width="14" height="2" rx="1" fill="currentColor"/></svg>
                  </button>
                </div>
                <p class="ev-personas-max" id="ev-personas-max-txt"></p>
              </div>
            </div>

          <?php elseif ($eventoTipo === 'reservayboleteria'): ?>
            <!-- ── BOLETERÍA + RESERVA: boletas obligatorias + palco opcional ── -->
            <div class="ev-compra-section">
              <p class="ev-compra-section-title">Selecciona tus boletas <span style="font-weight:400;text-transform:none;color:#bbb">(máx. 20 por persona)</span></p>
              <div id="ev-boletas-list"></div>
            </div>
            <?php if ($reservasArr): ?>
              <div class="ev-compra-section">
                <p class="ev-compra-section-title">Reservas disponibles <span style="font-weight:400;text-transform:none;color:#bbb">(opcional)</span></p>
                <div class="ev-tipo-alerta ev-tipo-alerta--mixto" style="margin-bottom:12px;font-size:.80rem;">
                  <i class="fas fa-info-circle"></i>
                  <div>
                    <strong>El valor de la reserva es consumible</strong>
                    <span>Se descuenta del consumo en el evento. Para acceder a una reserva debes haber seleccionado boletas.</span>
                  </div>
                </div>
                <div id="ev-palcos-list"></div>
                <p class="ev-palco-nota" id="ev-palco-nota" style="display:none"></p>
              </div>
            <?php endif; ?>

          <?php else: ?>
            <!-- ── SOLO BOLETERÍA ── -->
            <div class="ev-compra-section">
              <p class="ev-compra-section-title">Selecciona tus boletas <span style="font-weight:400;text-transform:none;color:#bbb">(máx. 20 por persona)</span></p>
              <div id="ev-boletas-list"></div>
              <p class="ev-boletas-nota" id="ev-sin-boletas" style="display:none">No hay boletas disponibles para este evento.</p>
            </div>
          <?php endif; ?>

          <!-- Código promocional (solo boletería y reservayboleteria) -->
          <?php if ($eventoTipo !== 'reserva'): ?>
            <div class="ev-compra-section">
              <p class="ev-compra-section-title">Código promocional</p>
              <div class="ev-promo-row">
                <input type="text" class="ev-input" id="promo-codigo" placeholder="Código promocional" autocomplete="off">
                <button class="ev-promo-btn" id="promo-aplicar">Aplicar</button>
              </div>
              <p class="ev-promo-msg" id="promo-msg"></p>
            </div>
          <?php endif; ?>

          <!-- Resumen -->
          <div class="ev-resumen">
            <div id="ev-resumen-boletas"></div>
            <div class="ev-resumen-row" id="ev-resumen-descuento" style="display:none">
              <span>Descuento</span><span id="ev-desc-val" class="ev-text-green"></span>
            </div>
            <div class="ev-resumen-divider"></div>
            <div class="ev-resumen-row ev-resumen-total">
              <span>Total a pagar</span><span id="ev-total-val">$ 0</span>
            </div>
            <?php if ($eventoTipo === 'reserva'): ?>
              <p class="ev-resumen-nota">
                <i class="fas fa-info-circle"></i>
                Este es el valor de reserva por persona.
              </p>
            <?php elseif ($eventoTipo === 'reservayboleteria'): ?>
              <p class="ev-resumen-nota">
                <i class="fas fa-info-circle"></i>
                El total incluye boletas y, si aplica, la reserva seleccionada.
              </p>
            <?php endif; ?>
          </div>

        </div>

        <div class="modal-footer p-0 border-0">
          <button class="ev-btn-comprar" id="ev-btn-comprar" disabled>
            <i class="fas fa-lock"></i> Continuar con el pago
          </button>
        </div>

      </div>
    </div>
  </div>
<?php endif; ?>

<!-- ========== MODAL POLÍTICAS ========== -->
<?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
  <div class="modal fade" id="modalPoliticas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content ev-politicas-modal">
        <div class="modal-header">
          <h5 class="modal-title"><?= $ev->evento_titulo_politica ?: 'Políticas del evento' ?></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body ev-politicas-body">
          <?= $ev->evento_descripcion_politica ?>
        </div>
        <div class="modal-footer ev-politicas-footer">
          <label class="ev-politicas-check">
            <input type="checkbox" id="ev-acepto-politicas">
            <span>He leído y acepto las políticas del evento</span>
          </label>
          <button type="button" class="ev-politicas-aceptar" id="ev-politicas-aceptar" disabled data-bs-dismiss="modal">
            Aceptar y continuar
          </button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script>
  (() => {
    'use strict';

    const EVENTO_TIPO = <?= json_encode($ev->evento_tipo) ?>;
    const EVENTO_ID = <?= (int) $ev->evento_id ?>;
    const BOLETAS = <?= $this->boletasJson ?>;
    const RESERVAS = <?= $this->reservasJson ?>;

    const formatCOP = n => (n === 0 ? 'Gratis' : '$ ' + Math.round(n).toLocaleString('es-CO'));

    // ── Política de aceptación ───────────────────────────────────────────────
    const checkAcepto = document.getElementById('ev-acepto-politicas');
    const btnAceptar = document.getElementById('ev-politicas-aceptar');
    const btnCta = document.getElementById('ev-btn-cta');
    const ctaHint = document.getElementById('ev-cta-hint');

    if (checkAcepto && btnCta) {
      checkAcepto.addEventListener('change', () => {
        btnAceptar.disabled = !checkAcepto.checked;
      });
      btnAceptar.addEventListener('click', () => {
        btnCta.disabled = false;
        if (ctaHint) ctaHint.style.display = 'none';
      });
    }

    // Auto-cerrar alerta de error de URL
    const alertaEl = document.getElementById('ev-alerta-error');
    if (alertaEl) {
      setTimeout(() => alertaEl.classList.add('ev-alerta-saliendo'), 4500);
      setTimeout(() => alertaEl.remove(), 5000);
    }

    function mostrarErrorInline(msg) {
      let el = document.getElementById('ev-error-inline');
      if (!el) {
        el = document.createElement('p');
        el.id = 'ev-error-inline';
        el.className = 'ev-error-inline';
        const footer = document.querySelector('.modal-footer');
        if (footer) footer.insertAdjacentElement('beforebegin', el);
      }
      el.textContent = msg;
      el.style.display = '';
      setTimeout(() => {
        if (el) el.style.display = 'none';
      }, 4000);
    }

    // ────────────────────────────────────────────────────────────────────────
    // SOLO RESERVA
    // ────────────────────────────────────────────────────────────────────────
    if (EVENTO_TIPO === 'reserva') {

      let reservaSelId = null;
      let reservaSelPrecio = 0;
      let reservaSelDisp = 0;
      let cantPersonas = 1;

      function renderReservas() {
        const container = document.getElementById('ev-reservas-list');
        if (!RESERVAS || RESERVAS.length === 0) {
          container.innerHTML = '<p class="ev-boletas-nota">No hay opciones de reserva disponibles.</p>';
          return;
        }

        RESERVAS.forEach(r => {
          const agotada = r.disponibles <= 0;
          const btn = document.createElement('div');
          btn.className = 'ev-reserva-opcion' + (agotada ? ' ev-reserva-agotada' : '');
          btn.dataset.id = r.id;
          btn.dataset.precio = r.precio;
          btn.dataset.disp = r.disponibles;
          btn.innerHTML =
            `<div class="ev-reserva-info">` +
            `<span class="ev-reserva-nombre">${r.nombre}</span>` +
            (agotada ?
              `<span class="ev-badge-agotada">Sin cupos</span>` :
              `<span class="ev-reserva-cupos">${r.disponibles} cupos</span>`) +
            `</div>` +
            `<span class="ev-reserva-precio">${r.precio === 0 ? 'Gratis' : formatCOP(r.precio) + ' / persona'}</span>`;

          if (!agotada) {
            btn.addEventListener('click', () => {
              document.querySelectorAll('.ev-reserva-opcion').forEach(b => b.classList.remove('ev-reserva-opcion--activa'));
              btn.classList.add('ev-reserva-opcion--activa');
              reservaSelId = r.id;
              reservaSelPrecio = r.precio;
              reservaSelDisp = Math.min(20, r.disponibles);
              cantPersonas = 1;
              document.getElementById('personas-val').textContent = '1';
              document.getElementById('personas-plural').textContent = 's';
              const maxTxt = document.getElementById('ev-personas-max-txt');
              if (maxTxt) maxTxt.textContent = 'Máximo ' + reservaSelDisp + ' persona' + (reservaSelDisp !== 1 ? 's' : '') + ' por reserva';
              document.getElementById('personas-menos').disabled = true;
              document.getElementById('personas-mas').disabled = reservaSelDisp <= 1;
              document.getElementById('ev-personas-wrap').style.display = '';
              recalcular();
            });
          }
          container.appendChild(btn);
        });
      }

      function recalcular() {
        const total = reservaSelPrecio * cantPersonas;
        const lines = reservaSelId ?
          [`<div class="ev-res-line"><span>Reserva (${cantPersonas} persona${cantPersonas > 1 ? 's' : ''})</span><span>${formatCOP(total)}</span></div>`] :
          [];
        document.getElementById('ev-resumen-boletas').innerHTML = lines.join('');
        document.getElementById('ev-total-val').textContent = formatCOP(total);
        document.getElementById('ev-btn-comprar').disabled = !reservaSelId;
      }

      function actualizarBotonesPersonas() {
        document.getElementById('personas-menos').disabled = cantPersonas <= 1;
        document.getElementById('personas-mas').disabled = cantPersonas >= reservaSelDisp;
        document.getElementById('personas-plural').textContent = cantPersonas === 1 ? '' : 's';
      }

      document.getElementById('personas-menos').addEventListener('click', () => {
        if (cantPersonas > 1) {
          cantPersonas--;
          document.getElementById('personas-val').textContent = cantPersonas;
          actualizarBotonesPersonas();
          recalcular();
        }
      });
      document.getElementById('personas-mas').addEventListener('click', () => {
        if (cantPersonas < reservaSelDisp) {
          cantPersonas++;
          document.getElementById('personas-val').textContent = cantPersonas;
          actualizarBotonesPersonas();
          recalcular();
        }
      });

      renderReservas();
      recalcular();

      document.getElementById('ev-btn-comprar').addEventListener('click', () => {
        const nombre = document.getElementById('comp-nombre').value.trim();
        const documento = document.getElementById('comp-documento').value.trim();
        const nacimiento = document.getElementById('comp-nacimiento').value.trim();
        const email = document.getElementById('comp-email').value.trim();
        const vendedor = document.getElementById('comp-vendedor').value.trim();

        if (!nombre || !documento || !nacimiento || !email) {
          mostrarErrorInline('Completa todos los datos del comprador.');
          return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          mostrarErrorInline('Ingresa un correo electrónico válido.');
          return;
        }
        if (!reservaSelId) {
          mostrarErrorInline('Selecciona una opción de reserva.');
          return;
        }

        const total = reservaSelPrecio * cantPersonas;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/page/evento/generarpago';
        const campos = {
          evento_id: EVENTO_ID,
          nombre,
          documento,
          fechanacimiento: nacimiento,
          email,
          vendedor,
          reserva_evento_id: reservaSelId,
          cantidad_personas: cantPersonas,
          total,
          codigo: ''
        };
        for (const [key, val] of Object.entries(campos)) {
          const inp = document.createElement('input');
          inp.type = 'hidden';
          inp.name = key;
          inp.value = val;
          form.appendChild(inp);
        }
        document.body.appendChild(form);
        form.submit();
      });

      // ────────────────────────────────────────────────────────────────────────
      // SOLO BOLETERÍA + BOLETERÍA Y RESERVA
      // ────────────────────────────────────────────────────────────────────────
    } else {

      let descuentoAplicado = 0;
      let promoAplicado = null;
      let palcoSelId = null;
      let palcoSelPrecio = 0;
      let palcoSelNombre = '';
      let palcoReqBoletaId = 0;
      let palcoReqCant = 0;

      function getPrecioUnitario(b) {
        if (EVENTO_TIPO === 'reservayboleteria') return b.precio + b.precioadicional;
        return b.precio;
      }

      function renderBoletas() {
        const container = document.getElementById('ev-boletas-list');
        if (!BOLETAS || BOLETAS.length === 0) {
          const p = document.getElementById('ev-sin-boletas');
          if (p) p.style.display = '';
          return;
        }
        BOLETAS.forEach((b, i) => {
          const agotada = b.disponibles <= 0;
          const maxSel = Math.min(20, b.disponibles);

          let precioHtml = '';
          if (EVENTO_TIPO === 'reservayboleteria' && b.precioadicional > 0) {
            precioHtml = `<span class="ev-chip-precio-detalle">` +
              `<span>Boleta: ${formatCOP(b.precio)}</span>` +
              `<span>Reserva: ${formatCOP(b.precioadicional)}</span>` +
              `<strong>Total c/u: ${formatCOP(b.precio + b.precioadicional)}</strong></span>`;
          } else {
            precioHtml = `<span class="ev-chip-precio">${formatCOP(b.precio)}</span>`;
          }

          const row = document.createElement('div');
          row.className = 'ev-boleta-row' + (agotada ? ' ev-boleta-agotada' : '');
          row.innerHTML =
            `<div class="ev-boleta-info"><span class="ev-boleta-nombre">${b.tipo_nombre}</span>` + precioHtml +
            (agotada ? `<span class="ev-badge-agotada">Agotada</span>` : `<span class="ev-boleta-saldo">${b.disponibles} disponibles</span>`) +
            `</div>` +
            `<div class="ev-boleta-qty">` +
            `<button class="ev-qty-btn" data-idx="${i}" data-dir="-1"${agotada ? ' disabled' : ''}>−</button>` +
            `<span class="ev-qty-val" id="qty-val-${i}">0</span>` +
            `<button class="ev-qty-btn" data-idx="${i}" data-dir="1"${agotada ? ' disabled' : ''}>+</button>` +
            `</div>`;
          container.appendChild(row);
        });

        container.addEventListener('click', e => {
          const btn = e.target.closest('.ev-qty-btn');
          if (!btn) return;
          const idx = parseInt(btn.dataset.idx, 10);
          const dir = parseInt(btn.dataset.dir, 10);
          const span = document.getElementById('qty-val-' + idx);
          const maxSel = Math.min(20, BOLETAS[idx].disponibles);
          let val = parseInt(span.textContent, 10) + dir;
          val = Math.max(0, Math.min(maxSel, val));
          span.textContent = val;
          recalcular();
        });
      }

      function renderPalcos() {
        const container = document.getElementById('ev-palcos-list');
        if (!container || !RESERVAS || RESERVAS.length === 0) return;

        // Opción "ninguno"
        const ninguno = document.createElement('div');
        ninguno.className = 'ev-reserva-opcion ev-reserva-opcion--activa';
        ninguno.dataset.id = '0';
        ninguno.innerHTML = '<span class="ev-reserva-nombre">Sin reserva</span>';
        ninguno.addEventListener('click', () => {
          seleccionarPalco(ninguno, 0, 0, '', 0, 0);
        });
        container.appendChild(ninguno);

        RESERVAS.forEach(r => {
          const agotada = r.disponibles <= 0;
          const btn = document.createElement('div');
          btn.className = 'ev-reserva-opcion' + (agotada ? ' ev-reserva-agotada' : '');
          btn.dataset.id = r.id;
          btn.innerHTML =
            `<div class="ev-reserva-info">` +
            `<span class="ev-reserva-nombre">${r.nombre}</span>` +
            (r.capacidad > 0 ? `<span class="ev-reserva-cupos"><i class="fas fa-users"></i> ${r.capacidad} persona${r.capacidad !== 1 ? 's' : ''}</span>` : '') +
            (agotada ? `<span class="ev-badge-agotada">Sin disponibilidad</span>` : `<span class="ev-reserva-cupos">${r.disponibles} disponibles</span>`) +
            `</div>` +
            `<span class="ev-reserva-precio">${formatCOP(r.precio)}</span>`;

          if (!agotada) {
            btn.addEventListener('click', () => {
              seleccionarPalco(btn, r.id, r.precio, r.nombre, r.boleta_req, r.boletas_x_reserva);
            });
          }
          container.appendChild(btn);
        });
      }

      function seleccionarPalco(el, id, precio, nombre, reqBoletaId, reqCant) {
        document.querySelectorAll('.ev-reserva-opcion').forEach(b => b.classList.remove('ev-reserva-opcion--activa'));
        el.classList.add('ev-reserva-opcion--activa');
        palcoSelId = id || null;
        palcoSelPrecio = precio;
        palcoSelNombre = nombre;
        palcoReqBoletaId = reqBoletaId;
        palcoReqCant = reqCant;

        const nota = document.getElementById('ev-palco-nota');
        if (nota) {
          if (id && reqBoletaId > 0 && reqCant > 0) {
            const bNombre = BOLETAS.find(b => b.id === reqBoletaId)?.tipo_nombre ?? 'requerida';
            nota.textContent = '⚠ Este palco requiere mínimo ' + reqCant + ' boleta(s) tipo "' + bNombre + '".';
            nota.style.display = '';
          } else {
            nota.style.display = 'none';
          }
        }
        recalcular();
      }

      function recalcular() {
        let subtotal = 0;
        const lines = [];

        BOLETAS.forEach((b, i) => {
          const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
          if (!qty) return;
          const unitario = getPrecioUnitario(b);
          subtotal += unitario * qty;
          lines.push(`<div class="ev-res-line"><span>${b.tipo_nombre} × ${qty}</span><span>${formatCOP(unitario * qty)}</span></div>`);
        });

        if (palcoSelId && palcoSelPrecio > 0) {
          subtotal += palcoSelPrecio;
          lines.push(`<div class="ev-res-line"><span>Palco: ${palcoSelNombre}</span><span>${formatCOP(palcoSelPrecio)}</span></div>`);
        }

        if (promoAplicado && promoAplicado.porcentaje > 0) {
          descuentoAplicado = Math.round(subtotal * promoAplicado.porcentaje / 100);
        }

        document.getElementById('ev-resumen-boletas').innerHTML = lines.join('');

        const descEl = document.getElementById('ev-resumen-descuento');
        const descVal = document.getElementById('ev-desc-val');
        const total = Math.max(0, subtotal - descuentoAplicado);

        if (descuentoAplicado > 0 && subtotal > 0) {
          descEl.style.display = '';
          descVal.textContent = '− ' + formatCOP(descuentoAplicado);
        } else {
          descEl.style.display = 'none';
        }

        document.getElementById('ev-total-val').textContent = formatCOP(total);
        document.getElementById('ev-btn-comprar').disabled = (subtotal === 0);
      }

      // Código promocional
      const promoBtn = document.getElementById('promo-aplicar');
      if (promoBtn) {
        promoBtn.addEventListener('click', () => {
          const codigo = document.getElementById('promo-codigo').value.trim();
          const msg = document.getElementById('promo-msg');
          if (!codigo) {
            msg.textContent = '';
            return;
          }
          msg.className = 'ev-promo-msg ev-promo-msg--info';
          msg.textContent = 'Validando código…';

          fetch('/page/evento/validarpromo', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'codigo=' + encodeURIComponent(codigo) + '&evento_id=' + EVENTO_ID,
          }).then(r => r.json()).then(data => {
            if (!data.ok) {
              msg.className = 'ev-promo-msg ev-promo-msg--error';
              msg.textContent = data.mensaje;
              promoAplicado = null;
              descuentoAplicado = 0;
              recalcular();
              return;
            }
            promoAplicado = data;
            let subtotal = 0;
            BOLETAS.forEach((b, i) => {
              const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
              if (qty) subtotal += getPrecioUnitario(b) * qty;
            });
            if (palcoSelId && palcoSelPrecio > 0) subtotal += palcoSelPrecio;
            descuentoAplicado = data.porcentaje > 0 ? Math.round(subtotal * data.porcentaje / 100) : data.valor;
            msg.className = 'ev-promo-msg ev-promo-msg--ok';
            msg.textContent = data.mensaje;
            recalcular();
          }).catch(() => {
            const msg = document.getElementById('promo-msg');
            msg.className = 'ev-promo-msg ev-promo-msg--error';
            msg.textContent = 'Error al validar el código. Intenta de nuevo.';
          });
        });
      }

      renderBoletas();
      renderPalcos();
      recalcular();

      document.getElementById('ev-btn-comprar').addEventListener('click', () => {
        const nombre = document.getElementById('comp-nombre').value.trim();
        const documento = document.getElementById('comp-documento').value.trim();
        const nacimiento = document.getElementById('comp-nacimiento').value.trim();
        const email = document.getElementById('comp-email').value.trim();
        const vendedor = document.getElementById('comp-vendedor').value.trim();
        const codigoEl = document.getElementById('promo-codigo');
        const codigo = codigoEl ? codigoEl.value.trim() : '';

        if (!nombre || !documento || !nacimiento || !email) {
          mostrarErrorInline('Completa todos los datos del comprador.');
          return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
          mostrarErrorInline('Ingresa un correo electrónico válido.');
          return;
        }

        const boletas = [];
        let subtotal = 0;
        BOLETAS.forEach((b, i) => {
          const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
          if (qty > 0) {
            boletas.push({
              id: b.id,
              cantidad: qty
            });
            subtotal += getPrecioUnitario(b) * qty;
          }
        });

        if (boletas.length === 0) {
          mostrarErrorInline('Selecciona al menos una boleta.');
          return;
        }

        // Validar boletas requeridas por el palco en el frontend
        if (palcoSelId && palcoReqBoletaId > 0 && palcoReqCant > 0) {
          const selReq = boletas.find(b => b.id === palcoReqBoletaId);
          if (!selReq || selReq.cantidad < palcoReqCant) {
            const bNombre = BOLETAS.find(b => b.id === palcoReqBoletaId)?.tipo_nombre ?? 'requerida';
            mostrarErrorInline('Para este palco necesitas al menos ' + palcoReqCant + ' boleta(s) tipo "' + bNombre + '".');
            return;
          }
        }

        if (palcoSelId && palcoSelPrecio > 0) subtotal += palcoSelPrecio;
        const totalFinal = Math.max(0, subtotal - descuentoAplicado);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/page/evento/generarpago';
        const campos = {
          evento_id: EVENTO_ID,
          nombre,
          documento,
          fechanacimiento: nacimiento,
          email,
          vendedor,
          codigo,
          boletas: JSON.stringify(boletas),
          reserva_evento_id: palcoSelId || 0,
          total: totalFinal,
        };
        for (const [key, val] of Object.entries(campos)) {
          const inp = document.createElement('input');
          inp.type = 'hidden';
          inp.name = key;
          inp.value = val;
          form.appendChild(inp);
        }
        document.body.appendChild(form);
        form.submit();
      });
    }
  })();
</script>