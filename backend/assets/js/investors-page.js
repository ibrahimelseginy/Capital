(function(){
  'use strict';
  const root=document.querySelector('[data-investors-page]');if(!root)return;
  const icons={
    default:'<circle cx="12" cy="12" r="9"></circle><path d="M12 8v4l3 2"></path>',
    person:'<circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path>',
    company:'<rect x="4" y="3" width="16" height="18" rx="1"></rect><path d="M9 8h2M13 8h2M9 12h2M13 12h2"></path>',
    fund:'<path d="M3 21h18M5 21V7l7-4 7 4v14"></path>',
    angel:'<path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"></path>',
    family:'<path d="M3 21h18M6 21v-8M18 21v-8M4 13l8-8 8 8"></path>',
    ready:'<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><path d="M22 4L12 14.01l-3-3"></path>',
    security:'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
    flexible:'<path d="M8 3H5a2 2 0 0 0-2 2v3M21 8V5a2 2 0 0 0-2-2h-3M3 16v3a2 2 0 0 0 2 2h3M16 21h3a2 2 0 0 0 2-2v-3"></path>',
    transparency:'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>',
    speed:'<circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>',
    money:'<line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>'
  };
  function element(tag,className,text){const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;}
  function svg(key){const node=document.createElementNS('http://www.w3.org/2000/svg','svg');node.setAttribute('viewBox','0 0 24 24');node.setAttribute('fill','none');node.setAttribute('stroke','currentColor');node.setAttribute('stroke-width','2');node.setAttribute('stroke-linecap','round');node.setAttribute('stroke-linejoin','round');node.setAttribute('aria-hidden','true');node.innerHTML=icons[key]||icons.default;return node;}
  function grouped(rows){return rows.reduce(function(result,item){(result[item.section_key]||(result[item.section_key]=[])).push(item);return result;},{});}
  function setSection(name,visible){const section=root.querySelector('[data-investors-section="'+name+'"]');if(section)section.hidden=!visible;}
  function link(url,className,label){
    if(!url||!label)return null;
    const route=/^https?:\/\//i.test(url)?url:url.replace(/\.html(?=[?#]|$)/,'.php');
    const resolved=window.STC_API.link(route);
    if(!/^https?:$/.test(new URL(resolved,location.href).protocol))return null;
    const node=element('a',className,label);node.href=resolved;
    if(/^https?:\/\//i.test(url)){node.target='_blank';node.rel='noopener noreferrer';}return node;
  }
  function head(item,target){target.replaceChildren();if(!item)return;if(item.subtitle)target.append(element('span','eyebrow',item.subtitle));target.append(element('h2','section-title mt-16',item.title||''));if(item.body)target.append(element('p','section-lead',item.body));}
  function typeCard(item){const card=element('article','investor-type-card');const icon=element('div','sector-ico');icon.append(svg(item.icon_key));card.append(icon,element('b','',item.title||''),element('span','',item.subtitle||''));return card;}
  function benefitCard(item,index){const card=element('article','investor-benefit-card'+(item.badge_style==='orange'?' priority':''));const icon=element('div','sector-ico');icon.append(svg(item.icon_key));card.append(icon,element('span','benefit-rank',String(index+1).padStart(2,'0')),element('h3','',item.title||''),element('p','',item.body||''));return card;}
  function journeyStep(item,index){const step=element('div','jstep'+(item.badge_style==='orange'?' hl':''));step.append(element('div','jn',String(index+1).padStart(2,'0')));const body=element('div','jb');body.append(element('h4','',item.title||''),element('p','',item.body||''));step.append(body);return step;}
  function faqItem(item,index){const box=element('div','acc-item');const answerId='investor-faq-'+index;const button=element('button','acc-q',item.title||'');button.type='button';button.setAttribute('aria-expanded','false');button.setAttribute('aria-controls',answerId);button.addEventListener('click',function(){if(typeof window.toggleAcc==='function')window.toggleAcc(button);});const icon=element('span','ico');const plus=svg('default');plus.innerHTML='<path d="M12 5v14M5 12h14"></path>';icon.append(plus);button.append(icon);const answer=element('div','acc-a');answer.id=answerId;answer.hidden=true;answer.setAttribute('aria-hidden','true');answer.append(element('p','',item.body||''));box.append(button,answer);return box;}
  function render(rows,sections){
    const data=grouped(rows);
    const hero=data.hero&&data.hero[0];setSection('hero',!!hero);if(hero){const heroRoot=root.querySelector('[data-investors-hero]');const intro=element('div');if(hero.subtitle)intro.append(element('span','eyebrow',hero.subtitle));intro.append(element('h1','mt-16',hero.title||''));const side=element('div','investor-hero-side');side.append(element('p','',hero.body||''));const proof=element('div','investor-hero-proof');proof.setAttribute('aria-label','ضمانات تجربة المستثمر');String(hero.value_suffix||'').split(/[,،]+/).map(function(value){return value.trim();}).filter(Boolean).forEach(function(value){proof.append(element('span','',value));});if(proof.children.length)side.append(proof);const actions=element('div','cta-actions');const primary=link(hero.primary_url,'btn btn-primary btn-lg',hero.badge_label);const secondary=link(hero.secondary_url,'btn btn-ghost btn-lg',hero.value_text);if(primary)actions.append(primary);if(secondary)actions.append(secondary);if(actions.children.length)side.append(actions);heroRoot.replaceChildren(intro,side);}
    const types=data.investor_type||[];setSection('investor_type',types.length>0);root.querySelector('[data-investor-types]').replaceChildren.apply(root.querySelector('[data-investor-types]'),types.map(typeCard));
    const benefits=data.benefit||[];const benefitsHead=data.benefits_header&&data.benefits_header[0];setSection('benefit',benefits.length>0||!!benefitsHead);head(benefitsHead,root.querySelector('[data-benefits-head]'));root.querySelector('[data-investor-benefits]').replaceChildren.apply(root.querySelector('[data-investor-benefits]'),benefits.map(benefitCard));
    const journey=data.journey_step||[];const journeyHead=data.journey_header&&data.journey_header[0];setSection('journey',journey.length>0||!!journeyHead);const journeyCopy=root.querySelector('[data-journey-head]');head(journeyHead,journeyCopy);if(journeyHead){const start=link(journeyHead.primary_url,'btn btn-primary mt-24',journeyHead.badge_label);if(start)journeyCopy.append(start);}const journeyRoot=root.querySelector('[data-investor-journey]');journeyRoot.replaceChildren(element('div','journey-line'));journey.forEach(function(item,index){journeyRoot.append(journeyStep(item,index));});
    const faq=data.faq||[];const faqHead=data.faq_header&&data.faq_header[0];setSection('faq',faq.length>0||!!faqHead);head(faqHead,root.querySelector('[data-faq-head]'));root.querySelector('[data-investor-faq]').replaceChildren.apply(root.querySelector('[data-investor-faq]'),faq.map(faqItem));
    const cta=data.cta&&data.cta[0];setSection('cta',!!cta);if(cta){const box=root.querySelector('[data-investors-cta]');box.replaceChildren();if(cta.subtitle)box.append(element('span','cta-kicker',cta.subtitle));box.append(element('h2','',cta.title||''),element('p','',cta.body||''));const actions=element('div','cta-actions');const primary=link(cta.primary_url,'btn btn-primary btn-lg',cta.badge_label);const secondary=link(cta.secondary_url,'btn btn-ghost btn-lg',cta.value_text);if(primary)actions.append(primary);if(secondary)actions.append(secondary);if(actions.children.length)box.append(actions);}
    if(Array.isArray(sections))sections.slice().sort((a,b)=>a.sort_order-b.sort_order).forEach(item=>{
      const section=root.querySelector('[data-investors-section="'+item.key+'"]');
      if(section){if(!item.is_active)section.hidden=true;root.append(section);}
    });
    root.setAttribute('aria-busy','false');
  }
  function error(){let box=root.querySelector('.investors-api-error');if(!box){box=element('div','public-opportunities-empty investors-api-error');root.prepend(box);}box.replaceChildren(element('b','','تعذر تحميل صفحة المستثمرين'),element('p','','تحقق من الاتصال ثم حاول مرة أخرى.'));const retry=element('button','btn btn-soft btn-sm','إعادة المحاولة');retry.type='button';retry.addEventListener('click',load);box.append(retry);root.setAttribute('aria-busy','false');}
  let pending=false,lastPayload=null;
  function load(){
    if(pending)return;pending=true;
    return fetch(window.STC_API.url('investors.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)})
      .then(function(response){if(!response.ok)throw new Error();return response.json();})
      .then(function(payload){
        if(!payload.ok||!Array.isArray(payload.data))throw new Error();
        const old=root.querySelector('.investors-api-error');if(old)old.remove();
        const signature=JSON.stringify([payload.data,payload.meta?.sections]);
        if(signature!==lastPayload){render(payload.data,payload.meta?.sections);lastPayload=signature;}
      }).catch(error).finally(function(){pending=false;});
  }
  load();window.setInterval(function(){if(!document.hidden)load();},30000);
})();
