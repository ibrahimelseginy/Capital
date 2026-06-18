@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تفاصيل المشروع - ' . $project->title : 'Project Details - ' . $project->title)

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
    }
    
    .nav-tabs {
        display: flex;
        gap: 1rem;
        border-bottom: 2px solid var(--border-subtle);
        margin-bottom: 2rem;
        overflow-x: auto;
    }
    .nav-tab {
        padding: 1rem 1.5rem;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .nav-tab:hover {
        color: var(--text-primary);
    }
    .nav-tab.active {
        color: var(--action-primary);
        border-bottom-color: var(--action-primary);
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-box {
        background: rgba(196,164,119,0.05);
        border: 1px solid rgba(196,164,119,0.2);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--action-primary);
        margin-bottom: 0.5rem;
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-6">
        <div class="d-flex gap-4 items-center">
            @if($project->image)
            <div style="width: 80px; height: 80px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
                <img src="{{ Storage::url($project->image) }}" alt="Project" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            @endif
            <div>
                <a href="{{ route('admin.projects') }}" class="text-secondary d-flex items-center gap-2 mb-2" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    {{ app()->getLocale() == 'ar' ? 'العودة للمشاريع' : 'Back to Projects' }}
                </a>
                <h1 class="text-h2 m-0" style="font-weight: 700;">{{ $project->title }}</h1>
                <p class="text-secondary mt-1">{{ $project->sub_category }}</p>
            </div>
        </div>
        <span class="badge badge-{{ strtolower($project->status) == 'active' ? 'active' : 'pending' }}" style="padding: 0.5rem 1rem; font-size: 1rem;">
            {{ ucfirst($project->status) }}
        </span>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <div class="nav-tabs">
        <div class="nav-tab active" onclick="switchTab('overview')">{{ app()->getLocale() == 'ar' ? 'نظرة عامة' : 'Overview' }}</div>
        <div class="nav-tab" onclick="switchTab('metrics')">{{ app()->getLocale() == 'ar' ? 'معدلات النمو' : 'Growth Metrics' }}</div>
        <div class="nav-tab" onclick="switchTab('consultants')">{{ app()->getLocale() == 'ar' ? 'الاستشاريين' : 'Consultants' }}</div>
        <div class="nav-tab" onclick="switchTab('files')">{{ app()->getLocale() == 'ar' ? 'الملفات والتقارير' : 'Files & Reports' }}</div>
        <div class="nav-tab" onclick="switchTab('exits')">{{ app()->getLocale() == 'ar' ? 'طلبات التخارج' : 'Exit Requests' }}</div>
    </div>

    <!-- Overview Tab -->
    <div id="overview" class="tab-content active">
        <div class="glass-card mb-6">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'الوصف (Profile)' : 'Description' }}</h3>
            <p style="line-height: 1.8; color: var(--text-secondary);">{{ $project->description }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="stat-box">
                <div class="stat-value">${{ number_format($project->budget ?? 0) }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'الميزانية المستهدفة' : 'Target Budget' }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($project->capital ?? 0) }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'رأس المال' : 'Capital' }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">${{ number_format($project->funding_ask ?? 0) }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'التمويل المطلوب' : 'Funding Ask' }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $project->investors_count ?? 0 }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'المستثمرين' : 'Investors' }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $project->shareholders_count ?? 0 }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'المساهمين' : 'Shareholders' }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ number_format($project->total_shares ?? 0) }}</div>
                <div class="text-secondary font-medium">{{ app()->getLocale() == 'ar' ? 'عدد الأسهم' : 'Total Shares' }}</div>
            </div>
        </div>

        <div class="glass-card mb-6" style="background: rgba(196,164,119,0.05); border: 1px solid rgba(196,164,119,0.2);">
            <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'فريق إدارة المشروع' : 'Project Management Team' }}</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div class="d-flex items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--action-primary); color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; font-size: 1.2rem; flex-shrink: 0;">
                        @if($project->projectManager && $project->projectManager->avatar)
                            <img src="{{ Storage::url($project->projectManager->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($project->projectManager)
                            {{ mb_strtoupper(mb_substr($project->projectManager->name, 0, 2)) }}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </div>
                    <div>
                        <strong class="text-secondary d-block mb-1" style="font-size: 0.85rem;">{{ app()->getLocale() == 'ar' ? 'مدير المشروع' : 'Project Manager' }}</strong>
                        <div class="text-h6" style="font-weight: 600; margin: 0;">{{ $project->projectManager->name ?? '--' }}</div>
                    </div>
                </div>

                <div class="d-flex items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--action-primary); color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; font-size: 1.2rem; flex-shrink: 0;">
                        @if($project->accountManager && $project->accountManager->avatar)
                            <img src="{{ Storage::url($project->accountManager->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($project->accountManager)
                            {{ mb_strtoupper(mb_substr($project->accountManager->name, 0, 2)) }}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </div>
                    <div>
                        <strong class="text-secondary d-block mb-1" style="font-size: 0.85rem;">{{ app()->getLocale() == 'ar' ? 'مدير الحسابات' : 'Account Manager' }}</strong>
                        <div class="text-h6" style="font-weight: 600; margin: 0;">{{ $project->accountManager->name ?? '--' }}</div>
                    </div>
                </div>

                <div class="d-flex items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--action-primary); color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; font-size: 1.2rem; flex-shrink: 0;">
                        @if($project->financialManager && $project->financialManager->avatar)
                            <img src="{{ Storage::url($project->financialManager->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($project->financialManager)
                            {{ mb_strtoupper(mb_substr($project->financialManager->name, 0, 2)) }}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </div>
                    <div>
                        <strong class="text-secondary d-block mb-1" style="font-size: 0.85rem;">{{ app()->getLocale() == 'ar' ? 'مدير مالي (استشاري)' : 'Financial Manager' }}</strong>
                        <div class="text-h6" style="font-weight: 600; margin: 0;">{{ $project->financialManager->name ?? '--' }}</div>
                    </div>
                </div>

                <div class="d-flex items-center gap-3">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--action-primary); color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: bold; font-size: 1.2rem; flex-shrink: 0;">
                        @if($project->executiveManager && $project->executiveManager->avatar)
                            <img src="{{ Storage::url($project->executiveManager->avatar) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($project->executiveManager)
                            {{ mb_strtoupper(mb_substr($project->executiveManager->name, 0, 2)) }}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        @endif
                    </div>
                    <div>
                        <strong class="text-secondary d-block mb-1" style="font-size: 0.85rem;">{{ app()->getLocale() == 'ar' ? 'مدير تنفيذي (استشاري)' : 'Executive Manager' }}</strong>
                        <div class="text-h6" style="font-weight: 600; margin: 0;">{{ $project->executiveManager->name ?? '--' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metrics Tab -->
    <div id="metrics" class="tab-content">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'مؤشرات معدل النمو' : 'Growth Metrics' }}</h3>
            <button class="btn btn-primary" onclick="openModal('addMetricModal')">{{ app()->getLocale() == 'ar' ? 'إضافة مؤشر' : 'Add Metric' }}</button>
        </div>
        
        @if($project->metrics->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            @foreach($project->metrics as $metric)
            <div class="glass-card text-center" style="position:relative; padding-bottom: 3rem;">
                <div class="text-h1" style="color:var(--action-primary); font-weight:800; margin-bottom:0.5rem;">
                    {{ $metric->prefix }}{{ $metric->value }}{{ $metric->suffix }}
                </div>
                <div class="text-secondary font-medium">{{ $metric->label }}</div>
                <div style="position:absolute; bottom: 1rem; left:0; right:0; display:flex; justify-content:center; gap:0.5rem;">
                    <button class="btn btn-secondary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;" onclick="showEditMetricModal({{ $metric->id }}, `{{ addslashes($metric->label) }}`, `{{ addslashes($metric->value) }}`, `{{ addslashes($metric->prefix) }}`, `{{ addslashes($metric->suffix) }}`)">{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}</button>
                    <form action="{{ route('admin.projects.metrics.destroy', $metric->id) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="padding: 0.2rem 0.5rem; font-size: 0.8rem; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-12">
            {{ app()->getLocale() == 'ar' ? 'لا توجد مؤشرات نمو مضافة حتى الآن.' : 'No growth metrics added yet.' }}
        </div>
        @endif
    </div>

    <!-- Consultants Tab -->
    <div id="consultants" class="tab-content">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'الاستشاريين' : 'Consultants' }}</h3>
            <button class="btn btn-primary" onclick="openModal('addConsultantModal')">{{ app()->getLocale() == 'ar' ? 'إضافة استشاري' : 'Add Consultant' }}</button>
        </div>
        
        @if($project->consultants->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($project->consultants as $consultant)
            <div class="glass-card d-flex gap-4 items-center">
                <div style="width:50px; height:50px; border-radius:50%; background:var(--action-primary); color:white; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:700;">
                    {{ strtoupper(substr($consultant->name, 0, 2)) }}
                </div>
                <div style="flex:1">
                    <h4 class="m-0" style="font-weight:600;">{{ $consultant->name }}</h4>
                    <div class="text-caption text-secondary">{{ $consultant->role }}</div>
                    @if($consultant->description)
                    <p class="text-caption mt-1" style="color:var(--text-tertiary);">{{ $consultant->description }}</p>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-secondary" style="padding: 0.3rem; border-radius: 50%;" onclick="showEditConsultantModal({{ $consultant->id }}, `{{ addslashes($consultant->name) }}`, `{{ addslashes($consultant->role) }}`, `{{ addslashes($consultant->description) }}`)" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form action="{{ route('admin.projects.consultants.destroy', $consultant->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="padding: 0.3rem; border-radius: 50%; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-12">
            {{ app()->getLocale() == 'ar' ? 'لا يوجد استشاريين مضافين حتى الآن.' : 'No consultants added yet.' }}
        </div>
        @endif
    </div>

    <!-- Files Tab -->
    <div id="files" class="tab-content">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'التقارير الخاصة بالمشروع' : 'Project Reports' }}</h3>
            <button class="btn btn-primary" onclick="openModal('addReportModal')">{{ app()->getLocale() == 'ar' ? 'إضافة تقرير' : 'Add Report' }}</button>
        </div>
        @if($project->reports->count() > 0)
        <div style="display:grid; gap:1rem; margin-bottom: 3rem;">
            @foreach($project->reports as $report)
            <div class="glass-card d-flex justify-between items-center" style="padding: 1rem 1.5rem;">
                <div class="d-flex gap-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <div>
                        <div style="font-weight:600;">{{ $report->title }}</div>
                        <div class="text-caption text-secondary">{{ $report->created_at->format('M d, Y') }} - {{ $report->period }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ Storage::url($report->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.4rem 1rem;">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</a>
                    <form action="{{ route('admin.projects.reports.destroy', $report->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 1rem; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-8 mb-8">{{ app()->getLocale() == 'ar' ? 'لا توجد تقارير.' : 'No reports.' }}</div>
        @endif

        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'المستندات والملفات' : 'Documents & Files' }}</h3>
            <button class="btn btn-primary" onclick="openModal('addDocumentModal')">{{ app()->getLocale() == 'ar' ? 'رفع مستند' : 'Add Document' }}</button>
        </div>
        @if($project->documents->count() > 0)
        <div style="display:grid; gap:1rem;">
            @foreach($project->documents as $doc)
            <div class="glass-card d-flex justify-between items-center" style="padding: 1rem 1.5rem;">
                <div class="d-flex gap-3 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                    <div>
                        <div style="font-weight:600;">{{ $doc->title }}</div>
                        <div class="text-caption text-secondary">{{ $doc->created_at->format('M d, Y') }} - {{ $doc->type }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.4rem 1rem;">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</a>
                    <form action="{{ route('admin.projects.documents.destroy', $doc->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" style="padding: 0.4rem 1rem; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-8">{{ app()->getLocale() == 'ar' ? 'لا توجد مستندات.' : 'No documents.' }}</div>
        @endif
    </div>

    <!-- Exits Tab -->
    <div id="exits" class="tab-content">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'طلبات التخارج' : 'Exit Requests' }}</h3>
            <button class="btn btn-primary" onclick="openModal('addExitModal')">{{ app()->getLocale() == 'ar' ? 'إضافة طلب تخارج' : 'Add Exit Request' }}</button>
        </div>
        @if($project->exitRequests->count() > 0)
        <div style="display:grid; gap:1rem;">
            @foreach($project->exitRequests as $exit)
            <div class="glass-card d-flex justify-between items-center">
                <div style="flex:1">
                    <h4 class="m-0" style="font-weight:600;">{{ $exit->user->name ?? 'User' }}</h4>
                    <p class="text-secondary mt-1 m-0">{{ $exit->type }} - ${{ number_format($exit->amount ?? 0) }}</p>
                    <div class="text-caption text-tertiary mt-2">{{ $exit->created_at->format('M d, Y') }}</div>
                </div>
                <div class="d-flex gap-3 items-center">
                    <span class="badge badge-{{ strtolower($exit->status) == 'approved' || strtolower($exit->status) == 'completed' ? 'active' : (strtolower($exit->status) == 'rejected' ? 'rejected' : 'pending') }}">{{ $exit->status }}</span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-secondary" style="padding: 0.3rem; border-radius: 50%;" onclick="showEditExitModal({{ $exit->id }}, `{{ $exit->user_id }}`, `{{ $exit->request_date }}`, `{{ $exit->type }}`, `{{ $exit->amount }}`, `{{ $exit->status }}`)" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </button>
                        <form action="{{ route('admin.projects.exits.destroy', $exit->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" style="padding: 0.3rem; border-radius: 50%; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-12">
            {{ app()->getLocale() == 'ar' ? 'لا توجد طلبات تخارج لهذا المشروع.' : 'No exit requests for this project.' }}
        </div>
        @endif
    </div>

</div>

<!-- Add Metric Modal -->
<div id="addMetricModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إضافة مؤشر نمو' : 'Add Metric' }}</h3>
        <form action="{{ route('admin.projects.metrics.store', $project->id) }}" method="POST">
            @csrf
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'اسم المؤشر (مثال: الأرباح)' : 'Label' }}</label>
                    <input type="text" name="label" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'القيمة (مثال: 50)' : 'Value' }}</label>
                    <input type="text" name="value" class="form-input w-full" required>
                </div>
                <div class="d-flex gap-4">
                    <div style="flex:1">
                        <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'بادئة (مثال: + أو $)' : 'Prefix' }}</label>
                        <input type="text" name="prefix" class="form-input w-full">
                    </div>
                    <div style="flex:1">
                        <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'لاحقة (مثال: % أو M)' : 'Suffix' }}</label>
                        <input type="text" name="suffix" class="form-input w-full">
                    </div>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addMetricModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'إضافة' : 'Add' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Consultant Modal -->
<div id="addConsultantModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إضافة استشاري للمشروع' : 'Add Consultant' }}</h3>
        <form action="{{ route('admin.projects.consultants.store', $project->id) }}" method="POST">
            @csrf
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'اسم الاستشاري' : 'Name' }}</label>
                    <input type="text" name="name" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الدور / المسمى (مثال: خبير مالي)' : 'Role' }}</label>
                    <input type="text" name="role" class="form-input w-full">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'وصف أو ملاحظات' : 'Description' }}</label>
                    <textarea name="description" class="form-input w-full" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addConsultantModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'إضافة' : 'Add' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Exit Request Modal -->
<div id="addExitModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 90vh; overflow-y: auto;">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إضافة طلب تخارج جديد' : 'Add Exit Request' }}</h3>
        <form action="{{ route('admin.projects.exits.store', $project->id) }}" method="POST">
            @csrf
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'المستخدم' : 'User' }}</label>
                    <select name="user_id" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="">{{ app()->getLocale() == 'ar' ? 'اختر مستخدم' : 'Select User' }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'تاريخ الطلب' : 'Request Date' }}</label>
                    <input type="date" name="request_date" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'نوع التخارج' : 'Exit Type' }}</label>
                    <select name="type" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="Partial Exit">{{ app()->getLocale() == 'ar' ? 'تخارج جزئي' : 'Partial Exit' }}</option>
                        <option value="Full Exit">{{ app()->getLocale() == 'ar' ? 'تخارج كلي' : 'Full Exit' }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'المبلغ' : 'Amount' }} ($)</label>
                    <input type="number" step="0.01" name="amount" class="form-input w-full" style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                    <select name="status" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="Under Review">{{ app()->getLocale() == 'ar' ? 'قيد المراجعة' : 'Under Review' }}</option>
                        <option value="Approved">{{ app()->getLocale() == 'ar' ? 'مقبول' : 'Approved' }}</option>
                        <option value="Rejected">{{ app()->getLocale() == 'ar' ? 'مرفوض' : 'Rejected' }}</option>
                        <option value="Completed">{{ app()->getLocale() == 'ar' ? 'مكتمل' : 'Completed' }}</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addExitModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'إضافة' : 'Add' }}</button>
            </div>
        </form>
    </div>
</div>
<!-- Edit Metric Modal -->
<div id="editMetricModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل مؤشر النمو' : 'Edit Metric' }}</h3>
        <form id="editMetricForm" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'اسم المؤشر' : 'Label' }}</label>
                    <input type="text" name="label" id="editMetricLabel" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'القيمة' : 'Value' }}</label>
                    <input type="text" name="value" id="editMetricValue" class="form-input w-full" required>
                </div>
                <div class="d-flex gap-4">
                    <div style="flex:1">
                        <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'بادئة' : 'Prefix' }}</label>
                        <input type="text" name="prefix" id="editMetricPrefix" class="form-input w-full">
                    </div>
                    <div style="flex:1">
                        <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'لاحقة' : 'Suffix' }}</label>
                        <input type="text" name="suffix" id="editMetricSuffix" class="form-input w-full">
                    </div>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editMetricModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Consultant Modal -->
<div id="editConsultantModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل بيانات الاستشاري' : 'Edit Consultant' }}</h3>
        <form id="editConsultantForm" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'اسم الاستشاري' : 'Name' }}</label>
                    <input type="text" name="name" id="editConsultantName" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الدور / المسمى' : 'Role' }}</label>
                    <input type="text" name="role" id="editConsultantRole" class="form-input w-full">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'وصف أو ملاحظات' : 'Description' }}</label>
                    <textarea name="description" id="editConsultantDesc" class="form-input w-full" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editConsultantModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Exit Request Modal -->
<div id="editExitModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 90vh; overflow-y: auto;">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل طلب التخارج' : 'Edit Exit Request' }}</h3>
        <form id="editExitForm" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'المستخدم' : 'User' }}</label>
                    <select name="user_id" id="editExitUserId" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="">{{ app()->getLocale() == 'ar' ? 'اختر مستخدم' : 'Select User' }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'تاريخ الطلب' : 'Request Date' }}</label>
                    <input type="date" name="request_date" id="editExitDate" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'نوع التخارج' : 'Exit Type' }}</label>
                    <select name="type" id="editExitType" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="Partial Exit">{{ app()->getLocale() == 'ar' ? 'تخارج جزئي' : 'Partial Exit' }}</option>
                        <option value="Full Exit">{{ app()->getLocale() == 'ar' ? 'تخارج كلي' : 'Full Exit' }}</option>
                    </select>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'المبلغ' : 'Amount' }} ($)</label>
                    <input type="number" step="0.01" name="amount" id="editExitAmount" class="form-input w-full" style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                    <select name="status" id="editExitStatus" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                        <option value="Under Review">{{ app()->getLocale() == 'ar' ? 'قيد المراجعة' : 'Under Review' }}</option>
                        <option value="Approved">{{ app()->getLocale() == 'ar' ? 'مقبول' : 'Approved' }}</option>
                        <option value="Rejected">{{ app()->getLocale() == 'ar' ? 'مرفوض' : 'Rejected' }}</option>
                        <option value="Completed">{{ app()->getLocale() == 'ar' ? 'مكتمل' : 'Completed' }}</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editExitModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Report Modal -->
<div id="addReportModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إضافة تقرير للمشروع' : 'Add Report' }}</h3>
        <form action="{{ route('admin.projects.reports.store', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'عنوان التقرير' : 'Title' }}</label>
                    <input type="text" name="title" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الفترة (مثال: الربع الأول 2026)' : 'Period' }}</label>
                    <input type="text" name="period" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الملف' : 'File' }} (PDF/DOCX/JPG/PNG)</label>
                    <input type="file" name="file" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'تخصيص للمستخدم (اختياري)' : 'Assign to User (Optional)' }}</label>
                    <select name="user_id" class="form-input w-full" style="padding: 0.8rem 1rem;">
                        <option value="">{{ app()->getLocale() == 'ar' ? 'متاح للجميع' : 'Available to all' }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addReportModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'رفع التقرير' : 'Upload' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Document Modal -->
<div id="addDocumentModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <h3 class="text-h3 mb-6" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'رفع مستند' : 'Upload Document' }}</h3>
        <form action="{{ route('admin.projects.documents.store', $project->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex flex-col gap-4">
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Title' }}</label>
                    <input type="text" name="title" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'النوع (عقد، دراسة جدوى، إلخ)' : 'Type' }}</label>
                    <input type="text" name="type" class="form-input w-full" required>
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'الملف' : 'File' }} (PDF/DOCX/JPG/PNG)</label>
                    <input type="file" name="file" class="form-input w-full" required style="padding: 0.8rem 1rem;">
                </div>
                <div>
                    <label class="text-caption font-semibold">{{ app()->getLocale() == 'ar' ? 'تخصيص للمستخدم (اختياري)' : 'Assign to User (Optional)' }}</label>
                    <select name="user_id" class="form-input w-full" style="padding: 0.8rem 1rem;">
                        <option value="">{{ app()->getLocale() == 'ar' ? 'متاح للجميع' : 'Available to all' }}</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-8 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addDocumentModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'رفع المستند' : 'Upload' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    
    event.currentTarget.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

function openModal(id) {
    const modal = document.getElementById(id);
    modal.style.display = 'flex';
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

function showEditMetricModal(id, label, value, prefix, suffix) {
    document.getElementById('editMetricForm').action = '/admin/projects/metrics/' + id;
    document.getElementById('editMetricLabel').value = label;
    document.getElementById('editMetricValue').value = value;
    document.getElementById('editMetricPrefix').value = prefix;
    document.getElementById('editMetricSuffix').value = suffix;
    openModal('editMetricModal');
}

function showEditConsultantModal(id, name, role, desc) {
    document.getElementById('editConsultantForm').action = '/admin/projects/consultants/' + id;
    document.getElementById('editConsultantName').value = name;
    document.getElementById('editConsultantRole').value = role;
    document.getElementById('editConsultantDesc').value = desc;
    openModal('editConsultantModal');
}

function showEditExitModal(id, userId, date, type, amount, status) {
    document.getElementById('editExitForm').action = '/admin/projects/exits/' + id;
    document.getElementById('editExitUserId').value = userId;
    document.getElementById('editExitDate').value = date;
    document.getElementById('editExitType').value = type;
    document.getElementById('editExitAmount').value = amount;
    document.getElementById('editExitStatus').value = status;
    openModal('editExitModal');
}
</script>
@endsection
