@extends('layouts.guest')

@section('title', app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Login')

@section('content')
<style>
  @media (max-width: 1024px) {
    .auth-branding { display: none !important; }
    .auth-logo-mobile { display: block !important; }
  }
  @media (min-width: 1025px) {
    .auth-logo-mobile { display: none !important; }
  }
  .auth-input { color: var(--text-primary) !important; }
  .auth-input:focus { color: var(--text-primary) !important; border-color: var(--action-primary) !important; background: var(--bg-primary) !important; box-shadow: 0 0 0 4px rgba(196,164,119,0.1); }
</style>

<section class="min-h-screen d-flex" style="background:var(--bg-primary);overflow:hidden;">
  
  <!-- Branding Side -->
  <div class="auth-branding" style="flex:1;position:relative;background:linear-gradient(135deg, var(--color-soft-black) 0%, #000 100%);display:flex;flex-direction:column;justify-content:space-between;padding:var(--space-16);color:white;overflow:hidden">
    <!-- Abstract shapes -->
    <div style="position:absolute;top:-20%;left:-10%;width:80%;height:80%;background:radial-gradient(circle, var(--action-primary) 0%, transparent 60%);opacity:0.15;filter:blur(80px);border-radius:50%"></div>
    <div style="position:absolute;bottom:-10%;right:-10%;width:70%;height:70%;background:radial-gradient(circle, var(--accent-gold) 0%, transparent 60%);opacity:0.1;filter:blur(60px);border-radius:50%"></div>
    
    <a href="{{ url('/') }}" style="position:relative;z-index:2;display:inline-block;align-self:center;margin-top:120px;margin-bottom:var(--space-8);max-height:220px;overflow:hidden">
      <img src="{{ asset('Group 102.png') }}" alt="Logo" id="auth-logo" height="220" style="height:220px;max-height:220px;width:auto;display:block">
    </a>
    
    <div style="position:relative;z-index:2;max-width:520px;padding-bottom:var(--space-12)">
      <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="1" style="margin-bottom:var(--space-8);opacity:0.6"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>
      <p class="text-display-lg mb-8" style="color:#FFF;line-height:1.5;font-weight:var(--weight-medium);font-size:2.2rem">
        {{ app()->getLocale() == 'ar' ? 'نبني شركات تقنية صُممت لتقود المستقبل.' : 'Building technology companies designed to lead the future.' }}
      </p>
      <div style="color:var(--text-tertiary);letter-spacing:2px;text-transform:uppercase;font-size:1.1rem">
        {{ app()->getLocale() == 'ar' ? 'سفن تك كابيتال' : 'SEVEN TECH CAPITAL' }}
      </div>
    </div>
  </div>

  <!-- Form Side -->
  <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:var(--space-8);position:relative">
    <!-- Mobile Logo -->
    <a href="{{ url('/') }}" class="auth-logo-mobile" style="position:absolute;top:var(--space-8);left:var(--space-8)">
      <img src="{{ asset('Group 102.png') }}" alt="Logo" height="48" id="auth-logo-mobile">
    </a>

    <div style="max-width:440px;width:100%;padding-top:var(--space-12)">
      <div class="mb-12">
        <h1 class="text-display-lg mb-4" style="font-weight:var(--weight-bold);letter-spacing:-1px">
          {{ app()->getLocale() == 'ar' ? 'مرحباً بعودتك' : 'Welcome Back' }}
        </h1>
        <p class="text-body-lg text-secondary">
          {{ app()->getLocale() == 'ar' ? 'سجل الدخول إلى حسابك في سفن تك كابيتال.' : 'Sign in to your SEVEN TECH CAPITAL account.' }}
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
      
      <form class="d-flex flex-col gap-6" method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group" style="position:relative">
          <label class="form-label text-caption" style="color:var(--text-secondary);margin-bottom:var(--space-2);font-weight:var(--weight-medium)">
            {{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
          </label>
          <div style="position:relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);{{ app()->getLocale() == 'ar' ? 'right:18px' : 'left:18px' }};color:var(--text-tertiary);pointer-events:none"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            <input type="email" name="email" class="form-input auth-input" style="width:100%;padding:1.1rem;padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}:3.5rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
          </div>
        </div>
        
        <div class="form-group" style="position:relative">
          <div class="d-flex justify-between items-center mb-2">
            <label class="form-label text-caption" style="color:var(--text-secondary);margin:0;font-weight:var(--weight-medium)">
              {{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}
            </label>
            <a href="#" class="text-caption" style="color:var(--action-primary);font-weight:var(--weight-medium);text-decoration:none;transition:opacity 0.2s" onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
              {{ app()->getLocale() == 'ar' ? 'هل نسيت كلمة المرور؟' : 'Forgot password?' }}
            </a>
          </div>
          <div style="position:relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);{{ app()->getLocale() == 'ar' ? 'right:18px' : 'left:18px' }};color:var(--text-tertiary);pointer-events:none"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" name="password" class="form-input auth-input" style="width:100%;padding:1.1rem;padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}:3.5rem;background:var(--bg-secondary);border:1px solid transparent;border-radius:var(--radius-lg);transition:all 0.3s ease;font-size:1rem" placeholder="••••••••" required>
          </div>
        </div>
        
        <div class="form-check mt-2">
          <label class="d-flex items-center gap-3 cursor-pointer" style="cursor:pointer">
            <div style="position:relative;display:flex;align-items:center">
              <input type="checkbox" name="remember" style="width:22px;height:22px;border-radius:6px;border:2px solid var(--border-default);appearance:none;outline:none;cursor:pointer;background:var(--bg-surface);transition:all 0.2s" onchange="this.style.background=this.checked?'var(--action-primary)':'var(--bg-surface)';this.style.borderColor=this.checked?'var(--action-primary)':'var(--border-default)';this.nextElementSibling.style.opacity=this.checked?1:0;this.nextElementSibling.style.transform=this.checked?'scale(1)':'scale(0.5)'">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:4px;pointer-events:none;opacity:0;transform:scale(0.5);transition:all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275)"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <span class="text-body-sm text-secondary select-none">
              {{ app()->getLocale() == 'ar' ? 'تذكرني' : 'Remember me' }}
            </span>
          </label>
        </div>
        
        <button type="submit" class="btn btn-primary w-full mt-6" style="font-size:1.1rem;padding:1.2rem;border-radius:var(--radius-lg);box-shadow:0 8px 24px rgba(196,164,119,0.3);transform:translateY(0);transition:all 0.3s ease" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(196,164,119,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(196,164,119,0.3)'">
          {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
        </button>
      </form>
      
      <div class="text-center" style="margin-top:40px; margin-bottom: 40px;">
        <p class="text-body-sm text-secondary">
          {{ app()->getLocale() == 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }} 
          <a href="{{ route('register') }}" style="color:var(--text-primary);font-weight:var(--weight-semibold);text-decoration:none;margin-inline-start:var(--space-2);position:relative;padding-bottom:2px" onmouseover="this.querySelector('.line').style.width='100%'" onmouseout="this.querySelector('.line').style.width='0%'">
            {{ app()->getLocale() == 'ar' ? 'إنشاء حساب' : 'Create Account' }}
            <span class="line" style="position:absolute;bottom:0;left:0;width:0%;height:2px;background:var(--action-primary);transition:width 0.3s ease"></span>
          </a>
        </p>
      </div>
      
    </div>
  </div>
</section>
@endsection

