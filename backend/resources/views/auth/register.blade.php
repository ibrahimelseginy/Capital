@extends('layouts.guest')

@section('title', app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create Your Account')

@section('content')
<style>
  @media (max-width: 1024px) {
    .reg-branding { display: none !important; }
    .reg-logo-mobile { display: block !important; }
    .reg-form-container { padding: var(--space-6) !important; }
  }
  @media (min-width: 1025px) {
    .reg-logo-mobile { display: none !important; }
  }
  .reg-input { color: var(--text-primary) !important; }
  .reg-input:focus { color: var(--text-primary) !important; border-color: var(--action-primary) !important; background: var(--bg-primary) !important; box-shadow: 0 0 0 4px rgba(196,164,119,0.1); }
  
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

<section class="min-h-screen d-flex" style="background:var(--bg-primary);overflow:hidden;flex-direction:{{ app()->getLocale() == 'ar' ? 'row-reverse' : 'row' }}">
  
  <!-- Branding Side -->
  <div class="reg-branding" style="flex:1;position:relative;background:linear-gradient(135deg, #0f1115 0%, #000 100%);display:flex;flex-direction:column;justify-content:space-between;padding:var(--space-16);color:white;overflow:hidden">
    <div style="position:absolute;top:-10%;left:-20%;width:90%;height:90%;background:radial-gradient(circle, var(--accent-gold) 0%, transparent 60%);opacity:0.12;filter:blur(80px);border-radius:50%"></div>
    <div style="position:absolute;bottom:-20%;right:-10%;width:80%;height:80%;background:radial-gradient(circle, var(--action-primary) 0%, transparent 60%);opacity:0.1;filter:blur(60px);border-radius:50%"></div>
    
    <a href="{{ url('/') }}" style="position:relative;z-index:2;display:inline-block;align-self:center;margin-top:120px;margin-bottom:var(--space-8);max-height:220px;overflow:hidden">
      <img src="{{ asset('Group 102.png') }}" alt="Logo" id="reg-logo" height="220" style="height:220px;max-height:220px;width:auto;display:block">
    </a>
    
    <div style="position:relative;z-index:2;max-width:520px;padding-bottom:var(--space-12)">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="1.5" style="margin-bottom:var(--space-8);opacity:0.6"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      <p class="text-display-lg mb-8" style="color:#FFF;line-height:1.4;font-weight:var(--weight-medium);font-size:2rem">
        {{ app()->getLocale() == 'ar' ? 'نجمع بين رأس المال والاستراتيجية لبناء المستقبل.' : 'Uniting capital and strategy to build the future.' }}
      </p>
      <div style="color:var(--text-tertiary);letter-spacing:2px;text-transform:uppercase;font-size:0.9rem">
        {{ app()->getLocale() == 'ar' ? 'انضم إلينا اليوم' : 'JOIN US TODAY' }}
      </div>
    </div>
  </div>

  <!-- Form Side -->
  <div class="reg-form-container" style="flex:1;display:flex;align-items:flex-start;justify-content:center;padding:var(--space-12);padding-top:10vh;position:relative;overflow-y:auto;max-height:100vh">
    <a href="{{ url('/') }}" class="reg-logo-mobile" style="position:absolute;top:var(--space-8);left:var(--space-8)">
      <img src="{{ asset('Group 102.png') }}" alt="Logo" height="48">
    </a>

    <div style="max-width:560px;width:100%;margin:0 auto">
      <div class="mb-10 text-center" style="margin-bottom: 3rem;">
        <h1 class="text-h2 mb-3" style="font-weight:var(--weight-bold);letter-spacing:-0.5px">
          {{ app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create Your Account' }}
        </h1>
        <p class="text-body-lg text-secondary">
          {{ app()->getLocale() == 'ar' ? 'انضم إلى منظومة سفن تك كابيتال.' : 'Join the SEVEN TECH CAPITAL ecosystem.' }}
        </p>
      </div>

      @if ($errors->any())
          <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
              <ul style="margin: 0; padding-inline-start: 20px;">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif

      <!-- Account Type Selection -->
      <div class="d-flex gap-4 mb-8" id="account-type-selector" style="margin-top: 2rem;">
        
        <div class="flex-1 account-type-btn selected" data-type="investor" onclick="
          document.querySelectorAll('.account-type-btn').forEach(b => { b.classList.remove('selected'); b.querySelector('.account-type-icon').style.color='var(--text-secondary)'; });
          this.classList.add('selected');
          this.querySelector('.account-type-icon').style.color='var(--action-primary)';
          document.getElementById('hidden-account-type').value = this.dataset.type;
        ">
          <div class="account-type-icon" style="color:var(--action-primary)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg></div>
          <div class="text-body-sm" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'مستثمر' : 'Investor' }}</div>
        </div>

        <div class="flex-1 account-type-btn" data-type="entrepreneur" onclick="
          document.querySelectorAll('.account-type-btn').forEach(b => { b.classList.remove('selected'); b.querySelector('.account-type-icon').style.color='var(--text-secondary)'; });
          this.classList.add('selected');
          this.querySelector('.account-type-icon').style.color='var(--action-primary)';
          document.getElementById('hidden-account-type').value = this.dataset.type;
        ">
          <div class="account-type-icon" style="color:var(--text-secondary)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg></div>
          <div class="text-body-sm" style="font-weight:var(--weight-semibold)">{{ app()->getLocale() == 'ar' ? 'رائد أعمال' : 'Entrepreneur' }}</div>
        </div>

      </div>

      <form class="d-flex flex-col gap-5" method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="hidden" id="hidden-account-type" name="role" value="investor">
        
        <div class="grid-2" style="gap:var(--space-5)">
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'الاسم الأول' : 'First Name' }}</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
          </div>
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'اسم العائلة' : 'Last Name' }}</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
          </div>
        </div>
        
        <div class="grid-2" style="gap:var(--space-5)">
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="you@example.com" required>
          </div>
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" dir="ltr">
          </div>
        </div>
        
        <div class="grid-2" style="gap:var(--space-5)">
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}</label>
            <input type="password" name="password" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
          </div>
          <div class="form-group" style="position:relative">
            <label class="form-label text-caption" style="color:var(--text-secondary);font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
            <input type="password" name="password_confirmation" class="form-input reg-input" style="padding:1rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" required>
          </div>
        </div>
        
        <div class="form-check mt-3 mb-2">
          <label class="d-flex items-start gap-3 cursor-pointer" style="cursor:pointer">
            <div style="position:relative;display:flex;align-items:center;margin-top:2px">
              <input type="checkbox" style="width:22px;height:22px;border-radius:6px;border:2px solid var(--border-default);appearance:none;outline:none;cursor:pointer;background:var(--bg-surface);transition:all 0.2s" required onchange="this.style.background=this.checked?'var(--action-primary)':'var(--bg-surface)';this.style.borderColor=this.checked?'var(--action-primary)':'var(--border-default)';this.nextElementSibling.style.opacity=this.checked?1:0;this.nextElementSibling.style.transform=this.checked?'scale(1)':'scale(0.5)'">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:4px;pointer-events:none;opacity:0;transform:scale(0.5);transition:all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275)"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="text-body-sm text-secondary select-none" style="line-height:1.6">
              {!! app()->getLocale() == 'ar' ? "أوافق على <a href='#' style='color:var(--action-primary);text-decoration:none'>شروط الخدمة</a> و <a href='#' style='color:var(--action-primary);text-decoration:none'>سياسة الخصوصية</a>" : "I agree to the <a href='#' style='color:var(--action-primary);text-decoration:none'>Terms of Service</a> and <a href='#' style='color:var(--action-primary);text-decoration:none'>Privacy Policy</a>" !!}
            </span>
          </label>
        </div>
        
        <button type="submit" class="btn btn-primary w-full mt-2" style="font-size:1.1rem;padding:1.2rem;border-radius:var(--radius-lg);box-shadow:0 8px 24px rgba(196,164,119,0.3);transform:translateY(0);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(196,164,119,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(196,164,119,0.3)'">
          {{ app()->getLocale() == 'ar' ? 'إنشاء الحساب' : 'Create Account' }}
        </button>
      </form>
      
      <div class="text-center" style="margin-top:40px; margin-bottom: 40px;">
        <p class="text-body-sm text-secondary">
          {{ app()->getLocale() == 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?' }} 
          <a href="{{ route('login') }}" style="color:var(--text-primary);font-weight:var(--weight-semibold);text-decoration:none;margin-inline-start:var(--space-2);position:relative;padding-bottom:2px" onmouseover="this.querySelector('.line').style.width='100%'" onmouseout="this.querySelector('.line').style.width='0%'">
            {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
            <span class="line" style="position:absolute;bottom:0;left:0;width:0%;height:2px;background:var(--action-primary);transition:width 0.3s ease"></span>
          </a>
        </p>
      </div>
    </div>
  </div>
</section>
@endsection
