<aside class="dashboard-sidebar">
  <div class="sidebar-header">
    <a href="{{ url('/') }}"><img src="{{ asset('Group 97.png') }}" alt="STC" height="28" id="sidebar-logo"></a>
  </div>
  
  @if(auth()->check())
  <div class="sidebar-user" onclick="window.location.href='{{ auth()->user()->role === 'investor' ? url('/dashboard/profile') : '#' }}'">
    <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
    <div class="sidebar-user-info">
      <div class="text-label">{{ auth()->user()->name }}</div>
      <div class="text-caption text-secondary" style="text-transform:capitalize">{{ auth()->user()->role }}</div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="margin-inline-start:auto;flex-shrink:0"><path d="m9 18 6-6-6-6"/></svg>
  </div>
  
  <nav class="sidebar-nav">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->is('admin') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'نظرة عامة' : 'Overview' }}</span>
        </a>
        <a href="{{ route('admin.projects') }}" class="sidebar-link {{ request()->is('admin/projects*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'المشاريع' : 'Projects' }}</span>
        </a>
        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'المستخدمين' : 'Users' }}</span>
        </a>
        <a href="{{ route('admin.requests') }}" class="sidebar-link {{ request()->is('admin/requests*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الطلبات' : 'Requests' }}</span>
        </a>
        <a href="{{ route('admin.files') }}" class="sidebar-link {{ request()->is('admin/files*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><line x1="12" x2="12" y1="18" y2="12"/><line x1="9" x2="15" y1="15" y2="15"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'إدارة الملفات' : 'Files & Reports' }}</span>
        </a>
        <a href="{{ route('admin.content') }}" class="sidebar-link {{ request()->is('admin/content*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M3 15h6"/><path d="M3 18h6"/><path d="M3 12h6"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'إدارة محتوى النظام' : 'System Content' }}</span>
        </a>
        <a href="{{ route('admin.website') }}" class="sidebar-link {{ request()->is('admin/website*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'إدارة الموقع العام' : 'Website Management' }}</span>
        </a>
    @elseif(auth()->user()->role === 'entrepreneur')
        <a href="{{ route('entrepreneur.dashboard') }}" class="sidebar-link {{ request()->is('entrepreneur') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'نظرة عامة' : 'Overview' }}</span>
        </a>
        <a href="{{ route('entrepreneur.projects') }}" class="sidebar-link {{ request()->is('entrepreneur/projects*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'مشاريعي' : 'My Projects' }}</span>
        </a>
        <a href="{{ route('entrepreneur.funding') }}" class="sidebar-link {{ request()->is('entrepreneur/funding*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'التمويل' : 'Funding' }}</span>
        </a>

    @else
        <!-- Investor Nav -->
        <a href="{{ url('/dashboard') }}" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'نظرة عامة' : 'Overview' }}</span>
        </a>
        <a href="{{ url('/dashboard/projects') }}" class="sidebar-link {{ request()->is('dashboard/projects*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'مشاريع المحفظة' : 'Portfolio Projects' }}</span>
        </a>
        <a href="{{ url('/dashboard/reports') }}" class="sidebar-link {{ request()->is('dashboard/reports*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</span>
        </a>
        <a href="{{ url('/dashboard/documents') }}" class="sidebar-link {{ request()->is('dashboard/documents*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'المستندات' : 'Documents' }}</span>
        </a>
        <a href="{{ url('/dashboard/ndas') }}" class="sidebar-link {{ request()->is('dashboard/ndas*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'مركز NDA' : 'NDA Center' }}</span>
        </a>
        <a href="{{ url('/dashboard/exit-requests') }}" class="sidebar-link {{ request()->is('dashboard/exit-requests*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'طلبات الخروج' : 'Exit Requests' }}</span>
        </a>
        <a href="{{ url('/dashboard/exit-records') }}" class="sidebar-link {{ request()->is('dashboard/exit-records*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'سجلات الخروج' : 'Exit Records' }}</span>
        </a>
        <a href="{{ url('/dashboard/consultations') }}" class="sidebar-link {{ request()->is('dashboard/consultations*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الاستشارات' : 'Consultations' }}</span>
        </a>
        <a href="{{ url('/dashboard/events') }}" class="sidebar-link {{ request()->is('dashboard/events*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الفعاليات' : 'Events' }}</span>
        </a>
        <a href="{{ url('/dashboard/profile') }}" class="sidebar-link {{ request()->is('dashboard/profile*') ? 'active' : '' }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'الملف الشخصي' : 'Profile & Security' }}</span>
        </a>
    @endif
  </nav>
  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}" style="display:inline;width:100%;">
        @csrf
        <button type="submit" class="sidebar-link" style="width:100%;text-align:{{ app()->getLocale() == 'ar' ? 'right' : 'left' }};background:transparent;border:none;cursor:pointer;font-family:inherit;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Sign Out' }}</span>
        </button>
    </form>
  </div>
  @endif
</aside>
