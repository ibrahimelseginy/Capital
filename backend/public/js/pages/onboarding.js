/**
 * SEVEN TECH CAPITAL — Onboarding Flows
 * 8-step investor + 8-step entrepreneur onboarding
 */
import LangManager from '../language.js';

export function investorOnboardingPage() {
  const isAr = typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
  const t = (en, ar) => isAr ? ar : en;
  const req = '<span class="required">*</span>';

  const steps = isAr
    ? ['المعلومات الشخصية','الملف الاستثماري','الخبرة','التفضيلات','المستندات','اتفاقية السرية','المراجعة','إرسال']
    : ['Personal Info','Investment Profile','Experience','Preferences','Documents','NDA','Review','Submit'];

  return `
  <style>
    .onboarding-section {
      background: var(--bg-surface);
      border: 1px solid var(--border-default);
      border-radius: var(--radius-lg);
      padding: var(--space-8);
      margin-bottom: var(--space-8);
      box-shadow: var(--shadow-sm);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    .onboarding-section:hover {
      box-shadow: 0 12px 32px rgba(0,0,0,0.08);
      border-color: rgba(196,164,119,0.3);
    }
    .onboarding-section::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--action-primary) 0%, transparent 100%);
      opacity: 0;
      transition: opacity 0.3s;
    }
    .onboarding-section:hover::before {
      opacity: 1;
    }
    .onboarding-input {
      padding: 1.1rem;
      background: var(--bg-secondary);
      border: 1px solid transparent;
      border-radius: var(--radius-md);
      transition: all 0.3s ease;
      font-size: 1rem;
      width: 100%;
    }
    .onboarding-input:focus {
      border-color: var(--action-primary);
      background: var(--bg-primary);
      box-shadow: 0 0 0 4px rgba(196,164,119,0.1);
      outline: none;
    }
    .step-title {
      font-size: 1.3rem;
      font-weight: var(--weight-bold);
      margin-bottom: var(--space-6);
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: var(--space-3);
    }
    .step-icon {
      width: 36px; height: 36px;
      border-radius: 10px;
      background: rgba(196,164,119,0.15);
      color: var(--action-primary);
      display: flex; align-items: center; justify-content: center;
    }
    .file-upload-box {
      border: 2px dashed var(--border-default);
      border-radius: var(--radius-lg);
      padding: var(--space-6);
      text-align: center;
      transition: all 0.3s;
      background: rgba(196,164,119,0.02);
      cursor: pointer;
    }
    .file-upload-box:hover {
      border-color: var(--action-primary);
      background: rgba(196,164,119,0.08);
    }
    @media (max-width: 768px) {
      .onboarding-section { padding: var(--space-5); }
    }
  </style>
  <section class="min-h-screen" style="background:var(--bg-secondary);padding: 120px var(--space-4) var(--space-12)">
    <div style="max-width:800px;margin:0 auto">
      <div class="text-center mb-10 reveal"><a href="#/"><img src="${window.__stcLogo || 'Group 102.png'}" id="onboarding-logo" alt="STC" style="height: 90px !important; width: auto !important; margin: 0 auto; display: block; max-width: 300px;"></a></div>
      
      <div class="text-center mb-12 reveal" style="animation-delay:0.1s">
        <h1 class="text-display-sm mb-4" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${t('Investor Application', 'طلب انضمام مستثمر')}</h1>
        <p class="text-body-lg text-secondary" style="max-width:500px;margin:0 auto">${t('Complete the following steps to apply as an investor at SEVEN TECH CAPITAL.', 'أكمل الخطوات التالية للتقديم كمستثمر في سفن تك كابيتال.')}</p>
      </div>
      
      <!-- Stepper -->
      <div class="stepper mb-12 reveal" style="animation-delay:0.2s;background:var(--bg-surface);padding:var(--space-6);border-radius:var(--radius-lg);box-shadow:var(--shadow-sm);border:1px solid var(--border-default)">
        ${steps.map((label, i) => `
        <div class="stepper-item ${i === 0 ? 'active' : ''}">
          <div class="stepper-number" style="${i===0?'box-shadow:0 0 0 4px rgba(196,164,119,0.2)':''}">${i + 1}</div>
          <span class="stepper-label text-body-sm" style="${i===0?'font-weight:var(--weight-bold);color:var(--text-primary)':''}">${label}</span>
        </div>`).join('')}
      </div>

      <form class="d-flex flex-col gap-6 reveal" style="animation-delay:0.3s">
        
        <!-- Step 1: Personal Info -->
        <div class="onboarding-section">
          <h2 class="step-title">
            <div class="step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            ${t('Step 1: Personal Information', 'الخطوة 1: المعلومات الشخصية')}
          </h2>
          <div class="grid-2" style="gap:var(--space-5);margin-bottom:var(--space-5)">
            <div class="form-group"><label class="form-label text-secondary">${t('Full Name (English)', 'الاسم الكامل (إنجليزي)')} ${req}</label><input type="text" class="onboarding-input" required></div>
            <div class="form-group"><label class="form-label text-secondary">${t('Full Name (Arabic)', 'الاسم الكامل (عربي)')}</label><input type="text" class="onboarding-input" dir="rtl"></div>
          </div>
          <div class="grid-2" style="gap:var(--space-5);margin-bottom:var(--space-5)">
            <div class="form-group"><label class="form-label text-secondary">${t('Email', 'البريد الإلكتروني')} ${req}</label><input type="email" class="onboarding-input" required></div>
            <div class="form-group"><label class="form-label text-secondary">${t('Phone', 'رقم الهاتف')} ${req}</label><input type="tel" class="onboarding-input" required dir="ltr"></div>
          </div>
          <div class="grid-2" style="gap:var(--space-5);margin-bottom:var(--space-5)">
            <div class="form-group"><label class="form-label text-secondary">${t('Nationality', 'الجنسية')} ${req}</label><select class="onboarding-input form-select" required><option value="">${t('Select...', 'اختر...')}</option><option>${t('Saudi Arabia', 'السعودية')}</option><option>${t('UAE', 'الإمارات')}</option><option>${t('Kuwait', 'الكويت')}</option><option>${t('Bahrain', 'البحرين')}</option><option>${t('Oman', 'عمان')}</option><option>${t('Qatar', 'قطر')}</option><option>${t('Other', 'أخرى')}</option></select></div>
            <div class="form-group"><label class="form-label text-secondary">${t('Country of Residence', 'بلد الإقامة')}</label><select class="onboarding-input form-select"><option value="">${t('Select...', 'اختر...')}</option><option>${t('Saudi Arabia', 'السعودية')}</option><option>${t('UAE', 'الإمارات')}</option><option>${t('Other', 'أخرى')}</option></select></div>
          </div>
          <div class="form-group"><label class="form-label text-secondary">${t('National ID / Passport Number', 'رقم الهوية الوطنية / جواز السفر')}</label><input type="text" class="onboarding-input"></div>
        </div>

        <!-- Step 2: Investment Profile -->
        <div class="onboarding-section">
          <h2 class="step-title">
            <div class="step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
            ${t('Step 2: Investment Profile', 'الخطوة 2: الملف الاستثماري')}
          </h2>
          <div class="grid-2" style="gap:var(--space-5);margin-bottom:var(--space-5)">
            <div class="form-group"><label class="form-label text-secondary">${t('Investor Type', 'نوع المستثمر')} ${req}</label><select class="onboarding-input form-select" required><option value="">${t('Select...', 'اختر...')}</option><option>${t('Individual', 'فرد')}</option><option>${t('Institutional', 'مؤسسي')}</option><option>${t('Family Office', 'مكتب عائلة')}</option><option>${t('Corporate', 'شركة')}</option><option>${t('Fund', 'صندوق')}</option></select></div>
            <div class="form-group"><label class="form-label text-secondary">${t('Investment Budget Range', 'نطاق الميزانية الاستثمارية')} ${req}</label><select class="onboarding-input form-select" required><option value="">${t('Select...', 'اختر...')}</option><option>$50K - $200K</option><option>$200K - $500K</option><option>$500K - $1M</option><option>$1M - $5M</option><option>$5M+</option></select></div>
          </div>
          <div class="form-group"><label class="form-label text-secondary">${t('Investment Horizon', 'أفق الاستثمار')}</label><select class="onboarding-input form-select"><option>${t('3-5 years', '3-5 سنوات')}</option><option>${t('5-7 years', '5-7 سنوات')}</option><option>${t('7-10 years', '7-10 سنوات')}</option><option>${t('10+ years', '10+ سنوات')}</option></select></div>
        </div>

        <!-- Step 3: Experience -->
        <div class="onboarding-section">
          <h2 class="step-title">
            <div class="step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
            ${t('Step 3: Experience', 'الخطوة 3: الخبرة')}
          </h2>
          <div class="form-group mb-5"><label class="form-label text-secondary">${t('Previous Investment Experience', 'خبرة الاستثمار السابقة')}</label><textarea class="onboarding-input" rows="3" placeholder="${t('Describe your investment background, previous investments, and sectors of interest...', 'صف خلفيتك الاستثمارية، والاستثمارات السابقة، والقطاعات التي تهتم بها...')}"></textarea></div>
          <div class="form-group mb-5"><label class="form-label text-secondary">${t('Sectors of Interest', 'القطاعات ذات الاهتمام')}</label>
            <div class="d-flex gap-3 flex-wrap mt-3">
              ${(isAr ? ['التقنية المالية','الذكاء الاصطناعي والبيانات','التقنية العقارية','التقنية الصحية','تقنية التعليم','الخدمات اللوجستية','التجارة الإلكترونية','SaaS'] : ['FinTech','AI & Data','PropTech','HealthTech','EdTech','Logistics','E-Commerce','SaaS']).map(s => `
              <label class="chip" style="cursor:pointer;padding:var(--space-3) var(--space-4);border-radius:24px;border:1px solid var(--border-default)"><input type="checkbox" style="display:none" onchange="this.parentElement.style.borderColor=this.checked?'var(--action-primary)':'var(--border-default)';this.parentElement.style.background=this.checked?'rgba(196,164,119,0.1)':'transparent';this.parentElement.style.color=this.checked?'var(--action-primary)':'var(--text-primary)'"><span style="font-weight:var(--weight-medium)">${s}</span></label>
              `).join('')}
            </div>
          </div>
          <div class="form-group"><label class="form-label text-secondary">${t('How did you hear about us?', 'كيف سمعت عنا؟')}</label><select class="onboarding-input form-select"><option value="">${t('Select...', 'اختر...')}</option><option>${t('Referral', 'إحالة')}</option><option>${t('Event', 'فعالية')}</option><option>${t('Social Media', 'وسائل التواصل الاجتماعي')}</option><option>${t('Website', 'الموقع الإلكتروني')}</option><option>${t('Press', 'الصحافة')}</option><option>${t('Other', 'أخرى')}</option></select></div>
        </div>

        <!-- Step 4: Documents -->
        <div class="onboarding-section">
          <h2 class="step-title">
            <div class="step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 3v5h5"/><path d="M12 18v-6"/><path d="M9 15h6"/></svg></div>
            ${t('Step 4: Documents', 'الخطوة 4: المستندات')}
          </h2>
          <div class="grid-2" style="gap:var(--space-5)">
            <div class="form-group">
              <label class="form-label text-secondary">${t('ID Document', 'مستند الهوية')} ${req}</label>
              <div class="file-upload-box d-flex flex-col items-center justify-center gap-3">
                <div style="width:56px;height:56px;border-radius:50%;background:rgba(196,164,119,0.1);color:var(--action-primary);display:flex;align-items:center;justify-content:center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg></div>
                <div class="text-body" style="font-weight:var(--weight-semibold)">${t('Upload national ID or passport', 'ارفع الهوية الوطنية أو جواز السفر')}</div>
                <div class="text-caption text-tertiary">${t('PDF, JPG, PNG — Max 5MB', 'PDF, JPG, PNG — الحد الأقصى 5 ميغابايت')}</div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label text-secondary">${t('Proof of Funds (optional)', 'إثبات توفر الأموال (اختياري)')}</label>
              <div class="file-upload-box d-flex flex-col items-center justify-center gap-3">
                <div style="width:56px;height:56px;border-radius:50%;background:rgba(196,164,119,0.1);color:var(--action-primary);display:flex;align-items:center;justify-content:center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg></div>
                <div class="text-body" style="font-weight:var(--weight-semibold)">${t('Bank statement or financial proof', 'كشف حساب بنكي أو إثبات مالي')}</div>
                <div class="text-caption text-tertiary">${t('PDF — Max 10MB', 'PDF — الحد الأقصى 10 ميغابايت')}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 5: NDA Agreement -->
        <div class="onboarding-section" style="border-color:rgba(196,164,119,0.5)">
          <h2 class="step-title" style="color:var(--action-primary)">
            <div class="step-icon" style="background:var(--action-primary);color:#fff"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
            ${t('Step 5: NDA Agreement', 'الخطوة 5: اتفاقية السرية')}
          </h2>
          <div style="height:220px;overflow-y:auto;padding:var(--space-6);background:var(--bg-secondary);border-radius:var(--radius-lg);margin-bottom:var(--space-6);border:1px solid var(--border-default)">
            <p class="text-body-sm text-secondary" style="line-height:1.8">${t('This Non-Disclosure Agreement ("Agreement") is entered into as of the date of electronic signature by and between SEVEN TECH CAPITAL ("Company") and the undersigned investor ("Recipient"). The Recipient agrees to hold in confidence and not disclose any proprietary information, including but not limited to: financial projections, portfolio performance data, investment terms, venture details, strategic plans, and any other information marked as confidential...', 'تم إبرام اتفاقية عدم الإفصاح هذه ("الاتفاقية") من تاريخ التوقيع الإلكتروني بين سفن تك كابيتال ("الشركة") والمستثمر الموقّع أدناه ("المتلقي"). يوافق المتلقي على الحفاظ على السرية وعدم الإفصاح عن أي معلومات خاصة، بما في ذلك على سبيل المثال لا الحصر: التوقعات المالية، بيانات أداء المحفظة، شروط الاستثمار، تفاصيل المشاريع، الخطط الاستراتيجية، وأي معلومات أخرى مصنفة كسرية...')}</p>
            <p class="text-body-sm text-secondary mt-4" style="line-height:1.8">${t('The Recipient acknowledges that any breach of this Agreement may cause irreparable harm to the Company and its portfolio companies, and agrees that the Company shall be entitled to seek equitable relief in addition to all other remedies available at law or in equity.', 'يقر المتلقي بأن أي خرق لهذه الاتفاقية قد يسبب ضرراً لا يمكن إصلاحه للشركة وشركات محفظتها، ويوافق على أنه يحق للشركة السعي للحصول على تعويض منصف بالإضافة إلى جميع العلاجات الأخرى المتاحة بموجب القانون أو الإنصاف.')}</p>
          </div>
          <div class="form-check d-flex items-center gap-3">
            <input type="checkbox" style="width:22px;height:22px;accent-color:var(--action-primary);cursor:pointer" required>
            <span class="text-body" style="font-weight:var(--weight-medium)">${t('I have read and agree to the Non-Disclosure Agreement', 'لقد قرأت وأوافق على اتفاقية عدم الإفصاح')} ${req}</span>
          </div>
          
          <div class="mt-6 pt-6" style="border-top: 1px solid var(--border-default)">
            <label class="form-label text-secondary mb-3">${t('E-Signature', 'التوقيع الإلكتروني')} ${req}</label>
            <div>
              <canvas id="signature-pad" width="600" height="150" style="width: 100%; height: 150px; border: 1px solid var(--border-default); border-radius: var(--radius-md); background: var(--bg-primary); cursor: crosshair; touch-action: none;"></canvas>
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" onload="
                const canvas = this.previousElementSibling;
                if(!canvas || canvas.dataset.initialized) return;
                canvas.dataset.initialized = 'true';
                const ctx = canvas.getContext('2d');
                ctx.strokeStyle = '#c4a477';
                ctx.lineWidth = 2;
                let drawing = false;
                const getPos = (e) => {
                  const rect = canvas.getBoundingClientRect();
                  const evt = e.touches ? e.touches[0] : e;
                  return { x: evt.clientX - rect.left, y: evt.clientY - rect.top };
                };
                const start = (e) => { e.preventDefault(); drawing = true; const p = getPos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); };
                const move = (e) => { if(!drawing) return; e.preventDefault(); const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); };
                const stop = (e) => { drawing = false; };
                canvas.addEventListener('mousedown', start);
                canvas.addEventListener('mousemove', move);
                canvas.addEventListener('mouseup', stop);
                canvas.addEventListener('mouseout', stop);
                canvas.addEventListener('touchstart', start, {passive:false});
                canvas.addEventListener('touchmove', move, {passive:false});
                canvas.addEventListener('touchend', stop);
              " style="display:none">
            </div>
            <div class="d-flex justify-between items-center mt-2">
              <span class="text-caption text-tertiary">${t('Sign inside the box', 'وقّع داخل المربع')}</span>
              <button type="button" class="btn btn-ghost btn-sm" onclick="const c = document.getElementById('signature-pad'); const ctx = c.getContext('2d'); ctx.clearRect(0,0,c.width,c.height);">${t('Clear Signature', 'مسح التوقيع')}</button>
            </div>
          </div>
        </div>

        <!-- Step 6: Review & Submit -->
        <div class="onboarding-section" style="background:var(--bg-primary);border-width:2px;border-color:rgba(196,164,119,0.3)">
          <h2 class="step-title">
            <div class="step-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
            ${t('Step 6: Review & Submit', 'الخطوة 6: المراجعة والإرسال')}
          </h2>
          <p class="text-body text-secondary mb-8">${t('By submitting this application, you confirm that all information provided is accurate and complete. Our team will review your application within 5 business days.', 'بتقديمك هذا الطلب، فإنك تؤكد أن جميع المعلومات دقيقة وكاملة. سيقوم فريقنا بمراجعة طلبك خلال 5 أيام عمل.')}</p>
          <div class="form-check mb-5 d-flex items-center gap-3">
            <input type="checkbox" style="width:22px;height:22px;accent-color:var(--action-primary);cursor:pointer" required>
            <span class="text-body">${t('I confirm that all information provided is accurate', 'أؤكد أن جميع المعلومات دقيقة')} ${req}</span>
          </div>
          <div class="form-check mb-10 d-flex items-center gap-3">
            <input type="checkbox" style="width:22px;height:22px;accent-color:var(--action-primary);cursor:pointer" required>
            <span class="text-body">${t('I agree to the <a href="#/terms" class="text-accent">Terms of Service</a> and <a href="#/privacy" class="text-accent">Privacy Policy</a>', 'أوافق على <a href="#/terms" class="text-accent">شروط الخدمة</a> و<a href="#/privacy" class="text-accent">سياسة الخصوصية</a>')} ${req}</span>
          </div>
          
          <div class="d-flex gap-4 justify-end pt-8" style="border-top:1px solid var(--border-default)">
            <a href="#/" class="btn btn-ghost" style="padding:var(--space-4) var(--space-6);font-size:1.1rem">${t('Cancel', 'إلغاء')}</a>
            <button type="submit" class="btn btn-primary btn-lg" style="padding:var(--space-4) var(--space-8);font-size:1.1rem;box-shadow:0 8px 24px rgba(196,164,119,0.3)" onclick="if(this.closest('form').checkValidity()){event.preventDefault();this.innerHTML='<svg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'20\\' height=\\'20\\' viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\'><path d=\\'M22 11.08V12a10 10 0 1 1-5.93-9.14\\'/><polyline points=\\'22 4 12 14.01 9 11.01\\'/></svg> ${t('Application Submitted', 'تم إرسال الطلب')}';this.style.background='var(--color-success)';this.disabled=true;setTimeout(()=>window.location.hash='/dashboard/investor/overview',1500)}">${t('Submit Application', 'إرسال الطلب')}</button>
          </div>
        </div>
      </form>
    </div>
  </section>`;
}

export function entrepreneurOnboardingPage() {
  const isAr = typeof LangManager !== 'undefined' && LangManager.currentLang === 'ar';
  const t = (en, ar) => isAr ? ar : en;
  const req = '<span class="required">*</span>';

  const steps = isAr
    ? ['المعلومات الشخصية','فكرة المشروع','تحليل السوق','الفريق','الماليات','المستندات','اتفاقية السرية','إرسال']
    : ['Personal Info','Venture Idea','Market Analysis','Team','Financials','Documents','NDA','Submit'];

  return `
  <section class="min-h-screen" style="background:var(--bg-secondary);padding: 120px var(--space-4) var(--space-12)">
    <div class="container-narrow">
      <div class="text-center mb-10 reveal"><a href="#/"><img src="${window.__stcLogo || 'Group 102.png'}" id="onboarding-logo" alt="STC" style="height: 90px !important; width: auto !important; margin: 0 auto; display: block; max-width: 300px;"></a></div>
      <div class="card" style="padding:var(--space-8)">
        <h1 class="text-h3 mb-2 text-center">${t('Entrepreneur Application', 'طلب انضمام رائد أعمال')}</h1>
        <p class="text-body-sm text-secondary text-center mb-8">${t('Apply to build your venture with SEVEN TECH CAPITAL.', 'قدّم طلبك لبناء مشروعك مع سفن تك كابيتال.')}</p>
        
        <!-- Stepper -->
        <div class="stepper mb-10">
          ${steps.map((label, i) => `
          <div class="stepper-item ${i === 0 ? 'active' : ''}">
            <div class="stepper-number">${i + 1}</div>
            <span class="stepper-label">${label}</span>
          </div>`).join('')}
        </div>

        <form class="d-flex flex-col gap-5">
          <h2 class="text-h5 mb-2">${t('Step 1: Personal Information', 'الخطوة 1: المعلومات الشخصية')}</h2>
          <div class="grid-2" style="gap:var(--space-4)">
            <div class="form-group"><label class="form-label">${t('Full Name', 'الاسم الكامل')} ${req}</label><input type="text" class="form-input" required></div>
            <div class="form-group"><label class="form-label">${t('Email', 'البريد الإلكتروني')} ${req}</label><input type="email" class="form-input" required></div>
          </div>
          <div class="grid-2" style="gap:var(--space-4)">
            <div class="form-group"><label class="form-label">${t('Phone', 'رقم الهاتف')} ${req}</label><input type="tel" class="form-input" required></div>
            <div class="form-group"><label class="form-label">${t('LinkedIn', 'لينكد إن')}</label><input type="url" class="form-input" placeholder="https://linkedin.com/in/..."></div>
          </div>
          <div class="form-group"><label class="form-label">${t('Short Bio', 'نبذة مختصرة')}</label><textarea class="form-input" rows="2" placeholder="${t('Brief professional background...', 'خلفية مهنية مختصرة...')}"></textarea></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 2: Venture Idea', 'الخطوة 2: فكرة المشروع')}</h3>
          <div class="form-group"><label class="form-label">${t('Venture Name', 'اسم المشروع')} ${req}</label><input type="text" class="form-input" required></div>
          <div class="grid-2" style="gap:var(--space-4)">
            <div class="form-group"><label class="form-label">${t('Sector', 'القطاع')} ${req}</label><select class="form-input form-select" required><option value="">${t('Select...', 'اختر...')}</option><option>${t('FinTech', 'التقنية المالية')}</option><option>${t('AI & Data', 'الذكاء الاصطناعي والبيانات')}</option><option>${t('PropTech', 'التقنية العقارية')}</option><option>${t('HealthTech', 'التقنية الصحية')}</option><option>${t('EdTech', 'تقنية التعليم')}</option><option>${t('Logistics', 'الخدمات اللوجستية')}</option><option>${t('E-Commerce', 'التجارة الإلكترونية')}</option><option>SaaS</option><option>${t('Other', 'أخرى')}</option></select></div>
            <div class="form-group"><label class="form-label">${t('Stage', 'المرحلة')} ${req}</label><select class="form-input form-select" required><option value="">${t('Select...', 'اختر...')}</option><option>${t('Idea', 'فكرة')}</option><option>${t('Prototype', 'نموذج أولي')}</option><option>MVP</option><option>${t('Early Traction', 'جذب مبكر')}</option><option>${t('Growth', 'نمو')}</option></select></div>
          </div>
          <div class="form-group"><label class="form-label">${t('Problem Statement', 'بيان المشكلة')} ${req}</label><textarea class="form-input" rows="3" placeholder="${t('What problem are you solving?', 'ما المشكلة التي تحلها؟')}" required></textarea></div>
          <div class="form-group"><label class="form-label">${t('Proposed Solution', 'الحل المقترح')} ${req}</label><textarea class="form-input" rows="3" placeholder="${t('How does your venture solve this problem?', 'كيف يحل مشروعك هذه المشكلة؟')}" required></textarea></div>
          <div class="form-group"><label class="form-label">${t('Unique Value Proposition', 'القيمة الفريدة المقترحة')}</label><textarea class="form-input" rows="2" placeholder="${t('What makes your approach different?', 'ما الذي يميز نهجك عن غيره؟')}"></textarea></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 3: Market & Traction', 'الخطوة 3: السوق والجذب')}</h3>
          <div class="form-group"><label class="form-label">${t('Target Market', 'السوق المستهدف')}</label><textarea class="form-input" rows="2" placeholder="${t('Who is your target customer? What is the market size?', 'من هو عميلك المستهدف؟ ما حجم السوق؟')}"></textarea></div>
          <div class="grid-2" style="gap:var(--space-4)">
            <div class="form-group"><label class="form-label">${t('Target Geography', 'النطاق الجغرافي')}</label><select class="form-input form-select"><option>${t('MENA', 'الشرق الأوسط وشمال أفريقيا')}</option><option>${t('Saudi Arabia', 'المملكة العربية السعودية')}</option><option>${t('GCC', 'دول الخليج')}</option><option>${t('Global', 'عالمي')}</option></select></div>
            <div class="form-group"><label class="form-label">${t('Competitive Landscape', 'المشهد التنافسي')}</label><input type="text" class="form-input" placeholder="${t('Key competitors...', 'المنافسون الرئيسيون...')}"></div>
          </div>
          <div class="form-group"><label class="form-label">${t('Current Traction (if any)', 'الجذب الحالي (إن وُجد)')}</label><textarea class="form-input" rows="2" placeholder="${t('Users, revenue, partnerships, LOIs...', 'المستخدمون، الإيرادات، الشراكات...')}"></textarea></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 4: Team', 'الخطوة 4: الفريق')}</h3>
          <div class="form-group"><label class="form-label">${t('Team Size', 'حجم الفريق')}</label><select class="form-input form-select"><option>${t('Solo Founder', 'مؤسس فردي')}</option><option>2-3</option><option>4-6</option><option>7-10</option><option>+10</option></select></div>
          <div class="form-group"><label class="form-label">${t('Team Description', 'وصف الفريق')}</label><textarea class="form-input" rows="3" placeholder="${t('Key team members, roles, and relevant experience...', 'أعضاء الفريق الرئيسيون، أدوارهم، وخبراتهم...')}"></textarea></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 5: Financials', 'الخطوة 5: الماليات')}</h3>
          <div class="grid-2" style="gap:var(--space-4)">
            <div class="form-group"><label class="form-label">${t('Funding Required', 'التمويل المطلوب')}</label><select class="form-input form-select"><option value="">${t('Select range...', 'اختر النطاق...')}</option><option>$50K - $200K</option><option>$200K - $500K</option><option>$500K - $1M</option><option>$1M - $3M</option><option>+$3M</option></select></div>
            <div class="form-group"><label class="form-label">${t('Previous Funding', 'التمويل السابق')}</label><select class="form-input form-select"><option>${t('None', 'لا يوجد')}</option><option>${t('Bootstrapped', 'تمويل ذاتي')}</option><option>${t('Pre-Seed', 'ما قبل البذرة')}</option><option>${t('Seed', 'بذرة')}</option><option>${t('Series A+', 'سلسلة أ+')}</option></select></div>
          </div>
          <div class="form-group"><label class="form-label">${t('Revenue Model', 'نموذج الإيرادات')}</label><textarea class="form-input" rows="2" placeholder="${t('How will/does the venture generate revenue?', 'كيف يحقق المشروع إيراداته؟')}"></textarea></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 6: Documents', 'الخطوة 6: المستندات')}</h3>
          <div class="form-group"><label class="form-label">${t('Pitch Deck', 'العرض التقديمي')}</label><div class="file-upload"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg><span class="text-body-sm">${t('Upload pitch deck', 'ارفع العرض التقديمي')}</span><span class="text-caption text-tertiary">PDF, PPTX — ${t('Max 25MB', 'الحد الأقصى 25 ميغابايت')}</span></div></div>
          <div class="form-group"><label class="form-label">${t('Business Plan (optional)', 'خطة العمل (اختياري)')}</label><div class="file-upload"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg><span class="text-body-sm">${t('Upload business plan', 'ارفع خطة العمل')}</span><span class="text-caption text-tertiary">PDF — ${t('Max 10MB', 'الحد الأقصى 10 ميغابايت')}</span></div></div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 7: NDA Agreement', 'الخطوة 7: اتفاقية السرية')}</h3>
          <div class="card" style="padding:var(--space-6);background:var(--bg-secondary);border-color:var(--accent-gold)">
            <h4 class="text-label mb-3">${t('Non-Disclosure Agreement', 'اتفاقية عدم الإفصاح')}</h4>
            <div style="height:160px;overflow-y:auto;padding:var(--space-4);background:var(--bg-surface);border-radius:var(--radius-md);border:1px solid var(--border-default);margin-bottom:var(--space-4)">
              <p class="text-body-sm text-secondary" style="line-height:1.8">${t(
                'This Non-Disclosure Agreement is entered into by and between SEVEN TECH CAPITAL and the undersigned entrepreneur. Both parties agree to protect confidential information shared during the venture building process, including but not limited to: proprietary technology, market research, financial data, partnership details, and strategic plans...',
                'تم إبرام اتفاقية عدم الإفصاح هذه بين سفن تك كابيتال ورائد الأعمال الموقّع أدناه. يوافق الطرفان على حماية المعلومات السرية المتبادلة خلال عملية بناء المشروع، بما في ذلك على سبيل المثال لا الحصر: التقنيات الخاصة، أبحاث السوق، البيانات المالية، تفاصيل الشراكات، والخطط الاستراتيجية...'
              )}</p>
            </div>
            <div class="form-check"><input type="checkbox" class="form-check-input" required><span class="text-body-sm">${t('I have read and agree to the NDA', 'لقد قرأت وأوافق على اتفاقية عدم الإفصاح')} ${req}</span></div>
          </div>

          <h3 class="text-h5 mt-6 mb-2">${t('Step 8: Review & Submit', 'الخطوة 8: المراجعة والإرسال')}</h3>
          <div class="card" style="padding:var(--space-6);background:var(--bg-secondary)">
            <p class="text-body-sm text-secondary mb-4">${t(
              'Our team will review your application within 10 business days. We may reach out for additional information or to schedule an introductory call.',
              'سيقوم فريقنا بمراجعة طلبك خلال 10 أيام عمل. قد نتواصل معك لطلب معلومات إضافية أو لجدولة مكالمة تعريفية.'
            )}</p>
            <div class="form-check mb-4"><input type="checkbox" class="form-check-input" required><span class="text-body-sm">${t('I confirm all information is accurate', 'أؤكد أن جميع المعلومات دقيقة')} ${req}</span></div>
            <div class="form-check"><input type="checkbox" class="form-check-input" required><span class="text-body-sm">${t(
              'I agree to the <a href="#/terms" class="text-accent">Terms of Service</a> and <a href="#/privacy" class="text-accent">Privacy Policy</a>',
              'أوافق على <a href="#/terms" class="text-accent">شروط الخدمة</a> و<a href="#/privacy" class="text-accent">سياسة الخصوصية</a>'
            )} ${req}</span></div>
          </div>

          <div class="d-flex gap-3 justify-end mt-4">
            <a href="#/" class="btn btn-ghost">${t('Cancel', 'إلغاء')}</a>
            <button type="button" class="btn btn-primary btn-lg" onclick="this.textContent='✓ ${t('Application Submitted', 'تم إرسال الطلب')}';this.style.background='var(--color-success)';this.disabled=true;setTimeout(()=>window.location.hash='/dashboard/entrepreneur/overview',1500)">${t('Submit Application', 'إرسال الطلب')}</button>
          </div>
        </form>
      </div>
    </div>
  </section>`;
}
