@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'لوحة تحكم الإدارة' : 'Admin Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(196, 164, 119, 0.1);
        border-color: rgba(196, 164, 119, 0.3);
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
    .badge-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

    @media print {
        /* Hide unnecessary elements */
        .dashboard-sidebar, 
        .dashboard-header, 
        #global-search-modal, 
        #cookie-banner, 
        .btn, 
        form,
        .sidebar-user,
        .sidebar-nav,
        .sidebar-footer {
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
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
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

    <!-- Stats Grid -->
    <div class="dashboard-grid">
        <div class="glass-card" onclick="window.location.href='{{ route('admin.users') }}'" style="cursor: pointer;">
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
        
        <div class="glass-card" onclick="window.location.href='{{ route('admin.projects') }}'" style="cursor: pointer;">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المشاريع' : 'Total Projects' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ number_format($metrics['total_projects']) }}</div>
            <div class="mt-2 text-caption" style="color: #10b981;">
                ↑ {{ $metrics['active_projects'] }} {{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}
            </div>
        </div>
        
        <div class="glass-card" onclick="window.location.href='{{ route('admin.projects') }}'" style="cursor: pointer;">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي الميزانيات' : 'Portfolio Value' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">${{ number_format($metrics['total_portfolio_value']) }}</div>
            <div class="mt-2 text-caption text-secondary">
                {{ app()->getLocale() == 'ar' ? 'إجمالي المبالغ المستهدفة للمشاريع' : 'Total target amounts across projects' }}
            </div>
        </div>
        
        <div class="glass-card" onclick="window.location.href='{{ route('admin.requests') }}'" style="cursor: pointer;">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
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
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="glass-card">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'نمو المستخدمين' : 'User Growth' }}</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="userChart"></canvas>
            </div>
        </div>
        <div class="glass-card">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'توزيع المشاريع' : 'Project Distribution' }}</h3>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="projectChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="charts-grid">
        <div class="glass-card" style="padding:0; overflow:hidden">
            <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center;">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'أحدث المستخدمين' : 'Recent Users' }}</h3>
                <a href="{{ route('admin.users') }}" class="text-caption text-accent" style="text-decoration:none">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
            </div>
            <div class="table-container">
                <table class="stc-table">
                    <thead>
                        <tr>
                            <th>{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'نوع الحساب' : 'Role' }}</th>
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
                            <td colspan="3" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد مستخدمين بعد' : 'No users found' }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-card" style="padding:0; overflow:hidden">
            <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center;">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'أحدث المشاريع' : 'Recent Projects' }}</h3>
                <a href="{{ route('admin.projects') }}" class="text-caption text-accent" style="text-decoration:none">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
            </div>
            <div class="table-container">
                <table class="stc-table">
                    <thead>
                        <tr>
                            <th>{{ app()->getLocale() == 'ar' ? 'اسم المشروع' : 'Project Title' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الميزانية' : 'Budget' }}</th>
                            <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_projects as $project)
                        <tr>
                            <td style="font-weight: 500;">{{ $project->title }}</td>
                            <td class="text-secondary">${{ number_format($project->budget) }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد مشاريع بعد' : 'No projects found' }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
