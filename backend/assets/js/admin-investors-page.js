(function(){
  'use strict';
  const manager=document.querySelector('[data-admin-investors-page-manager]');if(!manager)return;
  const allowed=['create_investor_page_item','update_investor_page_item','delete_investor_page_item'];
  manager.querySelectorAll('[data-admin-investors-page-form]').forEach(function(form){
    form.addEventListener('submit',function(event){
      const submitter=event.submitter;const hidden=form.querySelector('input[name="action"]');const action=submitter&&submitter.name==='action'?submitter.value:(hidden?hidden.value:'');
      if(!allowed.includes(action))return;event.preventDefault();if(action!=='delete_investor_page_item'&&!form.reportValidity())return;
      const data=new FormData(form);data.set('action',action);const buttons=form.querySelectorAll('button[type="submit"],button:not([type])');buttons.forEach(function(button){button.disabled=true;});if(submitter)submitter.classList.add('is-loading');
      fetch('../api/admin-investors-page.php',{method:'POST',body:data,headers:{Accept:'application/json'},credentials:'same-origin'})
        .then(function(response){return response.json().catch(function(){return {};}).then(function(payload){if(!response.ok||!payload.ok)throw new Error(payload.message||'تعذر تنفيذ الطلب.');return payload;});})
        .then(function(payload){if(typeof window.showToast==='function')window.showToast(payload.message||'تم حفظ التغييرات.','success');else window.alert(payload.message||'تم حفظ التغييرات.');window.setTimeout(function(){window.location.reload();},450);})
        .catch(function(error){buttons.forEach(function(button){button.disabled=false;});if(submitter)submitter.classList.remove('is-loading');if(typeof window.showToast==='function')window.showToast(error.message,'error');else window.alert(error.message);});
    });
  });
})();
