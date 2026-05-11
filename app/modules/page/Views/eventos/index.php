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

<section class="evl-page">
  <div class="container">

    <!-- ── Hero ─────────────────────────────────────────── -->
    <header class="evl-hero">
      <div class="evl-hero-left">
        <div class="evl-hero-label">Programación</div>
        <h1 class="evl-hero-title">Próximos <em>Eventos</em></h1>
      </div>
      <?php if ($totalCount > 0): ?>
        <p class="evl-hero-count">
          <strong><?= $totalCount ?></strong>
          <span>evento<?= $totalCount !== 1 ? 's' : '' ?> disponible<?= $totalCount !== 1 ? 's' : '' ?></span>
        </p>
      <?php endif; ?>
    </header>

    <?php if (empty($eventos)): ?>

      <!-- ── Empty state ──────────────────────────────────── -->
      <div class="evl-empty">
        <div class="evl-empty-icon"><i class="fas fa-calendar-times"></i></div>
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

                <div class="evl-card-media">
                  <?php if ($ev['imagen']): ?>
                    <img class="evl-card-img" src="/images/<?= ($ev['imagen']) ?>"
                      alt="<?= ($ev['nombre']) ?>" loading="lazy">
                  <?php else: ?>
                    <div class="evl-card-no-img"></div>
                  <?php endif; ?>
                  <div class="evl-card-media-overlay"></div>

                  <div class="evl-card-date">
                    <span class="evl-card-date-day"><?= ($ev['dia']) ?></span>
                    <span class="evl-card-date-mon"><?= ($ev['mes_corto']) ?></span>
                  </div>

                  <?php if ($tipoLabel): ?>
                    <span class="evl-card-tipo"
                      style="background:<?= $tipoColor ?>cc;color:#fff;border:1.5px solid <?= $tipoColor ?>;">
                      <?= ($tipoLabel) ?>
                    </span>
                  <?php endif; ?>
                </div>

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
                      <i class="fas fa-clock"></i>
                      <?= ($ev['hora']) ?> &middot; <?= ($ev['mes_largo']) ?> <?= ($ev['anio']) ?>
                    </span>
                    <span class="evl-card-cta">
                      Ver <i class="fas fa-arrow-right"></i>
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

          <?php if ($currentPage > 1): ?>
            <a href="?pagina=<?= $currentPage - 1 ?>" class="evl-pag-btn evl-pag-btn--nav">
              <i class="fas fa-chevron-left"></i> Anterior
            </a>
          <?php else: ?>
            <span class="evl-pag-btn evl-pag-btn--nav evl-pag-btn--disabled">
              <i class="fas fa-chevron-left"></i> Anterior
            </span>
          <?php endif; ?>

          <?php $rango = evlPagRange($currentPage, $totalPages); ?>
          <?php if ($rango[0] > 1): ?>
            <a href="?pagina=1" class="evl-pag-btn">1</a>
            <?php if ($rango[0] > 2): ?>
              <span class="evl-pag-ellipsis">…</span>
            <?php endif; ?>
          <?php endif; ?>

          <?php foreach ($rango as $p): ?>
            <?php if ($p === $currentPage): ?>
              <span class="evl-pag-btn evl-pag-btn--active"><?= $p ?></span>
            <?php else: ?>
              <a href="?pagina=<?= $p ?>" class="evl-pag-btn"><?= $p ?></a>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php if (end($rango) < $totalPages): ?>
            <?php if (end($rango) < $totalPages - 1): ?>
              <span class="evl-pag-ellipsis">…</span>
            <?php endif; ?>
            <a href="?pagina=<?= $totalPages ?>" class="evl-pag-btn"><?= $totalPages ?></a>
          <?php endif; ?>

          <?php if ($currentPage < $totalPages): ?>
            <a href="?pagina=<?= $currentPage + 1 ?>" class="evl-pag-btn evl-pag-btn--nav">
              Siguiente <i class="fas fa-chevron-right"></i>
            </a>
          <?php else: ?>
            <span class="evl-pag-btn evl-pag-btn--nav evl-pag-btn--disabled">
              Siguiente <i class="fas fa-chevron-right"></i>
            </span>
          <?php endif; ?>

        </nav>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>
