@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'لوحة تحكم رائد الأعمال' : 'Entrepreneur Dashboard')

@section('content')
<style>
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stc-table { width: 100%; border-collapse: collapse; }
    .stc-table th { text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}; padding: 1rem; border-bottom: 2px solid var(--border-default); color: var(--text-secondary); font-weight: 600; font-size: 0.875rem; }
    .stc-table td { padding: 1rem; border-bottom: 1px solid var(--border-default); color: var(--text-primary); font-size: 0.95rem; }
    .stc-table tr:last-child td { border-bottom: none; }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-md);
        color: var(--text-primary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        width: 100%;
    }
    .quick-action-btn:hover {
        background: var(--bg-secondary);
        border-color: var(--action-primary);
        transform: translateY(-2px);
    }
    .quick-action-icon {
        width: 40px; height: 40px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(196, 164, 119, 0.1); color: var(--action-primary);
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'مرحباً، ' : 'Welcome, ' }} {{ auth()->check() ? auth()->user()->name : 'رائد الأعمال' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'إليك ملخص سريع لأداء مشاريعك وتمويلاتك.' : 'Here is a quick overview of your projects and funding.' }}</p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('addProjectModal').style.display='flex'">
            {{ app()->getLocale() == 'ar' ? '+ إضافة مشروع جديد' : '+ Add New Project' }}
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:56px;height:56px;border-radius:var(--radius-md);background:rgba(196,164,119,0.1);color:var(--action-primary);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'مشاريعي' : 'My Projects' }}</div>
                <div class="text-h3 mt-1" style="font-weight:700">{{ $metrics['total_projects'] }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:56px;height:56px;border-radius:var(--radius-md);background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي التمويل المطلوب' : 'Total Funding Required' }}</div>
                <div class="text-h3 mt-1" style="font-weight:700">${{ number_format($metrics['total_funding']) }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:56px;height:56px;border-radius:var(--radius-md);background:rgba(245,158,11,0.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'تقارير معلقة' : 'Pending Reports' }}</div>
                <div class="text-h3 mt-1" style="font-weight:700">{{ $metrics['pending_reports'] }}</div>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:2rem;">
        <!-- Chart Section -->
        <div class="glass-card" style="padding:1.5rem;">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'تقدم المشاريع' : 'Projects Progress' }}</h3>
            <canvas id="entrepreneurChart" height="250"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="glass-card" style="padding:1.5rem; background: rgba(196, 164, 119, 0.02)">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'إجراءات سريعة' : 'Quick Actions' }}</h3>
            <div class="d-flex flex-col gap-3">
                <button class="quick-action-btn" onclick="document.getElementById('addProjectModal').style.display='flex'">
                    <div class="quick-action-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></div>
                    <div>
                        <div style="font-size:1rem;">{{ app()->getLocale() == 'ar' ? 'إضافة مشروع' : 'Add Project' }}</div>
                        <div class="text-caption text-secondary mt-1" style="font-weight:400">{{ app()->getLocale() == 'ar' ? 'قدم فكرة مشروعك الجديدة للمستثمرين' : 'Submit your new project idea to investors' }}</div>
                    </div>
                </button>
                <button class="quick-action-btn" onclick="window.location.href='#'">
                    <div class="quick-action-icon" style="background:rgba(59,130,246,0.1); color:#3b82f6;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></div>
                    <div>
                        <div style="font-size:1rem;">{{ app()->getLocale() == 'ar' ? 'رفع عرض تقديمي (Pitch Deck)' : 'Upload Pitch Deck' }}</div>
                        <div class="text-caption text-secondary mt-1" style="font-weight:400">{{ app()->getLocale() == 'ar' ? 'تحديث مستندات مشاريعك' : 'Update your project documents' }}</div>
                    </div>
                </button>
                <button class="quick-action-btn" onclick="window.location.href='#'">
                    <div class="quick-action-icon" style="background:rgba(16,185,129,0.1); color:#10b981;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></div>
                    <div>
                        <div style="font-size:1rem;">{{ app()->getLocale() == 'ar' ? 'طلب تعديل' : 'Request Edit' }}</div>
                        <div class="text-caption text-secondary mt-1" style="font-weight:400">{{ app()->getLocale() == 'ar' ? 'اطلب تعديلاً على بيانات مشروع معتمد' : 'Request an edit for an approved project' }}</div>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Projects Table -->
    <div class="glass-card" style="padding:0; overflow:hidden">
        <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center;">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'أحدث المشاريع' : 'Recent Projects' }}</h3>
            <a href="{{ route('entrepreneur.projects') }}" class="text-primary" style="font-weight:600; text-decoration:none;">{{ app()->getLocale() == 'ar' ? 'عرض الكل' : 'View All' }}</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'تاريخ التقديم' : 'Submission Date' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                    <tr>
                        <td>
                            <div style="font-weight: 600;">{{ $project->title }}</div>
                        </td>
                        <td class="text-secondary" style="font-weight:500">${{ number_format($project->budget) }}</td>
                        <td class="text-secondary">{{ $project->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" style="opacity:0.2; margin-bottom:1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg><br>
                            {{ app()->getLocale() == 'ar' ? 'لم تقم بتقديم أي مشاريع بعد.' : 'You have not submitted any projects yet.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Project Modal -->
<div id="addProjectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card fade-in" style="width:100%; max-width:600px; background:var(--bg-primary);">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h3 m-0">{{ app()->getLocale() == 'ar' ? 'إضافة مشروع جديد' : 'Add New Project' }}</h3>
            <button onclick="document.getElementById('addProjectModal').style.display='none'" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        
        <form action="#" method="POST" class="d-flex flex-col gap-4">
            @csrf
            <div>
                <label class="form-label mb-2" style="display:block">{{ app()->getLocale() == 'ar' ? 'اسم المشروع' : 'Project Title' }}</label>
                <input type="text" name="title" class="form-input" style="width:100%;" required>
            </div>
            
            <div>
                <label class="form-label mb-2" style="display:block">{{ app()->getLocale() == 'ar' ? 'وصف المشروع' : 'Project Description' }}</label>
                <textarea name="description" class="form-input" style="width:100%; min-height:100px; resize:vertical;" required></textarea>
            </div>
            
            <div class="d-flex gap-4">
                <div style="flex:1">
                    <label class="form-label mb-2" style="display:block">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة ($)' : 'Target Budget ($)' }}</label>
                    <input type="number" name="budget" class="form-input" style="width:100%;" required>
                </div>
                <div style="flex:1">
                    <label class="form-label mb-2" style="display:block">{{ app()->getLocale() == 'ar' ? 'القطاع' : 'Sector' }}</label>
                    <select name="sector" class="form-input" style="width:100%; padding:0.75rem;">
                        <option value="tech">Technology</option>
                        <option value="health">Healthcare</option>
                        <option value="finance">FinTech</option>
                        <option value="real_estate">Real Estate</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex gap-3 justify-end">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addProjectModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'تقديم للمراجعة' : 'Submit for Review' }}</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
    const ctx = document.getElementById('entrepreneurChart').getContext('2d');
    
    // Smooth gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(196, 164, 119, 0.4)');
    gradient.addColorStop(1, 'rgba(196, 164, 119, 0.0)');

    const labels = isAr ? ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'] : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    
    // Simulate funding progress (cumulative)
    let totalFunding = {{ $metrics['total_funding'] }};
    let dataPoints = [
        Math.floor(totalFunding * 0.1),
        Math.floor(totalFunding * 0.25),
        Math.floor(totalFunding * 0.4),
        Math.floor(totalFunding * 0.65),
        Math.floor(totalFunding * 0.8),
        totalFunding
    ];

    if(totalFunding === 0) {
        dataPoints = [0, 0, 0, 0, 0, 0];
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: isAr ? 'التمويل المتراكم ($)' : 'Cumulative Funding ($)',
                data: dataPoints,
                borderColor: '#c4a477',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#c4a477',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(16, 16, 16, 0.9)',
                    titleFont: { family: 'Outfit', size: 13 },
                    bodyFont: { family: 'Outfit', size: 14, weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0, 0, 0, 0.05)', drawBorder: false },
                    ticks: {
                        font: { family: 'Outfit', size: 11 },
                        color: '#666',
                        callback: function(value) {
                            if(value >= 1000) return '$' + (value/1000) + 'k';
                            return '$' + value;
                        }
                    }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { family: 'Outfit', size: 12 }, color: '#666' }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection
