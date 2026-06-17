@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الملفات' : 'File Management')

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
    .badge-primary { background: rgba(196, 164, 119, 0.15); color: var(--action-primary); }
    .badge-success { background: rgba(16, 185, 129, 0.15); color: #059669; }
    
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

    /* Modern Tabs */
    .tabs-container {
        background: var(--bg-surface);
        padding: 0.5rem;
        border-radius: var(--radius-lg);
        display: inline-flex;
        border: 1px solid var(--border-default);
        margin-bottom: 2rem;
    }
    
    .tab {
        padding: 0.75rem 2rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.3s;
    }
    
    .tab.active {
        background: var(--action-primary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .tab-pane { display: none; }
    .tab-pane.active { display: block; animation: fadeIn 0.4s ease; }
    
    /* Files Grid */
    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .file-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--action-primary);
    }

    .file-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-lg);
        background: rgba(196, 164, 119, 0.1);
        color: var(--action-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .file-card:hover .file-icon {
        transform: scale(1.1);
    }

    .file-details { margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; }
    
    .assignee-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: var(--bg-secondary);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        margin-top: 0.5rem;
    }

    /* Modal Styling */
    .upload-zone {
        border: 2px dashed var(--border-strong);
        border-radius: var(--radius-lg);
        padding: 2rem;
        text-align: center;
        background: var(--bg-secondary);
        transition: all 0.3s;
        cursor: pointer;
        position: relative;
    }
    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--action-primary);
        background: rgba(196, 164, 119, 0.05);
    }
    .upload-zone input[type="file"] {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Beautiful Empty State */
    .beautiful-empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 5rem 2rem; text-align: center; background: var(--bg-surface);
        border: 1px dashed var(--border-strong); border-radius: var(--radius-xl);
        grid-column: 1 / -1;
    }

    .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-h2" style="font-weight: 700; letter-spacing: -0.5px;">{{ app()->getLocale() == 'ar' ? 'إدارة الملفات والتقارير' : 'Files & Reports' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'ارفع وشارك المستندات الهامة مع المستثمرين والمشاريع بسهولة.' : 'Easily upload and share important documents with investors and projects.' }}</p>
        </div>
        <div class="d-flex gap-3">
            <button class="btn btn-secondary" style="border-radius: var(--radius-full); padding: 0.75rem 1.5rem;" onclick="openModal('uploadReportModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                {{ app()->getLocale() == 'ar' ? 'رفع تقرير' : 'Upload Report' }}
            </button>
            <button class="btn btn-primary" style="border-radius: var(--radius-full); padding: 0.75rem 1.5rem;" onclick="openModal('uploadDocModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                {{ app()->getLocale() == 'ar' ? 'رفع مستند' : 'Upload Document' }}
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <div class="d-flex justify-between items-center mb-6 flex-wrap gap-4">
        <div class="tabs-container">
            <div class="tab active" onclick="switchTab('documents')">{{ app()->getLocale() == 'ar' ? 'المستندات' : 'Documents' }}</div>
            <div class="tab" onclick="switchTab('reports')">{{ app()->getLocale() == 'ar' ? 'التقارير' : 'Reports' }}</div>
        </div>
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="fileSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث في الملفات...' : 'Search files...' }}" onkeyup="filterFiles()">
        </div>
    </div>

    <!-- Documents Tab -->
    <div id="documents-tab" class="tab-pane active">
        <div class="files-grid">
            @forelse($documents as $index => $doc)
            <a href="{{ route('admin.documents.show', $doc->id) }}" class="file-card stagger-item" style="text-decoration:none; color:inherit; animation-delay: {{ 0.05 * ($index + 1) }}s;" data-title="{{ strtolower($doc->title) }}">
                <div class="d-flex justify-between items-start">
                    <div class="file-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                    <span class="badge badge-success">{{ $doc->status }}</span>
                </div>
                
                <h3 class="text-h4 mt-2 mb-1" style="font-weight: 700;">{{ $doc->title }}</h3>
                <div class="text-caption text-tertiary">{{ $doc->type }} Document</div>
                
                @if($doc->user || $doc->project)
                <div class="assignee-tag">
                    @if($doc->user)
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        {{ $doc->user->name }}
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        {{ $doc->project->title }}
                    @endif
                </div>
                @endif
                
                <div class="file-details">
                    <span class="text-caption text-tertiary">{{ $doc->created_at->format('M d, Y') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--action-primary)" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </div>
            </a>
            @empty
            <div class="beautiful-empty-state">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
                <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا يوجد مستندات' : 'No documents yet' }}</h3>
            </div>
            @endforelse
            <div class="beautiful-empty-state empty-search" style="display:none;">
                <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج' : 'No results found' }}</h3>
            </div>
        </div>
    </div>

    <!-- Reports Tab -->
    <div id="reports-tab" class="tab-pane">
        <div class="files-grid">
            @forelse($reports as $index => $rep)
            <a href="{{ route('admin.reports.show', $rep->id) }}" class="file-card stagger-item" style="text-decoration:none; color:inherit; animation-delay: {{ 0.05 * ($index + 1) }}s;" data-title="{{ strtolower($rep->title) }}">
                <div class="d-flex justify-between items-start">
                    <div class="file-icon" style="background: rgba(59, 130, 246, 0.1); color: #2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                    </div>
                    <span class="badge badge-primary">{{ $rep->period }}</span>
                </div>
                
                <h3 class="text-h4 mt-2 mb-1" style="font-weight: 700;">{{ $rep->title }}</h3>
                <div class="text-caption text-tertiary">{{ $rep->type }} Report</div>
                
                @if($rep->user || $rep->project)
                <div class="assignee-tag">
                    @if($rep->user)
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        {{ $rep->user->name }}
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        {{ $rep->project->title }}
                    @endif
                </div>
                @endif
                
                <div class="file-details">
                    <span class="text-caption text-tertiary">{{ $rep->created_at->format('M d, Y') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                </div>
            </a>
            @empty
            <div class="beautiful-empty-state">
                <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                </div>
                <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا يوجد تقارير' : 'No reports yet' }}</h3>
            </div>
            @endforelse
            <div class="beautiful-empty-state empty-search" style="display:none;">
                <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج' : 'No results found' }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="uploadDocModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:550px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'رفع مستند جديد' : 'Upload Document' }}</h3>
            <button type="button" onclick="closeModal('uploadDocModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            
            <div class="upload-zone" id="docDropZone">
                <input type="file" name="file" required onchange="document.getElementById('docFileName').innerText = this.files[0].name; document.getElementById('docFileName').style.color='var(--action-primary)';">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--text-tertiary); margin-bottom:1rem;"></svg>
                <div class="text-h5" style="font-weight:600; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'اسحب وأفلت الملف هنا أو انقر للتصفح' : 'Drag & Drop file here or click to browse' }}</div>
                <div class="text-caption text-secondary" id="docFileName">{{ app()->getLocale() == 'ar' ? 'يدعم PDF, DOCX, وصيغ الصور' : 'Supports PDF, DOCX, and Image formats' }}</div>
            </div>

            <div class="d-flex gap-4">
                <div style="flex:1">
                    <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Title' }}</label>
                    <input type="text" name="title" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                </div>
                <div style="flex:1">
                    <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</label>
                    <select name="type" class="form-input" required style="width:100%; padding:0.8rem 1rem; border-radius:var(--radius-md);">
                        <option value="Legal">Legal</option>
                        <option value="Financial">Financial</option>
                        <option value="NDA">NDA</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'تعيين (اختياري)' : 'Assignment (Optional)' }}</label>
                <div class="d-flex gap-4">
                    <select name="user_id" class="form-input" style="flex:1; padding:0.8rem 1rem; border-radius:var(--radius-md);">
                        <option value="">-- {{ app()->getLocale() == 'ar' ? 'اختر مستخدم' : 'Select User' }} --</option>
                        @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                    </select>
                    <select name="project_id" class="form-input" style="flex:1; padding:0.8rem 1rem; border-radius:var(--radius-md);">
                        <option value="">-- {{ app()->getLocale() == 'ar' ? 'اختر مشروع' : 'Select Project' }} --</option>
                        @foreach($projects as $project) <option value="{{ $project->id }}">{{ $project->title }}</option> @endforeach
                    </select>
                </div>
            </div>
            
            <input type="hidden" name="status" value="Active">

            <div class="mt-4 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" style="border-radius:var(--radius-full); padding:0.75rem 1.5rem;" onclick="closeModal('uploadDocModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary" style="border-radius:var(--radius-full); padding:0.75rem 2rem;">{{ app()->getLocale() == 'ar' ? 'رفع وحفظ' : 'Upload & Save' }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Upload Report Modal -->
<div id="uploadReportModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width:100%; max-width:550px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div class="d-flex justify-between items-center mb-6">
            <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'رفع تقرير جديد' : 'Upload Report' }}</h3>
            <button type="button" onclick="closeModal('uploadReportModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            
            <div class="upload-zone" id="repDropZone">
                <input type="file" name="file" required onchange="document.getElementById('repFileName').innerText = this.files[0].name; document.getElementById('repFileName').style.color='var(--action-primary)';">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color:var(--text-tertiary); margin-bottom:1rem;"></svg>
                <div class="text-h5" style="font-weight:600; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'اسحب وأفلت الملف هنا أو انقر للتصفح' : 'Drag & Drop file here or click to browse' }}</div>
                <div class="text-caption text-secondary" id="repFileName">{{ app()->getLocale() == 'ar' ? 'يدعم PDF, DOCX, وصيغ الصور' : 'Supports PDF, DOCX, and Image formats' }}</div>
            </div>

            <div class="d-flex gap-4">
                <div style="flex:1">
                    <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'عنوان التقرير' : 'Title' }}</label>
                    <input type="text" name="title" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                </div>
                <div style="flex:1">
                    <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'الفترة' : 'Period' }}</label>
                    <input type="text" name="period" class="form-input" placeholder="e.g. Q1 2026" required style="width:100%; padding:0.8rem 1rem;">
                </div>
            </div>

            <div>
                <label class="text-caption" style="font-weight:600; display:block; margin-bottom:0.5rem;">{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</label>
                <select name="type" class="form-input" required style="width:100%; padding:0.8rem 1rem; border-radius:var(--radius-md);">
                    <option value="Quarterly">Quarterly / ربع سنوي</option>
                    <option value="Monthly">Monthly / شهري</option>
                    <option value="Due Diligence">Due Diligence / فحص</option>
                </select>
            </div>
            
            <input type="hidden" name="status" value="Published">

            <div class="mt-4 d-flex justify-end gap-3">
                <button type="button" class="btn btn-secondary" style="border-radius:var(--radius-full); padding:0.75rem 1.5rem;" onclick="closeModal('uploadReportModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary" style="border-radius:var(--radius-full); padding:0.75rem 2rem;">{{ app()->getLocale() == 'ar' ? 'رفع وحفظ' : 'Upload & Save' }}</button>
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
    filterFiles(); 
}

function filterFiles() {
    const query = document.getElementById('fileSearch').value.toLowerCase();
    const activeTab = document.querySelector('.tab-pane.active').id;
    const cards = document.querySelectorAll(`#${activeTab} .file-card`);
    let visibleCount = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        if (title.includes(query)) {
            card.style.display = 'flex';
            visibleCount++;
            card.style.animation = 'none';
            card.offsetHeight; /* trigger reflow */
            card.style.animation = null; 
        } else {
            card.style.display = 'none';
        }
    });

    const emptySearch = document.querySelector(`#${activeTab} .empty-search`);
    if (emptySearch) {
        emptySearch.style.display = visibleCount === 0 && query !== '' ? 'flex' : 'none';
    }
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
