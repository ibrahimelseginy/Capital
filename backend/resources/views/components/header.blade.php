<header class="dashboard-topbar">
  <div class="d-flex items-center gap-4">
    <button class="dashboard-menu-toggle" onclick="document.querySelector('.dashboard-sidebar').classList.toggle('mobile-open')">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
    </button>
    <div>
      <h1 class="text-h4" style="margin:0;line-height:1.2">@yield('title', 'Dashboard')</h1>
      <p class="text-caption text-tertiary" style="margin:0;margin-top:2px">{{ now()->locale(app()->getLocale())->translatedFormat('l, F j, Y') }}</p>
    </div>
  </div>
  <div class="d-flex items-center gap-3">
    <!-- Search -->
    <div style="position:relative;" class="topbar-search">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="position:absolute;top:50%;transform:translateY(-50%);left:12px;pointer-events:none"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث...' : 'Search...' }}" style="width:200px;padding:8px 12px 8px 36px;background:var(--bg-secondary);border:1px solid var(--border-default);border-radius:var(--radius-full);font-size:13px;color:var(--text-primary);outline:none;transition:all 0.2s ease" onfocus="this.style.width='260px';this.style.borderColor='var(--action-primary)';this.style.boxShadow='0 0 0 3px rgba(255,90,0,0.1)'" onblur="this.style.width='200px';this.style.borderColor='var(--border-default)';this.style.boxShadow='none'">
    </div>

    <!-- Notifications -->
    <style>
      .notification-item.unread {
        background-color: var(--color-primary-lighter) !important;
      }
      .notification-item:hover {
        background-color: var(--action-ghost-hover) !important;
      }
      [dir="rtl"] .notification-dropdown-premium {
        right: auto !important;
        left: 0 !important;
      }
    </style>
    <div class="notification-dropdown-wrapper" style="position:relative;">
      <button class="header-action-btn notification-trigger" aria-label="Notifications" style="position:relative;display:flex;align-items:center;justify-content:center;color:inherit;text-decoration:none;background:transparent;border:none;cursor:pointer;padding:0;width:36px;height:36px;border-radius:50%;transition:background-color 0.2s;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        <span class="notification-badge" style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--color-error);border-radius:50%;border:2px solid var(--bg-surface)"></span>
      </button>

      <!-- Dropdown Panel -->
      <div class="notification-dropdown-premium" style="display:none;position:absolute;top:100%;right:0;margin-top:12px;width:340px;background:var(--bg-surface);border:1px solid var(--border-default);border-radius:var(--radius-xl);box-shadow:var(--shadow-xl);z-index:999;overflow:hidden;animation:fadeIn var(--duration-fast) var(--ease-out) forwards;">
        <!-- Dropdown Header -->
        <div style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--border-subtle);display:flex;justify-content:space-between;align-items:center;background:var(--bg-secondary);">
          <h4 style="margin:0;font-size:14px;font-weight:var(--weight-bold);color:var(--text-primary);">
            {{ app()->getLocale() == 'ar' ? 'الإشعارات' : 'Notifications' }}
          </h4>
          <button class="clear-notifications-btn" style="background:transparent;border:none;font-size:11px;font-weight:var(--weight-semibold);color:var(--action-primary);cursor:pointer;padding:0;display:flex;align-items:center;gap:4px;">
            <span>{{ app()->getLocale() == 'ar' ? 'تحديد الكل كمقروء' : 'Mark all read' }}</span>
          </button>
        </div>
        <!-- Dropdown Body -->
        <div class="notification-list" style="max-height:300px;overflow-y:auto;display:flex;flex-direction:column;text-align:start;">
          <!-- Notification 1 -->
          <div class="notification-item unread" style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--border-subtle);display:flex;gap:12px;cursor:pointer;transition:background-color 0.2s ease;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--color-success-bg);color:var(--color-success);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style="flex:1;">
              <div style="font-size:12px;font-weight:var(--weight-bold);color:var(--text-primary);line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تم تقديم طلب التخارج بنجاح' : 'Exit Request Submitted' }}
              </div>
              <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تم استلام طلب التخارج الخاص بك لمشروع FinFlow بقيمة $200K وهو قيد المراجعة.' : 'Your exit request for FinFlow worth $200K has been received and is under review.' }}
              </div>
              <div style="font-size:10px;color:var(--text-tertiary);margin-top:6px;">
                {{ app()->getLocale() == 'ar' ? 'قبل دقيقة' : '1 minute ago' }}
              </div>
            </div>
          </div>
          <!-- Notification 2 -->
          <div class="notification-item unread" style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--border-subtle);display:flex;gap:12px;cursor:pointer;transition:background-color 0.2s ease;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--color-info-bg);color:var(--color-info);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
            </div>
            <div style="flex:1;">
              <div style="font-size:12px;font-weight:var(--weight-bold);color:var(--text-primary);line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تقرير مالي جديد' : 'New Performance Report' }}
              </div>
              <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تم نشر تقرير الأداء المالي للربع الأول لمشروع FinFlow.' : 'Q1 financial performance report for FinFlow is now published.' }}
              </div>
              <div style="font-size:10px;color:var(--text-tertiary);margin-top:6px;">
                {{ app()->getLocale() == 'ar' ? 'قبل ساعتين' : '2 hours ago' }}
              </div>
            </div>
          </div>
          <!-- Notification 3 -->
          <div class="notification-item" style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--border-subtle);display:flex;gap:12px;cursor:pointer;transition:background-color 0.2s ease;">
            <div style="width:36px;height:36px;border-radius:50%;background:var(--color-gold-light);color:var(--accent-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div style="flex:1;">
              <div style="font-size:12px;font-weight:var(--weight-bold);color:var(--text-primary);line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تم تأكيد موعد الاستشارة' : 'Consultation Scheduled' }}
              </div>
              <div style="font-size:11px;color:var(--text-secondary);margin-top:2px;line-height:1.4;">
                {{ app()->getLocale() == 'ar' ? 'تم تأكيد موعد لقائك لمراجعة محفظتك الاستثمارية مع أحمد الرشيد.' : 'Your portfolio review session with Ahmad Al-Rashid has been successfully scheduled.' }}
              </div>
              <div style="font-size:10px;color:var(--text-tertiary);margin-top:6px;">
                {{ app()->getLocale() == 'ar' ? 'قبل يوم' : 'Yesterday' }}
              </div>
            </div>
          </div>
        </div>
        <!-- Dropdown Footer -->
        <div style="padding:var(--space-3) var(--space-4);text-align:center;border-top:1px solid var(--border-subtle);background:var(--bg-secondary);">
          <a href="#" style="font-size:11px;font-weight:var(--weight-semibold);color:var(--text-secondary);text-decoration:none;">
            {{ app()->getLocale() == 'ar' ? 'عرض جميع الإشعارات' : 'View all notifications' }}
          </a>
        </div>
      </div>
    </div>
    <!-- Theme Toggle -->
    <button class="header-action-btn" data-action="toggle-theme" aria-label="Toggle theme">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
    </button>
    <!-- Language Toggle -->
    <button class="lang-toggle" data-action="toggle-lang">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
      <span class="lang-label">{{ app()->getLocale() == 'ar' ? 'English' : 'عربي' }}</span>
    </button>
    <div class="sidebar-avatar" style="width:36px;height:36px;font-size:12px;cursor:pointer">KA</div>
  </div>
</header>
