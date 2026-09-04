(function () {
  'use strict';
  const manager=document.querySelector('[data-home-manager]');
  if(!manager)return;
  const dirty=new Set();
  let newId=0;
  function direct(node,selector){return node.querySelector(':scope > '+selector);}
  const measure=document.createElement('canvas').getContext('2d');
  function fitControl(input){
    const field=input.closest('[data-field]');
    if(!field||!input.getClientRects().length)return;
    if(input.tagName==='TEXTAREA'){
      input.style.height='auto';
      const style=getComputedStyle(input);
      const borders=parseFloat(style.borderTopWidth)+parseFloat(style.borderBottomWidth);
      input.style.height=Math.min(220,Math.max(58,input.scrollHeight+borders))+'px';
    }else if(measure&&['text','url','select'].includes(field.dataset.type)){
      const style=getComputedStyle(input);
      measure.font=style.font;
      const value=input.tagName==='SELECT'?input.selectedOptions[0]?.textContent||'':input.value;
      const textWidth=measure.measureText(value||input.placeholder||'').width;
      const label=direct(field,'.label');
      let labelWidth=0;
      if(label){measure.font=getComputedStyle(label).font;labelWidth=Math.min(200,measure.measureText(label.textContent).width);}
      // Reserve room for padding, a caret/select arrow, and the field label.
      field.style.setProperty('--home-field-width',Math.ceil(Math.min(380,Math.max(120,labelWidth+2,textWidth+(input.tagName==='SELECT'?52:30))))+'px');
    }
  }
  let fitFrame=0;
  function fitVisibleFields(){
    cancelAnimationFrame(fitFrame);
    fitFrame=requestAnimationFrame(()=>manager.querySelectorAll('[data-input]').forEach(fitControl));
  }
  manager.addEventListener('toggle',event=>{if(event.target.open)fitVisibleFields();},true);
  manager.addEventListener('change',event=>fitControl(event.target));
  // Observe width only: fitting text itself changes the manager's height.
  if(typeof ResizeObserver!=='undefined'){
    let previousWidth=0;
    new ResizeObserver(entries=>{const width=entries[0].contentRect.width;if(width!==previousWidth){previousWidth=width;fitVisibleFields();}}).observe(manager);
  }else window.addEventListener('resize',fitVisibleFields);
  if(document.fonts)document.fonts.ready.then(fitVisibleFields);
  // Start with every section and nested card collapsed, even with a saved hash.
  // Explicit navigation and validation still reveal the requested fields.
  fitVisibleFields();
  function readFields(container){
    const result={};
    container.querySelectorAll(':scope > [data-field], :scope > .home-item-controls > [data-field]').forEach(field=>{
      const type=field.dataset.type;
      if(type==='list'){
        result[field.dataset.field]=Array.from(direct(field,'.home-editor-items').children).map(item=>readFields(item.querySelector(':scope > .home-editor-item-body > .home-editor-fields')));
      }else{
        const input=field.querySelector('[data-input]');
        result[field.dataset.field]=type==='checkbox'?input.checked:type==='number'?Number(input.value):input.value;
      }
    });
    return result;
  }
  function mark(form){if(!form)return;dirty.add(form);const result=form.querySelector('[data-home-result]');result.textContent='تغييرات غير محفوظة';result.className='';}
  manager.addEventListener('input',event=>{
    mark(event.target.closest('form'));
    fitControl(event.target);
    const field=event.target.closest('[data-field]');
    if(field&&['title','name','label','text'].includes(field.dataset.field)){
      const item=field.closest('.home-editor-item');
      if(item)direct(item,'summary').querySelector('[data-item-title]').textContent=event.target.value||'عنصر';
    }
  });
  manager.addEventListener('invalid',event=>{let parent=event.target.parentElement;while(parent&&parent!==manager){if(parent.tagName==='DETAILS')parent.open=true;parent=parent.parentElement;}},true);
  manager.addEventListener('click',event=>{
    const add=event.target.closest('[data-home-add]');
    if(add){
      const field=add.closest('[data-field]'),list=direct(field,'.home-editor-items');
      if(list.children.length>=Number(list.dataset.max)){window.alert('وصلت إلى الحد الأقصى لعناصر هذا القسم.');return;}
      const clone=direct(field,'template').content.cloneNode(true);
      clone.querySelectorAll('[id]').forEach(input=>{const old=input.id;input.id='home-new-'+(++newId);const label=clone.querySelector('label[for="'+old+'"]');if(label)label.htmlFor=input.id;});
      const order=clone.firstElementChild.querySelector(':scope > .home-editor-item-body > .home-editor-fields > .home-item-controls > [data-field="sort_order"] [data-input]');
      if(order)order.value=String(list.children.length+1);
      list.append(clone);fitVisibleFields();mark(add.closest('form'));return;
    }
    const remove=event.target.closest('[data-home-remove]');
    if(remove&&window.confirm('حذف العنصر من هذا القسم؟ لن يطبق الحذف إلا بعد الحفظ.')){const form=remove.closest('form');remove.closest('.home-editor-item').remove();mark(form);}
    const nav=event.target.closest('.home-editor-nav a');
    if(nav){const section=document.getElementById(nav.hash.slice(1));if(section)section.open=true;}
  });
  manager.addEventListener('submit',async event=>{
    const form=event.target.closest('[data-home-form]');if(!form)return;event.preventDefault();
    const result=form.querySelector('[data-home-result]');
    const controls=Array.from(form.querySelectorAll('input,textarea,select,button'));
    const content=readFields(direct(form,'.home-editor-fields'));
    const body=new FormData();body.set('csrf',form.querySelector('[name=csrf]').value);body.set('section',form.dataset.section);body.set('revision',form.dataset.revision);body.set('content',JSON.stringify(content));
    controls.forEach(input=>input.disabled=true);result.textContent='جارٍ حفظ القسم…';result.className='';
    try{
      const response=await fetch(manager.dataset.endpoint||'../api/admin-home.php',{method:'POST',body,credentials:'same-origin',headers:{Accept:'application/json'},signal:AbortSignal.timeout(15000)});
      const payload=await response.json();if(!response.ok||!payload.ok)throw new Error(payload.message||'تعذر الحفظ.');
      form.dataset.revision=String(payload.revision);dirty.delete(form);result.textContent=payload.message;result.className='is-success';
      // Persist IDs allocated to new cards so a second save updates the same records.
      if(Array.isArray(payload.item_ids))form.querySelectorAll(':scope > .home-editor-fields > [data-field="items"] > .home-editor-items > .home-editor-item').forEach((item,index)=>{
        const id=item.querySelector(':scope > .home-editor-item-body > .home-editor-fields > [data-field="id"] [data-input]');
        if(id&&payload.item_ids[index])id.value=payload.item_ids[index];
      });
      form.closest('details').querySelector('[data-section-state]').textContent=content.is_active?'ظاهر':'مخفي';
    }catch(error){result.textContent=error.message||'تعذر الاتصال. أعد المحاولة.';result.className='is-error';}
    finally{controls.forEach(input=>input.disabled=false);}
  });
  window.addEventListener('beforeunload',event=>{if(dirty.size){event.preventDefault();event.returnValue='';}});
})();
