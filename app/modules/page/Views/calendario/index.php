<!-- ===== CALENDARIO DE EVENTOS ===== -->
<div id="cal-context">

  <section id="cal-section" class="cal-section">
    <div class="container">

      <div class="cal-header">
        <button class="cal-nav-btn" id="cal-prev" aria-label="Mes anterior">
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="cal-month-title-wrap">
          <h2 class="cal-month-title" id="cal-month-label"></h2>
          <div class="cal-header-sub">
            <button class="cal-today-btn" id="cal-today">Hoy</button>
            <button class="cal-theme-btn" id="cal-theme-toggle" aria-label="Cambiar tema">
              <i class="fas fa-sun"></i>
            </button>
          </div>
        </div>
        <button class="cal-nav-btn" id="cal-next" aria-label="Mes siguiente">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

      <div class="cal-legend" id="cal-legend"></div>

      <div class="cal-weekdays" aria-hidden="true">
        <div>Dom</div>
        <div>Lun</div>
        <div>Mar</div>
        <div>Mié</div>
        <div>Jue</div>
        <div>Vie</div>
        <div>Sáb</div>
      </div>

      <div class="cal-grid" id="cal-grid" role="grid" aria-label="Calendario de eventos"></div>

    </div>
  </section>

  <!-- Modal detalle del evento -->
  <div class="modal fade" id="calEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content cal-modal-content">
        <div class="modal-header cal-modal-header">
          <h5 class="modal-title" id="calEventModalLabel"></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body cal-modal-body" id="calEventModalBody"></div>
        <div class="modal-footer cal-modal-footer" id="calEventModalFooter"></div>
      </div>
    </div>
  </div>

</div><!-- /cal-context -->

<script>
  (() => {
    'use strict';

    const EVENTOS_POR_FECHA = <?php echo $this->eventosJson; ?>;
    const SEDES = <?php echo $this->sedesJson; ?>;
    const vendedor = new URLSearchParams(window.location.search).get('vendedor') || '';


    const MONTH_NAMES = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    const calCtx = document.getElementById('cal-context');
    const grid = document.getElementById('cal-grid');
    const monthLabel = document.getElementById('cal-month-label');
    const legend = document.getElementById('cal-legend');
    const themeToggle = document.getElementById('cal-theme-toggle');

    const now = new Date();
    let curYear = now.getFullYear();
    let curMonth = now.getMonth();
    let curTheme = localStorage.getItem('cal-theme') || 'light';
    let curFilter = null;
    let SEDE_MAP = {};

    const pad2 = n => n < 10 ? '0' + n : '' + n;

    function hexToTextColor (hex) {
      const r = parseInt(hex.slice(1, 3), 16);
      const g = parseInt(hex.slice(3, 5), 16);
      const b = parseInt(hex.slice(5, 7), 16);
      return (r * 299 + g * 587 + b * 114) / 1000 >= 128 ? '#1a1a1a' : '#ffffff';
    }

    function buildSedeMap () {
      SEDES.forEach(sede => {
        const bg = sede.color || '#888888';
        SEDE_MAP[sede.id] = { nombre: sede.nombre, direccion: sede.direccion, bg, text: hexToTextColor(bg) };
      });
    }

    function applyTheme (theme, rerender = false) {
      curTheme = theme;
      calCtx.dataset.theme = theme;
      localStorage.setItem('cal-theme', theme);
      themeToggle.querySelector('i').className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
      buildSedeMap();
      if (rerender) renderMonth(curYear, curMonth);
      else buildLegend();
    }

    function getSedeCountsForMonth () {
      const counts = {};
      const prefix = `${curYear}-${pad2(curMonth + 1)}-`;
      Object.entries(EVENTOS_POR_FECHA).forEach(([dateKey, evList]) => {
        if (dateKey.startsWith(prefix)) {
          evList.forEach(ev => { counts[ev.lugar] = (counts[ev.lugar] || 0) + 1; });
        }
      });
      return counts;
    }

    function buildLegend () {
      const counts = getSedeCountsForMonth();
      const total = Object.values(counts).reduce((s, n) => s + n, 0);
      legend.innerHTML = '';

      const todosBtn = document.createElement('button');
      todosBtn.type = 'button';
      const todosActive = curFilter === null;
      todosBtn.className = 'cal-legend-item cal-legend-item--todos' +
        (todosActive ? ' cal-legend-item--active' : ' cal-legend-item--inactive');
      todosBtn.innerHTML =
        `<span class="cal-legend-name">Todos <span class="cal-legend-count">${total}</span></span>`;
      todosBtn.addEventListener('click', () => applyFilter(null));
      legend.appendChild(todosBtn);

      SEDES.forEach(sede => {
        const info = SEDE_MAP[sede.id];
        const count = counts[sede.id] || 0;
        const isActive = curFilter === sede.id;
        const isInactive = curFilter !== null && !isActive;
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'cal-legend-item' +
          (isActive ? ' cal-legend-item--active' : '') +
          (isInactive ? ' cal-legend-item--inactive' : '');
        item.innerHTML =
          `<span class="cal-legend-dot" style="background:${info.bg}"></span>` +
          `<span class="cal-legend-name">${info.nombre} <span class="cal-legend-count">${count}</span></span>`;
        item.addEventListener('click', () => applyFilter(sede.id));
        legend.appendChild(item);
      });
    }

    function applyFilter (sedeId) {
      curFilter = sedeId;
      buildLegend();
      grid.querySelectorAll('.cal-event-chip').forEach(chip => {
        const show = sedeId === null || chip.dataset.sede === String(sedeId);
        chip.classList.toggle('cal-event-chip--filtered', !show);
      });
      grid.querySelectorAll('.cal-cell--has-events').forEach(cell => {
        const hasVisible = cell.querySelector('.cal-event-chip:not(.cal-event-chip--filtered)');
        cell.classList.toggle('cal-cell--all-filtered', !hasVisible);
      });
    }

    function renderMonth (year, month) {
      monthLabel.textContent = `${MONTH_NAMES[month]} ${year}`;

      const firstDay = new Date(year, month, 1).getDay();
      const totalDays = new Date(year, month + 1, 0).getDate();
      const prevTotal = new Date(year, month, 0).getDate();

      grid.innerHTML = '';

      for (let i = 0; i < firstDay; i++) {
        const cell = document.createElement('div');
        cell.className = 'cal-cell cal-cell--other';
        cell.innerHTML = `<span class="cal-cell-day">${prevTotal - firstDay + 1 + i}</span>`;
        grid.appendChild(cell);
      }

      const todayStr = `${now.getFullYear()}-${pad2(now.getMonth() + 1)}-${pad2(now.getDate())}`;

      for (let d = 1; d <= totalDays; d++) {
        const dateKey = `${year}-${pad2(month + 1)}-${pad2(d)}`;
        const isToday = dateKey === todayStr;
        const events = EVENTOS_POR_FECHA[dateKey] || [];

        const cell = document.createElement('div');
        cell.className = 'cal-cell' +
          (isToday ? ' cal-cell--today' : '') +
          (events.length ? ' cal-cell--has-events' : '');
        cell.setAttribute('role', 'gridcell');

        let html = `<span class="cal-cell-day">${d}</span>`;

        if (events.length) {
          html += '<div class="cal-events-list">';
          events.forEach(ev => {
            const sede = SEDE_MAP[ev.lugar] || { bg: '#888', text: '#fff' };
            const thumb = ev.imagen
              ? `<img class="cal-chip-thumb" src="/images/${ev.imagen}" alt="" loading="lazy" onerror="this.style.display='none'">`
              : '';
            html += `<button class="cal-event-chip"` +
              ` style="background:${sede.bg};color:${sede.text};"` +
              ` data-date="${dateKey}" data-id="${ev.id}" data-sede="${ev.lugar}">` +
              thumb +
              `<span class="cal-chip-info">` +
              (ev.hora ? `<span class="cal-event-time">${ev.hora}</span>` : '') +
              `<span class="cal-event-name">${ev.nombre}</span>` +
              `</span></button>`;
          });
          html += '</div>';
        }

        cell.innerHTML = html;
        grid.appendChild(cell);
      }

      const totalCells = firstDay + totalDays;
      const trailing = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
      for (let t = 1; t <= trailing; t++) {
        const cell = document.createElement('div');
        cell.className = 'cal-cell cal-cell--other';
        cell.innerHTML = `<span class="cal-cell-day">${t}</span>`;
        grid.appendChild(cell);
      }

      applyFilter(curFilter);
    }

    const bsModal = new bootstrap.Modal(document.getElementById('calEventModal'));
    const modalTitle = document.getElementById('calEventModalLabel');
    const modalBody = document.getElementById('calEventModalBody');

    const formatCOP = amount =>
      (!amount || amount === 0) ? 'Entrada libre' : '$ ' + parseInt(amount, 10).toLocaleString('es-CO');

    const formatDateES = dateKey => {
      const p = dateKey.split('-');
      return `${parseInt(p[2], 10)} de ${MONTH_NAMES[parseInt(p[1], 10) - 1]} de ${p[0]}`;
    };

    function openModal (ev) {
      const sede = SEDE_MAP[ev.lugar] || { nombre: 'Por confirmar', bg: '#888', text: '#fff' };
      modalTitle.textContent = ev.nombre;

      let body = '';
      if (ev.imagen) {
        body += `<div class="cal-modal-img-wrap">` +
          `<img src="/images/${ev.imagen}" alt="${ev.nombre}" class="cal-modal-img">` +
          `</div>`;
      }
      body += `<div class="cal-modal-details">`;
      body += `<p class="cal-modal-title-body">${ev.nombre}</p>`;
      body += `<div class="cal-modal-info-grid">`;
      body += `<div class="cal-info-card cal-info-card--sede" style="--sede-bg:${sede.bg};--sede-text:${sede.text};">` +
        `<span class="cal-info-card__icon"><i class="fas fa-map-marker-alt"></i></span>` +
        `<span class="cal-info-card__body"><span class="cal-info-card__label">Sede</span>` +
        `<span class="cal-info-card__value">${sede.nombre}</span></span></div>`;
      body += `<div class="cal-info-card">` +
        `<span class="cal-info-card__icon cal-info-card__icon--date"><i class="fas fa-calendar-alt"></i></span>` +
        `<span class="cal-info-card__body"><span class="cal-info-card__label">Fecha</span>` +
        `<span class="cal-info-card__value">${formatDateES(ev.fecha)}</span></span></div>`;
      if (ev.hora) body += `<div class="cal-info-card">` +
        `<span class="cal-info-card__icon cal-info-card__icon--time"><i class="fas fa-clock"></i></span>` +
        `<span class="cal-info-card__body"><span class="cal-info-card__label">Hora</span>` +
        `<span class="cal-info-card__value">${ev.hora}</span></span></div>`;
      body += `<div class="cal-info-card cal-info-card--price">` +
        `<span class="cal-info-card__icon cal-info-card__icon--price"><i class="fas fa-ticket-alt"></i></span>` +
        `<span class="cal-info-card__body"><span class="cal-info-card__label">Entrada</span>` +
        `<span class="cal-info-card__value">${formatCOP(ev.costo)}</span></span></div>`;
      body += `</div>`;
      if (ev.descripcion) body += `<div class="cal-modal-desc">${ev.descripcion}</div>`;
      body += `</div>`;

      modalBody.innerHTML = body;

      const detalleUrl = '/page/eventos/detalle?id=' + ev.id + (vendedor ? '&vendedor=' + encodeURIComponent(vendedor) : '');
      let btnIcon, btnText;
      if (ev.tipo === 'reserva') {
        btnIcon = 'fas fa-calendar-check';
        btnText = 'Más información / Reservas';
      } else if (ev.tiene_boletas) {
        btnIcon = 'fas fa-ticket-alt';
        btnText = 'Más información / Comprar';
      } else {
        btnIcon = 'fas fa-info-circle';
        btnText = 'Más información';
      }
      document.getElementById('calEventModalFooter').innerHTML =
        `<a href="${detalleUrl}" class="btn-detalle-evento w-100">` +
        `<i class="${btnIcon} me-2"></i>${btnText}</a>`;

      bsModal.show();
    }

    grid.addEventListener('click', e => {
      const chip = e.target.closest('.cal-event-chip');
      if (!chip) return;
      const dateKey = chip.dataset.date;
      const evId = parseInt(chip.dataset.id, 10);
      const list = EVENTOS_POR_FECHA[dateKey] || [];
      const ev = list.find(x => x.id === evId);
      if (ev) openModal(ev);
    });

    themeToggle.addEventListener('click', () => {
      applyTheme(curTheme === 'dark' ? 'light' : 'dark', true);
    });

    document.getElementById('cal-prev').addEventListener('click', () => {
      curMonth--;
      if (curMonth < 0) { curMonth = 11; curYear--; }
      renderMonth(curYear, curMonth);
    });
    document.getElementById('cal-next').addEventListener('click', () => {
      curMonth++;
      if (curMonth > 11) { curMonth = 0; curYear++; }
      renderMonth(curYear, curMonth);
    });
    document.getElementById('cal-today').addEventListener('click', () => {
      curYear = now.getFullYear();
      curMonth = now.getMonth();
      renderMonth(curYear, curMonth);
    });

    // Init
    applyTheme(curTheme);
    renderMonth(curYear, curMonth);

    // Auto-abrir evento si viene ?evento=ID en la URL
    const autoId = parseInt(new URLSearchParams(window.location.search).get('evento'), 10);
    if (autoId) {
      let targetEv = null;
      for (const [, evList] of Object.entries(EVENTOS_POR_FECHA)) {
        const found = evList.find(x => x.id === autoId);
        if (found) { targetEv = found; break; }
      }
      if (targetEv) {
        const [y, m] = targetEv.fecha.split('-');
        const evYear = parseInt(y, 10);
        const evMonth = parseInt(m, 10) - 1;
        if (evYear !== curYear || evMonth !== curMonth) {
          curYear = evYear;
          curMonth = evMonth;
          renderMonth(curYear, curMonth);
        }
        openModal(targetEv);
      }
    }
  })();
</script>
