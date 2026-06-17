@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Content Management')

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
    .stc-table td { padding: 1rem; border-bottom: 1px solid var(--border-default); color: var(--text-primary); font-size: 0.95rem; vertical-align: top; }
    .stc-table tr:hover td { background: rgba(196, 164, 119, 0.03); }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; background:var(--bg-secondary); color:var(--text-secondary); }
    .action-icon-btn { background: transparent; border: none; padding: 0.5rem; border-radius: var(--radius-full); color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
    .action-icon-btn:hover { background: var(--bg-secondary); color: var(--action-primary); }
    .action-icon-btn.reject:hover { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .form-control { width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-default); background: var(--bg-surface); color: var(--text-primary); transition: all 0.3s; margin-top: 0.5rem; }
    .form-control:focus { outline: none; border-color: var(--action-primary); }
    .form-group { margin-bottom: 1.5rem; }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة المحتوى' : 'Content Management' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'إدارة النصوص، الصور والفيديوهات في الموقع' : 'Manage text, images, and videos on the website' }}</p>
        </div>
        <button class="btn btn-primary" onclick="showAddModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-inline-end:0.5rem"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            {{ app()->getLocale() == 'ar' ? 'إضافة محتوى جديد' : 'Add New Content' }}
        </button>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        <ul style="margin:0; padding-inline-start:1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="glass-card" style="padding:0; overflow:hidden">
        <div style="overflow-x:auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'القسم' : 'Section' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'المفتاح' : 'Key' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'النوع' : 'Type' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'القيمة' : 'Value / Preview' }}</th>
                        <th style="text-align:center">{{ app()->getLocale() == 'ar' ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contents as $content)
                    <tr>
                        <td style="font-weight:600">{{ $content->section ?? '-' }}</td>
                        <td class="text-secondary">{{ $content->key }}</td>
                        <td><span class="badge">{{ $content->type }}</span></td>
                        <td>
                            @if($content->type == 'image' && $content->file_path)
                                <img src="{{ asset('storage/' . $content->file_path) }}" alt="Preview" style="max-height:80px; border-radius:var(--radius-sm);">
                            @elseif($content->type == 'video' && $content->file_path)
                                <video src="{{ asset('storage/' . $content->file_path) }}" style="max-height:80px; border-radius:var(--radius-sm);" controls></video>
                            @else
                                <div style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $content->value }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 justify-center">
                                <button type="button" class="action-icon-btn" onclick="showEditModal({{ $content->id }}, '{{ $content->section }}', '{{ $content->key }}', '{{ $content->type }}', `{{ addslashes($content->value) }}`)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <form action="{{ route('admin.content.destroy', $content->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="action-icon-btn reject">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            {{ app()->getLocale() == 'ar' ? 'لا يوجد محتوى مضاف بعد.' : 'No content added yet.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="contentModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:600px; background:var(--bg-primary);">
        <div class="d-flex justify-between items-center mb-4">
            <h3 class="text-h4 m-0" id="modalTitle">{{ app()->getLocale() == 'ar' ? 'إضافة محتوى' : 'Add Content' }}</h3>
            <button onclick="closeModal()" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="contentForm" method="POST" action="{{ route('admin.content.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div>
            
            <div class="form-group">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'القسم (اختياري)' : 'Section (Optional)' }}</label>
                <input type="text" name="section" id="f_section" class="form-control" placeholder="e.g. home_hero">
            </div>
            
            <div class="form-group">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'المفتاح (Key)' : 'Key' }}</label>
                <input type="text" name="key" id="f_key" class="form-control" required placeholder="e.g. main_title">
                <small class="text-secondary" style="font-size:0.8rem; margin-top:4px; display:block;">{{ app()->getLocale() == 'ar' ? 'يجب أن يكون فريداً (Unique) باللغة الإنجليزية بدون مسافات.' : 'Must be unique English word without spaces.' }}</small>
            </div>
            
            <div class="form-group">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'نوع المحتوى' : 'Content Type' }}</label>
                <select name="type" id="f_type" class="form-control" required onchange="toggleInputs()">
                    <option value="text">Text (نص قصير)</option>
                    <option value="textarea">Textarea (نص طويل)</option>
                    <option value="image">Image (صورة)</option>
                    <option value="video">Video (فيديو)</option>
                </select>
            </div>
            
            <div class="form-group" id="val_text_container">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'النص' : 'Text Value' }}</label>
                <input type="text" name="value" id="f_value_text" class="form-control">
            </div>
            
            <div class="form-group" id="val_textarea_container" style="display:none;">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'النص الطويل' : 'Textarea Value' }}</label>
                <textarea name="value" id="f_value_textarea" class="form-control" rows="5"></textarea>
            </div>
            
            <div class="form-group" id="val_file_container" style="display:none;">
                <label class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'الملف' : 'File' }}</label>
                <input type="file" name="file" id="f_file" class="form-control">
            </div>

            <div class="mt-6 d-flex justify-end gap-2">
                <button type="button" class="btn btn-secondary" onclick="closeModal()" style="background:var(--bg-surface); color:var(--text-primary); border:1px solid var(--border-default); padding:0.5rem 1rem; border-radius:var(--radius-md); cursor:pointer;">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                <button type="submit" class="btn btn-primary" style="background:var(--action-primary); color:#fff; border:none; padding:0.5rem 1rem; border-radius:var(--radius-md); cursor:pointer;">{{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save' }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleInputs() {
    const type = document.getElementById('f_type').value;
    document.getElementById('val_text_container').style.display = 'none';
    document.getElementById('val_textarea_container').style.display = 'none';
    document.getElementById('val_file_container').style.display = 'none';
    
    // Disable inputs to prevent submitting wrong fields
    document.getElementById('f_value_text').disabled = true;
    document.getElementById('f_value_textarea').disabled = true;
    document.getElementById('f_file').disabled = true;
    
    if (type === 'text') {
        document.getElementById('val_text_container').style.display = 'block';
        document.getElementById('f_value_text').disabled = false;
    } else if (type === 'textarea') {
        document.getElementById('val_textarea_container').style.display = 'block';
        document.getElementById('f_value_textarea').disabled = false;
    } else {
        document.getElementById('val_file_container').style.display = 'block';
        document.getElementById('f_file').disabled = false;
    }
}

function showAddModal() {
    document.getElementById('contentForm').action = "{{ route('admin.content.store') }}";
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('modalTitle').innerText = "{{ app()->getLocale() == 'ar' ? 'إضافة محتوى' : 'Add Content' }}";
    
    document.getElementById('f_section').value = '';
    document.getElementById('f_key').value = '';
    document.getElementById('f_key').readOnly = false;
    document.getElementById('f_type').value = 'text';
    document.getElementById('f_value_text').value = '';
    document.getElementById('f_value_textarea').value = '';
    
    toggleInputs();
    document.getElementById('contentModal').style.display = 'flex';
}

function showEditModal(id, section, key, type, value) {
    document.getElementById('contentForm').action = "/admin/content/" + id;
    document.getElementById('methodField').innerHTML = ''; // removed @method('PUT') as we are using POST to update and sending files. Wait, POST to an update route is fine, see routes/web.php
    document.getElementById('modalTitle').innerText = "{{ app()->getLocale() == 'ar' ? 'تعديل المحتوى' : 'Edit Content' }}";
    
    document.getElementById('f_section').value = section;
    document.getElementById('f_key').value = key;
    document.getElementById('f_key').readOnly = true; // Key cannot be changed after creation easily
    document.getElementById('f_type').value = type;
    
    if (type === 'text') {
        document.getElementById('f_value_text').value = value;
    } else if (type === 'textarea') {
        document.getElementById('f_value_textarea').value = value;
    }
    
    toggleInputs();
    document.getElementById('contentModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('contentModal').style.display = 'none';
}

// Initial toggle
toggleInputs();
</script>
@endsection
