(function () {
  'use strict';

  const grid = document.querySelector('[data-public-opportunities]');
  const count = document.querySelector('[data-public-opportunities-count]');
  const updated = document.querySelector('[data-public-opportunities-updated]');
  if (!grid) return;

  const apiUrl = window.STC_API
    ? window.STC_API.url('opportunities.php')
    : (grid.getAttribute('data-api') || 'api/opportunities.php');
  let loading = false;
  let contentCopy = {updated_label:'آخر تحديث',empty_title:'لا توجد فرص استثمارية منشورة حاليًا',empty_description:'ستظهر الفرص هنا تلقائيًا فور نشرها من لوحة الإدارة.'};

  function element(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (typeof text === 'string') node.textContent = text;
    return node;
  }

  function money(value, currency) {
    try {
      return new Intl.NumberFormat('ar-EG', {
        style: 'currency',
        currency: currency || 'USD',
        maximumFractionDigits: 0
      }).format(Number(value) || 0);
    } catch (_) {
      return (Number(value) || 0).toLocaleString('ar-EG') + ' ' + (currency || 'USD');
    }
  }

  function card(item) {
    const article = element('article', 'public-opportunity-card');
    const head = element('div', 'public-opportunity-head');
    head.append(
      element('span', 'public-opportunity-sector', item.sector || 'قطاع غير محدد'),
      element('span', 'public-opportunity-id', item.id || '')
    );

    const title = element('h3', '', item.title || 'فرصة استثمارية');
    const details = element('div', 'public-opportunity-details');
    const stage = element('div');
    stage.append(element('span', '', 'المرحلة'), element('b', '', item.stage || 'غير محددة'));
    const funding = element('div');
    funding.append(element('span', '', 'التمويل المستهدف'), element('b', '', money(item.target_amount, item.currency)));
    details.append(stage, funding);

    const footer = element('div', 'public-opportunity-footer');
    const link = element('a', 'public-opportunity-link', 'عرض التفاصيل الآمنة');
    link.href = window.STC_API ? window.STC_API.link('login.php?tab=register') : 'login.html?tab=register';
    footer.append(link);
    article.append(head, title, details, footer);
    return article;
  }

  function message(title, body, isError) {
    const box = element('div', 'public-opportunities-empty' + (isError ? ' is-error' : ''));
    const heading=element('b', '', title);const description=element('p', '', body);
    if(!isError){heading.setAttribute('data-empty-title','');description.setAttribute('data-empty-description','');}
    box.append(heading,description);
    if (isError) {
      const retry = element('button', 'btn btn-soft btn-sm', 'إعادة المحاولة');
      retry.type = 'button';
      retry.addEventListener('click', load);
      box.append(retry);
    }
    return box;
  }

  function updateTime(value) {
    if (!updated) return;
    if (!value) {
      updated.textContent = 'تحديث تلقائي';
      return;
    }
    const date = new Date(value);
    updated.textContent = Number.isNaN(date.getTime())
      ? 'تحديث تلقائي'
      : (contentCopy.updated_label||'آخر تحديث') + ' ' + new Intl.DateTimeFormat('ar-EG', {hour:'2-digit', minute:'2-digit'}).format(date);
  }

  async function load() {
    if (loading) return;
    loading = true;
    grid.setAttribute('aria-busy', 'true');
    try {
      const response = await fetch(apiUrl, {
        headers: {Accept: 'application/json'},
        credentials: 'omit',
        cache: 'no-store',
        signal: AbortSignal.timeout(10000)
      });
      if (!response.ok) throw new Error('request_failed');
      const payload = await response.json();
      if (!payload.ok || !Array.isArray(payload.data)) throw new Error('invalid_payload');

      const items = payload.data;
      grid.replaceChildren(...(items.length
        ? items.map(card)
        : [message(contentCopy.empty_title, contentCopy.empty_description, false)]));
      if (count) count.textContent = String(items.length);
      updateTime(payload.meta && payload.meta.generated_at);
    } catch (_) {
      grid.replaceChildren(message('تعذر تحميل الفرص الاستثمارية', 'تحقق من الاتصال ثم أعد المحاولة.', true));
      if (count) count.textContent = '—';
      if (updated) updated.textContent = 'تعذر التحديث';
    } finally {
      loading = false;
      grid.setAttribute('aria-busy', 'false');
    }
  }

  document.addEventListener('sectors:content',function(event){
    const value=event.detail&&event.detail.opportunities;if(value&&typeof value==='object')contentCopy=Object.assign(contentCopy,value);
    const heading=grid.querySelector('[data-empty-title]'),description=grid.querySelector('[data-empty-description]');
    if(heading)heading.textContent=contentCopy.empty_title;if(description)description.textContent=contentCopy.empty_description;
  });
  load();
  window.setInterval(function () {
    if (!document.hidden) load();
  }, 30000);
})();
