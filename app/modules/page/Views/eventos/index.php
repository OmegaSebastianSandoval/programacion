<?php
$eventos = $this->eventos ?? [];
$currentPage = (int) ($this->currentPage ?? 1);
$totalPages = (int) ($this->totalPages ?? 1);
$totalCount = (int) ($this->totalCount ?? 0);

$tipoLabels = [
  'boleteria' => 'Boletería',
  'reserva' => 'Reserva',
  'reservayboleteria' => 'Boleta + Palco',
];
$tipoAccents = [
  'boleteria' => '#3b82f6',
  'reserva' => '#f59e0b',
  'reservayboleteria' => '#7c3aed',
];

// Helper de paginación — muestra hasta 5 páginas centradas en la actual
function evlPagRange($current, $total, $window = 2)
{
  $start = max(1, $current - $window);
  $end = min($total, $current + $window);
  if ($end - $start < $window * 2) {
    if ($start === 1)
      $end = min($total, 1 + $window * 2);
    else
      $start = max(1, $end - $window * 2);
  }
  return range($start, $end);
}
?>
<style>
  /* ============================================================
   LISTADO DE EVENTOS — evl-*
   ============================================================ */
  .evl-page {
    padding: 3.5rem 0 5.5rem;
    min-height: calc(100vh - var(--global-header-height, 76px));
    background: #f5f6f8;
  }

  /* ── Hero ─────────────────────────────────────────────────── */
  .evl-hero {
    text-align: center;
    margin-bottom: 3rem;
  }

  .evl-hero-label {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--brand-rosa);
    margin-bottom: .75rem;
  }

  .evl-hero-label::before,
  .evl-hero-label::after {
    content: '';
    display: block;
    width: 24px;
    height: 1.5px;
    background: var(--brand-rosa);
    opacity: .5;
  }

  .evl-hero-title {
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900;
    color: #0d1117;
    letter-spacing: -.03em;
    line-height: 1.1;
    margin: 0 0 .65rem;
  }

  .evl-hero-count {
    font-size: .88rem;
    color: #888;
    margin: 0;
  }

  /* ── Grid ─────────────────────────────────────────────────── */
  .evl-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }

  /* ── Card ─────────────────────────────────────────────────── */
  .evl-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0, 0, 0, .07);
    transition: transform .25s cubic-bezier(.22, 1, .36, 1),
      box-shadow .25s cubic-bezier(.22, 1, .36, 1);
    display: flex;
    flex-direction: column;
  }

  .evl-card:hover {
    box-shadow: 0 8px 28px rgba(0, 0, 0, .11);
  }

  .evl-card-link {
    display: flex;
    flex-direction: column;
    height: 100%;
    text-decoration: none;
    color: inherit;
  }

  /* ── Media (imagen) ───────────────────────────────────────── */
  .evl-card-media {
    position: relative;
    width: 100%;
    padding-bottom: 58%;
    background: var(--brand-verde);
    overflow: hidden;
    flex-shrink: 0;
  }

  .evl-card-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s cubic-bezier(.22, 1, .36, 1);
  }

  .evl-card:hover .evl-card-img {
    transform: scale(1.04);
  }

  .evl-card-no-img {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--brand-verde) 0%, #1a7a62 100%);
  }

  .evl-card-media-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom,
        rgba(0, 0, 0, .04) 0%,
        rgba(0, 0, 0, .0) 40%,
        rgba(0, 0, 0, .35) 100%);
    pointer-events: none;
  }

  /* Date badge */
  .evl-card-date {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 10px;
    padding: .38rem .6rem;
    min-width: 42px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .18);
    line-height: 1;
    gap: .12rem;
  }

  .evl-card-date-day {
    font-size: 1.25rem;
    font-weight: 900;
    color: #0d1117;
    letter-spacing: -.02em;
  }

  .evl-card-date-mon {
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: var(--brand-rosa);
  }

  /* Tipo badge */
  .evl-card-tipo {
    position: absolute;
    top: 12px;
    right: 12px;
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    padding: .24rem .7rem;
    border-radius: 50px;
    line-height: 1.4;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
  }

  /* ── Body (contenido) ─────────────────────────────────────── */
  .evl-card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: .55rem;
    padding: 1.15rem 1.25rem 1.25rem;
  }

  .evl-card-sede {
    display: inline-flex;
    align-items: center;
    gap: .38rem;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .04em;
    color: #666;
    text-transform: uppercase;
  }

  .evl-card-sede-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--sede-color, #0b4b3e);
    flex-shrink: 0;
  }

  .evl-card-title {
    font-size: 1.08rem;
    font-weight: 800;
    color: #0d1117;
    line-height: 1.3;
    letter-spacing: -.01em;
    margin: 0;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    overflow: hidden;
  }

  .evl-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: .85rem;
    border-top: 1px solid #f0f0f5;
  }

  .evl-card-time {
    display: flex;
    align-items: center;
    gap: .35rem;
    font-size: .78rem;
    font-weight: 600;
    color: #999;
  }

  .evl-card-time i {
    font-size: .75rem;
  }

  .evl-card-cta {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .78rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--brand-rosa);
    transition: gap .2s ease;
  }

  .evl-card-cta i {
    font-size: .72rem;
  }

  /* ── Empty state ──────────────────────────────────────────── */
  .evl-empty {
    text-align: center;
    padding: 5rem 1rem;
  }

  .evl-empty-icon {
    font-size: 3.5rem;
    color: #ddd;
    margin-bottom: 1.25rem;
  }

  .evl-empty-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #333;
    margin: 0 0 .5rem;
  }

  .evl-empty-sub {
    font-size: .9rem;
    color: #aaa;
    margin: 0 0 1.75rem;
  }

  /* ── Pagination ───────────────────────────────────────────── */
  .evl-pag-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    margin-top: 3.5rem;
    flex-wrap: wrap;
  }

  .evl-pag-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 .55rem;
    border-radius: 50px;
    border: 1.5px solid #e2e2ec;
    background: #fff;
    color: #555;
    font-size: .85rem;
    font-weight: 700;
    text-decoration: none;
    transition: background .18s, border-color .18s, color .18s, box-shadow .18s;
    cursor: pointer;
    white-space: nowrap;
  }

  .evl-pag-btn:hover {
    border-color: var(--brand-rosa);
    color: var(--brand-rosa);
    background: #fff5f8;
  }

  .evl-pag-btn--active {
    background: var(--brand-rosa) !important;
    border-color: var(--brand-rosa) !important;
    color: #fff !important;
    box-shadow: 0 4px 14px rgba(221, 18, 121, .3);
    pointer-events: none;
  }

  .evl-pag-btn--disabled {
    opacity: .3;
    pointer-events: none;
  }

  .evl-pag-btn--nav {
    padding: 0 .95rem;
    gap: .35rem;
    font-size: .78rem;
    letter-spacing: .03em;
    text-transform: uppercase;
  }

  .evl-pag-ellipsis {
    color: #bbb;
    font-size: .85rem;
    padding: 0 .3rem;
    user-select: none;
  }

  /* ── Responsive ───────────────────────────────────────────── */
  @media (max-width: 991.98px) {
    .evl-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: 1.1rem;
    }
  }

  @media (max-width: 575.98px) {
    .evl-page {
      padding: 2rem 0 4rem;
    }

    .evl-hero {
      margin-bottom: 2rem;
    }

    .evl-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .evl-card-media {
      padding-bottom: 52%;
    }

    .evl-card-body {
      padding: 1rem;
    }
  }
</style>

<section class="evl-page">
  <div class="container">

    <!-- ── Hero ─────────────────────────────────────────── -->
    <div class="evl-hero">
      <div class="evl-hero-label">Programación</div>
      <h1 class="evl-hero-title">Próximos Eventos</h1>
      <?php if ($totalCount > 0): ?>
        <p class="evl-hero-count">
          <?= $totalCount ?> evento<?= $totalCount !== 1 ? 's' : '' ?> disponible<?= $totalCount !== 1 ? 's' : '' ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (empty($eventos)): ?>

      <!-- ── Empty state ──────────────────────────────────── -->
      <div class="evl-empty">
        <div class="evl-empty-icon">
          <i class="fa-regular fa-calendar-xmark"></i>
        </div>
        <h2 class="evl-empty-title">Sin eventos próximos</h2>
        <p class="evl-empty-sub">No hay eventos programados por ahora. ¡Vuelve pronto!</p>
        <a href="/" class="btn-vermas">Volver al inicio</a>
      </div>

    <?php else: ?>

      <!-- ── Grid de tarjetas ──────────────────────────────── -->
      <div class="evl-grid">
        <?php foreach ($eventos as $ev): ?>
          <?php
          $tipoLabel = $tipoLabels[$ev['tipo']] ?? null;
          $tipoColor = $tipoAccents[$ev['tipo']] ?? '#888888';
          ?>
          <article class="evl-card">
            <a href="/page/eventos/detalle?id=<?= (int) $ev['id'] ?>" class="evl-card-link"
              aria-label="<?= ($ev['nombre']) ?>">

              <!-- Imagen -->
              <div class="evl-card-media">
                <?php if ($ev['imagen']): ?>
                  <img class="evl-card-img" src="/images/<?= ($ev['imagen']) ?>" alt="<?= ($ev['nombre']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="evl-card-no-img"></div>
                <?php endif; ?>
                <div class="evl-card-media-overlay"></div>

                <!-- Fecha flotante -->
                <div class="evl-card-date">
                  <span class="evl-card-date-day"><?= ($ev['dia']) ?></span>
                  <span class="evl-card-date-mon"><?= ($ev['mes_corto']) ?></span>
                </div>

                <!-- Badge de tipo -->
                <?php if ($tipoLabel): ?>
                  <span class="evl-card-tipo"
                    style="background:<?= $tipoColor ?>cc;color:#fff;border:1.5px solid <?= $tipoColor ?>;"><?= htmlspecialchars($tipoLabel) ?></span>
                <?php endif; ?>
              </div>

              <!-- Contenido -->
              <div class="evl-card-body">
                <?php if ($ev['sede_nombre']): ?>
                  <div class="evl-card-sede" style="--sede-color: <?= ($ev['sede_color']) ?>">
                    <span class="evl-card-sede-dot"></span>
                    <?= ($ev['sede_nombre']) ?>
                  </div>
                <?php endif; ?>

                <h3 class="evl-card-title"><?= ($ev['nombre']) ?></h3>

                <div class="evl-card-footer">
                  <span class="evl-card-time">
                    <i class="fa-regular fa-clock"></i>
                    <?= ($ev['hora']) ?> · <?= ($ev['mes_largo']) ?>     <?= ($ev['anio']) ?>
                  </span>
                  <span class="evl-card-cta">
                    Ver más <i class="fa-solid fa-arrow-right"></i>
                  </span>
                </div>
              </div>

            </a>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- ── Paginación ────────────────────────────────────── -->
      <?php if ($totalPages > 1): ?>
        <nav class="evl-pag-wrap" aria-label="Paginación de eventos">

          <!-- Anterior -->
          <?php if ($currentPage > 1): ?>
            <a href="?pagina=<?= $currentPage - 1 ?>" class="evl-pag-btn evl-pag-btn--nav">
              <i class="fa-solid fa-chevron-left"></i> Anterior
            </a>
          <?php else: ?>
            <span class="evl-pag-btn evl-pag-btn--nav evl-pag-btn--disabled">
              <i class="fa-solid fa-chevron-left"></i> Anterior
            </span>
          <?php endif; ?>

          <!-- Primera página + ellipsis -->
          <?php $rango = evlPagRange($currentPage, $totalPages); ?>
          <?php if ($rango[0] > 1): ?>
            <a href="?pagina=1" class="evl-pag-btn">1</a>
            <?php if ($rango[0] > 2): ?>
              <span class="evl-pag-ellipsis">…</span>
            <?php endif; ?>
          <?php endif; ?>

          <!-- Páginas del rango -->
          <?php foreach ($rango as $p): ?>
            <?php if ($p === $currentPage): ?>
              <span class="evl-pag-btn evl-pag-btn--active"><?= $p ?></span>
            <?php else: ?>
              <a href="?pagina=<?= $p ?>" class="evl-pag-btn"><?= $p ?></a>
            <?php endif; ?>
          <?php endforeach; ?>

          <!-- Última página + ellipsis -->
          <?php if (end($rango) < $totalPages): ?>
            <?php if (end($rango) < $totalPages - 1): ?>
              <span class="evl-pag-ellipsis">…</span>
            <?php endif; ?>
            <a href="?pagina=<?= $totalPages ?>" class="evl-pag-btn"><?= $totalPages ?></a>
          <?php endif; ?>

          <!-- Siguiente -->
          <?php if ($currentPage < $totalPages): ?>
            <a href="?pagina=<?= $currentPage + 1 ?>" class="evl-pag-btn evl-pag-btn--nav">
              Siguiente <i class="fa-solid fa-chevron-right"></i>
            </a>
          <?php else: ?>
            <span class="evl-pag-btn evl-pag-btn--nav evl-pag-btn--disabled">
              Siguiente <i class="fa-solid fa-chevron-right"></i>
            </span>
          <?php endif; ?>

        </nav>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>