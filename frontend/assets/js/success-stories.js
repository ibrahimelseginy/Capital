(function () {
  'use strict';
  const root=document.querySelector('[data-success-stories-page]');
  const grid=document.querySelector('[data-success-stories]');
  const filters=document.querySelector('[data-story-filters]');
  if(!root||!grid||!filters)return;
  let stories=[];let selected='all';

  function element(tag,className,text){const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;}
  function setText(selector,value){const node=root.querySelector(selector);if(node&&typeof value==='string')node.textContent=value;}
  function emptyState(message,isError){const box=element('div','public-opportunities-empty stories-empty'+(isError?' is-error':''));box.append(element('b','',message));if(isError){const retry=element('button','btn btn-soft btn-sm','إعادة المحاولة');retry.type='button';retry.addEventListener('click',load);box.append(retry);}return box;}

  function storyCard(story){
    const card=element('article','card story-card card-hover');card.dataset.category=story.category_key||'';
    const top=element('div','story-top');const meta=element('div','story-card-meta');
    meta.append(element('span','badge badge-orange',story.sector_label||''),element('span','badge',story.anonymous_label||''));
    top.append(meta,element('h3','',story.title||''));
    [[story.problem_label||'المشكلة',story.problem],[story.solution_label||'الحل',story.solution]].forEach(function(item){const detail=element('div','story-detail');detail.append(element('b','',item[0]),element('p','',item[1]||''));top.append(detail);});
    const duration=element('div','story-duration');const badge=element('span','badge badge-info');
    const clock=document.createElementNS('http://www.w3.org/2000/svg','svg');clock.setAttribute('viewBox','0 0 24 24');clock.setAttribute('fill','none');clock.setAttribute('stroke','currentColor');clock.setAttribute('stroke-width','2');clock.setAttribute('aria-hidden','true');clock.innerHTML='<circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>';
    badge.append(clock,document.createTextNode(((story.duration||'')+' '+(story.launch_suffix||'')).trim()));duration.append(badge);top.append(duration);
    const metrics=element('div','story-metrics');(Array.isArray(story.metrics)?story.metrics.slice(0,3):[]).forEach(function(metric){const item=element('div','m');item.append(element('b','',String(metric.value||'')),element('span','',String(metric.label||'')));metrics.append(item);});
    card.append(top,metrics);return card;
  }

  function renderStories(){const visible=selected==='all'?stories:stories.filter(function(story){return story.category_key===selected;});grid.replaceChildren(...(visible.length?visible.map(storyCard):[emptyState('لا توجد قصص منشورة في هذا التصنيف',false)]));grid.setAttribute('aria-busy','false');}
  function setSelected(value){selected=value;filters.querySelectorAll('[data-story-filter]').forEach(function(button){const active=button.dataset.storyFilter===selected;button.classList.toggle('active',active);button.setAttribute('aria-checked',active?'true':'false');button.tabIndex=active?0:-1;});renderStories();}
  function filterButton(key,label,active){const button=element('button','btn btn-soft btn-sm'+(active?' active':''),label);button.type='button';button.dataset.storyFilter=key;button.setAttribute('role','radio');button.setAttribute('aria-checked',active?'true':'false');button.tabIndex=active?0:-1;button.addEventListener('click',function(){setSelected(key);});button.addEventListener('keydown',function(event){if(!['ArrowLeft','ArrowRight','Home','End'].includes(event.key))return;event.preventDefault();const buttons=Array.from(filters.querySelectorAll('[data-story-filter]'));let index=buttons.indexOf(button);if(event.key==='Home')index=0;else if(event.key==='End')index=buttons.length-1;else index=(index+(event.key==='ArrowLeft'?1:-1)+buttons.length)%buttons.length;buttons[index].focus();setSelected(buttons[index].dataset.storyFilter);});return button;}
  function renderFilters(items){const safe=(Array.isArray(items)?items:[]).filter(function(item){return item&&item.key&&item.label;});if(!safe.some(function(item){return item.key===selected;}))selected=safe[0]?safe[0].key:'all';filters.replaceChildren(...safe.map(function(item){return filterButton(String(item.key),String(item.label),selected===item.key);}));filters.setAttribute('aria-busy','false');renderStories();}

  function render(payload){
    const hero=payload.hero;const heroSection=root.querySelector('[data-stories-section="hero"]');if(heroSection)heroSection.hidden=!hero;
    if(hero){setText('[data-stories-hero-eyebrow]',hero.eyebrow||'');setText('[data-stories-hero-title]',hero.title||'');setText('[data-stories-hero-description]',hero.description||'');}
    stories=payload.data;renderFilters(payload.filters);
    if(Array.isArray(payload.meta&&payload.meta.sections))payload.meta.sections.slice().sort((a,b)=>a.sort_order-b.sort_order).forEach(function(item){const section=root.querySelector('[data-stories-section="'+item.key+'"]');if(section){section.hidden=!item.is_active;root.append(section);}});
    root.setAttribute('aria-busy','false');
  }

  function load(){grid.setAttribute('aria-busy','true');fetch(window.STC_API?window.STC_API.url('success-stories.php'):(grid.getAttribute('data-api')||'api/success-stories.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)})
    .then(function(response){if(!response.ok)throw new Error();return response.json();})
    .then(function(payload){if(!payload.ok||!Array.isArray(payload.data)||!Array.isArray(payload.filters))throw new Error();render(payload);})
    .catch(function(){grid.replaceChildren(emptyState('تعذر تحميل قصص النجاح',true));grid.setAttribute('aria-busy','false');filters.setAttribute('aria-busy','false');root.setAttribute('aria-busy','false');});}
  load();window.setInterval(function(){if(!document.hidden)load();},30000);
})();

