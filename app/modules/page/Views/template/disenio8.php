<?php
/*
 * Diseño 8 — Split Media Card
 * Imagen izquierda (fondo cover) + panel de contenido derecha.
 * Ideal para: servicios, características con imagen, artículos con contexto visual.
 *
 * contenido_imagen      → imagen del panel izquierdo
 * contenido_fondo_color → color de acento (hex); default: var(--color-acento)
 * contenido_borde       → 1 = agrega card con borde y sombra
 * contenido_titulo      → título (si contenido_titulo_ver = 1)
 * contenido_descripcion → texto / descripción
 * contenido_archivo     → descarga opcional
 * contenido_enlace      → URL del botón CTA
 * contenido_vermas      → texto del botón CTA
 */
$d8id     = $contenido->contenido_id;
$d8accent = $contenido->contenido_fondo_color ?: 'var(--color-acento, #4f46e5)';
$d8border = $contenido->contenido_borde == '1' ? '1px solid rgba(0,0,0,.09)' : 'none';
$d8shadow = $contenido->contenido_borde == '1' ? '0 4px 24px rgba(0,0,0,.09)' : 'none';
$d8radius = $contenido->contenido_borde == '1' ? '14px' : '0';
?>
<style>
  .d8-<?= $d8id ?> {
    display: flex;
    min-height: 280px;
    border: <?= $d8border ?>;
    border-radius: <?= $d8radius ?>;
    box-shadow: <?= $d8shadow ?>;
    overflow: hidden;
    background: #fff;
  }
  .d8-img-<?= $d8id ?> {
    flex: 0 0 42%;
    position: relative;
    overflow: hidden;
    background: #e9ecef url('/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>') center / cover no-repeat;
    min-height: 220px;
  }
  .d8-img-inner-<?= $d8id ?> {
    position: absolute;
    inset: 0;
    background: url('/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>') center / cover no-repeat;
    transition: transform .55s cubic-bezier(.25,.46,.45,.94);
    will-change: transform;
  }
  .d8-<?= $d8id ?>:hover .d8-img-inner-<?= $d8id ?> {
    transform: scale(1.06);
  }
  .d8-body-<?= $d8id ?> {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 36px 32px;
    gap: 12px;
    min-width: 0;
  }
  .d8-accent-line-<?= $d8id ?> {
    width: 36px;
    height: 3px;
    border-radius: 2px;
    background: <?= $d8accent ?>;
    margin-bottom: 2px;
  }
  .d8-title-<?= $d8id ?> {
    font-size: clamp(1rem, 2vw, 1.3rem);
    font-weight: 800;
    line-height: 1.25;
    color: var(--color-texto, #1f2a37);
    margin: 0;
    letter-spacing: -.01em;
  }
  .d8-desc-<?= $d8id ?> {
    font-size: .9rem;
    line-height: 1.7;
    color: var(--color-texto-suave, #555);
    margin: 0;
  }
  .d8-footer-<?= $d8id ?> {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 4px;
  }
  .d8-dl-<?= $d8id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .82rem;
    color: <?= $d8accent ?>;
    text-decoration: none;
    font-weight: 600;
    transition: opacity .2s ease;
  }
  .d8-dl-<?= $d8id ?>:hover { opacity: .75; text-decoration: none; }
  .d8-cta-<?= $d8id ?> {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: <?= $d8accent ?>;
    color: #fff;
    font-size: .82rem;
    font-weight: 700;
    padding: 9px 22px;
    border-radius: 30px;
    text-decoration: none;
    letter-spacing: .03em;
    text-transform: uppercase;
    transition: opacity .22s ease, gap .22s ease;
  }
  .d8-cta-<?= $d8id ?>:hover {
    opacity: .85;
    color: #fff;
    text-decoration: none;
    gap: 10px;
  }
  @media (max-width: 640px) {
    .d8-<?= $d8id ?> { flex-direction: column; }
    .d8-img-<?= $d8id ?> { flex: 0 0 210px; min-height: 210px; }
    .d8-body-<?= $d8id ?> { padding: 24px 20px; }
  }
</style>

<div class="caja-contenido-simple design-eight d8-<?= $d8id ?>">
  <?php if ($contenido->contenido_imagen): ?>
    <div class="d8-img-<?= $d8id ?>" role="img" aria-label="<?= htmlspecialchars($contenido->contenido_titulo) ?>">
      <div class="d8-img-inner-<?= $d8id ?>"></div>
    </div>
  <?php endif; ?>
  <div class="d8-body-<?= $d8id ?>">
    <div class="d8-accent-line-<?= $d8id ?>" aria-hidden="true"></div>
    <?php if ($contenido->contenido_titulo_ver == 1 && $contenido->contenido_titulo): ?>
      <h3 class="d8-title-<?= $d8id ?>"><?= htmlspecialchars($contenido->contenido_titulo) ?></h3>
    <?php endif; ?>
    <?php if ($contenido->contenido_descripcion): ?>
      <div class="d8-desc-<?= $d8id ?>"><?= $contenido->contenido_descripcion ?></div>
    <?php endif; ?>
    <?php if ($contenido->contenido_archivo || $contenido->contenido_enlace): ?>
      <div class="d8-footer-<?= $d8id ?>">
        <?php if ($contenido->contenido_archivo): ?>
          <a href="/files/<?= htmlspecialchars($contenido->contenido_archivo) ?>"
            target="_blank" rel="noopener noreferrer" class="d8-dl-<?= $d8id ?>">
            <i class="fas fa-download" aria-hidden="true"></i> Descargar
          </a>
        <?php endif; ?>
        <?php if ($contenido->contenido_enlace): ?>
          <a href="<?= htmlspecialchars($contenido->contenido_enlace) ?>"
            <?= $contenido->contenido_enlace_abrir == 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
            class="d8-cta-<?= $d8id ?>">
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
