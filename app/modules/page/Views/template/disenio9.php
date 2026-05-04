<?php
/*
 * Diseño 9 — Card Blog / Noticia
 * Imagen 16:9 con zoom en hover + badge de categoría + cuerpo con meta.
 * Ideal para: noticias, artículos, portafolio, eventos, productos.
 *
 * contenido_imagen      → imagen principal (ratio 16:9)
 * contenido_fondo_color → color del badge de categoría (hex); default acento global
 * contenido_borde       → 1 = card con sombra más pronunciada
 * contenido_titulo      → título del artículo (si contenido_titulo_ver = 1)
 * contenido_descripcion → extracto / resumen
 * contenido_archivo     → descarga opcional
 * contenido_enlace      → URL "Leer Más"
 * contenido_vermas      → texto del botón (si vacío = "Leer Más")
 *                         Si empieza con "#" se usa como etiqueta de categoría en el badge
 *                         Ej: "#Tecnología" → badge "Tecnología" + botón default
 */
$d9id      = $contenido->contenido_id;
$d9accent  = $contenido->contenido_fondo_color ?: 'var(--color-acento, #4f46e5)';
$d9shadow  = $contenido->contenido_borde == '1'
  ? '0 8px 32px rgba(0,0,0,.14)'
  : '0 2px 10px rgba(0,0,0,.07)';
$d9radius  = '12px';

// Badge de categoría: si contenido_vermas empieza con "#"
$d9badge   = '';
$d9btnText = $contenido->contenido_vermas ?: 'Leer Más';
if ($contenido->contenido_vermas && strpos($contenido->contenido_vermas, '#') === 0) {
    $d9badge   = ltrim($contenido->contenido_vermas, '#');
    $d9btnText = 'Leer Más';
}
?>
<style>
  .d9-<?= $d9id ?> {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: <?= $d9radius ?>;
    overflow: hidden;
    box-shadow: <?= $d9shadow ?>;
    transition: box-shadow .3s ease, transform .3s ease;
  }
  .d9-<?= $d9id ?>:hover {
    box-shadow: 0 16px 48px rgba(0,0,0,.15);
    transform: translateY(-5px);
  }
  .d9-img-wrap-<?= $d9id ?> {
    position: relative;
    overflow: hidden;
    aspect-ratio: 16 / 9;
    background: #e9ecef;
    flex-shrink: 0;
  }
  .d9-img-<?= $d9id ?> {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .55s cubic-bezier(.25,.46,.45,.94);
    will-change: transform;
  }
  .d9-<?= $d9id ?>:hover .d9-img-<?= $d9id ?> {
    transform: scale(1.07);
  }
  .d9-placeholder-<?= $d9id ?> {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 2.5rem;
    background: #f0f2f5;
  }
  .d9-badge-<?= $d9id ?> {
    position: absolute;
    top: 14px;
    left: 14px;
    background: <?= $d9accent ?>;
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 1;
  }
  .d9-body-<?= $d9id ?> {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 22px 22px 20px;
    gap: 8px;
  }
  .d9-title-<?= $d9id ?> {
    font-size: 1rem;
    font-weight: 800;
    line-height: 1.35;
    color: var(--color-texto, #1f2a37);
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .d9-desc-<?= $d9id ?> {
    font-size: .875rem;
    line-height: 1.65;
    color: var(--color-texto-suave, #666);
    margin: 0;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .d9-footer-<?= $d9id ?> {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 8px;
    padding-top: 14px;
    border-top: 1px solid #f0f0f0;
  }
  .d9-dl-<?= $d9id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .8rem;
    color: var(--color-texto-suave, #666);
    text-decoration: none;
    transition: color .2s ease;
  }
  .d9-dl-<?= $d9id ?>:hover { color: <?= $d9accent ?>; text-decoration: none; }
  .d9-cta-<?= $d9id ?> {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .8rem;
    font-weight: 700;
    color: <?= $d9accent ?>;
    text-decoration: none;
    letter-spacing: .02em;
    transition: gap .22s ease, opacity .22s ease;
  }
  .d9-cta-<?= $d9id ?>:hover {
    opacity: .8;
    gap: 9px;
    text-decoration: none;
    color: <?= $d9accent ?>;
  }
</style>

<div class="caja-contenido-simple design-nine d9-<?= $d9id ?>">
  <div class="d9-img-wrap-<?= $d9id ?>">
    <?php if ($contenido->contenido_imagen): ?>
      <img class="d9-img-<?= $d9id ?>"
        src="/images/<?= htmlspecialchars($contenido->contenido_imagen) ?>"
        alt="<?= htmlspecialchars($contenido->contenido_titulo) ?>"
        loading="lazy">
    <?php else: ?>
      <div class="d9-placeholder-<?= $d9id ?>"><i class="fas fa-image" aria-hidden="true"></i></div>
    <?php endif; ?>
    <?php if ($d9badge): ?>
      <span class="d9-badge-<?= $d9id ?>"><?= htmlspecialchars($d9badge) ?></span>
    <?php endif; ?>
  </div>

  <div class="d9-body-<?= $d9id ?>">
    <?php if ($contenido->contenido_titulo_ver == 1 && $contenido->contenido_titulo): ?>
      <h3 class="d9-title-<?= $d9id ?>"><?= htmlspecialchars($contenido->contenido_titulo) ?></h3>
    <?php endif; ?>
    <?php if ($contenido->contenido_descripcion): ?>
      <p class="d9-desc-<?= $d9id ?>"><?= strip_tags($contenido->contenido_descripcion) ?></p>
    <?php endif; ?>

    <?php if ($contenido->contenido_archivo || $contenido->contenido_enlace): ?>
      <div class="d9-footer-<?= $d9id ?>">
        <?php if ($contenido->contenido_archivo): ?>
          <a href="/files/<?= htmlspecialchars($contenido->contenido_archivo) ?>"
            target="_blank" rel="noopener noreferrer" class="d9-dl-<?= $d9id ?>">
            <i class="fas fa-download" aria-hidden="true"></i> Descargar
          </a>
        <?php else: ?>
          <span></span>
        <?php endif; ?>
        <?php if ($contenido->contenido_enlace): ?>
          <a href="<?= htmlspecialchars($contenido->contenido_enlace) ?>"
            <?= $contenido->contenido_enlace_abrir == 1 ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
            class="d9-cta-<?= $d9id ?>">
            <?= htmlspecialchars($d9btnText) ?>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
