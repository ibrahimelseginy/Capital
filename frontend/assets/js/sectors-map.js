(function () {
  'use strict';
  const grid=document.querySelector('[data-sector-map]');
  if(!grid) return;
  const eyebrow=document.querySelector('[data-sector-map-eyebrow]');
  const title=document.querySelector('[data-sector-map-title]');
  const description=document.querySelector('[data-sector-map-description]');
  const heroCount=document.querySelector('[data-sector-map-count]');
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

  function card(item){
    const article=document.createElement('article'); article.className='sector-focus-card reveal visible';
    const top=document.createElement('div'); top.className='sector-focus-top';
    const icon=document.createElement('div'); icon.className='sector-ico';
    const svg=document.createElementNS('http://www.w3.org/2000/svg','svg');
    svg.setAttribute('viewBox','0 0 24 24'); svg.setAttribute('fill','none'); svg.setAttribute('stroke','currentColor'); svg.setAttribute('stroke-width','2'); svg.setAttribute('stroke-linecap','round'); svg.setAttribute('stroke-linejoin','round'); svg.setAttribute('aria-hidden','true');
    svg.innerHTML=icons[item.icon_key]||icons.software; icon.append(svg);
    const code=document.createElement('span'); code.className='sector-code'; code.textContent=item.code||''; top.append(icon,code);
    const body=document.createElement('div'); const name=document.createElement('h3'); name.textContent=item.name||''; const text=document.createElement('p'); text.textContent=item.description||'';
    const tags=document.createElement('div'); tags.className='sector-tags'; (Array.isArray(item.tags)?item.tags:[]).forEach(function(value){const tag=document.createElement('span');tag.textContent=value;tags.append(tag);});
    body.append(name,text,tags); article.append(top,body); return article;
  }

  function message(text){const box=document.createElement('div');box.className='public-opportunities-empty sector-map-message';const b=document.createElement('b');b.textContent=text;box.append(b);return box;}

  fetch(grid.getAttribute('data-api')||'api/sectors.php',{headers:{Accept:'application/json'},cache:'no-store'})
    .then(function(response){if(!response.ok)throw new Error();return response.json();})
    .then(function(payload){
      if(!payload.ok||!Array.isArray(payload.data))throw new Error();
      if(eyebrow)eyebrow.textContent=payload.intro.eyebrow||''; if(title)title.textContent=payload.intro.title||''; if(description)description.textContent=payload.intro.description||'';
      if(heroCount)heroCount.textContent=payload.data.length+' قطاعات';
      grid.replaceChildren.apply(grid,payload.data.length?payload.data.map(card):[message('لا توجد قطاعات منشورة حاليًا')]);
      grid.setAttribute('aria-busy','false');
    })
    .catch(function(){grid.replaceChildren(message('تعذر تحميل خريطة الفرص'));grid.setAttribute('aria-busy','false');});
})();
