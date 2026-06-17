@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'تفاصيل الملف' : 'File Details')

@section('content')
<style>
    .glass-panel {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
    }
    
    .file-layout {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
    
    .file-preview-container {
        flex: 2;
        min-width: 300px;
        background: var(--bg-secondary);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-lg);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 600px;
    }
    
    .file-preview-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .file-metadata-container {
        flex: 1;
        min-width: 300px;
    }
    
    .meta-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-subtle);
    }
    
    .meta-row:last-child {
        border-bottom: none;
    }
    
    .meta-label {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .meta-value {
        color: var(--text-primary);
        font-weight: 500;
        text-align: end;
    }
    
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-primary { background: rgba(196, 164, 119, 0.15); color: var(--action-primary); }
    .badge-success { background: rgba(16, 185, 129, 0.15); color: #059669; }

    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
</style>

<div class="fade-in">
    <!-- Header / Breadcrumbs -->
    <div class="mb-4 d-flex items-center gap-3">
        <a href="{{ route('admin.files') }}" class="btn btn-secondary" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; color: var(--text-primary);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transform: {{ app()->getLocale() == 'ar' ? 'rotate(180deg)' : 'none' }}"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <div>
            <h1 class="text-h2 m-0" style="font-weight: 700; display:flex; align-items:center; gap: 0.5rem;">
                {{ $file->title }}
            </h1>
            <p class="text-secondary mt-1 m-0">{{ $file->is_document ? (app()->getLocale() == 'ar' ? 'مستند' : 'Document') : (app()->getLocale() == 'ar' ? 'تقرير' : 'Report') }}</p>
        </div>
    </div>

    <div class="file-layout">
        <!-- Preview Section -->
        <div class="file-preview-container">
            @if($file->file_path)
                <iframe src="{{ Storage::url($file->file_path) }}" title="File Preview"></iframe>
            @else
                <div class="text-center text-tertiary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-3 mx-auto"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    <p>{{ app()->getLocale() == 'ar' ? 'لا يوجد ملف فعلي لعرضه' : 'No actual file to preview' }}</p>
                </div>
            @endif
        </div>

        <!-- Metadata Section -->
        <div class="file-metadata-container">
            <div class="glass-panel">
                <h3 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'تفاصيل الملف' : 'File Details' }}</h3>
                
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</span>
                    <span class="meta-value badge badge-primary">{{ $file->type }}</span>
                </div>
                
                @if(!$file->is_document)
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'الفترة' : 'Period' }}</span>
                    <span class="meta-value">{{ $file->period }}</span>
                </div>
                @endif
                
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</span>
                    <span class="meta-value badge badge-success">{{ $file->status }}</span>
                </div>
                
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'المشروع المرتبط' : 'Assigned Project' }}</span>
                    <span class="meta-value">
                        @if($file->project)
                            <span style="display:flex; align-items:center; gap:4px; justify-content:flex-end;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                                {{ $file->project->title }}
                            </span>
                        @else
                            <span class="text-tertiary">-</span>
                        @endif
                    </span>
                </div>
                
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'المستخدم المرتبط' : 'Assigned User' }}</span>
                    <span class="meta-value">
                        @if($file->user)
                            <span style="display:flex; align-items:center; gap:4px; justify-content:flex-end;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                {{ $file->user->name }}
                            </span>
                        @else
                            <span class="text-tertiary">-</span>
                        @endif
                    </span>
                </div>
                
                <div class="meta-row">
                    <span class="meta-label">{{ app()->getLocale() == 'ar' ? 'تاريخ الرفع' : 'Uploaded At' }}</span>
                    <span class="meta-value">{{ $file->created_at->format('F d, Y h:i A') }}</span>
                </div>
                
                <div class="mt-8 d-flex flex-col gap-3">
                    @if($file->file_path)
                        <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-primary btn-icon" style="width: 100%; border-radius: var(--radius-full);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            {{ app()->getLocale() == 'ar' ? 'تحميل الملف' : 'Download File' }}
                        </a>
                    @endif
                    <button class="btn btn-secondary btn-icon" style="width: 100%; border-radius: var(--radius-full); color: var(--color-error); border-color: rgba(239, 68, 68, 0.3);" onclick="alert('{{ app()->getLocale() == 'ar' ? 'سيتم حذف الملف من هنا' : 'Delete file functionality' }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        {{ app()->getLocale() == 'ar' ? 'حذف الملف' : 'Delete File' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
