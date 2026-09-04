(function () {
  'use strict';

  const root=document.querySelector('[data-news-events]');
  const featuredRoot=document.querySelector('[data-featured-content]');
  const newsGrid=document.querySelector('[data-knowledge-grid]');
  const eventsList=document.querySelector('[data-events-list]');
  if(!root||!featuredRoot||!newsGrid||!eventsList)return;

  function element(tag,className,text){
    const node=document.createElement(tag);
    if(className)node.className=className;
    if(typeof text==='string')node.textContent=text;
    return node;
  }

  function parsedDate(value){
    const date=new Date(value||'');
    return Number.isNaN(date.getTime())?null:date;
  }

  function formatDate(value){
    const date=parsedDate(value);
    return date?new Intl.DateTimeFormat('ar-EG',{day:'numeric',month:'long',year:'numeric'}).format(date):'تاريخ غير محدد';
  }

  function badgeClass(item){
    if(item.content_type==='article')return 'badge-info';
    if(item.content_type==='update')return 'badge-warning';
    if(String(item.category_label||'').includes('شراكة'))return 'badge-success';
    return 'badge-orange';
  }

  function safeExternalLink(url,className,text){
    if(!url)return null;
    const link=element('a',className,text);
    link.href=url; link.target='_blank'; link.rel='noopener noreferrer';
    return link;
  }

  function message(text,detail,isError){
    const box=element('div','public-opportunities-empty knowledge-api-message'+(isError?' is-error':''));
    box.append(element('b','',text));
    if(detail)box.append(element('p','',detail));
    if(isError){
      const retry=element('button','btn btn-soft btn-sm','إعادة المحاولة');
      retry.type='button'; retry.addEventListener('click',load); box.append(retry);
    }
    return box;
  }

  function featuredArticle(item){
    const article=element('article','featured-article');
    const visual=element('div','featured-visual');
    if(item.cover_image){
      const image=element('img');
      image.src=item.cover_image; image.alt='غلاف '+(item.title||'المحتوى المميز'); image.loading='lazy'; image.decoding='async';
      visual.append(image);
    }else{
      visual.classList.add('is-empty');
      visual.append(element('span','featured-image-empty','لا توجد صورة غلاف'));
    }
    visual.append(element('span','badge badge-orange','مميّز'));

    const copy=element('div','featured-copy');
    copy.append(element('span','badge '+badgeClass(item),item.category_label||'محتوى'));
    copy.append(element('h2','mt-16',item.title||''));
    copy.append(element('p','',item.excerpt||''));
    const meta=element('div','article-meta');
    meta.append(element('span','',formatDate(item.published_at)));
    if(item.reading_time)meta.append(element('span','',item.reading_time));
    copy.append(meta);
    const read=safeExternalLink(item.external_url,'btn btn-soft btn-sm mt-24','اقرأ المحتوى');
    if(read)copy.append(read);else copy.append(element('span','knowledge-link-unavailable mt-24','لا يوجد رابط قراءة منشور'));
    article.append(visual,copy);
    return article;
  }

  function knowledgeCard(item){
    const card=element(item.external_url?'a':'article','knowledge-card');
    if(item.external_url){card.href=item.external_url;card.target='_blank';card.rel='noopener noreferrer';}
    const meta=element('div','knowledge-card-meta');
    meta.append(element('span','badge '+badgeClass(item),item.category_label||'محتوى'),element('time','',formatDate(item.published_at)));
    card.append(meta,element('h3','',item.title||''),element('p','',item.excerpt||''));
    card.append(element('span','knowledge-more',item.external_url?'اقرأ المزيد':'بدون رابط خارجي'));
    return card;
  }

  function eventCard(item){
    const date=parsedDate(item.starts_at);
    const capacity=Number.isFinite(Number(item.capacity))?Number(item.capacity):0;
    const registered=Math.max(0,Number(item.registered_count)||0);
    const full=capacity>0&&registered>=capacity;
    const article=element('article','event-card');

    const dateBox=element('div','event-date');
    dateBox.append(element('b','',date?new Intl.DateTimeFormat('ar-EG',{day:'numeric'}).format(date):'—'));
    dateBox.append(element('span','',date?new Intl.DateTimeFormat('ar-EG',{month:'long'}).format(date):'غير محدد'));

    const copy=element('div','event-copy');
    const badges=element('div','row gap-8 flex-wrap');
    let location=item.location||'المكان غير محدد';
    if(date)location+=' · '+new Intl.DateTimeFormat('ar-EG',{hour:'numeric',minute:'2-digit'}).format(date);
    badges.append(element('span','badge badge-info',location),element('span','badge '+(full?'badge-warning':'badge-success'),full?'قائمة انتظار':'متاح'));
    copy.append(badges,element('h3','',item.title||''),element('p','',item.description||''));
    if(capacity>0){
      const progress=element('div','event-progress');
      const track=element('div','progress'); const bar=element('span');
      bar.style.width=Math.min(100,Math.round((registered/capacity)*100))+'%'; track.append(bar);
      progress.append(track,element('span','',registered+'/'+capacity)); copy.append(progress);
    }

    const actions=element('div','event-actions');
    const register=safeExternalLink(item.registration_url,'btn '+(full?'btn-ghost':'btn-primary'),full?'انضم لقائمة الانتظار':'سجّل الآن');
    if(register)actions.append(register);
    else{const disabled=element('button','btn btn-ghost','التسجيل غير متاح');disabled.type='button';disabled.disabled=true;actions.append(disabled);}
    const calendar=safeExternalLink(item.calendar_url,'btn btn-ghost btn-sm','+ للتقويم');
    if(calendar)actions.append(calendar);

    article.append(dateBox,copy,actions);
    return article;
  }

  function render(payload){
    const content=Array.isArray(payload.content)?payload.content:[];
    const events=Array.isArray(payload.events)?payload.events:[];
    if(content.length){
      const featured=content.find(function(item){return item.is_featured;})||content[0];
      featuredRoot.replaceChildren(featuredArticle(featured));
      const remaining=content.filter(function(item){return item.id!==featured.id;});
      newsGrid.replaceChildren.apply(newsGrid,remaining.length?remaining.map(knowledgeCard):[message('لا توجد أخبار إضافية منشورة','أضف محتوى جديدًا من لوحة الإدارة.',false)]);
    }else{
      featuredRoot.replaceChildren(message('لا توجد أخبار أو مقالات منشورة','ستظهر البيانات هنا بعد إضافتها ونشرها من الإدارة.',false));
      newsGrid.replaceChildren();
    }
    eventsList.replaceChildren.apply(eventsList,events.length?events.map(eventCard):[message('لا توجد فعاليات منشورة','ستظهر الفعاليات هنا بعد إضافتها ونشرها من الإدارة.',false)]);
    featuredRoot.setAttribute('aria-busy','false'); newsGrid.setAttribute('aria-busy','false'); eventsList.setAttribute('aria-busy','false');
  }

  function handleFallback(){render({"ok": true, "content": [{"id": "CNT-001", "title": "منهجية تقليل مخاطر التنفيذ في الاستثمار الجريء", "content_type": "article", "category_label": "مقال", "excerpt": "كيف نُجهّز المشروع تقنيًا وتشغيليًا قبل تفعيل رأس المال، ولماذا يصنع ذلك فرصًا أكثر جاهزية للنمو.", "reading_time": "قراءة 6 دقائق", "cover_image": "assets/img/knowledge-risk-cover.png", "external_url": "news-events.html", "is_featured": 1, "published_at": "2026-07-10 12:00:00"}, {"id": "CNT-002", "title": "إطلاق الإصدار التشغيلي الأول", "content_type": "news", "category_label": "خبر", "excerpt": "نطلق بوابتَي المستثمر ورائد الأعمال ولوحة الإدارة خلال 10 أيام.", "reading_time": "", "cover_image": "", "external_url": "news-events.html", "is_featured": 0, "published_at": "2026-07-17 12:00:00"}, {"id": "CNT-003", "title": "توسع مؤسسي نحو السعودية والإمارات", "content_type": "news", "category_label": "شراكة", "excerpt": "خطة توسع جغرافي مع فصل بيانات كل دولة وفق الإقامة القانونية.", "reading_time": "", "cover_image": "", "external_url": "news-events.html", "is_featured": 0, "published_at": "2026-07-02 12:00:00"}], "events": [{"id": "EVT-001", "title": "يوم الاستثمار التقني", "starts_at": "2026-07-23 17:00:00", "location": "هجين · Zoom", "description": "عرض منهجية الصندوق ولقاء مباشر مع الفريق.", "capacity": 60, "registered_count": 42, "registration_url": "", "calendar_url": ""}, {"id": "EVT-002", "title": "ورشة: تجهيز مشروعك للاستثمار", "starts_at": "2026-07-30 18:00:00", "location": "عن بُعد · Google Meet", "description": "لرواد الأعمال — كيف تبني ملفًا استثماريًا قويًا.", "capacity": 60, "registered_count": 60, "registration_url": "", "calendar_url": ""}]});}
  function load(){
    fetch(root.getAttribute('data-api')||'api/news-events.php',{headers:{Accept:'application/json'},cache:'no-store'})
      .then(function(response){if(!response.ok)throw new Error();return response.json();})
      .then(function(payload){if(!payload.ok)throw new Error();render(payload);})
      .catch(handleFallback);
  }
  load();
  window.setInterval(function(){if(!document.hidden)load();},30000);
})();
