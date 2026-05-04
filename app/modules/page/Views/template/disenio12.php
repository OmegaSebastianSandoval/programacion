<?php
/*
 * Diseño 12 — Feature Item con Barra Lateral
 * Barra de acento izquierda + ícono con fondo + título + descripción.
 * Ideal para: listas de características, ventajas, pasos de proceso, FAQ, servicios.
 *
 * contenido_imagen      → ícono/imagen (48×48, opcional)
 * contenido_fondo_color → color de la barra y del fondo del ícono (hex); default acento global
 * contenido_borde       → 1 = variante "paso numerado" (muestra número secuencial)
 *                         0 = variante estándar con ícono/imagen
 * contenido_titulo      → título de la característica (si contenido_titulo_ver = 1)
 * contenido_descripcion → descripción / detalle
 * contenido_archivo     → descarga opcional
 * contenido_enlace      → enlace / CTA al final
 * contenido_vermas      → texto del botón CTA
 */
$d12id     = $contenido->contenido_id;
$d12accent = $contenido->contenido_fondo_color ?: 'var(--color-acento, #4f46e5)';
$d12step   = $contenido->contenido_borde == '1';

// Para variante numerada: intentar leer número del inicio del título
// O contar el orden del elemento en la página (usamos contenido_id como fallback visual)
$d12num    = '';
if ($d12step && $contenido->contenido_titulo) {
    if (preg_match('/^(\d+)[.\)\-\s]/', $contenido->contenido_titulo, $m)) {
        $d12num = $m[1];
    }
}
?>
<style>
  .d12-<?= $d12id ?> {
    display: flex;
    align-items: flex-start;
    gap: 0;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow .3s ease, transform .3s ease;
  }
  .d12-<?= $d12id ?>:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,.1);
    transform: translateY(-3px);
  }
  .d12-bar-<?= $d12id ?> {
    flex: 0 0 4px;
    align-self: stretch;
    background: <?= $d12accent ?>;
    border-radius: 0;
    transition: flex-basis .25s ease;
  }
  .d12-<?= $d12id ?>:hover .d12-bar-<?= $d12id ?> {
    flex-basis: 6px;
  }
  .d12-inner-<?= $d12id ?> {
    flex: 1;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 22px 22px 22px 20px;
    min-width: 0;
  }
  /* Ícono con imagen */
  .d12-icon-img-wrap-<?= $d12id ?> {
    flex-shrink: 0;
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: <?= $d12accent ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }
  .d12-icon-img-<?= $d12id ?> {
    width: 30px;
    height: 30px;
    object-fit: contain;
    filter: brightness(0) invert(1);
  }
  /* Ícono con número (variante paso) */
  .d12-step-num-<?= $d12id ?> {
    flex-shrink: 0;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: <?= $d12accent ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: -.02em;
  }
  /* Sin ícono: bullet decorativo */
  .d12-bullet-<?= $d12id ?> {
    flex-shrink: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: <?= $d12accent ?>;
    margin-top: 7px;
  }
  .d12-text-<?= $d12id ?> {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .d12-title-<?= $d12id ?> {
    font-size: 1rem;
    font-weight: 800;
    color: var(--color-texto, #1f2a37);
    margin: 0;
    line-height: 1.3;
  }
  .d12-desc-<?= $d12id ?> {
    font-size: .875rem;
    color: var(--color-texto-suave, #555);
    line-height: 1.65;
    margin: 0;
  }
  .d12-footer-<?= $d12id ?> {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 6px;
  }
  .d12-dl-<?= $d12id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .8rem;
    color: var(--color-texto-suave, #666);
    text-decoration: none;
    transition: color .2s ease;
  }
  .d12-dl-<?= $d12id ?>:hover { color: <?= $d12accent ?>; text-decoration: none; }
  .d12-cta-<?= $d12id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .8rem;
    font-weight: 700;
    color: <?= $d12accent ?>;
    text-decoration: none;
    letter-spacing: .02em;
    transition: gap .2s ease, opacity .2s ease;
  }
  .d12-cta-<?= $d12id ?>:hover {
    gap: 9px;
    opacity: .8;
    text-decoration: none;
    color: <?= $d12accent ?>;
  }
</style>

<div class="caja-contenido-simple design-twelve d12-<?= $d12id ?>">
  <div class="d12-bar-<?= $d12id ?>" aria-hidden="true"></div>
  <div class="d12-inner-<?= $d12id ?>">

    <?php if ($d12step && $d12num): ?>
      <div class="d12-step-num-<?= $d12id ?>" aria-hidden="true"><?= htmlspecialchars($d12num) ?></div>
    <?php elseif ($contenido->contenido_imagen): ?>
      <div class="d12-icon-img-wrap-<?= $d12id ?>" aria-hidden="true">
        <img class="d12-icon-img-<?= $d12id ?>"
          src="/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>"
          alt="">
      </div>
    <?php else: ?>
      <div class="d12-bullet-<?= $d12id ?>" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="d12-text-<?= $d12id ?>">
      <?php if ($contenido->contenido_titulo_ver == 1 && $contenido->contenido_titulo): ?>
        <h3 class="d12-title-<?= $d12id ?>"><?= htmlspecialchars($contenido->contenido_titulo) ?></h3>
      <?php endif; ?>
      <?php if ($contenido->contenido_descripcion): ?>
        <div class="d12-desc-<?= $d12id ?>"><?= $contenido->contenido_descripcion ?></div>
      <?php endif; ?>
      <?php if ($contenido->contenido_archivo || $contenido->contenido_enlace): ?>
        <div class="d12-footer-<?= $d12id ?>">
          <?php if ($contenido->contenido_archivo): ?>
            <a href="/files/<?= htmlspecialchars($contenido->contenido_archivo) ?>"
              target="_blank" rel="noopener noreferrer" class="d12-dl-<?= $d12id ?>">
              <i class="fas fa-download" aria-hidden="true"></i> Descargar
            </a>
          <?php endif; ?>
          <?php if ($contenido->contenido_enlace): ?>
            <a href="<?= htmlspecialchars($contenido->contenido_enlace) ?>"
              <?= $contenido->contenido_enlace_abrir == 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
              class="d12-cta-<?= $d12id ?>">
              <?= htmlspecialchars($contenido->contenido_vermas ?: 'Ver Más') ?>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
</div>
