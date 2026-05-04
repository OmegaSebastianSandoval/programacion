<?php
/*
 * Diseño 11 — Stat / Número Destacado (con animación de contador)
 * Número grande centrado + etiqueta + ícono opcional. Contador animado al entrar en pantalla.
 * Ideal para: "Sobre Nosotros", logros, métricas, contadores, indicadores.
 *
 * contenido_titulo      → el número / stat a mostrar (ej: "500", "98", "15")
 *                         Puede llevar prefijo/sufijo: "$ 500", "98 %", "15+"
 *                         El sistema detecta el número e intenta animarlo.
 * contenido_descripcion → la etiqueta descriptiva (ej: "Clientes satisfechos")
 * contenido_imagen      → ícono/logo pequeño (64×64, opcional)
 * contenido_fondo_color → color de acento del número e ícono (hex); default acento global
 * contenido_borde       → 1 = fondo degradado sutil en lugar de blanco puro
 * contenido_enlace      → enlace opcional al hacer clic en la card
 * contenido_vermas      → texto del botón CTA (opcional; si no hay, la card entera es clicable)
 */
$d11id     = $contenido->contenido_id;
$d11accent = $contenido->contenido_fondo_color ?: 'var(--color-acento, #4f46e5)';
$d11grad   = $contenido->contenido_borde == '1';
$d11bg     = $d11grad
  ? 'linear-gradient(145deg, #f8f9ff 0%, #fff 100%)'
  : '#fff';

// Separar el número del texto del título para animarlo
// Ej: "500+" → número=500, sufijo="+"   "$ 1.200" → número=1200, prefijo="$ "
$d11raw    = trim($contenido->contenido_titulo ?? '');
$d11num    = 0;
$d11prefix = '';
$d11suffix = '';
if (preg_match('/^(.*?)(\d[\d.,]*)(.*)$/', $d11raw, $m)) {
    $d11prefix = $m[1];
    $d11num    = (int) str_replace(['.', ','], '', $m[2]);
    $d11suffix = $m[3];
} else {
    $d11prefix = $d11raw; // texto puro, sin número
}
$d11hasNum = $d11num > 0;
?>
<style>
  .d11-<?= $d11id ?> {
    background: <?= $d11bg ?>;
    border-radius: 16px;
    padding: 40px 28px 34px;
    text-align: center;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    transition: box-shadow .3s ease, transform .3s ease;
  }
  .d11-<?= $d11id ?>:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,.1);
    transform: translateY(-4px);
  }
  /* Fondo decorativo */
  .d11-<?= $d11id ?>::before {
    content: '';
    position: absolute;
    bottom: -30px;
    right: -30px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: <?= $d11accent ?>;
    opacity: .05;
    pointer-events: none;
  }
  .d11-<?= $d11id ?>::after {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: <?= $d11accent ?>;
    opacity: .04;
    pointer-events: none;
  }
  .d11-icon-wrap-<?= $d11id ?> {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: <?= $d11accent ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
    flex-shrink: 0;
    position: relative;
  }
  .d11-icon-img-<?= $d11id ?> {
    width: 36px;
    height: 36px;
    object-fit: contain;
    filter: brightness(0) invert(1);
  }
  .d11-accent-top-<?= $d11id ?> {
    width: 44px;
    height: 4px;
    background: <?= $d11accent ?>;
    border-radius: 2px;
    position: relative;
  }
  .d11-number-<?= $d11id ?> {
    display: block;
    font-size: clamp(2.4rem, 5vw, 3.4rem);
    font-weight: 900;
    line-height: 1;
    color: <?= $d11accent ?>;
    letter-spacing: -.03em;
    position: relative;
    font-variant-numeric: tabular-nums;
  }
  .d11-label-<?= $d11id ?> {
    font-size: .92rem;
    color: var(--color-texto-suave, #666);
    line-height: 1.5;
    margin: 4px 0 0;
    position: relative;
  }
  .d11-cta-<?= $d11id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    font-size: .8rem;
    font-weight: 700;
    color: <?= $d11accent ?>;
    text-decoration: none;
    letter-spacing: .03em;
    transition: gap .2s ease, opacity .2s ease;
  }
  .d11-cta-<?= $d11id ?>:hover {
    opacity: .75;
    gap: 9px;
    text-decoration: none;
    color: <?= $d11accent ?>;
  }
</style>

<?php
$d11wrapTag   = $contenido->contenido_enlace && !$contenido->contenido_vermas ? 'a' : 'div';
$d11wrapAttrs = '';
if ($d11wrapTag === 'a') {
    $d11wrapAttrs = 'href="' . htmlspecialchars($contenido->contenido_enlace) . '"'
        . ($contenido->contenido_enlace_abrir == 1 ? ' target="_blank" rel="noopener noreferrer"' : '');
}
?>
<<?= $d11wrapTag ?> <?= $d11wrapAttrs ?> class="caja-contenido-simple design-eleven d11-<?= $d11id ?>"
  <?php if ($d11hasNum): ?>
    data-d11-target="<?= $d11num ?>"
    data-d11-prefix="<?= htmlspecialchars($d11prefix) ?>"
    data-d11-suffix="<?= htmlspecialchars($d11suffix) ?>"
  <?php endif; ?>>

  <?php if ($contenido->contenido_imagen): ?>
    <div class="d11-icon-wrap-<?= $d11id ?>" aria-hidden="true">
      <img class="d11-icon-img-<?= $d11id ?>"
        src="/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>"
        alt="">
    </div>
  <?php else: ?>
    <div class="d11-accent-top-<?= $d11id ?>" aria-hidden="true"></div>
  <?php endif; ?>

  <?php if ($contenido->contenido_titulo_ver == 1 && $d11raw): ?>
    <?php if ($d11hasNum): ?>
      <span class="d11-number-<?= $d11id ?>" aria-label="<?= htmlspecialchars($d11raw) ?>">
        <?= htmlspecialchars($d11prefix) ?><span class="d11-count-<?= $d11id ?>">0</span><?= htmlspecialchars($d11suffix) ?>
      </span>
    <?php else: ?>
      <span class="d11-number-<?= $d11id ?>"><?= htmlspecialchars($d11raw) ?></span>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($contenido->contenido_descripcion): ?>
    <div class="d11-label-<?= $d11id ?>"><?= strip_tags($contenido->contenido_descripcion) ?></div>
  <?php endif; ?>

  <?php if ($contenido->contenido_enlace && $contenido->contenido_vermas): ?>
    <a href="<?= htmlspecialchars($contenido->contenido_enlace) ?>"
      <?= $contenido->contenido_enlace_abrir == 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
      class="d11-cta-<?= $d11id ?>">
      <?= htmlspecialchars($contenido->contenido_vermas) ?>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  <?php endif; ?>

</<?= $d11wrapTag ?>>

<?php if ($d11hasNum): ?>
<script>
(function() {
  var el = document.querySelector('.d11-<?= $d11id ?>[data-d11-target]');
  if (!el) return;
  var countEl = el.querySelector('.d11-count-<?= $d11id ?>');
  if (!countEl) return;
  var target   = +el.dataset.d11Target;
  var duration = Math.min(2000, Math.max(800, target * 1.5));
  var started  = false;

  function animate(start) {
    var startTs = null;
    function step(ts) {
      if (!startTs) startTs = ts;
      var progress = Math.min((ts - startTs) / duration, 1);
      // easeOutExpo
      var ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
      var val = Math.round(ease * target);
      // Formatear con separador de miles
      countEl.textContent = val.toLocaleString('es');
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting && !started) {
          started = true;
          animate();
          obs.disconnect();
        }
      });
    }, { threshold: 0.35 });
    obs.observe(el);
  } else {
    animate();
  }
})();
</script>
<?php endif; ?>
