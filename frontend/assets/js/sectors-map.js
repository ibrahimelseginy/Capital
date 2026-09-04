(function () {
  'use strict';
  const root=document.querySelector('[data-sectors-page]');
  const grid=document.querySelector('[data-sector-map]');
  if(!root||!grid)return;

  const icons={
    software:'<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
    fintech:'<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>',
    ai:'<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
    health:'<path d="M12 21s-8-5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 6-8 11-8 11z"/>',
    education:'<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
    iot:'<circle cx="12" cy="12" r="2"/><path d="M4.9 4.9a10 10 0 0 0 0 14.2M19.1 4.9a10 10 0 0 1 0 14.2"/>',
    logistics:'<rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>',
    digital:'<path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/>'
  };

  function element(tag,className,text){const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;}
  function setText(selector,value){const node=root.querySelector(selector);if(node&&typeof value==='string')node.textContent=value;}
  function card(item){
    const article=element('article','sector-focus-card reveal in');article.id='sector-'+item.code;article.tabIndex=-1;
    const top=element('div','sector-focus-top');const icon=element('div','sector-ico');
    const svg=document.createElementNS('http://www.w3.org/2000/svg','svg');svg.setAttribute('viewBox','0 0 24 24');svg.setAttribute('fill','none');svg.setAttribute('stroke','currentColor');svg.setAttribute('stroke-width','2');svg.setAttribute('stroke-linecap','round');svg.setAttribute('stroke-linejoin','round');svg.setAttribute('aria-hidden','true');svg.innerHTML=icons[item.icon_key]||icons.software;icon.append(svg);
    top.append(icon,element('span','sector-code',item.code||''));
    const body=element('div');body.append(element('h3','',item.name||''),element('p','',item.description||''));
    const tags=element('div','sector-tags');(Array.isArray(item.tags)?item.tags:[]).forEach(function(value){tags.append(element('span','',value));});body.append(tags);article.append(top,body);return article;
  }
  function message(text){const box=element('div','public-opportunities-empty sector-map-message');box.append(element('b','',text));return box;}
  function setSection(name,visible){const section=root.querySelector('[data-sectors-section="'+name+'"]');if(section)section.hidden=!visible;}

  function render(payload){
    const hero=payload.hero;setSection('hero',!!hero);
    if(hero){setText('[data-sectors-hero-eyebrow]',hero.eyebrow||'');setText('[data-sectors-hero-title]',hero.title||'');setText('[data-sectors-hero-description]',hero.description||'');setText('[data-sectors-hero-value]',hero.summary_value||'');setText('[data-sectors-hero-summary]',hero.summary_text||'');}

    const intro=payload.intro;setSection('map',!!intro);
    if(intro){setText('[data-sector-map-eyebrow]',intro.eyebrow||'');setText('[data-sector-map-title]',intro.title||'');setText('[data-sector-map-description]',intro.description||'');}
    const sectors=Array.isArray(payload.data)?payload.data:[];
    grid.replaceChildren(...(sectors.length?sectors.map(card):[message('لا توجد قطاعات منشورة حاليًا')]));
    grid.setAttribute('aria-busy','false');

    const opportunities=payload.opportunities;setSection('opportunities',!!opportunities);
    if(opportunities){setText('[data-opportunities-eyebrow]',opportunities.eyebrow||'');setText('[data-opportunities-title]',opportunities.title||'');setText('[data-opportunities-description]',opportunities.description||'');}
    document.dispatchEvent(new CustomEvent('sectors:content',{detail:{opportunities:opportunities||{}}}));

    const protectedContent=payload.protected;setSection('protected',!!protectedContent);
    if(protectedContent){setText('[data-protected-title]',protectedContent.title||'');setText('[data-protected-description]',protectedContent.description||'');const link=root.querySelector('[data-protected-button]');if(link){link.textContent=protectedContent.button_label||'';link.href=window.STC_API?window.STC_API.link(protectedContent.button_url||'login.php?tab=register'):(protectedContent.button_url||'login.html?tab=register');link.hidden=!protectedContent.button_label;}}

    if(Array.isArray(payload.meta&&payload.meta.sections))payload.meta.sections.slice().sort((a,b)=>a.sort_order-b.sort_order).forEach(function(item){const section=root.querySelector('[data-sectors-section="'+item.key+'"]');if(section){if(!item.is_active)section.hidden=true;root.append(section);}});
    root.setAttribute('aria-busy','false');
    const target=document.getElementById(location.hash.slice(1));if(target){target.scrollIntoView({block:'center'});target.focus({preventScroll:true});}
  }

  fetch(window.STC_API?window.STC_API.url('sectors.php'):(grid.getAttribute('data-api')||'api/sectors.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)})
    .then(function(response){if(!response.ok)throw new Error();return response.json();})
    .then(function(payload){if(!payload.ok||!Array.isArray(payload.data))throw new Error();render(payload);})
    .catch(function(){grid.replaceChildren(message('تعذر تحميل خريطة الفرص'));grid.setAttribute('aria-busy','false');root.setAttribute('aria-busy','false');});
})();
