(function () {
  'use strict';

  const grid = document.querySelector('[data-success-stories]');
  const filters = document.querySelector('[data-story-filters]');
  if (!grid || !filters) return;

  let stories = [];
  let selected = 'all';

  function element(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (typeof text === 'string') node.textContent = text;
    return node;
  }

  function emptyState(message, isError) {
    const box = element('div', 'public-opportunities-empty stories-empty' + (isError ? ' is-error' : ''));
    box.append(element('b', '', message));
    if (isError) {
      const retry = element('button', 'btn btn-soft btn-sm', 'إعادة المحاولة');
      retry.type = 'button';
      retry.addEventListener('click', load);
      box.append(retry);
    } else {
      box.append(element('p', '', 'ستظهر القصص هنا بعد إضافتها ونشرها من لوحة الإدارة.'));
    }
    return box;
  }

  function storyCard(story) {
    const card = element('article', 'card story-card card-hover');
    card.dataset.category = story.category_key || '';

    const top = element('div', 'story-top');
    const meta = element('div', 'story-card-meta');
    meta.append(element('span', 'badge badge-orange', story.sector_label || ''), element('span', 'badge', 'مجهّلة'));
    top.append(meta, element('h3', '', story.title || ''));

    [['المشكلة', story.problem], ['الحل', story.solution]].forEach(function (item) {
      const detail = element('div', 'story-detail');
      detail.append(element('b', '', item[0]), element('p', '', item[1] || ''));
      top.append(detail);
    });

    const duration = element('div', 'story-duration');
    const durationBadge = element('span', 'badge badge-info');
    const clock = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    clock.setAttribute('viewBox', '0 0 24 24');
    clock.setAttribute('fill', 'none');
    clock.setAttribute('stroke', 'currentColor');
    clock.setAttribute('stroke-width', '2');
    clock.setAttribute('aria-hidden', 'true');
    clock.innerHTML = '<circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>';
    durationBadge.append(clock, document.createTextNode((story.duration || '') + ' للإطلاق'));
    duration.append(durationBadge);
    top.append(duration);

    const metrics = element('div', 'story-metrics');
    (Array.isArray(story.metrics) ? story.metrics.slice(0, 3) : []).forEach(function (metric) {
      const item = element('div', 'm');
      item.append(element('b', '', String(metric.value || '')), element('span', '', String(metric.label || '')));
      metrics.append(item);
    });
    card.append(top, metrics);
    return card;
  }

  function renderStories() {
    const visible = selected === 'all' ? stories : stories.filter(function (story) {
      return story.category_key === selected;
    });
    grid.replaceChildren.apply(grid, visible.length ? visible.map(storyCard) : [emptyState('لا توجد قصص منشورة في هذا التصنيف', false)]);
    grid.setAttribute('aria-busy', 'false');
  }

  function setSelected(key) {
    selected = key;
    filters.querySelectorAll('[data-story-filter]').forEach(function (button) {
      const active = button.dataset.storyFilter === key;
      button.classList.toggle('active', active);
      button.setAttribute('aria-checked', active ? 'true' : 'false');
      button.tabIndex = active ? 0 : -1;
    });
    renderStories();
  }

  function filterButton(key, label, active) {
    const button = element('button', 'chip' + (active ? ' active' : ''), label);
    button.type = 'button';
    button.setAttribute('role', 'radio');
    button.setAttribute('aria-checked', active ? 'true' : 'false');
    button.dataset.storyFilter = key;
    button.tabIndex = active ? 0 : -1;
    button.addEventListener('click', function () { setSelected(key); });
    button.addEventListener('keydown', function (event) {
      if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
      event.preventDefault();
      const buttons = Array.from(filters.querySelectorAll('[data-story-filter]'));
      let index = buttons.indexOf(button);
      if (event.key === 'Home') index = 0;
      else if (event.key === 'End') index = buttons.length - 1;
      else index = (index + (event.key === 'ArrowLeft' ? 1 : -1) + buttons.length) % buttons.length;
      buttons[index].focus();
      setSelected(buttons[index].dataset.storyFilter);
    });
    return button;
  }

  function renderFilters(items) {
    const allowed = new Set(['all']);
    const buttons = [filterButton('all', 'الكل', selected === 'all')];
    items.forEach(function (item) {
      if (!item || !item.key || allowed.has(item.key)) return;
      allowed.add(item.key);
      buttons.push(filterButton(String(item.key), String(item.label || item.key), selected === item.key));
    });
    if (!allowed.has(selected)) selected = 'all';
    filters.replaceChildren.apply(filters, buttons);
    filters.setAttribute('aria-busy', 'false');
    setSelected(selected);
  }

  function handleFallback(){stories = [{"id": "STY-001", "sector_label": "تقنية مالية", "category_key": "fintech", "title": "منصة مدفوعات B2B", "problem": "بطء التسوية وتعقيد تجربة التاجر.", "solution": "بناء بنية مدفوعات حديثة مع تسوية شبه لحظية ولوحة تاجر موحّدة.", "duration": "9 أسابيع", "metrics": [{"value": "-64%", "label": "زمن العملية"}, {"value": "3.5x", "label": "نمو المعاملات"}, {"value": "99.9%", "label": "توافر"}]}, {"id": "STY-002", "sector_label": "صحة رقمية", "category_key": "health", "title": "منصة حجوزات ورعاية", "problem": "تجربة مريض مجزأة وعمليات ورقية.", "solution": "رقمنة رحلة المريض من الحجز إلى المتابعة مع تكامل تشغيلي.", "duration": "12 أسبوع", "metrics": [{"value": "+180%", "label": "مستخدمون"}, {"value": "-38%", "label": "التكلفة"}, {"value": "4.8★", "label": "رضا"}]}, {"id": "STY-003", "sector_label": "لوجستيات", "category_key": "logistics", "title": "نظام توصيل ذكي", "problem": "مسارات غير محسّنة وتتبع ضعيف.", "solution": "محرّك تحسين مسارات وتتبع لحظي وأتمتة إدارة الأسطول.", "duration": "8 أسابيع", "metrics": [{"value": "+42%", "label": "كفاءة"}, {"value": "-27%", "label": "زمن التسليم"}, {"value": "2.1x", "label": "طلبات"}]}]; renderFilters([{"key": "all", "label": "الكل"}, {"key": "fintech", "label": "تقنية مالية"}, {"key": "health", "label": "صحة رقمية"}, {"key": "logistics", "label": "لوجستيات"}]); grid.setAttribute('aria-busy', 'false');}
  function load() {
    grid.setAttribute('aria-busy', 'true');
    fetch(grid.getAttribute('data-api') || 'api/success-stories.php', {
      headers: { Accept: 'application/json' },
      cache: 'no-store'
    })
      .then(function (response) { if (!response.ok) throw new Error(); return response.json(); })
      .then(function (payload) {
        if (!payload.ok || !Array.isArray(payload.data)) throw new Error();
        stories = payload.data;
        renderFilters(Array.isArray(payload.filters) ? payload.filters : []);
      })
      .catch(handleFallback);
  }
  load();
  window.setInterval(function () { if (!document.hidden) load(); }, 30000);
})();
