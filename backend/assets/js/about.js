(function(){
  'use strict';
  const root=document.querySelector('[data-about-page]');
  if(!root)return;
  let pending=false,loaded=false;
  const icons={
    default:'<circle cx="12" cy="12" r="9"></circle><path d="M12 8v4l3 2"></path>',
    vision:'<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle>',
    mission:'<path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path>',
    method:'<path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>',
    experience:'<circle cx="12" cy="8" r="6"></circle><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path>',
    building:'<path d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 7h.5M9 11h.5M14 7h.5M14 11h.5"></path>',
    projects:'<rect x="2" y="7" width="20" height="14" rx="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
    clients:'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>',
    person:'<circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>'
  };

  function element(tag,className,text){
    const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;
  }
  function svg(iconKey){
    const node=document.createElementNS('http://www.w3.org/2000/svg','svg');
    node.setAttribute('viewBox','0 0 24 24');node.setAttribute('fill','none');node.setAttribute('stroke','currentColor');node.setAttribute('stroke-width','2');node.setAttribute('stroke-linecap','round');node.setAttribute('stroke-linejoin','round');node.setAttribute('aria-hidden','true');
    node.innerHTML=icons[iconKey]||icons.default;return node;
  }
  function setSection(name,visible){
    const section=root.querySelector('[data-about-section="'+name+'"]');if(section)section.hidden=!visible;
  }
  function grouped(rows){
    return rows.reduce(function(result,item){(result[item.section_key]||(result[item.section_key]=[])).push(item);return result;},{});
  }
  function brandCard(item){
    const card=element('article','about-brand-card');
    const brand=element('div','brand');const logo=element('img','logo-mark');logo.src='assets/img/icon.png';logo.width=34;logo.height=34;logo.alt='';logo.decoding='async';
    const title=element('span','',item.title||'');if(item.subtitle)title.append(element('small',item.subtitle.includes('Venture')?'venture-studio-label':'',item.subtitle));
    brand.append(logo,title);card.append(brand,element('p','text-2 mt-16',item.body||''));return card;
  }
  function vmmCard(item){
    const card=element('article','about-vmm-card');const icon=element('div','sector-ico');icon.append(svg(item.icon_key));card.append(icon,element('h3','',item.title||''),element('p','text-2 mt-8',item.body||''));return card;
  }
  function statCard(item){
    const card=element('div','stat');const icon=element('span','stat-ico');icon.append(svg(item.icon_key));const value=element('b','mono',(item.value_text||'0')+(item.value_suffix||''));card.append(icon,value,element('span','stat-label',item.title||''));return card;
  }
  function sectionHead(item,target){
    target.replaceChildren();
    if(!item)return;
    if(item.subtitle)target.append(element('span','eyebrow',item.subtitle));
    target.append(element('h2','section-title mt-16',item.title||''));
    if(item.body)target.append(element('p','section-lead',item.body));
  }
  function teamCard(item){
    const card=element('article','about-team-card');const avatar=element('div','avatar lg');avatar.append(svg('person'));card.append(avatar,element('b','fh',item.title||''),element('span','hint',item.subtitle||''));return card;
  }
  function geoCard(item){
    const card=element('article','about-geo-card');if(item.badge_label)card.append(element('span','badge badge-'+(item.badge_style||'info'),item.badge_label));card.append(element('h3','',item.title||''),element('p','',item.body||''));return card;
  }
  function actionLink(url,className,label){
    if(!url||!label)return null;
    const route=/^https?:\/\//i.test(url)?url:url.replace(/\.html(?=[?#]|$)/,'.php');
    const resolved=window.STC_API.link(route);
    if(!/^https?:$/.test(new URL(resolved,location.href).protocol))return null;
    const link=element('a',className,label);link.href=resolved;if(/^https?:\/\//i.test(url)){link.target='_blank';link.rel='noopener noreferrer';}return link;
  }
  function render(rows,sections){
    const data=grouped(rows);
    const hero=data.hero&&data.hero[0];const heroRoot=root.querySelector('[data-about-hero]');
    setSection('hero',!!hero);if(hero){const copy=element('div');if(hero.subtitle)copy.append(element('span','eyebrow',hero.subtitle));copy.append(element('h1','mt-16',hero.title||''));heroRoot.replaceChildren(copy,element('p','',hero.body||''));}
    const brands=data.brand||[];setSection('brand',brands.length>0);root.querySelector('[data-about-brands]').replaceChildren.apply(root.querySelector('[data-about-brands]'),brands.map(brandCard));
    const vmm=data.vmm||[];setSection('vmm',vmm.length>0);root.querySelector('[data-about-vmm]').replaceChildren.apply(root.querySelector('[data-about-vmm]'),vmm.map(vmmCard));
    const stats=data.stat||[];setSection('stat',stats.length>0);root.querySelector('[data-about-stats]').replaceChildren.apply(root.querySelector('[data-about-stats]'),stats.map(statCard));
    const team=data.team||[];const teamHead=data.team_header&&data.team_header[0];setSection('team',team.length>0||!!teamHead);sectionHead(teamHead,root.querySelector('[data-about-team-head]'));root.querySelector('[data-about-team]').replaceChildren.apply(root.querySelector('[data-about-team]'),team.map(teamCard));
    const geo=data.geo||[];const geoHead=data.geo_header&&data.geo_header[0];setSection('geo',geo.length>0||!!geoHead);sectionHead(geoHead,root.querySelector('[data-about-geo-head]'));root.querySelector('[data-about-geo]').replaceChildren.apply(root.querySelector('[data-about-geo]'),geo.map(geoCard));
    const cta=data.cta&&data.cta[0];setSection('cta',!!cta);if(cta){const box=root.querySelector('[data-about-cta]');box.replaceChildren(element('h2','',cta.title||''),element('p','',cta.body||''));const actions=element('div','cta-actions');const primary=actionLink(cta.primary_url,'btn btn-primary btn-lg',cta.badge_label||'ابدأ الآن');const secondary=actionLink(cta.secondary_url,'btn btn-ghost btn-lg',cta.value_text||'تواصل معنا');if(primary)actions.append(primary);if(secondary)actions.append(secondary);if(actions.children.length)box.append(actions);}
    if(Array.isArray(sections))sections.slice().sort((a,b)=>a.sort_order-b.sort_order).forEach(item=>{
      const section=root.querySelector('[data-about-section="'+item.key+'"]');
      if(section){if(!item.is_active)section.hidden=true;root.append(section);}
    });
    root.setAttribute('aria-busy','false');
  }
  function showError(){
    let box=root.querySelector('.about-api-error');if(!box){box=element('div','public-opportunities-empty about-api-error');root.prepend(box);}
    box.replaceChildren(element('b','',loaded?'تعذر تحديث المحتوى؛ المعروض آخر إصدار محفوظ.':'تعذر تحميل صفحة من نحن'),element('p','','تحقق من الاتصال وتشغيل قاعدة البيانات ثم حاول مرة أخرى.'));
    const retry=element('button','btn btn-soft btn-sm','إعادة المحاولة');retry.type='button';retry.addEventListener('click',load);box.append(retry);root.setAttribute('aria-busy','false');
  }
  function load(){
    if(pending)return;pending=true;
    return fetch(window.STC_API.url('about.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)})
      .then(function(response){if(!response.ok)throw new Error();return response.json();})
      .then(function(payload){if(!payload.ok||!Array.isArray(payload.data))throw new Error();const error=root.querySelector('.about-api-error');if(error)error.remove();render(payload.data,payload.meta?.sections);loaded=true;})
      .catch(showError).finally(function(){pending=false;});
  }
  load();window.setInterval(function(){if(!document.hidden)load();},30000);
})();
