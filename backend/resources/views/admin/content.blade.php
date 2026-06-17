@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Content Management')

@section('content')
<style>
    .glass-panel {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        transition: box-shadow 0.3s ease;
    }
    
    .glass-panel:hover {
        box-shadow: var(--shadow-md);
    }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed var(--border-strong);
        border-radius: var(--radius-lg);
        padding: 3rem 2rem;
        text-align: center;
        background: var(--bg-secondary);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .upload-zone:hover, .upload-zone.dragover {
        border-color: var(--action-primary);
        background: var(--color-primary-lighter);
        transform: scale(1.01);
    }

    .upload-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--bg-surface);
        color: var(--action-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        box-shadow: var(--shadow-sm);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .upload-zone:hover .upload-icon {
        transform: translateY(-5px) scale(1.05);
        box-shadow: var(--shadow-md);
    }

    .hidden-input {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Media Grid */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .media-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-md);
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .media-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--border-strong);
    }

    .media-card:focus-within {
        outline: 2px solid var(--action-primary);
        outline-offset: 2px;
    }

    .media-preview {
        height: 160px;
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .media-preview img, .media-preview video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .media-card:hover .media-preview img {
        transform: scale(1.05);
    }

    .media-details {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .media-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    /* Beautiful Empty State */
    .beautiful-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        margin-top: 2rem;
        animation: slideUpFade 0.5s ease forwards;
    }

    .empty-illustration {
        width: 120px;
        height: 120px;
        background: var(--bg-secondary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--text-tertiary);
    }

    /* Animations */
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stagger-item {
        opacity: 0;
        animation: slideUpFade 0.5s ease forwards;
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Content Management' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'قم برفع وإدارة ملفات الوسائط الخاصة بالموقع.' : 'Upload and manage your website media files.' }}</p>
        </div>
    </div>

    <!-- Error/Success Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 text-center" style="background: var(--color-success-bg); color: var(--color-success); border-radius: var(--radius-md);">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 text-center" style="background: var(--color-error-bg); color: var(--color-error); border-radius: var(--radius-md);">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Upload Panel -->
    <div class="glass-panel mb-8">
        <h2 class="text-h4 mb-4">{{ app()->getLocale() == 'ar' ? 'رفع محتوى جديد' : 'Upload New Content' }}</h2>
        <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="d-flex gap-4 flex-wrap mb-4">
                <div style="flex: 1; min-width: 250px;">
                    <label class="text-label">{{ app()->getLocale() == 'ar' ? 'عنوان المحتوى' : 'Content Title' }}</label>
                    <input type="text" name="title" class="form-input" required placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل عنواناً مميزاً' : 'Enter a unique title' }}">
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label class="text-label">{{ app()->getLocale() == 'ar' ? 'القسم (اختياري)' : 'Section (Optional)' }}</label>
                    <input type="text" name="section" class="form-input" placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: hero, about, features' : 'e.g. hero, about, features' }}">
                </div>
            </div>
            
            <div class="mb-4">
                <label class="text-label">{{ app()->getLocale() == 'ar' ? 'نوع المحتوى' : 'Content Type' }}</label>
                <select name="type" id="contentTypeSelect" class="form-input" onchange="toggleInputFields()">
                    <option value="image">{{ app()->getLocale() == 'ar' ? 'صورة' : 'Image' }}</option>
                    <option value="video">{{ app()->getLocale() == 'ar' ? 'فيديو' : 'Video' }}</option>
                    <option value="text">{{ app()->getLocale() == 'ar' ? 'نص' : 'Text' }}</option>
                </select>
            </div>

            <div id="fileUploadWrapper">
                <div class="upload-zone" id="dropZone">
                    <input type="file" name="file" class="hidden-input" id="fileInput" onchange="updateFileName(this)">
                    <div class="upload-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    </div>
                    <h3 class="text-h5" style="margin-bottom: 0.5rem;" id="fileNameDisplay">
                        {{ app()->getLocale() == 'ar' ? 'اسحب وأفلت الملف هنا' : 'Drag & drop file here' }}
                    </h3>
                    <p class="text-caption text-tertiary">
                        {{ app()->getLocale() == 'ar' ? 'أو انقر لاختيار ملف (JPG, PNG, MP4)' : 'Or click to browse (JPG, PNG, MP4)' }}
                    </p>
                </div>
            </div>

            <div id="textContentWrapper" style="display: none;">
                <label class="text-label">{{ app()->getLocale() == 'ar' ? 'النص' : 'Text Content' }}</label>
                <textarea name="text_content" class="form-input" rows="5" placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل النص هنا...' : 'Enter text here...' }}"></textarea>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                    {{ app()->getLocale() == 'ar' ? 'رفع وحفظ' : 'Upload & Save' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Media Gallery -->
    <h2 class="text-h4 mb-4 mt-8">{{ app()->getLocale() == 'ar' ? 'مكتبة الوسائط' : 'Media Library' }}</h2>

    @if($contents->count() > 0)
        <div class="media-grid">
            @foreach($contents as $index => $content)
                <div class="media-card stagger-item" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="media-preview">
                        @if($content->type === 'image' && $content->file_path)
                            <img src="{{ Storage::url($content->file_path) }}" alt="{{ $content->title }}">
                        @elseif($content->type === 'video' && $content->file_path)
                            <video src="{{ Storage::url($content->file_path) }}" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                            <div style="position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.6); color: white; padding: 2px 8px; border-radius: 12px; font-size: 10px;">VIDEO</div>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-tertiary)" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        @endif
                    </div>
                    <div class="media-details">
                        <h4 class="text-h6" style="margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $content->title }}">{{ $content->title }}</h4>
                        <p class="text-caption text-secondary mt-1">Section: {{ $content->section ?? 'N/A' }}</p>
                        <div class="media-actions mt-auto">
                            <form action="{{ route('admin.content.destroy', $content->id) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="width: 100%; color: var(--color-error); border-color: var(--color-error-bg);" onclick="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete this?' }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end: 4px;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    {{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="beautiful-empty-state">
            <div class="empty-illustration">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </div>
            <h3 class="text-h3" style="margin-bottom: 0.5rem;">{{ app()->getLocale() == 'ar' ? 'مكتبة الوسائط فارغة' : 'Media Library is Empty' }}</h3>
            <p class="text-body text-secondary" style="max-width: 400px; margin: 0 auto;">
                {{ app()->getLocale() == 'ar' ? 'قم برفع الصور، والفيديوهات، والنصوص الخاصة بموقعك من الأعلى للبدء في تشكيل هوية موقعك البصرية.' : 'Upload your images, videos, and texts from above to start building your visual identity.' }}
            </p>
        </div>
    @endif
</div>

<script>
    function toggleInputFields() {
        const type = document.getElementById('contentTypeSelect').value;
        const fileUploadWrapper = document.getElementById('fileUploadWrapper');
        const textContentWrapper = document.getElementById('textContentWrapper');

        if (type === 'text') {
            fileUploadWrapper.style.display = 'none';
            textContentWrapper.style.display = 'block';
        } else {
            fileUploadWrapper.style.display = 'block';
            textContentWrapper.style.display = 'none';
        }
    }

    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        if (input.files && input.files.length > 0) {
            display.textContent = input.files[0].name;
            display.style.color = 'var(--action-primary)';
        } else {
            display.textContent = "{{ app()->getLocale() == 'ar' ? 'اسحب وأفلت الملف هنا' : 'Drag & drop file here' }}";
            display.style.color = 'inherit';
        }
    }

    // Drag and Drop visual feedback
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('dragover');
    }

    function unhighlight(e) {
        dropZone.classList.remove('dragover');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName(fileInput);
    }
</script>
@endsection
