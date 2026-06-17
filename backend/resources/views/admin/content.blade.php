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
        background: rgba(196, 164, 119, 0.05);
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

    /* YouTube Inputs */
    .yt-input-group {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .yt-input-group input {
        flex: 1;
    }

    /* Media Grid */
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .media-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
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

    /* Horizontal Slider for Multiple Media */
    .media-slider {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        height: 180px;
        background: var(--bg-secondary);
        scrollbar-width: none; /* Firefox */
    }
    .media-slider::-webkit-scrollbar {
        display: none; /* Chrome */
    }

    .media-slide {
        flex: 0 0 100%;
        height: 100%;
        scroll-snap-align: center;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .media-slide img, .media-slide video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .media-slide iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .slider-indicators {
        position: absolute;
        bottom: 8px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 4px;
        z-index: 10;
        background: rgba(0,0,0,0.3);
        padding: 4px 8px;
        border-radius: 12px;
    }

    .slider-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
    }
    .slider-dot.active {
        background: white;
    }

    .media-details {
        padding: 1.5rem;
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
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 4rem 2rem; text-align: center; background: var(--bg-surface);
        border: 1px dashed var(--border-strong); border-radius: var(--radius-xl);
        margin-top: 2rem;
    }

    .stagger-item { opacity: 0; animation: slideUpFade 0.5s ease forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Content Management' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'ارفع عدة صور، فيديوهات، أو روابط يوتيوب معاً.' : 'Upload multiple images, videos, or YouTube links together.' }}</p>
        </div>
    </div>

    <!-- Error/Success Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 text-center" style="background: var(--color-success-bg); color: var(--color-success); border-radius: var(--radius-md); border: 1px solid rgba(16, 185, 129, 0.2);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-inline-end: 8px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span style="font-weight: 600;">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 text-center" style="background: var(--color-error-bg); color: var(--color-error); border-radius: var(--radius-md); border: 1px solid rgba(239, 68, 68, 0.2);">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Upload Panel -->
    <div class="glass-panel mb-8">
        <h2 class="text-h4 mb-5" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إنشاء محتوى جديد' : 'Create New Content' }}</h2>
        <form action="{{ route('admin.content.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="d-flex gap-4 flex-wrap mb-5">
                <div style="flex: 1; min-width: 250px;">
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'عنوان المحتوى' : 'Content Title' }}</label>
                    <input type="text" name="title" class="form-input" required placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: معرض صور حفل الافتتاح' : 'e.g. Opening Ceremony Gallery' }}" style="width: 100%; padding: 0.8rem 1rem;">
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'القسم (اختياري)' : 'Section (Optional)' }}</label>
                    <input type="text" name="section" class="form-input" placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: gallery, hero' : 'e.g. gallery, hero' }}" style="width: 100%; padding: 0.8rem 1rem;">
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'نوع المحتوى' : 'Content Type' }}</label>
                    <select name="type" id="contentTypeSelect" class="form-input" onchange="toggleInputFields()" style="width: 100%; padding: 0.8rem 1rem;">
                        <option value="image">{{ app()->getLocale() == 'ar' ? 'صور متعددة' : 'Multiple Images' }}</option>
                        <option value="video">{{ app()->getLocale() == 'ar' ? 'فيديوهات متعددة' : 'Multiple Videos' }}</option>
                        <option value="youtube">{{ app()->getLocale() == 'ar' ? 'روابط يوتيوب' : 'YouTube Links' }}</option>
                        <option value="text">{{ app()->getLocale() == 'ar' ? 'نص' : 'Text' }}</option>
                    </select>
                </div>
            </div>

            <!-- Media Upload (Images/Videos) -->
            <div id="fileUploadWrapper">
                <div class="upload-zone" id="dropZone">
                    <input type="file" name="files[]" class="hidden-input" id="fileInput" multiple onchange="updateFileName(this)">
                    <div class="upload-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    </div>
                    <h3 class="text-h4" style="margin-bottom: 0.5rem; font-weight: 700;" id="fileNameDisplay">
                        {{ app()->getLocale() == 'ar' ? 'اسحب وأفلت عدة ملفات هنا' : 'Drag & drop multiple files here' }}
                    </h3>
                    <p class="text-body text-tertiary">
                        {{ app()->getLocale() == 'ar' ? 'يمكنك تحديد أكثر من ملف في نفس الوقت' : 'You can select multiple files at once' }}
                    </p>
                </div>
            </div>

            <!-- YouTube URLs -->
            <div id="youtubeWrapper" style="display: none; background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-default);">
                <label class="text-caption" style="font-weight: 600; margin-bottom: 1rem; display: block;">{{ app()->getLocale() == 'ar' ? 'روابط فيديوهات يوتيوب' : 'YouTube Video URLs' }}</label>
                <div id="ytInputsContainer">
                    <div class="yt-input-group">
                        <input type="url" name="youtube_urls[]" class="form-input" placeholder="https://www.youtube.com/watch?v=..." style="padding: 0.8rem 1rem;">
                        <button type="button" class="btn btn-primary" onclick="addYtInput()" style="border-radius: var(--radius-md); padding: 0 1.5rem;">+</button>
                    </div>
                </div>
            </div>

            <!-- Text Content -->
            <div id="textContentWrapper" style="display: none;">
                <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'النص' : 'Text Content' }}</label>
                <textarea name="text_content" class="form-input" rows="5" placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل النص هنا...' : 'Enter text here...' }}" style="width: 100%; padding: 1rem;"></textarea>
            </div>

            <div class="mt-6 text-end">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem; border-radius: var(--radius-full);">
                    {{ app()->getLocale() == 'ar' ? 'حفظ المحتوى' : 'Save Content' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Media Library Grid -->
    <div class="d-flex justify-between items-center mb-6 mt-8">
        <h2 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'مكتبة المحتوى' : 'Content Library' }}</h2>
    </div>

    @if($contents->count() > 0)
        <div class="media-grid">
            @foreach($contents as $index => $content)
                <div class="media-card stagger-item" style="animation-delay: {{ $index * 0.1 }}s">
                    
                    @php
                        // Ensure file_path is an array
                        $mediaItems = is_array($content->file_path) ? $content->file_path : [$content->file_path];
                    @endphp

                    @if($content->type === 'text')
                        <div class="media-preview" style="background: var(--color-primary-lighter); color: var(--action-primary); padding: 1rem; text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        </div>
                    @else
                        <!-- Media Slider Container -->
                        <div style="position: relative;">
                            <div class="media-slider" id="slider-{{ $content->id }}" onscroll="updateSliderDots({{ $content->id }}, {{ count($mediaItems) }})">
                                @foreach($mediaItems as $idx => $item)
                                    <div class="media-slide">
                                        @if($content->type === 'image')
                                            <img src="{{ Storage::url($item) }}" alt="{{ $content->title }}">
                                        @elseif($content->type === 'video')
                                            <video src="{{ Storage::url($item) }}" muted loop onmouseover="this.play()" onmouseout="this.pause()"></video>
                                        @elseif($content->type === 'youtube')
                                            @php
                                                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $item, $matches);
                                                $ytId = $matches[1] ?? '';
                                            @endphp
                                            @if($ytId)
                                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" allowfullscreen></iframe>
                                            @else
                                                <div style="padding:1rem; text-align:center;">Invalid YouTube URL</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            @if(count($mediaItems) > 1)
                            <!-- Slider Dots -->
                            <div class="slider-indicators" id="dots-{{ $content->id }}">
                                @foreach($mediaItems as $idx => $item)
                                    <div class="slider-dot {{ $idx === 0 ? 'active' : '' }}"></div>
                                @endforeach
                            </div>
                            <!-- Type & Count Badge -->
                            <div style="position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.6); color: white; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 700; letter-spacing: 0.05em; display:flex; align-items:center; gap:4px; backdrop-filter: blur(4px);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                {{ count($mediaItems) }}
                            </div>
                            @endif
                        </div>
                    @endif

                    <div class="media-details">
                        <div class="d-flex justify-between items-start mb-2">
                            <h4 class="text-h5" style="margin:0; font-weight: 700; line-height: 1.3;">{{ $content->title }}</h4>
                        </div>
                        <p class="text-caption text-secondary" style="margin:0;">
                            <span style="display:inline-block; padding: 2px 8px; background: var(--bg-secondary); border-radius: 4px; font-weight: 600;">{{ strtoupper($content->type) }}</span> 
                            @if($content->section) &bull; Section: {{ $content->section }} @endif
                        </p>
                        
                        <div class="media-actions mt-auto" style="padding-top: 1.5rem;">
                            <form action="{{ route('admin.content.destroy', $content->id) }}" method="POST" style="width: 100%;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="width: 100%; color: var(--color-error); border-color: rgba(239, 68, 68, 0.3); border-radius: var(--radius-full);" onclick="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure you want to delete this entry and all its files?' }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end: 4px;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    {{ app()->getLocale() == 'ar' ? 'حذف الإدخال' : 'Delete Entry' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="beautiful-empty-state">
            <div style="width:100px; height:100px; background:var(--bg-secondary); border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--text-tertiary); margin-bottom: 1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
            </div>
            <h3 class="text-h2" style="font-weight: 700; margin-bottom: 0.5rem;">{{ app()->getLocale() == 'ar' ? 'لا يوجد محتوى' : 'Library is Empty' }}</h3>
            <p class="text-body text-secondary" style="max-width: 400px; margin: 0 auto;">
                {{ app()->getLocale() == 'ar' ? 'استخدم لوحة الرفع أعلاه لإضافة مجموعات من الصور والفيديوهات إلى مكتبتك.' : 'Use the upload panel above to add galleries of images and videos to your library.' }}
            </p>
        </div>
    @endif
</div>

<script>
    function toggleInputFields() {
        const type = document.getElementById('contentTypeSelect').value;
        const fileUploadWrapper = document.getElementById('fileUploadWrapper');
        const textContentWrapper = document.getElementById('textContentWrapper');
        const youtubeWrapper = document.getElementById('youtubeWrapper');

        fileUploadWrapper.style.display = 'none';
        textContentWrapper.style.display = 'none';
        youtubeWrapper.style.display = 'none';

        if (type === 'text') {
            textContentWrapper.style.display = 'block';
        } else if (type === 'youtube') {
            youtubeWrapper.style.display = 'block';
        } else {
            // image or video
            fileUploadWrapper.style.display = 'block';
        }
    }

    function addYtInput() {
        const container = document.getElementById('ytInputsContainer');
        const div = document.createElement('div');
        div.className = 'yt-input-group';
        div.innerHTML = `
            <input type="url" name="youtube_urls[]" class="form-input" placeholder="https://www.youtube.com/watch?v=..." style="padding: 0.8rem 1rem;">
            <button type="button" class="btn btn-secondary" onclick="this.parentElement.remove()" style="border-radius: var(--radius-md); padding: 0 1.2rem; color: var(--color-error); border-color: var(--color-error-bg);">-</button>
        `;
        container.appendChild(div);
    }

    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        if (input.files && input.files.length > 0) {
            if(input.files.length === 1) {
                display.textContent = input.files[0].name;
            } else {
                display.textContent = "{{ app()->getLocale() == 'ar' ? 'تم تحديد ' : 'Selected ' }}" + input.files.length + "{{ app()->getLocale() == 'ar' ? ' ملفات' : ' files' }}";
            }
            display.style.color = 'var(--action-primary)';
        } else {
            display.textContent = "{{ app()->getLocale() == 'ar' ? 'اسحب وأفلت عدة ملفات هنا' : 'Drag & drop multiple files here' }}";
            display.style.color = 'inherit';
        }
    }

    // Scroll Sync for Slider Dots
    function updateSliderDots(id, count) {
        if(count <= 1) return;
        const slider = document.getElementById('slider-' + id);
        const dots = document.getElementById('dots-' + id).children;
        
        // Calculate which slide is currently most visible
        const scrollLeft = slider.scrollLeft;
        const slideWidth = slider.clientWidth;
        const index = Math.round(scrollLeft / slideWidth);
        
        for(let i=0; i<dots.length; i++) {
            if(i === index) {
                dots[i].classList.add('active');
            } else {
                dots[i].classList.remove('active');
            }
        }
    }

    // Drag and Drop visual feedback
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

    ['dragenter', 'dragover'].forEach(eventName => { dropZone.addEventListener(eventName, highlight, false); });
    ['dragleave', 'drop'].forEach(eventName => { dropZone.addEventListener(eventName, unhighlight, false); });

    function highlight(e) { dropZone.classList.add('dragover'); }
    function unhighlight(e) { dropZone.classList.remove('dragover'); }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileName(fileInput);
    }
</script>
@endsection
