@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الملفات' : 'File Management')

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
    .stc-table td { padding: 1rem; border-bottom: 1px solid var(--border-default); color: var(--text-primary); font-size: 0.95rem; }
    .stc-table tr:last-child td { border-bottom: none; }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-primary { background: rgba(196, 164, 119, 0.1); color: var(--action-primary); }
    .badge-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .tabs { display: flex; gap: 1rem; border-bottom: 1px solid var(--border-default); margin-bottom: 1.5rem; }
    .tab { padding: 0.75rem 1.5rem; cursor: pointer; color: var(--text-secondary); font-weight: 500; border-bottom: 2px solid transparent; transition: all 0.3s; }
    .tab.active { color: var(--action-primary); border-bottom-color: var(--action-primary); }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة الملفات والتقارير' : 'Files & Reports' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'رفع وتعيين المستندات للمستخدمين أو المشاريع.' : 'Upload and assign documents to users or projects.' }}</p>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-secondary" onclick="document.getElementById('uploadReportModal').style.display='flex'">
                {{ app()->getLocale() == 'ar' ? '+ رفع تقرير' : '+ Upload Report' }}
            </button>
            <button class="btn btn-primary" onclick="document.getElementById('uploadDocModal').style.display='flex'">
                {{ app()->getLocale() == 'ar' ? '+ رفع مستند' : '+ Upload Document' }}
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
    @endif

    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .search-container {
            position: relative;
            max-width: 300px;
            width: 100%;
        }
        .search-container input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border-radius: var(--radius-full);
            border: 1px solid var(--border-default);
            background: var(--bg-surface);
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        html[dir="rtl"] .search-container input {
            padding: 0.6rem 2.5rem 0.6rem 1rem;
        }
        .search-container input:focus {
            outline: none;
            border-color: var(--action-primary);
            box-shadow: 0 0 0 3px rgba(196, 164, 119, 0.1);
        }
        .search-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }
        html[dir="rtl"] .search-icon {
            left: auto;
            right: 1rem;
        }
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            color: var(--border-default);
            margin-bottom: 1rem;
        }
    </style>

    <div class="dashboard-grid">
        <div class="glass-card">
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المستندات' : 'Total Documents' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ $metrics['total_documents'] }}</div>
            <div class="mt-2 text-caption text-success" style="color: #10b981;">{{ $metrics['active_documents'] }} {{ app()->getLocale() == 'ar' ? 'نشط' : 'Active' }}</div>
        </div>
        <div class="glass-card">
            <h3 class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي التقارير' : 'Total Reports' }}</h3>
            <div class="text-h2 mt-2" style="font-weight: 700;">{{ $metrics['total_reports'] }}</div>
            <div class="mt-2 text-caption text-success" style="color: #10b981;">{{ $metrics['published_reports'] }} {{ app()->getLocale() == 'ar' ? 'منشور' : 'Published' }}</div>
        </div>
    </div>

    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div class="d-flex justify-between items-center" style="padding: 0 1.5rem; border-bottom: 1px solid var(--border-default); gap: 1rem; flex-wrap: wrap; background: rgba(0,0,0,0.02);">
            <div class="tabs" style="border: none; margin: 0; padding-top: 0.5rem;">
                <div class="tab active" onclick="switchTab('documents')">{{ app()->getLocale() == 'ar' ? 'المستندات' : 'Documents' }}</div>
                <div class="tab" onclick="switchTab('reports')">{{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</div>
            </div>
            <div class="search-container" style="margin-bottom: 0.5rem;">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="fileSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث في الملفات...' : 'Search files...' }}" onkeyup="filterFiles()">
            </div>
        </div>

        <!-- Documents Tab -->
        <div id="documents-tab" class="tab-pane active" style="padding: 1.5rem; overflow-x: auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Title' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'التعيين' : 'Assigned To' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'تاريخ الرفع' : 'Date' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr class="data-row">
                        <td style="font-weight: 500;">
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" style="color:var(--text-primary); text-decoration:none;">{{ $doc->title }}</a>
                        </td>
                        <td><span class="badge badge-primary">{{ $doc->type }}</span></td>
                        <td class="text-secondary">
                            @if($doc->user)
                                👤 {{ $doc->user->name }}
                            @elseif($doc->project)
                                🏢 {{ $doc->project->title }}
                            @else
                                -
                            @endif
                        </td>
                        <td><span class="badge badge-success">{{ $doc->status }}</span></td>
                        <td class="text-secondary">{{ $doc->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="5" class="text-center py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد مستندات' : 'No documents found' }}</td></tr>
                    @endforelse
                    <tr class="empty-row" style="display:none"><td colspan="5" class="text-center py-4">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة للبحث' : 'No matching results found' }}</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Reports Tab -->
        <div id="reports-tab" class="tab-pane" style="padding: 1.5rem; overflow-x: auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'عنوان التقرير' : 'Title' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الفترة' : 'Period' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'التعيين' : 'Assigned To' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'تاريخ الرفع' : 'Date' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $rep)
                    <tr class="data-row">
                        <td style="font-weight: 500;">
                            <a href="{{ Storage::url($rep->file_path) }}" target="_blank" style="color:var(--text-primary); text-decoration:none;">{{ $rep->title }}</a>
                        </td>
                        <td class="text-secondary">{{ $rep->period }}</td>
                        <td><span class="badge badge-primary">{{ $rep->type }}</span></td>
                        <td class="text-secondary">
                            @if($rep->user)
                                👤 {{ $rep->user->name }}
                            @elseif($rep->project)
                                🏢 {{ $rep->project->title }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-secondary">{{ $rep->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="5" class="text-center py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد تقارير' : 'No reports found' }}</td></tr>
                    @endforelse
                    <tr class="empty-row" style="display:none"><td colspan="5" class="text-center py-4">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة للبحث' : 'No matching results found' }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="uploadDocModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
        <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'رفع مستند جديد' : 'Upload New Document' }}</h3>
        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Title' }}</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'نوع المستند' : 'Type' }}</label>
                <select name="type" class="form-input" required>
                    <option value="Legal">Legal / قانوني</option>
                    <option value="Financial">Financial / مالي</option>
                    <option value="NDA">NDA / اتفاقية سرية</option>
                    <option value="Other">Other / أخرى</option>
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'تعيين لمستخدم معين (اختياري)' : 'Assign to User (Optional)' }}</label>
                <select name="user_id" class="form-input">
                    <option value="">-- {{ app()->getLocale() == 'ar' ? 'لا يوجد' : 'None' }} --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'أو تعيين لمشروع معين (اختياري)' : 'Or Assign to Project (Optional)' }}</label>
                <select name="project_id" class="form-input">
                    <option value="">-- {{ app()->getLocale() == 'ar' ? 'لا يوجد' : 'None' }} --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'حالة المستند' : 'Status' }}</label>
                <select name="status" class="form-input" required>
                    <option value="Active">Active / نشط</option>
                    <option value="Pending">Pending / معلق</option>
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الملف' : 'File' }}</label>
                <input type="file" name="file" class="form-input" style="padding-top:0.6rem;" required>
            </div>
            <div class="d-flex gap-3 justify-end mt-4">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('uploadDocModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'رفع' : 'Upload' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Report Modal -->
<div id="uploadReportModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
        <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'رفع تقرير جديد' : 'Upload New Report' }}</h3>
        <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'عنوان التقرير' : 'Title' }}</label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الفترة' : 'Period (e.g. Q1 2026)' }}</label>
                <input type="text" name="period" class="form-input" required>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</label>
                <select name="type" class="form-input" required>
                    <option value="Quarterly">Quarterly / ربع سنوي</option>
                    <option value="Monthly">Monthly / شهري</option>
                    <option value="Due Diligence">Due Diligence / فحص نافي للجهالة</option>
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'تعيين لمستخدم معين (اختياري)' : 'Assign to User (Optional)' }}</label>
                <select name="user_id" class="form-input">
                    <option value="">-- {{ app()->getLocale() == 'ar' ? 'لا يوجد' : 'None' }} --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'أو تعيين لمشروع معين (اختياري)' : 'Or Assign to Project (Optional)' }}</label>
                <select name="project_id" class="form-input">
                    <option value="">-- {{ app()->getLocale() == 'ar' ? 'لا يوجد' : 'None' }} --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                <select name="status" class="form-input" required>
                    <option value="Published">Published / منشور</option>
                    <option value="Draft">Draft / مسودة</option>
                </select>
            </div>
            <div>
                <label class="form-label">{{ app()->getLocale() == 'ar' ? 'الملف' : 'File' }}</label>
                <input type="file" name="file" class="form-input" style="padding-top:0.6rem;" required>
            </div>
            <div class="d-flex gap-3 justify-end mt-4">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('uploadReportModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'رفع' : 'Upload' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabName) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    
    event.currentTarget.classList.add('active');
    document.getElementById(tabName + '-tab').classList.add('active');
    filterFiles(); // Re-apply filter on tab switch
}

function filterFiles() {
    const query = document.getElementById('fileSearch').value.toLowerCase();
    
    const activeTab = document.querySelector('.tab-pane.active').id;
    const rows = document.querySelectorAll(`#${activeTab} tbody tr.data-row`);
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

    const emptyRow = document.querySelector(`#${activeTab} tbody tr.empty-row`);
    if (emptyRow) {
        emptyRow.style.display = visibleCount === 0 ? '' : 'none';
    }
}
</script>
@endsection
