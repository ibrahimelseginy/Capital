(function(){
  'use strict';
  const root=document.querySelector('[data-contact-page]');const form=root&&root.querySelector('[data-contact-form]');if(!root||!form)return;
  function element(tag,className,text){const node=document.createElement(tag);if(className)node.className=className;if(typeof text==='string')node.textContent=text;return node;}
  function setText(selector,value){const node=root.querySelector(selector);if(node&&typeof value==='string')node.textContent=value;}
  function setPlaceholder(selector,value){const node=root.querySelector(selector);if(node&&typeof value==='string')node.placeholder=value;}
  function office(item){const row=element('div','office-row');row.append(element('span','badge badge-'+(item.style||'orange'),item.city||''),element('span','text-2',item.description||''));return row;}
  function render(payload){
    const hero=payload.sections&&payload.sections.hero;const contact=payload.sections&&payload.sections.contact;
    if(hero){setText('[data-contact-hero-eyebrow]',hero.eyebrow);setText('[data-contact-hero-title]',hero.title);setText('[data-contact-hero-description]',hero.description);}
    if(contact){
      setText('[data-contact-form-kicker]',contact.form_kicker);setText('[data-contact-form-title]',contact.form_title);setText('[data-contact-form-description]',contact.form_description);
      setText('[data-contact-name-label]',contact.name_label);setPlaceholder('[name="name"]',contact.name_placeholder);setText('[data-contact-email-label]',contact.email_label);setPlaceholder('[name="email"]',contact.email_placeholder);
      setText('[data-contact-whatsapp-label]',contact.whatsapp_label);setPlaceholder('[name="whatsapp"]',contact.whatsapp_placeholder);setText('[data-contact-topic-label]',contact.topic_label);setText('[data-contact-message-label]',contact.message_label);setPlaceholder('[name="message"]',contact.message_placeholder);setText('[data-contact-submit-label]',contact.submit_label);
      const select=form.querySelector('[name="topic"]');select.replaceChildren(...(contact.topics||[]).map(function(item){const option=element('option','',item.label||'');option.value=item.key||'';return option;}));
      setText('[data-contact-email-title]',contact.email_title);const email=root.querySelector('[data-contact-email]');email.textContent=contact.email_address||'';email.href='mailto:'+(contact.email_address||'');
      setText('[data-contact-whatsapp-title]',contact.whatsapp_title);const whatsapp=root.querySelector('[data-contact-whatsapp]');whatsapp.textContent=contact.whatsapp_number||'';whatsapp.href=contact.whatsapp_url||'#';
      setText('[data-contact-offices-title]',contact.offices_title);root.querySelector('[data-contact-offices]').replaceChildren(...(contact.offices||[]).map(office));setText('[data-contact-notice]',contact.notice);
    }
    (payload.meta&&Array.isArray(payload.meta.sections)?payload.meta.sections:[]).slice().sort(function(a,b){return a.sort_order-b.sort_order;}).forEach(function(item){const section=root.querySelector('[data-contact-section="'+item.key+'"]');if(section){section.hidden=!item.is_active;root.append(section);}});
    root.setAttribute('aria-busy','false');
  }
  function endpoint(){return window.STC_API?window.STC_API.url('contact.php'):(root.dataset.api||'api/contact.php');}
  function showResult(message,type){const result=form.querySelector('[data-contact-result]');result.textContent=message;result.className='auth-message mt-16 '+(type==='success'?'auth-message-success':'auth-message-error');result.hidden=false;}
  function load(){root.setAttribute('aria-busy','true');fetch(endpoint(),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)}).then(function(response){if(!response.ok)throw new Error();return response.json();}).then(function(payload){if(!payload.ok||!payload.sections)throw new Error();render(payload);}).catch(function(){root.setAttribute('aria-busy','false');showResult('تعذر تحميل بيانات التواصل. أعد تحميل الصفحة.','error');});}
  form.addEventListener('submit',function(event){event.preventDefault();if(!form.reportValidity())return;const button=form.querySelector('[type="submit"]');button.disabled=true;button.classList.add('is-loading');fetch(endpoint(),{method:'POST',body:new FormData(form),headers:{Accept:'application/json'},credentials:'omit',signal:AbortSignal.timeout(15000)}).then(function(response){return response.json().catch(function(){return {};}).then(function(payload){if(!response.ok||!payload.ok)throw new Error(payload.message||'تعذر إرسال الرسالة.');return payload;});}).then(function(payload){showResult(payload.message||'تم استلام رسالتك.','success');form.reset();}).catch(function(error){showResult(error.message,'error');}).finally(function(){button.disabled=false;button.classList.remove('is-loading');});});
  load();
})();
