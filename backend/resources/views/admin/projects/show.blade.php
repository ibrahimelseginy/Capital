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
        <div>
            <a href="{{ route('admin.projects') }}" class="text-secondary d-flex items-center gap-2 mb-2" style="text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                {{ app()->getLocale() == 'ar' ? 'العودة للمشاريع' : 'Back to Projects' }}
            </a>
            <h1 class="text-h2 m-0" style="font-weight: 700;">{{ $project->title }}</h1>
            <p class="text-secondary mt-1">{{ $project->sub_category }}</p>
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
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
            <div class="glass-card text-center" style="position:relative;">
                <div class="text-h1" style="color:var(--action-primary); font-weight:800; margin-bottom:0.5rem;">
                    {{ $metric->prefix }}{{ $metric->value }}{{ $metric->suffix }}
                </div>
                <div class="text-secondary font-medium">{{ $metric->label }}</div>
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
                <div>
                    <h4 class="m-0" style="font-weight:600;">{{ $consultant->name }}</h4>
                    <div class="text-caption text-secondary">{{ $consultant->role }}</div>
                    @if($consultant->description)
                    <p class="text-caption mt-1" style="color:var(--text-tertiary);">{{ $consultant->description }}</p>
                    @endif
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
        <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'التقارير الخاصة بالمشروع' : 'Project Reports' }}</h3>
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
                <a href="{{ Storage::url($report->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.4rem 1rem;">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</a>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-8 mb-8">{{ app()->getLocale() == 'ar' ? 'لا توجد تقارير.' : 'No reports.' }}</div>
        @endif

        <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'المستندات والملفات' : 'Documents & Files' }}</h3>
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
                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-secondary" style="padding: 0.4rem 1rem;">{{ app()->getLocale() == 'ar' ? 'تحميل' : 'Download' }}</a>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card text-center text-secondary py-8">{{ app()->getLocale() == 'ar' ? 'لا توجد مستندات.' : 'No documents.' }}</div>
        @endif
    </div>

    <!-- Exits Tab -->
    <div id="exits" class="tab-content">
        <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'طلبات التخارج' : 'Exit Requests' }}</h3>
        @if($project->exitRequests->count() > 0)
        <div style="display:grid; gap:1rem;">
            @foreach($project->exitRequests as $exit)
            <div class="glass-card d-flex justify-between items-center">
                <div>
                    <h4 class="m-0" style="font-weight:600;">{{ $exit->user->name ?? 'User' }}</h4>
                    <p class="text-secondary mt-1 m-0">{{ $exit->reason }}</p>
                    <div class="text-caption text-tertiary mt-2">{{ $exit->created_at->format('M d, Y') }}</div>
                </div>
                <span class="badge badge-{{ strtolower($exit->status) == 'approved' ? 'active' : (strtolower($exit->status) == 'rejected' ? 'rejected' : 'pending') }}">{{ $exit->status }}</span>
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
</script>
@endsection
