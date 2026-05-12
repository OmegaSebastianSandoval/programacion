<!-- Tipografías: editorial serif + mono de precisión -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600&display=swap"
  rel="stylesheet">

<div class="container-fluid gal-dash">

  <!-- ── HERO BAR ── -->
  <div class="gal-hero">
    <div class="gal-hero-grid">
      <div class="gal-hero-left">
        <div class="gal-hero-eyebrow">
          <span class="gal-live-dot"></span>
          <span>operaciones en tiempo real</span>
        </div>
        <h1 class="gal-hero-fecha"><?= $this->today ?></h1>
        <p class="gal-hero-brand">Galería Café Libro &mdash; Centro de Comando</p>
      </div>
      <div class="gal-hero-right">
        <div class="gal-clock" id="galClock">--:--:--</div>
        <div class="gal-clock-sub">hora local</div>
      </div>
    </div>
  </div>

  <!-- ── KPI CARDS ── -->
  <div class="row g-3 gal-kpi-row">

    <div class="col-6 col-xl-3">
      <div class="gal-kpi gal-anim" style="--delay:.05s">
        <div class="gal-kpi-stripe gal-stripe-green"></div>
        <div class="gal-kpi-body">
          <span class="gal-kpi-label">Ventas del Día</span>
          <span class="gal-kpi-val gal-val-green">
            $<?= number_format($this->total_ventas_hoy, 0, ',', '.') ?>
          </span>
          <span class="gal-kpi-icon-wrap"><i class="fas fa-arrow-trend-up"></i></span>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3">
      <div class="gal-kpi gal-anim" style="--delay:.12s">
        <div class="gal-kpi-stripe gal-stripe-amber"></div>
        <div class="gal-kpi-body">
          <span class="gal-kpi-label">Reservas del Día</span>
          <span class="gal-kpi-val gal-val-amber">
            $<?= number_format($this->total_reservas_hoy, 0, ',', '.') ?>
          </span>
          <span class="gal-kpi-icon-wrap"><i class="fas fa-calendar-check"></i></span>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3">
      <div class="gal-kpi gal-anim" style="--delay:.19s">
        <div class="gal-kpi-stripe gal-stripe-slate"></div>
        <div class="gal-kpi-body">
          <span class="gal-kpi-label">Compras Realizadas</span>
          <span class="gal-kpi-val"><?= $this->total_compras_count ?></span>
          <span class="gal-kpi-icon-wrap"><i class="fas fa-receipt"></i></span>
        </div>
      </div>
    </div>

    <div class="col-6 col-xl-3">
      <div class="gal-kpi gal-anim" style="--delay:.26s">
        <div class="gal-kpi-stripe gal-stripe-dark"></div>
        <div class="gal-kpi-body">
          <span class="gal-kpi-label">Reservas Realizadas</span>
          <span class="gal-kpi-val"><?= $this->total_reservas_count ?></span>
          <span class="gal-kpi-icon-wrap"><i class="fas fa-users"></i></span>
        </div>
      </div>
    </div>

  </div><!-- /kpi row -->

  <!-- ── EVENTOS DE HOY ── -->
  <div class="gal-section-hd gal-anim" style="--delay:.32s">
    <span class="gal-section-tag"><i class="fas fa-bolt"></i> Eventos de Hoy</span>
    <div class="gal-section-rule"></div>
  </div>

  <?php if (count($this->eventos_hoy) == 0) { ?>
    <div class="gal-empty gal-anim" style="--delay:.36s">
      <i class="fas fa-calendar-times gal-empty-icon"></i>
      <h4>Sin eventos programados para hoy</h4>
      <p>No hay eventos registrados con fecha de hoy.</p>
      <a href="/administracion/eventos" class="gal-btn-outline">
        <i class="fas fa-calendar-plus"></i> Administrar Eventos
      </a>
    </div>
  <?php } else { ?>
    <div class="row g-3 gal-eventos-row">
      <?php foreach ($this->eventos_hoy as $i => $ev) { ?>
        <?php
        $pct = $ev->boletas_total > 0
          ? round(($ev->boletas_vendidas / $ev->boletas_total) * 100)
          : 0;
        $pct_class = $pct >= 90 ? 'gal-bar-hot' : ($pct >= 60 ? 'gal-bar-mid' : 'gal-bar-ok');
        $recaudado = $ev->compras_total + $ev->reservas_total;
        $delay = 0.35 + ($i * 0.08);
        ?>
        <div class="col-12 col-lg-6 col-xxl-4">
          <div class="gal-evento-card gal-anim" style="--delay:<?= $delay ?>s">

            <div class="gal-ec-header">
              <div class="gal-ec-hora">
                <i class="fas fa-clock"></i>&thinsp;<?= $ev->evento_hora ?>
              </div>
              <a href="/administracion/reservaevento?reserva_evento_evento=<?= $ev->evento_id ?>" class="gal-ec-link"
                title="Ver zonas de reserva">
                Ver zonas <i class="fas fa-arrow-right"></i>
              </a>
            </div>

            <h2 class="gal-ec-nombre"><?= $ev->evento_nombre ?></h2>

            <div class="gal-ec-meta">
              <span><i class="fas fa-map-marker-alt"></i> <?= $ev->sede_nombre ?></span>
              <span><i class="fas fa-users"></i> Aforo <?= number_format($ev->evento_aforomaximo, 0, ',', '.') ?></span>
            </div>

            <!-- Boletas progress -->
            <div class="gal-boletas">
              <div class="gal-boletas-row">
                <span class="gal-boletas-lbl">Boletas</span>
                <span class="gal-boletas-counts">
                  <strong><?= number_format($ev->boletas_vendidas, 0, ',', '.') ?></strong>
                  <em>/ <?= number_format($ev->boletas_total, 0, ',', '.') ?></em>
                  <span class="gal-pct"><?= $pct ?>%</span>
                </span>
              </div>
              <div class="gal-progress">
                <div class="gal-progress-fill <?= $pct_class ?>" style="width:<?= $pct ?>%"></div>
              </div>
              <div class="gal-saldo">
                <?= number_format($ev->boletas_saldo, 0, ',', '.') ?> disponibles
              </div>
            </div>

            <!-- Mini stats -->
            <div class="gal-ec-stats">
              <div class="gal-ec-stat">
                <div class="gal-ec-stat-n"><?= $ev->compras_count ?></div>
                <div class="gal-ec-stat-l">Compras</div>
              </div>
              <div class="gal-ec-divider"></div>
              <div class="gal-ec-stat">
                <div class="gal-ec-stat-n"><?= $ev->reservas_count ?></div>
                <div class="gal-ec-stat-l">Reservas</div>
              </div>
              <div class="gal-ec-divider"></div>
              <div class="gal-ec-stat gal-ec-stat--money">
                <div class="gal-ec-stat-n">$<?= number_format($recaudado, 0, ',', '.') ?></div>
                <div class="gal-ec-stat-l">Recaudado</div>
              </div>
            </div>

          </div>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <!-- ── COMPRAS DE HOY ── -->
  <div class="gal-section-hd gal-anim" style="--delay:.4s">
    <span class="gal-section-tag"><i class="fas fa-receipt"></i> Compras de Hoy</span>
    <div class="gal-section-rule"></div>
    <a href="/administracion/compras" class="gal-section-link">
      Ver todas <i class="fas fa-arrow-right"></i>
    </a>
  </div>

  <?php if (count($this->compras_hoy) == 0) { ?>
    <div class="gal-table-empty gal-anim" style="--delay:.44s">
      <i class="fas fa-inbox"></i> Sin compras registradas hoy
    </div>
  <?php } else { ?>
    <div class="gal-table-wrap gal-anim" style="--delay:.44s">
      <table class="gal-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Evento</th>
            <th>Email</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th class="text-end">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->compras_hoy as $compra) { ?>
            <?php
            $resp = strtoupper($compra->boleta_compra_respuesta);
            if ($resp == 'APROBADA' || $resp == 'APPROVED') {
              $pill = 'gal-pill-ok';
              $pill_txt = 'Aprobada';
            } elseif ($resp == 'RECHAZADA' || $resp == 'DECLINED' || $resp == 'CANCELED') {
              $pill = 'gal-pill-no';
              $pill_txt = 'Rechazada';
            } else {
              $pill = 'gal-pill-wait';
              $pill_txt = $compra->boleta_compra_respuesta ? $compra->boleta_compra_respuesta : 'Pendiente';
            }
            ?>
            <tr>
              <td><span class="gal-id"><?= $compra->boleta_compra_id ?></span></td>
              <td class="gal-name"><?= $compra->boleta_compra_nombre ?></td>
              <td class="gal-dim"><?= $compra->boleta_compra_evento_nombre ?></td>
              <td class="gal-dim"><?= $compra->boleta_compra_email ?></td>
              <td class="gal-dim"><?= $compra->boleta_compra_tipo ?></td>
              <td><span class="gal-pill <?= $pill ?>"><?= $pill_txt ?></span></td>
              <td class="text-end gal-money">$<?= number_format($compra->boleta_compra_total, 0, ',', '.') ?></td>
              <td class="text-end">
                <a href="/administracion/compras/info?id=<?= $compra->boleta_compra_id ?>" class="gal-action-btn"
                  title="Ver detalle">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } ?>

  <!-- ── RESERVAS DE HOY ── -->
  <div class="gal-section-hd gal-anim" style="--delay:.48s">
    <span class="gal-section-tag"><i class="fas fa-calendar-check"></i> Reservas de Hoy</span>
    <div class="gal-section-rule"></div>
    <a href="/administracion/reservas" class="gal-section-link">
      Ver todas <i class="fas fa-arrow-right"></i>
    </a>
  </div>

  <?php if (count($this->reservas_hoy) == 0) { ?>
    <div class="gal-table-empty gal-anim" style="--delay:.52s">
      <i class="fas fa-inbox"></i> Sin reservas registradas hoy
    </div>
  <?php } else { ?>
    <div class="gal-table-wrap gal-anim" style="--delay:.52s">
      <table class="gal-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Evento</th>
            <th>Email</th>
            <th>Origen</th>
            <th>Estado</th>
            <th class="text-end">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->reservas_hoy as $reserva) { ?>
            <?php
            $est = strtolower($reserva->reserva_estado);
            if ($est == 'confirmada' || $est == 'pagada' || $est == 'completada') {
              $pill_r = 'gal-pill-ok';
            } elseif ($est == 'cancelada') {
              $pill_r = 'gal-pill-no';
            } elseif ($est == 'reserva') {
              $pill_r = 'gal-pill-blue';
            } else {
              $pill_r = 'gal-pill-wait';
            }
            ?>
            <tr>
              <td><span class="gal-id"><?= $reserva->reserva_id ?></span></td>
              <td class="gal-name"><?= $reserva->reserva_nombre ?></td>
              <td class="gal-dim"><?= $reserva->reserva_evento_nombre ?></td>
              <td class="gal-dim"><?= $reserva->reserva_email ?></td>
              <td class="gal-dim"><?= $reserva->reserva_tipo_origen ?></td>
              <td><span class="gal-pill <?= $pill_r ?>"><?= $reserva->reserva_estado ?></span></td>
              <td class="text-end gal-money">$<?= number_format($reserva->reserva_total, 0, ',', '.') ?></td>
              <td class="text-end">
                <a href="/administracion/reservas/info?id=<?= $reserva->reserva_id ?>" class="gal-action-btn"
                  title="Ver detalle">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } ?>

  <div style="height:3rem"></div>

</div><!-- /gal-dash -->

<script>
  (function () {
    function tick () {
      var el = document.getElementById('galClock');
      if (!el) return;
      var n = new Date();
      el.textContent =
        String(n.getHours()).padStart(2, '0') + ':' +
        String(n.getMinutes()).padStart(2, '0') + ':' +
        String(n.getSeconds()).padStart(2, '0');
    }
    tick();
    setInterval(tick, 1000);
  })();
</script>