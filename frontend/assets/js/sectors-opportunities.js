(function () {
  'use strict';

  const grid = document.querySelector('[data-public-opportunities]');
  const count = document.querySelector('[data-public-opportunities-count]');
  const updated = document.querySelector('[data-public-opportunities-updated]');
  if (!grid) return;

  const apiUrl = grid.getAttribute('data-api') || 'api/opportunities.php';
  let loading = false;

  function money(value, currency) {
    try {
      return new Intl.NumberFormat('ar-EG', {
        style: 'currency',
        currency: currency || 'USD',
        maximumFractionDigits: 0
      }).format(Number(value) || 0);
    } catch (_) {
      const data = [{"id": "OP-001", "title": "منصة PayTech B2B", "sector": "التقنية المالية", "stage": "Seed", "target_amount": 250000, "currency": "USD", "status": "available"}, {"id": "OP-002", "title": "نظام Smart-Health الرعاية الصحية", "sector": "الصحة الرقمية", "stage": "Pre-Series A", "target_amount": 500000, "currency": "USD", "status": "available"}, {"id": "OP-003", "title": "منصة SmartLog أتمتة الأسطول", "sector": "اللوجستيات والتوصيل", "stage": "Seed", "target_amount": 180000, "currency": "USD", "status": "available"}];
      grid.replaceChildren(...data.map(card));
      if (count) count.textContent = String(data.length);
      if (updated) updated.textContent = 'تحديث تلقائي';
    } finally {
      loading = false;
      grid.setAttribute('aria-busy', 'false');
    }
  }

  load();
  window.setInterval(function () {
    if (!document.hidden) load();
  }, 30000);
})();
