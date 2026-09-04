(function(){
  'use strict';
  const root=document.querySelector('[data-seven-tech-page]');if(!root)return;
  const servicesGrid=root.querySelector('[data-seven-services-grid]');const pointsRoot=root.querySelector('[data-seven-points]');const statsRoot=root.querySelector('[data-seven-stats]');
  if(!servicesGrid||!pointsRoot||!statsRoot)return;
  const icons={
    product:'<rect x="2" y="3" width="20" height="14" rx="2"></rect><path d="M8 21h8M12 17v4"></path>',
    systems:'<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>',
    ai:'<circle cx="12" cy="12" r="3"></circle><path d="M12 2v3M12 19v3M2 12h3M19 12h3"></path>',
    digital:'<path d="M3 3v18h18"></path><path d="M7 14l4-4 3 3 5-6"></path>',
    support:'<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83"></path>',
    cloud:'<path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path>'
  };
  function element(tag,className,text){const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;}
  function setText(selector,value){const node=root.querySelector(selector);if(node&&typeof value==='string')node.textContent=value;}
  function svg(markup,className){const wrap=element('div',className);const icon=document.createElementNS('http://www.w3.org/2000/svg','svg');icon.setAttribute('viewBox','0 0 24 24');icon.setAttribute('fill','none');icon.setAttribute('stroke','currentColor');icon.setAttribute('stroke-width','2');icon.setAttribute('stroke-linecap','round');icon.setAttribute('stroke-linejoin','round');icon.setAttribute('aria-hidden','true');icon.innerHTML=markup;wrap.append(icon);return wrap;}
  function serviceCard(item){const card=element('article','seven-tech-service-card');const top=element('div','service-card-top');top.append(svg(icons[item.icon]||icons.product,'service-ico'),element('span','',item.number||''));card.append(top,element('h3','',item.title||''),element('p','',item.description||''));return card;}
  function pointItem(item){const row=element('div','risk-item');row.append(svg('<path d="M5 12h14"></path><path d="m13 6 6 6-6 6"></path>','risk-arrow'),element('span','',item.text||''));return row;}
  function statItem(item){const box=element('div');box.append(element('b','',item.value||''),element('span','',item.label||''));return box;}
  function message(text){const box=element('div','public-opportunities-empty seven-tech-api-message');box.append(element('b','',text));const retry=element('button','btn btn-soft btn-sm','إعادة المحاولة');retry.type='button';retry.addEventListener('click',load);box.append(retry);return box;}
  function render(payload){
    const sections=payload.sections||{};const hero=sections.hero;const services=sections.services;const role=sections.role;
    if(hero){setText('[data-seven-brand-name]',hero.brand_name);setText('[data-seven-brand-subtitle]',hero.brand_subtitle);setText('[data-seven-hero-title]',hero.title);setText('[data-seven-hero-description]',hero.description);setText('[data-seven-card-label]',hero.card_label);setText('[data-seven-card-title]',hero.card_title);setText('[data-seven-card-description]',hero.card_description);}
    if(services){setText('[data-seven-services-eyebrow]',services.eyebrow);setText('[data-seven-services-title]',services.title);setText('[data-seven-services-description]',services.description);servicesGrid.replaceChildren(...(services.items||[]).map(serviceCard));}
    if(role){setText('[data-seven-role-eyebrow]',role.eyebrow);setText('[data-seven-role-title]',role.title);setText('[data-seven-role-description]',role.description);pointsRoot.replaceChildren(...(role.points||[]).map(pointItem));statsRoot.replaceChildren(...(role.stats||[]).map(statItem));}
    (payload.meta&&Array.isArray(payload.meta.sections)?payload.meta.sections:[]).slice().sort(function(a,b){return a.sort_order-b.sort_order;}).forEach(function(item){const section=root.querySelector('[data-seven-section="'+item.key+'"]');if(section){section.hidden=!item.is_active;root.append(section);}});
    root.setAttribute('aria-busy','false');
  }
  function load(){root.setAttribute('aria-busy','true');fetch(window.STC_API?window.STC_API.url('seven-tech.php'):(root.dataset.api||'api/seven-tech.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)}).then(function(response){if(!response.ok)throw new Error();return response.json();}).then(function(payload){if(!payload.ok||!payload.sections)throw new Error();render(payload);}).catch(function(){servicesGrid.replaceChildren(message('تعذر تحميل محتوى Seven Tech'));root.setAttribute('aria-busy','false');});}
  load();window.setInterval(function(){if(!document.hidden)load();},30000);
})();
