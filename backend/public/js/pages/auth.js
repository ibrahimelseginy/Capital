/**
 * SEVEN TECH CAPITAL — Auth Pages
 * Login, Register, Forgot Password
 */

import LangManager from '../language.js';

// ════════════════════════════════════════
// LOGIN
// ════════════════════════════════════════
export function loginPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const titleText = isAr ? "مرحباً بعودتك" : "Welcome Back";
  const subtitleText = isAr ? "سجل الدخول إلى حسابك في سفن تك كابيتال." : "Sign in to your SEVEN TECH CAPITAL account.";
  
  const lblEmail = isAr ? "البريد الإلكتروني" : "Email Address";
  const lblPassword = isAr ? "كلمة المرور" : "Password";
  const linkForgot = isAr ? "هل نسيت كلمة المرور؟" : "Forgot password?";
  const lblRemember = isAr ? "تذكرني" : "Remember me";
  const btnSubmit = isAr ? "تسجيل الدخول" : "Sign In";
  const msgNoAccount = isAr ? "ليس لديك حساب؟" : "Don't have an account?";
  const linkRegister = isAr ? "إنشاء حساب" : "Create Account";

  const quote = isAr ? "نبني شركات تقنية صُممت لتقود المستقبل." : "Building technology companies designed to lead the future.";
  const quoteAuthor = isAr ? "سفن تك كابيتال" : "SEVEN TECH CAPITAL";

  return `
  <style>
    @media (max-width: 1024px) {
      .auth-branding { display: none !important; }
      .auth-logo-mobile { display: block !important; }
    }
    @media (min-width: 1025px) {
      .auth-logo-mobile { display: none !important; }
    }
    .auth-input:focus { border-color: var(--action-primary) !important; background: var(--bg-primary) !important; box-shadow: 0 0 0 4px rgba(196,164,119,0.1); }
  </style>
  <section class="min-h-screen d-flex" style="background:var(--bg-primary);overflow:hidden">
    
    <!-- Branding Side -->
    <div class="auth-branding" style="flex:1;position:relative;background:linear-gradient(135deg, var(--color-soft-black) 0%, #000 100%);display:flex;flex-direction:column;justify-content:space-between;padding:var(--space-16);color:white;overflow:hidden">
      <!-- Abstract shapes -->
      <div style="position:absolute;top:-20%;left:-10%;width:80%;height:80%;background:radial-gradient(circle, var(--action-primary) 0%, transparent 60%);opacity:0.15;filter:blur(80px);border-radius:50%"></div>
      <div style="position:absolute;bottom:-10%;right:-10%;width:70%;height:70%;background:radial-gradient(circle, var(--accent-gold) 0%, transparent 60%);opacity:0.1;filter:blur(60px);border-radius:50%"></div>
      
      <a href="#/" style="position:relative;z-index:2;display:inline-block;align-self:center;margin-top:120px;margin-bottom:var(--space-8);max-height:220px;overflow:hidden">
        <img src="Group 102.png" alt="Logo" id="auth-logo" height="220" style="height:220px;max-height:220px;width:auto;display:block">
      </a>
      
      <div style="position:relative;z-index:2;max-width:520px;padding-bottom:var(--space-12)">
        <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="1" style="margin-bottom:var(--space-8);opacity:0.6"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
        <p class="text-display-lg mb-8" style="color:#FFF;line-height:1.5;font-weight:var(--weight-medium);font-size:2.2rem">${quote}</p>
        <div style="color:var(--text-tertiary);letter-spacing:2px;text-transform:uppercase;font-size:1.1rem">${quoteAuthor}</div>
      </div>
    </div>

    <!-- Form Side -->
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:var(--space-8);position:relative">
      <!-- Mobile Logo -->
      <a href="#/" class="auth-logo-mobile" style="position:absolute;top:var(--space-8);left:var(--space-8)">
        <img src="Group 102.png" alt="Logo" height="48" id="auth-logo-mobile">
      </a>

      <div class="reveal" style="max-width:440px;width:100%;padding-top:var(--space-12)">
        <div class="mb-12">
          <h1 class="text-display-lg mb-4" style="font-weight:var(--weight-bold);letter-spacing:-1px">${titleText}</h1>
          <p class="text-body-lg text-secondary">${subtitleText}</p>
        </div>
        
        <form class="d-flex flex-col gap-6" onsubmit="event.preventDefault();window.location.hash='/dashboard'">
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);margin-bottom:var(--space-2);font-weight:var(--weight-medium)">${lblEmail}</label>
            <div style="position:relative">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);${isAr?'right:18px':'left:18px'};color:var(--text-tertiary);pointer-events:none"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <input type="email" class="form-input auth-input" style="width:100%;padding:1.1rem;padding-${isAr?'right':'left'}:3.5rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="you@example.com" required>
            </div>
          </div>
          
          <div class="form-group" style="position:relative">
            <div class="d-flex justify-between items-center mb-2">
              <label class="form-label text-caption" style="color:var(--text-secondary);margin:0;font-weight:var(--weight-medium)">${lblPassword}</label>
              <a href="#/forgot-password" class="text-caption" style="color:var(--action-primary);font-weight:var(--weight-medium);text-decoration:none;transition:opacity 0.2s" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">${linkForgot}</a>
            </div>
            <div style="position:relative">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);${isAr?'right:18px':'left:18px'};color:var(--text-tertiary);pointer-events:none"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" class="form-input auth-input" style="width:100%;padding:1.1rem;padding-${isAr?'right':'left'}:3.5rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="••••••••" required>
            </div>
          </div>
          
          <div class="form-check mt-2">
            <label class="d-flex items-center gap-3 cursor-pointer" style="cursor:pointer">
              <div style="position:relative;display:flex;align-items:center">
                <input type="checkbox" style="width:22px;height:22px;border-radius:6px;border:2px solid var(--border-default);appearance:none;outline:none;cursor:pointer;background:var(--bg-surface);transition:all 0.2s" onchange="this.style.background=this.checked?'var(--action-primary)':'var(--bg-surface)';this.style.borderColor=this.checked?'var(--action-primary)':'var(--border-default)';this.nextElementSibling.style.opacity=this.checked?1:0;this.nextElementSibling.style.transform=this.checked?'scale(1)':'scale(0.5)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:4px;pointer-events:none;opacity:0;transform:scale(0.5);transition:all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275)"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <span class="text-body-sm text-secondary select-none">${lblRemember}</span>
            </label>
          </div>
          
          <button type="submit" class="btn btn-primary w-full mt-6" style="font-size:1.1rem;padding:1.2rem;border-radius:var(--radius-lg);box-shadow:0 8px 24px rgba(196,164,119,0.3);transform:translateY(0);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(196,164,119,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(196,164,119,0.3)'">${btnSubmit}</button>
        </form>
        
        <div class="text-center" style="margin-top:40px;">
          <p class="text-body-sm text-secondary">
            ${msgNoAccount} 
            <a href="#/register" style="color:var(--text-primary);font-weight:var(--weight-semibold);text-decoration:none;margin-inline-start:var(--space-2);position:relative;padding-bottom:2px" onmouseover="this.querySelector('.line').style.width='100%'" onmouseout="this.querySelector('.line').style.width='0%'">
              ${linkRegister}
              <span class="line" style="position:absolute;bottom:0;left:0;width:0%;height:2px;background:var(--action-primary);transition:width 0.3s ease"></span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>`;
}

// ════════════════════════════════════════
// REGISTER
// ════════════════════════════════════════
export function registerPage() {
  const isAr = LangManager.currentLang === 'ar';
  
  const titleText = isAr ? "إنشاء حساب جديد" : "Create Your Account";
  const subtitleText = isAr ? "انضم إلى منظومة سفن تك كابيتال." : "Join the SEVEN TECH CAPITAL ecosystem.";
  

  const typeInvestor = isAr ? "مستثمر" : "Investor";
  const typeEntrepreneur = isAr ? "رائد أعمال" : "Entrepreneur";
  const typeAdmin = isAr ? "مسؤول النظام" : "Admin";
  
  const lblFirstName = isAr ? "الاسم الأول" : "First Name";
  const lblLastName = isAr ? "اسم العائلة" : "Last Name";
  const lblEmail = isAr ? "البريد الإلكتروني" : "Email Address";
  const lblPhone = isAr ? "رقم الهاتف" : "Phone Number";
  const lblPassword = isAr ? "كلمة المرور" : "Password";
  const lblConfirm = isAr ? "تأكيد كلمة المرور" : "Confirm Password";
  const lblTerms = isAr ? "أوافق على <a href='#/terms' style='color:var(--action-primary);text-decoration:none'>شروط الخدمة</a> و <a href='#/privacy' style='color:var(--action-primary);text-decoration:none'>سياسة الخصوصية</a>" : "I agree to the <a href='#/terms' style='color:var(--action-primary);text-decoration:none'>Terms of Service</a> and <a href='#/privacy' style='color:var(--action-primary);text-decoration:none'>Privacy Policy</a>";
  const btnSubmit = isAr ? "إنشاء الحساب" : "Create Account";
  const msgAlready = isAr ? "لديك حساب بالفعل؟" : "Already have an account?";
  const linkSignIn = isAr ? "تسجيل الدخول" : "Sign In";

  const quote = isAr ? "نجمع بين رأس المال والاستراتيجية لبناء المستقبل." : "Uniting capital and strategy to build the future.";
  const quoteAuthor = isAr ? "انضم إلينا اليوم" : "JOIN US TODAY";

  return `
  <style>
    @media (max-width: 1024px) {
      .reg-branding { display: none !important; }
      .reg-logo-mobile { display: block !important; }
      .reg-form-container { padding: var(--space-6) !important; }
    }
    @media (min-width: 1025px) {
      .reg-logo-mobile { display: none !important; }
    }
    .reg-input:focus { border-color: var(--action-primary) !important; background: var(--bg-primary) !important; box-shadow: 0 0 0 4px rgba(196,164,119,0.1); }
    
    .account-type-btn {
      padding: var(--space-4) var(--space-2);
      border-radius: var(--radius-lg);
      border: 2px solid var(--border-default);
      background: transparent;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .account-type-btn:hover {
      border-color: var(--action-primary);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(196,164,119,0.1);
    }
    .account-type-btn.selected {
      border-color: var(--action-primary);
      background: rgba(196,164,119,0.05);
      box-shadow: 0 4px 12px rgba(196,164,119,0.15);
    }
    .account-type-icon {
      font-size: 2rem;
      margin-top: 10px;
      margin-bottom: var(--space-2);
      transition: transform 0.3s ease;
    }
    .account-type-btn:hover .account-type-icon {
      transform: scale(1.1);
    }
  </style>
  <section class="min-h-screen d-flex" style="background:var(--bg-primary);overflow:hidden;flex-direction:${isAr ? 'row-reverse' : 'row'}">
    
    <!-- Branding Side -->
    <div class="reg-branding" style="flex:1;position:relative;background:linear-gradient(135deg, #0f1115 0%, #000 100%);display:flex;flex-direction:column;justify-content:space-between;padding:var(--space-16);color:white;overflow:hidden">
      <div style="position:absolute;top:-10%;left:-20%;width:90%;height:90%;background:radial-gradient(circle, var(--accent-gold) 0%, transparent 60%);opacity:0.12;filter:blur(80px);border-radius:50%"></div>
      <div style="position:absolute;bottom:-20%;right:-10%;width:80%;height:80%;background:radial-gradient(circle, var(--action-primary) 0%, transparent 60%);opacity:0.1;filter:blur(60px);border-radius:50%"></div>
      
      <a href="#/" style="position:relative;z-index:2;display:inline-block;align-self:center;margin-top:120px;margin-bottom:var(--space-8);max-height:220px;overflow:hidden">
        <img src="Group 102.png" alt="Logo" id="reg-logo" height="220" style="height:220px;max-height:220px;width:auto;display:block">
      </a>
      
      <div style="position:relative;z-index:2;max-width:520px;padding-bottom:var(--space-12)">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="1.5" style="margin-bottom:var(--space-8);opacity:0.6"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <p class="text-display-lg mb-8" style="color:#FFF;line-height:1.4;font-weight:var(--weight-medium);font-size:2rem">${quote}</p>
        <div style="color:var(--text-tertiary);letter-spacing:2px;text-transform:uppercase;font-size:0.9rem">${quoteAuthor}</div>
      </div>
    </div>

    <!-- Form Side -->
    <div class="reg-form-container" style="flex:1;display:flex;align-items:flex-start;justify-content:center;padding:var(--space-12);padding-top:10vh;position:relative;overflow-y:auto;max-height:100vh">
      <a href="#/" class="reg-logo-mobile" style="position:absolute;top:var(--space-8);left:var(--space-8)">
        <img src="Group 102.png" alt="Logo" height="48">
      </a>

      <div class="reveal" style="max-width:560px;width:100%;margin:0 auto">
        <div class="mb-10 text-center" style="margin-bottom: 3rem;">
          <h1 class="text-h2 mb-3" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">${titleText}</h1>
          <p class="text-body-lg text-secondary">${subtitleText}</p>
        </div>

        <!-- Account Type Selection -->
        <div class="d-flex gap-4 mb-8" id="account-type-selector" style="margin-top: 2rem;">
          ${[
            {id:'investor', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>', text:typeInvestor},
            {id:'entrepreneur', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><mpath d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>', text:typeEntrepreneur},
            {id:'admin', icon:'<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>', text:typeAdmin}
          ].map((type, i) => `
          <div class="flex-1 account-type-btn ${i===0?'selected':''}" data-type="${type.id}" onclick="
            document.querySelectorAll('.account-type-btn').forEach(b => { b.classList.remove('selected'); b.querySelector('.account-type-icon').style.color='var(--text-secondary)'; });
            this.classList.add('selected');
            this.querySelector('.account-type-icon').style.color='var(--action-primary)';
            document.getElementById('hidden-account-type').value = this.dataset.type;
          ">
            <div class="account-type-icon" style="color:${i===0?'var(--action-primary)':'var(--text-secondary)'}">${type.icon}</div>
            <div class="text-body-sm" style="font-weight:var(--weight-semibold)">${type.text}</div>
          </div>`).join('')}
        </div>

        <form class="d-flex flex-col gap-5" onsubmit="event.preventDefault();var t=document.getElementById('hidden-account-type').value;window.location.hash=t==='entrepreneur'?'/dashboard/entrepreneur':(t==='admin'?'/dashboard/admin/overview':'/dashboard/investor')">
          <input type="hidden" id="hidden-account-type" value="investor">
          
          <div class="grid-2" style="gap:var(--space-5)">
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblFirstName}</label>
              <input type="text" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
            </div>
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblLastName}</label>
              <input type="text" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
            </div>
          </div>
          
          <div class="grid-2" style="gap:var(--space-5)">
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblEmail}</label>
              <input type="email" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="you@example.com" required>
            </div>
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblPhone}</label>
              <input type="tel" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" dir="ltr">
            </div>
          </div>
          
          <div class="grid-2" style="gap:var(--space-5)">
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblPassword}</label>
              <input type="password" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
            </div>
            <div class="form-group" style="position:relative">
              <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">${lblConfirm}</label>
              <input type="password" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
            </div>
          </div>
          
          <div class="form-check mt-3 mb-2">
            <label class="d-flex items-start gap-3 cursor-pointer" style="cursor:pointer">
              <div style="position:relative;display:flex;align-items:center;margin-top:2px">
                <input type="checkbox" style="width:22px;height:22px;border-radius:6px;border:2px solid var(--border-default);appearance:none;outline:none;cursor:pointer;background:var(--bg-surface);transition:all 0.2s" required onchange="this.style.background=this.checked?'var(--action-primary)':'var(--bg-surface)';this.style.borderColor=this.checked?'var(--action-primary)':'var(--border-default)';this.nextElementSibling.style.opacity=this.checked?1:0;this.nextElementSibling.style.transform=this.checked?'scale(1)':'scale(0.5)'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:4px;pointer-events:none;opacity:0;transform:scale(0.5);transition:all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275)"><polyline points="20 6 9 17 4 12"/></svg>
              </div>
              <span class="text-body-sm text-secondary select-none" style="line-height:1.6">${lblTerms}</span>
            </label>
          </div>
          
          <button type="submit" class="btn btn-primary w-full mt-2" style="font-size:1.1rem;padding:1.2rem;border-radius:var(--radius-lg);box-shadow:0 8px 24px rgba(196,164,119,0.3);transform:translateY(0);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(196,164,119,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(196,164,119,0.3)'">${btnSubmit}</button>
        </form>
        
        <div class="text-center" style="margin-top:40px;">
          <p class="text-body-sm text-secondary">
            ${msgAlready} 
            <a href="#/login" style="color:var(--text-primary);font-weight:var(--weight-semibold);text-decoration:none;margin-inline-start:var(--space-2);position:relative;padding-bottom:2px" onmouseover="this.querySelector('.line').style.width='100%'" onmouseout="this.querySelector('.line').style.width='0%'">
              ${linkSignIn}
              <span class="line" style="position:absolute;bottom:0;left:0;width:0%;height:2px;background:var(--action-primary);transition:width 0.3s ease"></span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </section>`;
}
