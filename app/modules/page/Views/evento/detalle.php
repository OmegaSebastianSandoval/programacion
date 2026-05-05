<?php
$ev   = $this->evento;
$sede = $this->sede;
$errorMsg = ($_GET['error']) ? htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8') : '';

$meses = [
  '',
  'enero',
  'febrero',
  'marzo',
  'abril',
  'mayo',
  'junio',
  'julio',
  'agosto',
  'septiembre',
  'octubre',
  'noviembre',
  'diciembre'
];

$fechaParts = explode('-', substr($ev->evento_fecha, 0, 10));
$fechaStr = (int) $fechaParts[2] . ' de ' . $meses[(int) $fechaParts[1]] . ' de ' . $fechaParts[0];
$hora = $ev->evento_hora ? substr($ev->evento_hora, 0, 5) : '';

$sedeColor = ($sede && $sede->sede_color) ? $sede->sede_color : '#0b4b3e';
$r = hexdec(substr($sedeColor, 1, 2));
$g = hexdec(substr($sedeColor, 3, 2));
$b = hexdec(substr($sedeColor, 5, 2));
$sedeTextColor = (($r * 299 + $g * 587 + $b * 114) / 1000 >= 128) ? '#1a1a1a' : '#ffffff';

$boletasArr = json_decode($this->boletasJson, true) ?: [];
$eventoTipo = $ev->evento_tipo;

$precioMinimo = null;
$hayBoletasDisponibles = false;
foreach ($boletasArr as $bItem) {
  if ($bItem['saldo'] <= 0) continue;
  $hayBoletasDisponibles = true;
  $precioRef = ($eventoTipo === 'reserva') ? $bItem['precioadicional'] : $bItem['precio'];
  if ($precioMinimo === null || $precioRef < $precioMinimo) {
    $precioMinimo = $precioRef;
  }
}
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
      <img class="ev-img-main" src="/images/<?= ($ev->evento_imagen) ?>"
        alt="<?= ($ev->evento_nombre) ?>">
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
            <i class="fas fa-clock"></i> <?= ($hora) ?>
          </span>
        <?php endif; ?>
        <?php if ($sede && $sede->sede_nombre): ?>
          <span class="ev-badge ev-badge-sede" style="background:<?= $sedeColor ?>;color:<?= $sedeTextColor ?>">
            <i class="fas fa-map-marker-alt"></i> <?= ($sede->sede_nombre) ?>
          </span>
        <?php endif; ?>
      </div>

      <!-- Alerta de tipo de cobro -->
      <?php
        if ($eventoTipo === 'reserva'):
      ?>
        <div class="ev-tipo-alerta ev-tipo-alerta--reserva">
          <i class="fas fa-hand-holding-usd"></i>
          <div>
            <strong>Solo se cobra la reserva</strong>
            <span>Pagas el valor de reserva para asegurar tu cupo. El saldo restante lo cancelas en taquilla el día del evento.</span>
          </div>
        </div>
      <?php elseif ($eventoTipo === 'reservayboleteria'): ?>
        <div class="ev-tipo-alerta ev-tipo-alerta--mixto">
          <i class="fas fa-layer-group"></i>
          <div>
            <strong>Se cobra boleta + reserva</strong>
            <span>El precio incluye el valor de la boleta y un valor de reserva, ambos se cobran al momento de la compra.</span>
          </div>
        </div>
      <?php else: ?>
        <div class="ev-tipo-alerta ev-tipo-alerta--boleteria">
          <i class="fas fa-ticket-alt"></i>
          <div>
            <strong>Se cobra el valor total de la boleta</strong>
            <span>Pagas el precio completo de tu entrada. Recibirás tu boleta electrónica al finalizar la compra.</span>
          </div>
        </div>
      <?php endif; ?>

      <!-- Título -->
      <h1 class="ev-info-title"><?= ($ev->evento_nombre) ?></h1>

      <!-- Descripción -->
      <?php if ($ev->evento_descripcion): ?>
        <div class="ev-info-desc">
          <?= $ev->evento_descripcion ?>
        </div>
      <?php endif; ?>

      <!-- Preview de tipos de boleta -->
      <?php if (($boletasArr)): ?>
        <div class="ev-info-boletas-preview">
          <p class="ev-info-section-label"><i class="fas fa-ticket-alt me-1"></i> Boletas disponibles</p>
          <div class="ev-info-boletas-list">
            <?php foreach ($boletasArr as $bItem): ?>
              <div class="ev-info-boleta-item<?= $bItem['disponibles'] <= 0 ? ' ev-info-boleta-agotada' : '' ?>">
                <div class="ev-info-boleta-left">
                  <span class="ev-info-boleta-nombre"><?= ($bItem['tipo_nombre']) ?></span>
                  <?php if ($bItem['disponibles'] <= 0): ?>
                    <span class="ev-badge-agotada">Agotada</span>
                  <?php else: ?>
                    <span class="ev-info-boleta-saldo"><?= $bItem['disponibles'] ?> disponibles</span>
                  <?php endif; ?>
                </div>
                <div class="ev-info-boleta-precios">
                  <?php if ($eventoTipo === 'reservayboleteria'): ?>
                    <span class="ev-info-boleta-precio">$ <?= number_format($bItem['precio'], 0, ',', '.') ?></span>
                    <span class="ev-info-boleta-precio-reserva">+ Reserva $ <?= number_format($bItem['precioadicional'], 0, ',', '.') ?></span>
                  <?php elseif ($eventoTipo === 'reserva'): ?>
                    <span class="ev-info-boleta-precio">$ <?= number_format($bItem['precioadicional'], 0, ',', '.') ?></span>
                  <?php else: ?>
                    <span class="ev-info-boleta-precio">$ <?= number_format($bItem['precio'], 0, ',', '.') ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Políticas -->
      <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
        <button class="ev-politicas-btn" data-bs-toggle="modal" data-bs-target="#modalPoliticas">
          <i class="fas fa-file-alt"></i> Ver políticas del evento
        </button>
      <?php endif; ?>

    </div>

    <!-- CTA sticky al fondo del panel -->
    <?php if (!$hayBoletasDisponibles): ?>
      <div class="ev-info-cta ev-info-cta--agotado">
        <div class="ev-agotado-icon"><i class="fas fa-ticket-alt"></i></div>
        <div class="ev-agotado-text">
          <span class="ev-agotado-title">Sin entradas disponibles</span>
          <span class="ev-agotado-sub">Las boletas para este evento se han agotado</span>
        </div>
      </div>
    <?php else: ?>
    <div class="ev-info-cta">
      <?php if ($precioMinimo !== null): ?>
        <span class="ev-info-cta-desde">
          Desde
          <strong>$ <?= number_format($precioMinimo, 0, ',', '.') ?></strong>
        </span>
      <?php endif; ?>
      <div class="ev-cta-wrap">
        <button class="ev-btn-cta" id="ev-btn-cta"
          data-bs-toggle="modal" data-bs-target="#modalCompra"
          <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): echo 'disabled'; endif; ?>>
          <i class="fas fa-ticket-alt"></i> <?= $eventoTipo === 'reserva' ? 'Pagar reserva' : 'Comprar entradas' ?>
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
<?php if ($hayBoletasDisponibles): ?>
<div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ev-modal-compra-dialog">
    <div class="modal-content ev-modal-compra-content">

      <div class="ev-card-header">
        <div class="ev-card-header-info">
          <h2 class="ev-card-header-title"><i class="fas fa-ticket-alt me-2"></i>Compra tu entrada</h2>
          <p class="ev-card-header-evento"><?= ($ev->evento_nombre) ?> — <?= $fechaStr ?></p>
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
          <input type="hidden" id="comp-vendedor" name="vendedor" value="<?= ($this->vendedor) ?>">
        </div>

        <!-- Boletas -->
        <div class="ev-compra-section">
          <p class="ev-compra-section-title">Selecciona tus boletas <span
              style="font-weight:400;text-transform:none;color:#bbb">(máx. 20 por persona)</span></p>
          <div id="ev-boletas-list"></div>
          <p class="ev-boletas-nota" id="ev-sin-boletas" style="display:none">No hay boletas disponibles para este evento.</p>
        </div>

        <!-- Código promocional -->
        <div class="ev-compra-section">
          <p class="ev-compra-section-title">Código promocional</p>
          <div class="ev-promo-row">
            <input type="text" class="ev-input" id="promo-codigo" placeholder="Código promocional" autocomplete="off">
            <button class="ev-promo-btn" id="promo-aplicar">Aplicar</button>
          </div>
          <p class="ev-promo-msg" id="promo-msg"></p>
        </div>

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
              Este es el valor de reserva. El saldo restante se cancela en taquilla.
            </p>
          <?php elseif ($eventoTipo === 'reservayboleteria'): ?>
            <p class="ev-resumen-nota">
              <i class="fas fa-info-circle"></i>
              El total incluye el valor de la boleta más el pago de reserva.
            </p>
          <?php endif; ?>
        </div>

      </div>

      <div class="modal-footer p-0 border-0">
        <button class="ev-btn-comprar" id="ev-btn-comprar" disabled>
          <i class="fas fa-lock"></i> Continuar con la compra
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
          <h5 class="modal-title"><?= ($ev->evento_titulo_politica ?: 'Políticas del evento') ?></h5>
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
          <button type="button" class="ev-politicas-aceptar" id="ev-politicas-aceptar" disabled
            data-bs-dismiss="modal">
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
    const EVENTO_ID   = <?= (int) $ev->evento_id ?>;
    const BOLETAS = <?= $this->boletasJson ?>;

    const formatCOP = n => '$ ' + Math.round(n).toLocaleString('es-CO');

    function getPrecioUnitario(b) {
      if (EVENTO_TIPO === 'reserva') return b.precioadicional;
      if (EVENTO_TIPO === 'reservayboleteria') return b.precio + b.precioadicional;
      return b.precio;
    }

    function renderBoletas() {
      const container = document.getElementById('ev-boletas-list');
      const sinBoletas = document.getElementById('ev-sin-boletas');

      if (!BOLETAS || BOLETAS.length === 0) {
        sinBoletas.style.display = '';
        return;
      }

      BOLETAS.forEach((b, i) => {
        const agotada = b.disponibles <= 0;
        const maxSel  = Math.min(20, b.disponibles);

        let precioHtml = '';
        if (EVENTO_TIPO === 'reservayboleteria') {
          precioHtml =
            `<span class="ev-chip-precio-detalle">` +
            `<span>Boleta: ${formatCOP(b.precio)}</span>` +
            `<span>Reserva: ${formatCOP(b.precioadicional)}</span>` +
            `<strong>Total c/u: ${formatCOP(b.precio + b.precioadicional)}</strong>` +
            `</span>`;
        } else if (EVENTO_TIPO === 'reserva') {
          precioHtml =
            `<span class="ev-chip-precio-detalle">` +
            `<strong>Reserva: ${formatCOP(b.precioadicional)}</strong>` +
            `</span>`;
        } else {
          precioHtml = `<span class="ev-chip-precio">${formatCOP(b.precio)}</span>`;
        }

        const row = document.createElement('div');
        row.className = 'ev-boleta-row' + (agotada ? ' ev-boleta-agotada' : '');
        row.innerHTML =
          `<div class="ev-boleta-info">` +
          `<span class="ev-boleta-nombre">${b.tipo_nombre}</span>` +
          precioHtml +
          (agotada
            ? `<span class="ev-badge-agotada">Agotada</span>`
            : `<span class="ev-boleta-saldo">${b.disponibles} disponibles</span>`) +
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

    let descuentoAplicado = 0;
    let promoAplicado = null;

    function recalcular() {
      let subtotal = 0;
      const lines = [];

      BOLETAS.forEach((b, i) => {
        const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
        if (!qty) return;
        const unitario = getPrecioUnitario(b);
        subtotal += unitario * qty;
        lines.push(
          `<div class="ev-res-line">` +
          `<span>${b.tipo_nombre} × ${qty}</span>` +
          `<span>${formatCOP(unitario * qty)}</span>` +
          `</div>`
        );
      });

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

    document.getElementById('promo-aplicar').addEventListener('click', () => {
      const codigo = document.getElementById('promo-codigo').value.trim();
      const msg    = document.getElementById('promo-msg');
      if (!codigo) { msg.textContent = ''; return; }

      msg.className = 'ev-promo-msg ev-promo-msg--info';
      msg.textContent = 'Validando código…';

      fetch('/page/evento/validarpromo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'codigo=' + encodeURIComponent(codigo) + '&evento_id=' + EVENTO_ID,
      })
        .then(r => r.json())
        .then(data => {
          if (!data.ok) {
            msg.className = 'ev-promo-msg ev-promo-msg--error';
            msg.textContent = data.mensaje;
            promoAplicado = null;
            descuentoAplicado = 0;
            recalcular();
            return;
          }

          promoAplicado = data;

          // Calcular descuento sobre el subtotal actual
          let subtotal = 0;
          BOLETAS.forEach((b, i) => {
            const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
            if (qty) subtotal += getPrecioUnitario(b) * qty;
          });

          if (data.porcentaje > 0) {
            descuentoAplicado = Math.round(subtotal * data.porcentaje / 100);
          } else {
            descuentoAplicado = data.valor;
          }

          msg.className = 'ev-promo-msg ev-promo-msg--ok';
          msg.textContent = data.mensaje;
          recalcular();
        })
        .catch(() => {
          msg.className = 'ev-promo-msg ev-promo-msg--error';
          msg.textContent = 'Error al validar el código. Intenta de nuevo.';
        });
    });

    renderBoletas();
    recalcular();

    // Política de aceptación: desbloquea el botón de compra
    const checkAcepto = document.getElementById('ev-acepto-politicas');
    const btnAceptar  = document.getElementById('ev-politicas-aceptar');
    const btnCta      = document.getElementById('ev-btn-cta');
    const ctaHint     = document.getElementById('ev-cta-hint');

    if (checkAcepto && btnCta) {
      checkAcepto.addEventListener('change', () => {
        btnAceptar.disabled = !checkAcepto.checked;
      });

      btnAceptar.addEventListener('click', () => {
        btnCta.disabled = false;
        if (ctaHint) ctaHint.style.display = 'none';
      });
    }

    // Submit de compra
    document.getElementById('ev-btn-comprar').addEventListener('click', () => {
      const nombre     = document.getElementById('comp-nombre').value.trim();
      const documento  = document.getElementById('comp-documento').value.trim();
      const nacimiento = document.getElementById('comp-nacimiento').value.trim();
      const email      = document.getElementById('comp-email').value.trim();
      const vendedor   = document.getElementById('comp-vendedor').value.trim();
      const codigoEl   = document.getElementById('promo-codigo');
      const codigo     = codigoEl ? codigoEl.value.trim() : '';

      if (!nombre || !documento || !nacimiento || !email) {
        mostrarErrorInline('Completa todos los datos del comprador.');
        return;
      }
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        mostrarErrorInline('Ingresa un correo electrónico válido.');
        return;
      }

      const boletas = [];
      let subtotal  = 0;
      BOLETAS.forEach((b, i) => {
        const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
        if (qty > 0) {
          boletas.push({ id: b.id, cantidad: qty });
          subtotal += getPrecioUnitario(b) * qty;
        }
      });

      if (boletas.length === 0) {
        mostrarErrorInline('Selecciona al menos una boleta.');
        return;
      }

      const totalFinal = Math.max(0, subtotal - descuentoAplicado);

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/page/evento/generarpago';

      const campos = {
        evento_id:      EVENTO_ID,
        nombre,
        documento,
        fechanacimiento: nacimiento,
        email,
        vendedor,
        codigo,
        boletas:        JSON.stringify(boletas),
        total:          totalFinal,
      };

      for (const [key, val] of Object.entries(campos)) {
        const inp  = document.createElement('input');
        inp.type   = 'hidden';
        inp.name   = key;
        inp.value  = val;
        form.appendChild(inp);
      }

      document.body.appendChild(form);
      form.submit();
    });

    function mostrarErrorInline(msg) {
      let el = document.getElementById('ev-error-inline');
      if (!el) {
        el = document.createElement('p');
        el.id        = 'ev-error-inline';
        el.className = 'ev-error-inline';
        const footer = document.querySelector('.modal-footer');
        if (footer) footer.insertAdjacentElement('beforebegin', el);
      }
      el.textContent = msg;
      el.style.display = '';
      setTimeout(() => { if (el) el.style.display = 'none'; }, 4000);
    }

    // Auto-cerrar alerta de error de URL al cargar
    const alertaEl = document.getElementById('ev-alerta-error');
    if (alertaEl) {
      setTimeout(() => alertaEl.classList.add('ev-alerta-saliendo'), 4500);
      setTimeout(() => alertaEl.remove(), 5000);
    }
  })();
</script>
