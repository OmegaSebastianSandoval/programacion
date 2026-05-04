<?php
$ev = $this->evento;
$sede = $this->sede;

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
$precioMinimo = null;
foreach ($boletasArr as $bItem) {
  if ($bItem['saldo'] > 0 && ($precioMinimo === null || $bItem['precio'] < $precioMinimo)) {
    $precioMinimo = $bItem['precio'];
  }
}
?>

<div class="ev-page">

  <!-- ===================== PANEL IZQUIERDO: IMAGEN ===================== -->
  <div class="ev-panel-img">
    <?php if ($ev->evento_imagen): ?>
      <img class="ev-img-main" src="/images/<?= htmlspecialchars($ev->evento_imagen) ?>"
        alt="<?= htmlspecialchars($ev->evento_nombre) ?>">
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
            <i class="fas fa-clock"></i> <?= htmlspecialchars($hora) ?>
          </span>
        <?php endif; ?>
        <?php if ($sede && $sede->sede_nombre): ?>
          <span class="ev-badge ev-badge-sede" style="background:<?= $sedeColor ?>;color:<?= $sedeTextColor ?>">
            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($sede->sede_nombre) ?>
          </span>
        <?php endif; ?>
      </div>

      <!-- Título -->
      <h1 class="ev-info-title"><?= htmlspecialchars($ev->evento_nombre) ?></h1>

      <!-- Descripción -->
      <?php if ($ev->evento_descripcion): ?>
        <div class="ev-info-desc">
          <?= $ev->evento_descripcion ?>
        </div>
      <?php endif; ?>

      <!-- Preview de tipos de boleta -->
      <?php if (!empty($boletasArr)): ?>
        <div class="ev-info-boletas-preview">
          <p class="ev-info-section-label"><i class="fas fa-ticket-alt me-1"></i> Boletas disponibles</p>
          <div class="ev-info-boletas-list">
            <?php foreach ($boletasArr as $bItem): ?>
              <div class="ev-info-boleta-item<?= $bItem['saldo'] <= 0 ? ' ev-info-boleta-agotada' : '' ?>">
                <div class="ev-info-boleta-left">
                  <span class="ev-info-boleta-nombre"><?= htmlspecialchars($bItem['tipo_nombre']) ?></span>
                  <?php if ($bItem['saldo'] <= 0): ?>
                    <span class="ev-badge-agotada">Agotada</span>
                  <?php else: ?>
                    <span class="ev-info-boleta-saldo"><?= $bItem['saldo'] ?> disponibles</span>
                  <?php endif; ?>
                </div>
                <span class="ev-info-boleta-precio">$ <?= number_format($bItem['precio'], 0, ',', '.') ?></span>
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
          <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>disabled<?php endif; ?>>
          <i class="fas fa-ticket-alt"></i> Comprar entradas
        </button>
        <?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
          <p class="ev-cta-hint" id="ev-cta-hint">
            <i class="fas fa-shield-alt"></i>
            Primero debes <button type="button" class="ev-cta-hint-link" data-bs-toggle="modal" data-bs-target="#modalPoliticas">leer y aceptar las políticas</button>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- ========== MODAL DE COMPRA ========== -->
<div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ev-modal-compra-dialog">
    <div class="modal-content ev-modal-compra-content">

      <div class="ev-card-header">
        <div class="ev-card-header-info">
          <h2 class="ev-card-header-title"><i class="fas fa-ticket-alt me-2"></i>Compra tu entrada</h2>
          <p class="ev-card-header-evento"><?= htmlspecialchars($ev->evento_nombre) ?> — <?= $fechaStr ?></p>
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
              <input type="text" class="ev-input" id="comp-nombre" placeholder="Tu nombre completo">
            </div>
            <div class="ev-form-group">
              <label>Documento</label>
              <input type="text" class="ev-input" id="comp-documento" placeholder="Número de doc.">
            </div>
            <div class="ev-form-group">
              <label>Fecha de nacimiento</label>
              <input type="date" class="ev-input" id="comp-nacimiento">
            </div>
            <div class="ev-form-group ev-form-group--full">
              <label>Correo electrónico</label>
              <input type="email" class="ev-input" id="comp-email" placeholder="correo@ejemplo.com">
            </div>
          </div>
          <input type="hidden" id="comp-vendedor" name="vendedor" value="<?= htmlspecialchars($this->vendedor) ?>">
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
            <input type="text" class="ev-input" id="promo-codigo" placeholder="CÓDIGO">
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
          <?php if ($ev->evento_tipo === 'reserva' || $ev->evento_tipo === 'reservayboleteria'): ?>
            <p class="ev-resumen-nota">
              <i class="fas fa-info-circle"></i>
              El valor de reserva asegura tu cupo. El saldo restante se cancela en taquilla.
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

<!-- ========== MODAL POLÍTICAS ========== -->
<?php if ($ev->evento_titulo_politica || $ev->evento_descripcion_politica): ?>
  <div class="modal fade" id="modalPoliticas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content ev-politicas-modal">
        <div class="modal-header">
          <h5 class="modal-title"><?= htmlspecialchars($ev->evento_titulo_politica ?: 'Políticas del evento') ?></h5>
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
    const EVENTO_PORCENTAJE = <?= (float) $ev->evento_porcentaje_pagoinicial ?>;
    const BOLETAS = <?= $this->boletasJson ?>;

    const formatCOP = n => '$ ' + Math.round(n).toLocaleString('es-CO');

    function calcPrecioUnitario(precio) {
      if (EVENTO_TIPO === 'boleteria') return precio;
      if (EVENTO_TIPO === 'reserva') return precio * (EVENTO_PORCENTAJE / 100);
      if (EVENTO_TIPO === 'reservayboleteria') return precio + precio * (EVENTO_PORCENTAJE / 100);
      return precio;
    }

    function renderBoletas() {
      const container = document.getElementById('ev-boletas-list');
      const sinBoletas = document.getElementById('ev-sin-boletas');

      if (!BOLETAS || BOLETAS.length === 0) {
        sinBoletas.style.display = '';
        return;
      }

      BOLETAS.forEach((b, i) => {
        const unitario = calcPrecioUnitario(b.precio);
        const agotada = b.saldo <= 0;

        let precioHtml = '';
        if (EVENTO_TIPO === 'reservayboleteria') {
          const reserva = b.precio * (EVENTO_PORCENTAJE / 100);
          precioHtml =
            `<span class="ev-chip-precio-detalle">` +
            `<span>Boleta: ${formatCOP(b.precio)}</span>` +
            `<span>+ Reserva (${EVENTO_PORCENTAJE}%): ${formatCOP(reserva)}</span>` +
            `<strong>Total c/u: ${formatCOP(unitario)}</strong>` +
            `</span>`;
        } else if (EVENTO_TIPO === 'reserva') {
          precioHtml =
            `<span class="ev-chip-precio-detalle">` +
            `<span style="color:#bbb;text-decoration:line-through">${formatCOP(b.precio)}</span>` +
            `<strong>Reserva (${EVENTO_PORCENTAJE}%): ${formatCOP(unitario)}</strong>` +
            `</span>`;
        } else {
          precioHtml = `<span class="ev-chip-precio">${formatCOP(unitario)}</span>`;
        }

        const row = document.createElement('div');
        row.className = 'ev-boleta-row' + (agotada ? ' ev-boleta-agotada' : '');
        row.innerHTML =
          `<div class="ev-boleta-info">` +
          `<span class="ev-boleta-nombre">${b.tipo_nombre}</span>` +
          precioHtml +
          (agotada
            ? `<span class="ev-badge-agotada">Agotada</span>`
            : `<span class="ev-boleta-saldo">${b.saldo} disponibles</span>`) +
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
        let val = parseInt(span.textContent, 10) + dir;
        val = Math.max(0, Math.min(20, Math.min(val, BOLETAS[idx].saldo)));
        span.textContent = val;
        recalcular();
      });
    }

    let descuentoAplicado = 0;

    function recalcular() {
      let subtotal = 0;
      const lines = [];

      BOLETAS.forEach((b, i) => {
        const qty = parseInt(document.getElementById('qty-val-' + i)?.textContent || '0', 10);
        if (!qty) return;
        const unitario = calcPrecioUnitario(b.precio);
        subtotal += unitario * qty;
        lines.push(
          `<div class="ev-res-line">` +
          `<span>${b.tipo_nombre} × ${qty}</span>` +
          `<span>${formatCOP(unitario * qty)}</span>` +
          `</div>`
        );
      });

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
      const msg = document.getElementById('promo-msg');
      if (!codigo) { msg.textContent = ''; return; }
      msg.className = 'ev-promo-msg ev-promo-msg--info';
      msg.textContent = 'Validando código…';
      // TODO: AJAX a backend de validación de códigos promocionales
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
  })();
</script>
