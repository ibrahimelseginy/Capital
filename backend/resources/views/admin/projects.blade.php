@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المشاريع' : 'Manage Projects')

@section('content')
<style>
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }
    
    .badge { 
        display: inline-flex; align-items: center; padding: 0.35rem 0.85rem; 
        border-radius: 9999px; font-size: 0.75rem; font-weight: 700; 
        text-transform: uppercase; letter-spacing: 0.05em; 
    }
    .badge-active { background: rgba(16, 185, 129, 0.15); color: #059669; }
    .badge-pending { background: rgba(245, 158, 11, 0.15); color: #d97706; }
    .badge-rejected { background: rgba(239, 68, 68, 0.15); color: #dc2626; }
    
    .search-container { position: relative; max-width: 400px; width: 100%; }
    .search-container input { 
        width: 100%; padding: 0.8rem 1.2rem 0.8rem 2.8rem; 
        border-radius: var(--radius-full); border: 1px solid var(--border-default); 
        background: var(--bg-surface); color: var(--text-primary); font-size: 0.95rem; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
    }
    html[dir="rtl"] .search-container input { padding: 0.8rem 2.8rem 0.8rem 1.2rem; }
    .search-container input:focus { 
        outline: none; border-color: var(--action-primary); 
        box-shadow: 0 0 0 4px rgba(196, 164, 119, 0.15); 
        transform: translateY(-2px);
    }
    .search-icon { position: absolute; top: 50%; left: 1.2rem; transform: translateY(-50%); color: var(--text-tertiary); }
    html[dir="rtl"] .search-icon { left: auto; right: 1.2rem; }

    /* Modern Project Cards Grid */
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .project-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .project-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--border-strong);
    }

    .project-card-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .project-card-body {
        padding: 0 1.5rem 1.5rem 1.5rem;
        flex: 1;
    }

    .project-card-footer {
        padding: 1rem 1.5rem;
        background: var(--bg-secondary);
        border-top: 1px solid var(--border-subtle);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5rem;
    }

    .budget-display {
        background: rgba(196, 164, 119, 0.08);
        border-radius: var(--radius-lg);
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid rgba(196, 164, 119, 0.2);
    }

    .action-icon-btn { 
        background: var(--bg-surface); border: 1px solid var(--border-default); 
        width: 36px; height: 36px; border-radius: 50%; color: var(--text-secondary); 
        cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; 
        box-shadow: var(--shadow-sm);
    }
    .action-icon-btn:hover { transform: scale(1.1); box-shadow: var(--shadow-md); }
    .action-icon-btn.approve:hover { background: var(--color-success); color: white; border-color: var(--color-success); }
    .action-icon-btn.reject:hover { background: var(--color-error); color: white; border-color: var(--color-error); }
    .action-icon-btn.edit:hover { background: var(--action-primary); color: white; border-color: var(--action-primary); }

    /* Empty State */
    .beautiful-empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 5rem 2rem; text-align: center; background: var(--bg-surface);
        border: 1px dashed var(--border-strong); border-radius: var(--radius-xl);
        margin-top: 2rem; grid-column: 1 / -1;
    }

    .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-h2" style="font-weight: 700; letter-spacing: -0.5px;">{{ app()->getLocale() == 'ar' ? 'إدارة المشاريع' : 'Manage Projects' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'اكتشف، راجع، واعتمد المشاريع المبتكرة في منصتك.' : 'Discover, review, and approve innovative projects on your platform.' }}</p>
        </div>
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="projectSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن مشروع...' : 'Search projects...' }}" onkeyup="filterProjects()">
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.1s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(196,164,119,0.15);color:var(--action-primary);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'إجمالي المشاريع' : 'Total Projects' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $projects->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.2s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(16,185,129,0.15);color:#059669;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'مشاريع نشطة' : 'Active Projects' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $projects->where('status', 'Active')->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.3s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(245,158,11,0.15);color:#d97706;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'قيد المراجعة' : 'Pending Review' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $projects->where('status', 'Pending')->count() }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Modern Projects Grid -->
    <div class="projects-grid" id="projectsContainer">
        @forelse($projects as $index => $project)
        <div class="project-card stagger-item" style="animation-delay: {{ 0.1 * ($index + 1) }}s;" data-title="{{ strtolower($project->title) }}" data-desc="{{ strtolower($project->description) }}">
            <div class="project-card-header">
                <div>
                    <h3 class="text-h4 m-0" style="font-weight: 700; line-height: 1.3;">{{ $project->title }}</h3>
                    <div class="text-caption text-tertiary mt-1 d-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $project->created_at->format('M d, Y') }}
                    </div>
                </div>
                <span class="badge badge-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
            </div>
            
            <div class="project-card-body">
                <p class="text-body text-secondary" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $project->description }}
                </p>
                
                <div class="budget-display">
                    <div style="background: var(--bg-surface); border-radius: 50%; width: 32px; height: 32px; display:flex; align-items:center; justify-content:center; color:var(--action-primary); box-shadow:var(--shadow-sm);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div class="text-caption text-tertiary">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</div>
                        <div class="text-h5" style="font-weight: 700; color: var(--text-primary);">${{ number_format($project->budget) }}</div>
                    </div>
                </div>
            </div>

            <div class="project-card-footer">
                <div class="d-flex gap-2">
                    <button type="button" class="action-icon-btn" title="{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}" onclick="showProjectDetails(`{{ addslashes($project->title) }}`, `{{ addslashes($project->description) }}`, `{{ number_format($project->budget) }}`, `{{ ucfirst($project->status) }}`, `{{ $project->created_at->format('M d, Y') }}`)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                    <button type="button" class="action-icon-btn edit" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}" onclick="showEditProjectModal({{ $project->id }}, `{{ addslashes($project->title) }}`, `{{ addslashes($project->description) }}`, {{ $project->budget ?? 0 }})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من حذف هذا المشروع؟' : 'Are you sure you want to delete this project?' }}');">
                        @csrf
                        @method('DELETE')
                        <button class="action-icon-btn reject" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
                
                @if(strtolower($project->status) == 'pending' || strtolower($project->status) == 'pending review')
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.projects.status', $project->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <input type="hidden" name="status" value="Active">
                        <button class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem; background: var(--color-success); border-color: var(--color-success);">
                            {{ app()->getLocale() == 'ar' ? 'اعتماد' : 'Approve' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.projects.status', $project->id) }}" method="POST" style="margin:0;">
                        @csrf
                        <input type="hidden" name="status" value="Rejected">
                        <button class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.8rem; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);">
                            {{ app()->getLocale() == 'ar' ? 'رفض' : 'Reject' }}
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="beautiful-empty-state">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="6" width="20" height="14" rx="2"/><path d="M12 6V2"/></svg>
            </div>
            <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد مشاريع حتى الآن' : 'No projects yet' }}</h3>
            <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'لم يقم رواد الأعمال بتقديم أي مشاريع على المنصة بعد.' : 'Entrepreneurs haven\'t submitted any projects to the platform yet.' }}</p>
        </div>
        @endforelse
        
        <div class="beautiful-empty-state" id="noResultsState" style="display:none;">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة' : 'No matching results' }}</h3>
            <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'جرب البحث بكلمات مختلفة' : 'Try searching with different keywords' }}</p>
        </div>
    </div>
    
    <!-- View Details Modal (Modernized) -->
    <div id="projectDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
        <div class="glass-card" style="width:100%; max-width:600px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" id="modalProjectTitle" style="font-weight: 700;"></h3>
                <button onclick="closeModal('projectDetailsModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="d-flex flex-col gap-6">
                <div style="background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.5rem;">
                    <label class="text-caption text-secondary" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</label>
                    <p id="modalProjectDesc" class="mt-2" style="font-size:1.05rem; line-height:1.7; color:var(--text-primary);"></p>
                </div>
                <div class="d-flex gap-4 flex-wrap">
                    <div style="flex:1; min-width: 150px; background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.5rem;">
                        <label class="text-caption text-secondary" style="font-weight: 600; text-transform: uppercase;">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</label>
                        <div id="modalProjectBudget" class="mt-2 text-h3" style="font-weight:700; color:var(--action-primary);"></div>
                    </div>
                    <div style="flex:1; min-width: 150px; background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.5rem;">
                        <label class="text-caption text-secondary" style="font-weight: 600; text-transform: uppercase;">{{ app()->getLocale() == 'ar' ? 'الحالة & التاريخ' : 'Status & Date' }}</label>
                        <div class="d-flex items-center gap-3 mt-2">
                            <div id="modalProjectStatus"></div>
                            <div id="modalProjectDate" class="text-secondary font-medium"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end">
                <button class="btn btn-secondary" style="padding: 0.75rem 2rem; border-radius: var(--radius-full);" onclick="closeModal('projectDetailsModal')">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
            </div>
        </div>
    </div>
    
    <!-- Edit Project Modal (Modernized) -->
    <div id="editProjectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
        <div class="glass-card" style="width:100%; max-width:550px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل المشروع' : 'Edit Project' }}</h3>
                <button onclick="closeModal('editProjectModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="editProjectForm" method="POST">
                @csrf
                <div class="d-flex flex-col gap-5">
                    <div>
                        <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Title' }}</label>
                        <input type="text" name="title" id="editProjectTitle" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                    </div>
                    <div>
                        <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</label>
                        <textarea name="description" id="editProjectDesc" class="form-input" required style="width:100%; padding:0.8rem 1rem;" rows="5"></textarea>
                    </div>
                    <div>
                        <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }} ($)</label>
                        <input type="number" name="budget" id="editProjectBudget" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                    </div>
                </div>
                <div class="mt-8 d-flex justify-end gap-3">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editProjectModal')" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterProjects() {
    const query = document.getElementById('projectSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.project-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        const desc = card.getAttribute('data-desc');
        
        if (title.includes(query) || desc.includes(query)) {
            card.style.display = 'flex';
            visibleCount++;
            // Re-trigger animation
            card.style.animation = 'none';
            card.offsetHeight; /* trigger reflow */
            card.style.animation = null; 
        } else {
            card.style.display = 'none';
        }
    });

    const noResultsState = document.getElementById('noResultsState');
    if (noResultsState) {
        noResultsState.style.display = visibleCount === 0 && query !== '' ? 'flex' : 'none';
    }
}

function showProjectDetails(title, desc, budget, status, date) {
    document.getElementById('modalProjectTitle').innerText = title;
    document.getElementById('modalProjectDesc').innerText = desc;
    document.getElementById('modalProjectBudget').innerText = '$' + budget;
    document.getElementById('modalProjectStatus').innerHTML = `<span class="badge badge-${status.toLowerCase()}">${status}</span>`;
    document.getElementById('modalProjectDate').innerText = date;
    
    openModal('projectDetailsModal');
}

function showEditProjectModal(id, title, desc, budget) {
    document.getElementById('editProjectForm').action = '/admin/projects/' + id + '/update';
    document.getElementById('editProjectTitle').value = title;
    document.getElementById('editProjectDesc').value = desc;
    document.getElementById('editProjectBudget').value = budget;
    
    openModal('editProjectModal');
}

function openModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
    // Small delay to allow display:flex to apply before animating opacity
    setTimeout(() => {
        modal.style.opacity = '1';
        modal.querySelector('.glass-card').style.transform = 'translateY(0)';
    }, 10);
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.style.opacity = '0';
    modal.querySelector('.glass-card').style.transform = 'translateY(20px)';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}
</script>
@endsection
