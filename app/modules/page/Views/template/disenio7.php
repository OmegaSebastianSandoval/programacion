<?php
/*
 * Diseño 7 — Hero Card con Overlay
 * Imagen full con gradiente oscuro + texto encima. Ken Burns en hover.
 * Ideal para: tarjetas destacadas, features visuales, portafolio, secciones hero.
 *
 * contenido_imagen      → imagen de fondo
 * contenido_fondo_color → color/opacidad del overlay (rgba o hex); default: rgba(0,0,0,0.55)
 * contenido_titulo      → título principal (si contenido_titulo_ver = 1)
 * contenido_descripcion → subtítulo / extracto (truncado a 3 líneas)
 * contenido_enlace      → URL del botón CTA
 * contenido_vermas      → texto del botón CTA
 * contenido_enlace_abrir → 1 = target _blank
 */
$d7id = $contenido->contenido_id;
$d7ov = $contenido->contenido_fondo_color ?: 'rgba(0,0,0,0.58)';
?>
<style>
  .d7-<?= $d7id ?> {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    min-height: 380px;
    border-radius: 14px;
    overflow: hidden;
    background: #1a1a2e;
    transition: box-shadow .4s ease;
  }
  .d7-<?= $d7id ?>:hover {
    box-shadow: 0 28px 72px rgba(0,0,0,.28);
  }
  .d7-bg-<?= $d7id ?> {
    position: absolute;
    inset: 0;
    background: url('/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>') center / cover no-repeat;
    transition: transform .65s cubic-bezier(.25,.46,.45,.94);
    will-change: transform;
  }
  .d7-<?= $d7id ?>:hover .d7-bg-<?= $d7id ?> {
    transform: scale(1.07);
  }
  .d7-ov-<?= $d7id ?> {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      to top,
      <?= $d7ov ?> 0%,
      rgba(0,0,0,.22) 55%,
      rgba(0,0,0,.04) 100%
    );
    transition: opacity .4s ease;
  }
  .d7-<?= $d7id ?>:hover .d7-ov-<?= $d7id ?> {
    opacity: .9;
  }
  .d7-body-<?= $d7id ?> {
    position: relative;
    padding: 0 28px 32px;
    color: #fff;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .d7-title-<?= $d7id ?> {
    font-size: clamp(1.05rem, 2.4vw, 1.55rem);
    font-weight: 800;
    line-height: 1.22;
    margin: 0;
    letter-spacing: -.015em;
    text-shadow: 0 2px 10px rgba(0,0,0,.5);
  }
  .d7-desc-<?= $d7id ?> {
    font-size: .875rem;
    line-height: 1.65;
    color: rgba(255,255,255,.82);
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .d7-cta-<?= $d7id ?> {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    width: fit-content;
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    border: 1.5px solid rgba(255,255,255,.48);
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    padding: 9px 22px;
    border-radius: 30px;
    text-decoration: none;
    transition: background .22s ease, border-color .22s ease, gap .22s ease;
    margin-top: 4px;
  }
  .d7-cta-<?= $d7id ?>:hover {
    background: rgba(255,255,255,.28);
    border-color: rgba(255,255,255,.82);
    color: #fff;
    text-decoration: none;
    gap: 12px;
  }
  @media (max-width: 576px) {
    .d7-<?= $d7id ?> { min-height: 300px; }
    .d7-body-<?= $d7id ?> { padding: 0 20px 24px; }
  }
</style>

<div class="caja-contenido-simple design-seven d7-<?= $d7id ?>">
  <div class="d7-bg-<?= $d7id ?>" role="img" aria-label="<?= htmlspecialchars($contenido->contenido_titulo) ?>"></div>
  <div class="d7-ov-<?= $d7id ?>" aria-hidden="true"></div>
  <div class="d7-body-<?= $d7id ?>">
    <?php if ($contenido->contenido_titulo_ver == 1 && $contenido->contenido_titulo): ?>
      <h3 class="d7-title-<?= $d7id ?>"><?= htmlspecialchars($contenido->contenido_titulo) ?></h3>
    <?php endif; ?>
    <?php if ($contenido->contenido_descripcion): ?>
      <p class="d7-desc-<?= $d7id ?>"><?= strip_tags($contenido->contenido_descripcion) ?></p>
    <?php endif; ?>
    <?php if ($contenido->contenido_enlace): ?>
      <a href="<?= htmlspecialchars($contenido->contenido_enlace) ?>"
        <?= $contenido->contenido_enlace_abrir == 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
        class="d7-cta-<?= $d7id ?>">
        <?= htmlspecialchars($contenido->contenido_vermas ?: 'Ver Más') ?>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
    <?php endif; ?>
  </div>
</div>
