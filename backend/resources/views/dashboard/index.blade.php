@extends('layouts.app')

@section('title', 'Overview')

@section('content')

    <!-- Welcome Banner -->
    <div class="card mb-8 reveal" style="padding:var(--space-8) var(--space-10);background:linear-gradient(135deg, #0f0f0f 0%, #1a1510 40%, #1a1714 60%, #0f0f0f 100%);border:1px solid rgba(255,90,0,0.15);position:relative;overflow:hidden;border-radius:var(--radius-xl)">
      <div style="position:absolute;top:-60%;right:-15%;width:500px;height:500px;background:radial-gradient(circle,rgba(255,90,0,0.12) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:absolute;bottom:-40%;left:-5%;width:350px;height:350px;background:radial-gradient(circle,rgba(198,161,91,0.08) 0%,transparent 65%);pointer-events:none"></div>
      <div style="position:relative;z-index:2">
        <div class="text-caption mb-3" style="color:var(--action-primary);font-weight:var(--weight-bold);text-transform:uppercase;letter-spacing:3px;font-size:11px">{{ app()->getLocale() == 'ar' ? 'لوحة المستثمر' : 'Investor Dashboard' }}</div>
        <h2 class="text-h2 mb-3" style="color:#fff;font-weight:var(--weight-bold);letter-spacing:-0.5px;line-height:1.2">{{ app()->getLocale() == 'ar' ? 'مرحباً بك، خالد' : 'Welcome back, Khalid' }} 👋</h2>
        <p class="text-body" style="color:rgba(255,255,255,0.55);max-width:500px;line-height:1.6">{{ app()->getLocale() == 'ar' ? 'إليك نظرة سريعة على أداء محفظتك والإجراءات المعلقة.' : 'Here\'s your portfolio performance and pending actions at a glance.' }}</p>
      </div>
    </div>

    <div class="grid-4 mb-8">
      
    <div class="metric-card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default);background:var(--bg-surface);position:relative;overflow:hidden;transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);cursor:pointer" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='var(--action-primary)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute;top:0;right:0;width:120px;height:120px;background:radial-gradient(circle, var(--action-primary)10 0%, transparent 70%);pointer-events:none"></div>
      <div class="d-flex justify-between items-start" style="position:relative;z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">{{ app()->getLocale() == 'ar' ? 'إجمالي الاستثمار' : 'Total Invested' }}</div>
          <div class="metric-card-value" style="font-size:2rem;line-height:1;letter-spacing:-1px">$2.4M</div>
          <div class="metric-card-change positive" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--color-success-bg);color:var(--color-success)">↑ 12% this quarter</div>
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:var(--action-primary)12;display:flex;align-items:center;justify-content:center;color:var(--action-primary)">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
      </div>
    </div>
      
    <div class="metric-card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default);background:var(--bg-surface);position:relative;overflow:hidden;transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);cursor:pointer" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='#3b82f6'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute;top:0;right:0;width:120px;height:120px;background:radial-gradient(circle, #3b82f610 0%, transparent 70%);pointer-events:none"></div>
      <div class="d-flex justify-between items-start" style="position:relative;z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">{{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}</div>
          <div class="metric-card-value" style="font-size:2rem;line-height:1;letter-spacing:-1px">5</div>
          
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:#3b82f612;display:flex;align-items:center;justify-content:center;color:#3b82f6">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
        </div>
      </div>
    </div>
      
    <div class="metric-card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default);background:var(--bg-surface);position:relative;overflow:hidden;transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);cursor:pointer" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='var(--color-success)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute;top:0;right:0;width:120px;height:120px;background:radial-gradient(circle, var(--color-success)10 0%, transparent 70%);pointer-events:none"></div>
      <div class="d-flex justify-between items-start" style="position:relative;z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">{{ app()->getLocale() == 'ar' ? 'عائد المحفظة' : 'Portfolio Return' }}</div>
          <div class="metric-card-value" style="font-size:2rem;line-height:1;letter-spacing:-1px">3.2x</div>
          <div class="metric-card-change positive" style="display:inline-flex;align-items:center;gap:4px;margin-top:8px;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:var(--color-success-bg);color:var(--color-success)">↑ 0.4x from Q4</div>
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:var(--color-success)12;display:flex;align-items:center;justify-content:center;color:var(--color-success)">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>
        </div>
      </div>
    </div>
      
    <div class="metric-card" style="padding:var(--space-6);border-radius:var(--radius-xl);border:1px solid var(--border-default);background:var(--bg-surface);position:relative;overflow:hidden;transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);cursor:pointer" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.1)';this.style.borderColor='var(--color-warning)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.borderColor='var(--border-default)'">
      <div style="position:absolute;top:0;right:0;width:120px;height:120px;background:radial-gradient(circle, var(--color-warning)10 0%, transparent 70%);pointer-events:none"></div>
      <div class="d-flex justify-between items-start" style="position:relative;z-index:2">
        <div>
          <div class="text-caption text-secondary mb-2" style="font-weight:var(--weight-semibold);text-transform:uppercase;letter-spacing:1.2px;font-size:11px">{{ app()->getLocale() == 'ar' ? 'اتفاقيات معلقة' : 'Pending NDAs' }}</div>
          <div class="metric-card-value" style="font-size:2rem;line-height:1;letter-spacing:-1px">3</div>
          
        </div>
        <div style="width:52px;height:52px;border-radius:var(--radius-lg);background:var(--color-warning)12;display:flex;align-items:center;justify-content:center;color:var(--color-warning)">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
      </div>
    </div>
    </div>

    <div class="grid-12" style="gap:var(--space-6)">
      <div style="grid-column:span 8">
        <!-- Portfolio Chart -->
        <div class="card mb-6" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex justify-between items-center mb-5">
            <h3 class="text-h5" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'نظرة على المحفظة' : 'Portfolio Overview' }}</h3>
            <div class="d-flex gap-2">
              <button class="chip active" style="border-radius:var(--radius-full)">{{ app()->getLocale() == 'ar' ? 'سنة' : '1Y' }}</button>
              <button class="chip" style="border-radius:var(--radius-full)">{{ app()->getLocale() == 'ar' ? 'الكل' : 'All' }}</button>
            </div>
          </div>
          <div style="height:240px;background:linear-gradient(to bottom, var(--bg-secondary), var(--bg-surface));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            <div style="position:absolute;bottom:0;left:0;right:0;height:60%;background:linear-gradient(to top, rgba(255,90,0,0.05), transparent)"></div>
            <svg viewBox="0 0 400 120" style="width:100%;height:80%;padding:0 20px" preserveAspectRatio="none">
              <polyline fill="none" stroke="var(--action-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="0,100 40,85 80,90 120,60 160,65 200,40 240,45 280,25 320,30 360,15 400,10"/>
              <polyline fill="url(#chartGrad)" stroke="none" points="0,100 40,85 80,90 120,60 160,65 200,40 240,45 280,25 320,30 360,15 400,10 400,120 0,120"/>
              <defs><linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="var(--action-primary)" stop-opacity="0.15"/><stop offset="100%" stop-color="var(--action-primary)" stop-opacity="0"/></linearGradient></defs>
            </svg>
          </div>
        </div>
        <!-- Recent Activity -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <div class="d-flex justify-between items-center mb-5" style="padding-bottom:var(--space-4);border-bottom:1px solid var(--border-subtle)">
            <h3 class="text-h5" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'النشاط الأخير' : 'Recent Activity' }}</h3>
          </div>
          
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:#3b82f6;margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px #3b82f620"></div>
            <div class="flex-1 text-body-sm">{{ app()->getLocale() == 'ar' ? 'تقرير ربع سنوي جديد لـ FinFlow' : 'New quarterly report available for FinFlow' }}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">{{ app()->getLocale() == 'ar' ? 'منذ ساعتين' : '2h ago' }}</div>
          </div>
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--color-success);margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px var(--color-success)20"></div>
            <div class="flex-1 text-body-sm">{{ app()->getLocale() == 'ar' ? 'DataPulse: تم الوصول إلى 10 آلاف مستخدم' : 'DataPulse milestone: 10K users reached' }}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">{{ app()->getLocale() == 'ar' ? 'منذ يوم' : '1d ago' }}</div>
          </div>
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--action-primary);margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px var(--action-primary)20"></div>
            <div class="flex-1 text-body-sm">{{ app()->getLocale() == 'ar' ? 'إشعار فرصة خروج لـ BuildOS' : 'Exit opportunity notification for BuildOS' }}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">{{ app()->getLocale() == 'ar' ? 'منذ يومين' : '2d ago' }}</div>
          </div>
          <div class="d-flex gap-4 py-3 activity-row" style="padding-inline:var(--space-3);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
            <div style="width:10px;height:10px;border-radius:50%;background:var(--accent-gold);margin-top:6px;flex-shrink:0;box-shadow:0 0 0 3px var(--accent-gold)20"></div>
            <div class="flex-1 text-body-sm">{{ app()->getLocale() == 'ar' ? 'تم توقيع NDA لمشروع ألفا' : 'NDA signed for Project Alpha' }}</div>
            <div class="text-caption text-tertiary" style="background:var(--bg-secondary);padding:2px 10px;border-radius:var(--radius-full);align-self:center;white-space:nowrap">{{ app()->getLocale() == 'ar' ? 'منذ 5 أيام' : '5d ago' }}</div>
          </div>
        </div>
      </div>
      <div style="grid-column:span 4">
        <!-- Allocation -->
        <div class="card mb-5" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-5" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'توزيع الاستثمارات' : 'Investment Allocation' }}</h4>
          <div style="height:160px;display:flex;align-items:center;justify-content:center;position:relative">
            <svg viewBox="0 0 120 120" style="width:140px;height:140px;transform:rotate(-90deg)">
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--bg-secondary)" stroke-width="12"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--action-primary)" stroke-width="12" stroke-dasharray="141.37 314.16" stroke-linecap="round"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--accent-gold)" stroke-width="12" stroke-dasharray="94.25 314.16" stroke-dashoffset="-141.37" stroke-linecap="round"/>
              <circle cx="60" cy="60" r="50" fill="none" stroke="var(--color-success)" stroke-width="12" stroke-dasharray="78.54 314.16" stroke-dashoffset="-235.62" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="d-flex flex-col gap-3 mt-4">
            
            <div class="d-flex justify-between items-center">
              <div class="d-flex gap-2 items-center"><div style="width:10px;height:10px;border-radius:50%;background:var(--action-primary)"></div><span class="text-caption" style="font-weight:var(--weight-medium)">FinTech</span></div>
              <span class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">45%</span>
            </div>
            <div class="d-flex justify-between items-center">
              <div class="d-flex gap-2 items-center"><div style="width:10px;height:10px;border-radius:50%;background:var(--accent-gold)"></div><span class="text-caption" style="font-weight:var(--weight-medium)">AI & Data</span></div>
              <span class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">30%</span>
            </div>
            <div class="d-flex justify-between items-center">
              <div class="d-flex gap-2 items-center"><div style="width:10px;height:10px;border-radius:50%;background:var(--color-success)"></div><span class="text-caption" style="font-weight:var(--weight-medium)">PropTech</span></div>
              <span class="text-caption text-secondary" style="font-weight:var(--weight-semibold)">25%</span>
            </div>
          </div>
        </div>
        <!-- Upcoming -->
        <div class="card" style="padding:var(--space-6);border-radius:var(--radius-xl)">
          <h4 class="text-label mb-4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'القادم' : 'Upcoming' }}</h4>
          <div class="d-flex flex-col gap-3">
            
            <div class="d-flex gap-3 items-center" style="padding:var(--space-3);border-radius:var(--radius-lg);transition:all 0.2s;cursor:pointer" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
              <div style="min-width:40px;text-align:center;padding:var(--space-2);background:var(--color-primary-lighter);border-radius:var(--radius-md)">
                <div class="text-h5" style="color:var(--action-primary);font-weight:var(--weight-bold)">15</div>
              </div>
              <div class="text-caption" style="font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'يوليو · يوم العروض' : 'Jul · Demo Day' }}</div>
            </div>
            <div class="d-flex gap-3 items-center" style="padding:var(--space-3);border-radius:var(--radius-lg);transition:all 0.2s;cursor:pointer" onmouseover="this.style.background='var(--bg-secondary)'" onmouseout="this.style.background='transparent'">
              <div style="min-width:40px;text-align:center;padding:var(--space-2);background:var(--color-primary-lighter);border-radius:var(--radius-md)">
                <div class="text-h5" style="color:var(--action-primary);font-weight:var(--weight-bold)">28</div>
              </div>
              <div class="text-caption" style="font-weight:var(--weight-medium)">{{ app()->getLocale() == 'ar' ? 'يوليو · إحاطة ق2' : 'Jul · Q2 Briefing' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection