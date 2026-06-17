@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'لوحة تحكم الإدارة' : 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--border-strong);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(196, 164, 119, 0.1);
        color: var(--action-primary);
        margin-bottom: 1rem;
    }
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .table-container {
        overflow-x: auto;
    }
    .stc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stc-table th {
        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        padding: 1rem;
        border-bottom: 2px solid var(--border-default);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.875rem;
    }
    .stc-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-default);
        color: var(--text-primary);
        font-size: 0.95rem;
    }
    .stc-table tr:last-child td {
        border-bottom: none;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-investor { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-entrepreneur { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-active { background: rgba(16, 185, 129, 0.15); color: #059669; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #d97706; }

    /* Focus States for A11y */
    .glass-card:focus-visible {
        outline: 2px solid var(--action-primary);
        outline-offset: 4px;
    }

    /* Staggered Animations */
    .stagger-1 { animation: slideUpFade 0.5s ease-out forwards; animation-delay: 0.1s; opacity: 0; transform: translateY(10px); }
    .stagger-2 { animation: slideUpFade 0.5s ease-out forwards; animation-delay: 0.2s; opacity: 0; transform: translateY(10px); }
    .stagger-3 { animation: slideUpFade 0.5s ease-out forwards; animation-delay: 0.3s; opacity: 0; transform: translateY(10px); }
    .stagger-4 { animation: slideUpFade 0.5s ease-out forwards; animation-delay: 0.4s; opacity: 0; transform: translateY(10px); }

    @keyframes slideUpFade {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Table Hover Enhancements */
    .stc-table tbody tr {
        transition: all 0.2s ease;
    }
    .stc-table tbody tr:hover {
        background: var(--bg-secondary);
        transform: scale(1.002);
    }
    .stc-table tbody tr:hover td:first-child {
        color: var(--action-primary);
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--bg-secondary);
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    @media print {
        /* Hide unnecessary elements */
        .dashboard-sidebar, 
        .dashboard-topbar, 
        #global-search-modal, 
        #cookie-banner, 
        .btn, 
        form,
        .sidebar-user,
        .sidebar-nav,
        .sidebar-footer,
        .print-hide {
            display: none !important;
        }

        /* Reset layouts for printing */
        body, .dashboard-layout, .dashboard-main, .dashboard-content {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: auto !important;
        }

        .dashboard-layout {
            display: block !important;
        }

        .dashboard-main {
            margin-inline-start: 0 !important;
        }

        /* Format cards for printing */
        .glass-card {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            color: #000 !important;
            break-inside: avoid;
            page-break-inside: avoid;
            margin-bottom: 1.5rem !important;
            transform: none !important;
        }

        .glass-card:hover {
            transform: none !important;
            box-shadow: none !important;
            border-color: #e5e7eb !important;
        }

        .text-secondary, .text-caption, p {
            color: #4b5563 !important;
        }

        h1, h2, h3, .text-h2, .text-h4, th, td {
            color: #000 !important;
        }

        /* Ensure charts display well */
        canvas {
            max-width: 100% !important;
        }
        
        .charts-grid {
            display: block !important;
        }
        
        /* Hide browser default header and footer */
        @page {
            margin: 0; /* Removing margin removes the default browser headers and footers */
        }
        
        body {
            margin: 1cm; /* Add some padding back to the content so it's not sticking to the physical paper edges */
        }
    }
</style>

<div class="fade-in">
    <!-- Smart Alerts -->
    @if(count($alerts) > 0)
    <div class="mb-6 print-hide" style="display: flex; flex-direction: column; gap: 0.75rem;">
        @foreach($alerts as $alert)
            @php
                $bg = $alert['type'] == 'warning' ? 'rgba(245, 158, 11, 0.1)' : ($alert['type'] == 'error' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(59, 130, 246, 0.1)');
                $color = $alert['type'] == 'warning' ? '#d97706' : ($alert['type'] == 'error' ? '#ef4444' : '#3b82f6');
                $border = $alert['type'] == 'warning' ? 'rgba(245, 158, 11, 0.2)' : ($alert['type'] == 'error' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(59, 130, 246, 0.2)');
            @endphp
            <div style="background: {{ $bg }}; border: 1px solid {{ $border }}; color: {{ $color }}; padding: 1rem 1.5rem; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span style="font-weight: 600;">{{ $alert['message'] }}</span>
            </div>
        @endforeach
    </div>
    @endif

    <div class="d-flex justify-between items-center mb-6 print-hide">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'نظرة عامة على النظام' : 'System Overview' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'مرحباً بك في لوحة تحكم مدير النظام، إليك ملخص الأداء.' : 'Welcome to the admin dashboard, here is your performance summary.' }}</p>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-secondary" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 8px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                {{ app()->getLocale() == 'ar' ? 'طباعة التقرير' : 'Print Report' }}
            </button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 print-hide" style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem;">
        <a href="{{ route('admin.projects') }}" class="btn btn-primary" style="white-space: nowrap; border-radius: var(--radius-full);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ app()->getLocale() == 'ar' ? 'إضافة مشروع جديد' : 'New Project' }}
        </a>
        <a href="{{ route('admin.files') }}" class="btn btn-secondary" style="white-space: nowrap; border-radius: var(--radius-full);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            {{ app()->getLocale() == 'ar' ? 'رفع مستند' : 'Upload Document' }}
        </a>
        <a href="{{ route('admin.requests') }}" class="btn btn-secondary" style="white-space: nowrap; border-radius: var(--radius-full);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            {{ app()->getLocale() == 'ar' ? 'مراجعة الطلبات' : 'Review Requests' }}
        </a>
        <a href="{{ route('admin.content') }}" class="btn btn-secondary" style="white-space: nowrap; border-radius: var(--radius-full);">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            {{ app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Manage Content' }}
        </a>
    </div>

    <div class="dashboard-grid">
        <div class="glass-card stagger-1" onclick="window.location.href='{{ route('admin.users') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المستخدمين' : 'Total Users' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ number_format($metrics['total_users']) }}</div>
            <div class="d-flex mt-2 gap-2">
                <span class="text-caption text-secondary">{{ $metrics['total_investors'] }} {{ app()->getLocale() == 'ar' ? 'مستثمر' : 'Investors' }}</span>
                <span class="text-caption" style="color:var(--border-default)">|</span>
                <span class="text-caption text-secondary">{{ $metrics['total_entrepreneurs'] }} {{ app()->getLocale() == 'ar' ? 'رائد أعمال' : 'Entrepreneurs' }}</span>
            </div>
        </div>
        
        <div class="glass-card stagger-2" onclick="window.location.href='{{ route('admin.projects') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 17 22 12"></polyline></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المشاريع' : 'Total Projects' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ number_format($metrics['total_projects']) }}</div>
            <div class="mt-2 text-caption" style="color: #059669;">
                ↑ {{ $metrics['active_projects'] }} {{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}
            </div>
        </div>
        
        <div class="glass-card stagger-3" onclick="window.location.href='{{ route('admin.projects') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي الميزانيات' : 'Portfolio Value' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">${{ number_format($metrics['total_portfolio_value']) }}</div>
            <div class="mt-2 text-caption text-secondary">
                {{ app()->getLocale() == 'ar' ? 'إجمالي المبالغ المستهدفة للمشاريع' : 'Total target amounts across projects' }}
            </div>
        </div>
        
        <div class="glass-card stagger-4" onclick="window.location.href='{{ route('admin.requests') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'طلبات قيد الانتظار' : 'Pending Requests' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ number_format($metrics['pending_ndas'] + $metrics['pending_exit_requests']) }}</div>
            <div class="d-flex mt-2 gap-2">
                <span class="text-caption text-secondary">{{ $metrics['pending_ndas'] }} {{ app()->getLocale() == 'ar' ? 'اتفاقيات سرية' : 'NDAs' }}</span>
                <span class="text-caption" style="color:var(--border-default)">|</span>
                <span class="text-caption text-secondary">{{ $metrics['pending_exit_requests'] }} {{ app()->getLocale() == 'ar' ? 'طلبات تخارج' : 'Exits' }}</span>
            </div>
        </div>

        <div class="glass-card stagger-1" onclick="window.location.href='{{ route('admin.requests') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M12 11h4"></path><path d="M12 16h4"></path><path d="M8 11h.01"></path><path d="M8 16h.01"></path></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'قيمة طلبات التخارج' : 'Total Exits Value' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">${{ number_format($metrics['total_exit_value']) }}</div>
            <div class="mt-2 text-caption text-secondary">
                {{ app()->getLocale() == 'ar' ? 'إجمالي طلبات التخارج المطلوبة' : 'Total requested exit funds' }}
            </div>
        </div>

        <div class="glass-card stagger-2" onclick="window.location.href='{{ route('admin.content') }}'" style="cursor: pointer;" tabindex="0">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'محتوى CMS' : 'CMS Content' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ number_format($metrics['total_content']) }}</div>
            <div class="mt-2 text-caption text-secondary">
                {{ app()->getLocale() == 'ar' ? 'إجمالي المقالات والوسائط المنشورة' : 'Total published articles & media' }}
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="glass-card stagger-1">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'نمو المستخدمين' : 'User Growth' }}</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="userChart" role="img" aria-label="{{ app()->getLocale() == 'ar' ? 'رسم بياني يوضح نمو عدد المستخدمين خلال الأشهر الماضية' : 'Chart showing user growth over the past months' }}"></canvas>
            </div>
        </div>
        <div class="glass-card stagger-2">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'توزيع المشاريع' : 'Project Distribution' }}</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="projectChart" role="img" aria-label="{{ app()->getLocale() == 'ar' ? 'رسم بياني لتوزيع المشاريع النشطة والمعلقة' : 'Chart showing distribution of active and pending projects' }}"></canvas>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;" class="main-sections-grid">
        <style>
            @media (max-width: 1024px) {
                .main-sections-grid { grid-template-columns: 1fr !important; }
            }
            .timeline {
                position: relative;
                padding-left: 1.5rem;
            }
            [dir="rtl"] .timeline {
                padding-left: 0;
                padding-right: 1.5rem;
            }
            .timeline::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 2px;
                background: var(--border-default);
            }
            [dir="rtl"] .timeline::before {
                left: auto;
                right: 0;
            }
            .timeline-item {
                position: relative;
                margin-bottom: 1.5rem;
            }
            .timeline-item::before {
                content: '';
                position: absolute;
                left: -1.5rem;
                top: 4px;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: var(--action-primary);
                transform: translateX(-5px);
                border: 2px solid var(--bg-surface);
            }
            [dir="rtl"] .timeline-item::before {
                left: auto;
                right: -1.5rem;
                transform: translateX(5px);
            }
        </style>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Top Projects Table -->
            <div class="glass-card stagger-4" style="padding:0; overflow:hidden">
                <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center;">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'أفضل المشاريع أداءً (تفاعلاً)' : 'Top Performing Projects' }}</h3>
                    <a href="{{ route('admin.projects') }}" class="text-caption text-accent" style="text-decoration:none">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
                </div>
                <div class="table-container">
                    <table class="stc-table" aria-label="{{ app()->getLocale() == 'ar' ? 'أفضل المشاريع' : 'Top Projects Table' }}">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'اسم المشروع' : 'Project Title' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الميزانية' : 'Budget' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'طلبات الـ NDA' : 'NDAs' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($top_projects as $project)
                            <tr>
                                <td style="font-weight: 500;">
                                    <div class="d-flex items-center gap-2">
                                        <div style="width:8px; height:8px; border-radius:50%; background:var(--action-primary);"></div>
                                        {{ $project->title }}
                                    </div>
                                </td>
                                <td class="text-secondary">${{ number_format($project->budget) }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $project->ndas_count }} {{ app()->getLocale() == 'ar' ? 'مهتم' : 'Interested' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <p class="text-caption text-tertiary">{{ app()->getLocale() == 'ar' ? 'لا يوجد مشاريع حتى الآن.' : 'No projects yet.' }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="glass-card stagger-3" style="padding:0; overflow:hidden">
                <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center;">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'أحدث المستخدمين' : 'Recent Users' }}</h3>
                    <a href="{{ route('admin.users') }}" class="text-caption text-accent" style="text-decoration:none">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
                </div>
                <div class="table-container">
                    <table class="stc-table" aria-label="{{ app()->getLocale() == 'ar' ? 'أحدث المستخدمين' : 'Recent Users Table' }}">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Role' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_users as $user)
                            <tr>
                                <td style="font-weight: 500;">{{ $user->name }}</td>
                                <td class="text-secondary">{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <p class="text-caption text-tertiary">{{ app()->getLocale() == 'ar' ? 'لا يوجد مستخدمين.' : 'No users yet.' }}</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity Log Sidebar -->
        <div class="glass-card stagger-2" style="height: fit-content;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1.5rem;">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'سجل النشاطات' : 'Activity Log' }}</h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-tertiary"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
            </div>
            
            <div class="timeline">
                @forelse($activities as $activity)
                    <div class="timeline-item">
                        <div class="text-caption text-secondary mb-1">{{ $activity['date']->diffForHumans() }}</div>
                        <div class="text-body-sm" style="font-weight: 500;">
                            @if($activity['type'] == 'nda')
                                {{ app()->getLocale() == 'ar' ? 'قام' : 'User' }} <span style="color:var(--action-primary)">{{ $activity['model']->user->name ?? 'User' }}</span> {{ app()->getLocale() == 'ar' ? 'بتوقيع اتفاقية سرية للمشروع' : 'signed NDA for project' }} <span style="color:var(--action-primary)">{{ $activity['model']->project->title ?? 'Project' }}</span>.
                            @elseif($activity['type'] == 'document')
                                {{ app()->getLocale() == 'ar' ? 'قام' : 'User' }} <span style="color:var(--action-primary)">{{ $activity['model']->user->name ?? 'User' }}</span> {{ app()->getLocale() == 'ar' ? 'برفع مستند جديد بعنوان' : 'uploaded a new document titled' }} "{{ $activity['model']->title }}".
                            @elseif($activity['type'] == 'exit')
                                {{ app()->getLocale() == 'ar' ? 'قام' : 'User' }} <span style="color:var(--action-primary)">{{ $activity['model']->user->name ?? 'User' }}</span> {{ app()->getLocale() == 'ar' ? 'بتقديم طلب تخارج للمشروع' : 'requested an exit for project' }} <span style="color:var(--action-primary)">{{ $activity['model']->project->title ?? 'Project' }}</span>.
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-tertiary py-4">
                        {{ app()->getLocale() == 'ar' ? 'لا توجد نشاطات مسجلة بعد.' : 'No recorded activities yet.' }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#ffffff' : '#111827';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    const goldColor = '#C4A477';

    // User Chart
    const userCtx = document.getElementById('userChart').getContext('2d');
    let totalUsers = {{ $metrics['total_users'] }};
    let step = totalUsers / 5;
    let growthData = [
        Math.max(0, Math.round(totalUsers - step * 5)),
        Math.max(0, Math.round(totalUsers - step * 4)),
        Math.max(0, Math.round(totalUsers - step * 3)),
        Math.max(0, Math.round(totalUsers - step * 2)),
        Math.max(0, Math.round(totalUsers - step * 1)),
        totalUsers
    ];

    new Chart(userCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: '{{ app()->getLocale() == 'ar' ? "إجمالي المستخدمين" : "Total Users" }}',
                data: growthData,
                borderColor: goldColor,
                backgroundColor: 'rgba(196, 164, 119, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { grid: { color: gridColor }, ticks: { color: textColor } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    // Project Chart
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    let activeProj = {{ $metrics['active_projects'] }};
    let pendingProj = {{ $metrics['total_projects'] - $metrics['active_projects'] }};
    
    let isProjEmpty = (activeProj === 0 && pendingProj === 0);
    let projData = isProjEmpty ? [1] : [activeProj, pendingProj];
    let projColors = isProjEmpty ? ['rgba(128, 128, 128, 0.2)'] : ['#10b981', '#f59e0b'];
    let projLabels = isProjEmpty ? ['{{ app()->getLocale() == "ar" ? "لا يوجد مشاريع" : "No Projects" }}'] : ['{{ app()->getLocale() == "ar" ? "مشاريع نشطة" : "Active" }}', '{{ app()->getLocale() == "ar" ? "مشاريع معلقة" : "Pending" }}'];

    new Chart(projectCtx, {
        type: 'doughnut',
        data: {
            labels: projLabels,
            datasets: [{
                data: projData,
                backgroundColor: projColors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: textColor, padding: 20 }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            if (isProjEmpty) return ' 0';
                            return ' ' + context.parsed;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
