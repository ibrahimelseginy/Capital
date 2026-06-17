@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المشاريع' : 'Manage Projects')

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
    .stc-table { width: 100%; border-collapse: collapse; }
    .stc-table th { text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}; padding: 1rem; border-bottom: 2px solid var(--border-default); color: var(--text-secondary); font-weight: 600; font-size: 0.875rem; }
    .stc-table td { padding: 1rem; border-bottom: 1px solid var(--border-default); color: var(--text-primary); font-size: 0.95rem; transition: background 0.2s; }
    .stc-table tr.data-row:hover td { background: rgba(196, 164, 119, 0.03); }
    .stc-table tr:last-child td { border-bottom: none; }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .action-icon-btn { background: transparent; border: none; padding: 0.5rem; border-radius: var(--radius-full); color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
    .action-icon-btn:hover { background: var(--bg-secondary); color: var(--action-primary); }
    .action-icon-btn.approve:hover { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .action-icon-btn.reject:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .search-container { position: relative; max-width: 300px; width: 100%; }
    .search-container input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: var(--radius-full); border: 1px solid var(--border-default); background: var(--bg-surface); color: var(--text-primary); font-size: 0.9rem; transition: all 0.3s; }
    html[dir="rtl"] .search-container input { padding: 0.6rem 2.5rem 0.6rem 1rem; }
    .search-container input:focus { outline: none; border-color: var(--action-primary); box-shadow: 0 0 0 3px rgba(196, 164, 119, 0.1); }
    .search-icon { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--text-secondary); }
    html[dir="rtl"] .search-icon { left: auto; right: 1rem; }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة المشاريع' : 'Manage Projects' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'مراجعة واعتماد المشاريع المرفوعة من قبل رواد الأعمال' : 'Review and approve projects submitted by entrepreneurs' }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(196,164,119,0.1);color:var(--action-primary);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المشاريع' : 'Total Projects' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $projects->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $projects->where('status', 'Active')->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(245,158,11,0.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'قيد المراجعة' : 'Pending Review' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $projects->where('status', 'Pending')->count() }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Projects Table -->
    <div class="glass-card" style="padding:0; overflow:hidden">
        <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; background:rgba(0,0,0,0.02)">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قائمة المشاريع' : 'Projects List' }}</h3>
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="projectSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن مشروع...' : 'Search projects...' }}" onkeyup="filterProjects()">
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'المشروع' : 'Project' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'تاريخ التقديم' : 'Submission Date' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th style="text-align:center">{{ app()->getLocale() == 'ar' ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody">
                    @forelse($projects as $project)
                    <tr class="data-row">
                        <td>
                            <div style="font-weight: 600;">{{ $project->title }}</div>
                            <div class="text-caption text-secondary mt-1">{{ \Illuminate\Support\Str::limit($project->description, 50) }}</div>
                        </td>
                        <td class="text-secondary" style="font-weight:500">${{ number_format($project->budget) }}</td>
                        <td class="text-secondary">{{ $project->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-center">
                                @if(strtolower($project->status) == 'pending' || strtolower($project->status) == 'pending review')
                                <form action="{{ route('admin.projects.status', $project->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="status" value="Active">
                                    <button class="action-icon-btn approve" title="{{ app()->getLocale() == 'ar' ? 'اعتماد' : 'Approve' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.projects.status', $project->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="status" value="Rejected">
                                    <button class="action-icon-btn reject" title="{{ app()->getLocale() == 'ar' ? 'رفض' : 'Reject' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </form>
                                @endif
                                <button type="button" class="action-icon-btn" title="{{ app()->getLocale() == 'ar' ? 'تعديل المشروع' : 'Edit Project' }}" onclick="showEditProjectModal({{ $project->id }}, `{{ addslashes($project->title) }}`, `{{ addslashes($project->description) }}`, {{ $project->budget ?? 0 }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button type="button" class="action-icon-btn" title="{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}" onclick="showProjectDetails(`{{ addslashes($project->title) }}`, `{{ addslashes($project->description) }}`, `{{ number_format($project->budget) }}`, `{{ ucfirst($project->status) }}`, `{{ $project->created_at->format('M d, Y') }}`)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </button>
                                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من حذف هذا المشروع؟' : 'Are you sure you want to delete this project?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-icon-btn reject" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" style="opacity:0.2; margin-bottom:1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg><br>
                            {{ app()->getLocale() == 'ar' ? 'لا يوجد مشاريع بعد' : 'No projects found' }}
                        </td>
                    </tr>
                    @endforelse
                    <tr class="empty-row" id="noResultsRow" style="display:none">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" style="opacity:0.2; margin-bottom:1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg><br>
                            {{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة للبحث' : 'No matching results found' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="projectDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
        <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
            <div class="d-flex justify-between items-center mb-4">
                <h3 class="text-h4 m-0" id="modalProjectTitle">{{ app()->getLocale() == 'ar' ? 'تفاصيل المشروع' : 'Project Details' }}</h3>
                <button onclick="document.getElementById('projectDetailsModal').style.display='none'" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</label>
                    <p id="modalProjectDesc" class="mt-1" style="font-size:0.95rem; line-height:1.5; color:var(--text-primary);"></p>
                </div>
                <div class="d-flex gap-4">
                    <div style="flex:1">
                        <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</label>
                        <div id="modalProjectBudget" class="mt-1" style="font-weight:600; color:var(--text-primary);"></div>
                    </div>
                    <div style="flex:1">
                        <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                        <div id="modalProjectStatus" class="mt-1"></div>
                    </div>
                </div>
                <div>
                    <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'تاريخ التقديم' : 'Submission Date' }}</label>
                    <div id="modalProjectDate" class="mt-1" style="color:var(--text-primary);"></div>
                </div>
            </div>
            <div class="mt-6 d-flex justify-end">
                <button class="btn btn-secondary" onclick="document.getElementById('projectDetailsModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
            </div>
        </div>
    </div>
    <div id="editProjectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
        <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
            <div class="d-flex justify-between items-center mb-4">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'تعديل المشروع' : 'Edit Project' }}</h3>
                <button onclick="document.getElementById('editProjectModal').style.display='none'" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="editProjectForm" method="POST">
                @csrf
                <div class="d-flex flex-col gap-4">
                    <div>
                        <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Title' }}</label>
                        <input type="text" name="title" id="editProjectTitle" class="form-control" required style="width:100%; padding:0.5rem; border:1px solid var(--border-default); border-radius:var(--radius-md); background:var(--bg-surface); color:var(--text-primary); margin-top:0.5rem;">
                    </div>
                    <div>
                        <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</label>
                        <textarea name="description" id="editProjectDesc" class="form-control" required style="width:100%; padding:0.5rem; border:1px solid var(--border-default); border-radius:var(--radius-md); background:var(--bg-surface); color:var(--text-primary); margin-top:0.5rem;" rows="4"></textarea>
                    </div>
                    <div>
                        <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</label>
                        <input type="number" name="budget" id="editProjectBudget" class="form-control" required style="width:100%; padding:0.5rem; border:1px solid var(--border-default); border-radius:var(--radius-md); background:var(--bg-surface); color:var(--text-primary); margin-top:0.5rem;">
                    </div>
                </div>
                <div class="mt-6 d-flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('editProjectModal').style.display='none'" style="background:var(--bg-surface); color:var(--text-primary); border:1px solid var(--border-default); padding:0.5rem 1rem; border-radius:var(--radius-md); cursor:pointer;">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="background:var(--action-primary); color:#fff; border:none; padding:0.5rem 1rem; border-radius:var(--radius-md); cursor:pointer;">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterProjects() {
    const query = document.getElementById('projectSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#projectsTableBody tr.data-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = visibleCount === 0 && query !== '' ? '' : 'none';
    }
}

function showEditProjectModal(id, title, desc, budget) {
    document.getElementById('editProjectForm').action = '/admin/projects/' + id + '/update';
    document.getElementById('editProjectTitle').value = title;
    document.getElementById('editProjectDesc').value = desc;
    document.getElementById('editProjectBudget').value = budget;
    
    document.getElementById('editProjectModal').style.display = 'flex';
}

function showProjectDetails(title, desc, budget, status, date) {
    document.getElementById('modalProjectTitle').innerText = title;
    document.getElementById('modalProjectDesc').innerText = desc;
    document.getElementById('modalProjectBudget').innerText = '$' + budget;
    document.getElementById('modalProjectStatus').innerHTML = `<span class="badge badge-${status.toLowerCase()}">${status}</span>`;
    document.getElementById('modalProjectDate').innerText = date;
    
    document.getElementById('projectDetailsModal').style.display = 'flex';
}
</script>
@endsection
