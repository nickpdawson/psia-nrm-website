/* NRM events archive: filter, list/calendar toggle, month calendar, ICS links. */
(function () {
  var events = window.NRM_EVENTS || [];
  var $ = function (id) { return document.getElementById(id); };
  var fDisc = $('ev-discipline'), fType = $('ev-type'), fWhen = $('ev-when'), fCeu = $('ev-ceu');
  var listEl = $('ev-list'), calEl = $('ev-cal'), emptyEl = $('ev-empty');
  var btnList = $('ev-view-list'), btnCal = $('ev-view-cal');
  if (!listEl) return;

  function filters() {
    return { disc: fDisc.value, type: fType.value, when: fWhen.value, ceu: fCeu.checked };
  }
  function matches(e, f) {
    if (f.disc && (e.disc || []).map(function (d) { return d.s; }).indexOf(f.disc) < 0) return false;
    if (f.type && (e.type || []).map(function (t) { return t.s; }).indexOf(f.type) < 0) return false;
    if (f.ceu && !e.ceu) return false;
    if (f.when === 'upcoming' && e.past) return false;
    if (f.when === 'past' && !e.past) return false;
    return true;
  }

  function applyList() {
    var f = filters(), shown = 0;
    Array.prototype.forEach.call(listEl.querySelectorAll('.ev-item'), function (el) {
      var e = {
        disc: (el.getAttribute('data-disc') || '').split(' ').filter(Boolean).map(function (s) { return { s: s }; }),
        type: (el.getAttribute('data-type') || '').split(' ').filter(Boolean).map(function (s) { return { s: s }; }),
        ceu: el.getAttribute('data-ceu') === '1',
        past: el.getAttribute('data-past') === '1'
      };
      var ok = matches(e, f);
      el.style.display = ok ? '' : 'none';
      if (ok) shown++;
    });
    emptyEl.style.display = (shown === 0 && !calEl.offsetParent) ? '' : 'none';
    updateIcsLinks(f);
  }

  function updateIcsLinks(f) {
    var params = [];
    if (f.disc) params.push('discipline=' + encodeURIComponent(f.disc));
    if (f.type) params.push('type=' + encodeURIComponent(f.type));
    if (f.ceu) params.push('ceu=1');
    var qs = params.length ? '&' + params.join('&') : '';
    document.querySelectorAll('a[href*="nrm_ics=all"]').forEach(function (a) {
      var base = a.getAttribute('href').split('&')[0];
      a.setAttribute('href', base + qs);
    });
  }

  // ---- Calendar ----
  var view = new Date(); view.setDate(1);
  function ymd(d) { return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2); }
  function renderCal() {
    var f = filters();
    var y = view.getFullYear(), m = view.getMonth();
    $('cal-title').textContent = view.toLocaleString('en-US', { month: 'long', year: 'numeric' });
    var first = new Date(y, m, 1), startDow = first.getDay();
    var days = new Date(y, m + 1, 0).getDate();
    // bucket events by start date within this month
    var byDay = {};
    events.forEach(function (e) {
      if (!matches(e, f)) return;
      var d = new Date(e.start + 'T00:00:00');
      if (d.getFullYear() === y && d.getMonth() === m) {
        (byDay[d.getDate()] = byDay[d.getDate()] || []).push(e);
      }
    });
    var html = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(function (d) {
      return '<div class="cal-dow">' + d + '</div>';
    }).join('');
    for (var i = 0; i < startDow; i++) html += '<div class="cal-cell cal-empty"></div>';
    var today = ymd(new Date());
    for (var day = 1; day <= days; day++) {
      var cellDate = y + '-' + ('0' + (m + 1)).slice(-2) + '-' + ('0' + day).slice(-2);
      var evs = byDay[day] || [];
      var inner = '<div class="cal-daynum">' + day + '</div>';
      evs.forEach(function (e) {
        var cls = e.past ? ' cal-ev-past' : '';
        inner += '<a class="cal-ev' + cls + '" href="' + e.url + '" title="' + e.title.replace(/"/g, '&quot;') + '">' + e.title + '</a>';
      });
      html += '<div class="cal-cell' + (cellDate === today ? ' cal-today' : '') + '">' + inner + '</div>';
    }
    $('cal-grid').innerHTML = html;
  }

  function showView(v) {
    var cal = v === 'cal';
    calEl.style.display = cal ? '' : 'none';
    listEl.style.display = cal ? 'none' : 'flex';
    btnCal.classList.toggle('is-active', cal); btnList.classList.toggle('is-active', !cal);
    btnCal.setAttribute('aria-selected', cal); btnList.setAttribute('aria-selected', !cal);
    btnCal.style.background = cal ? 'var(--psia-teal)' : '#fff'; btnCal.style.color = cal ? '#fff' : 'var(--text-secondary)';
    btnList.style.background = cal ? '#fff' : 'var(--psia-teal)'; btnList.style.color = cal ? 'var(--text-secondary)' : '#fff';
    emptyEl.style.display = 'none';
    if (cal) renderCal(); else applyList();
  }

  [fDisc, fType, fWhen, fCeu].forEach(function (el) {
    el.addEventListener('change', function () { applyList(); if (calEl.style.display !== 'none') renderCal(); });
  });
  btnList.addEventListener('click', function () { showView('list'); });
  btnCal.addEventListener('click', function () { showView('cal'); });
  $('cal-prev').addEventListener('click', function () { view.setMonth(view.getMonth() - 1); renderCal(); });
  $('cal-next').addEventListener('click', function () { view.setMonth(view.getMonth() + 1); renderCal(); });

  applyList();
})();
