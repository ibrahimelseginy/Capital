(function () {
  'use strict';
  const root=document.querySelector('[data-home-page]');
  if(!root)return;
  let version='',pending=false,loaded=root.dataset.homeLoaded==='true';
  const dialog=document.createElement('dialog');
  dialog.className='home-story-dialog';dialog.setAttribute('aria-labelledby','home-dialog-title');
  const close=document.createElement('button');close.type='button';close.className='home-dialog-close';close.textContent='×';close.setAttribute('aria-label','إغلاق المعاينة');
  const body=document.createElement('div');dialog.append(close,body);document.body.append(dialog);
  let trigger=null,oldOverflow='';
  function closeDialog(){dialog.close();}
  close.addEventListener('click',closeDialog);
  dialog.addEventListener('click',event=>{if(event.target===dialog){const r=dialog.getBoundingClientRect();if(event.clientX<r.left||event.clientX>r.right||event.clientY<r.top||event.clientY>r.bottom)closeDialog();}});
  dialog.addEventListener('close',()=>{document.body.style.overflow=oldOverflow;if(trigger&&trigger.isConnected)trigger.focus();});
  function links(container){container.querySelectorAll('a[data-home-link]').forEach(a=>a.setAttribute('href',window.STC_API.link(a.getAttribute('href'))));}
  root.addEventListener('click',event=>{
    const button=event.target.closest('[data-home-story]');if(!button)return;
    const template=document.getElementById(button.dataset.homeStory);if(!template)return;
    body.replaceChildren(template.content.cloneNode(true));body.querySelector('[data-dialog-title]').id='home-dialog-title';links(body);
    trigger=button;oldOverflow=document.body.style.overflow;document.body.style.overflow='hidden';dialog.showModal();close.focus();
  });
  function status(message){
    let box=document.querySelector('[data-home-load-error]');
    if(!box){box=document.createElement('div');box.dataset.homeLoadError='';box.className='home-load-error';box.setAttribute('role','status');root.before(box);}
    box.textContent=message+' ';
    const retry=document.createElement('button');retry.type='button';retry.textContent='إعادة المحاولة';retry.addEventListener('click',()=>load(true));box.append(retry);
  }
  async function load(force){
    if(pending||dialog.open||(!force&&(document.hidden||root.contains(document.activeElement))))return;
    pending=true;
    try{
      const response=await fetch(window.STC_API.url('home.php'),{headers:{Accept:'application/json'},credentials:'omit',cache:'no-store',signal:AbortSignal.timeout(10000)});
      const payload=await response.json();if(!response.ok||!payload.ok||typeof payload.html!=='string')throw new Error();
      if(version!==payload.version){
        // HTML is produced by our escaped PHP templates, not raw editor markup.
        root.innerHTML=payload.html;links(root);
        // New API nodes are not observed by app.js; use the design system's visible state.
        root.querySelectorAll('.reveal').forEach(el=>el.classList.add('in'));
        version=payload.version;
      }
      loaded=true;document.querySelector('[data-home-load-error]')?.remove();
    }catch(error){status(loaded?'تعذر تحديث المحتوى؛ المعروض هو آخر إصدار تم تحميله.':'تعذر تحميل المحتوى المحفوظ؛ المعروض نسخة العرض الأولية. تحقق من تشغيل الخادم وقاعدة البيانات.');}
    finally{pending=false;root.setAttribute('aria-busy','false');}
  }
  links(root);load(true);
  window.setInterval(()=>load(false),15000);
  window.addEventListener('focus',()=>load(false));
})();
