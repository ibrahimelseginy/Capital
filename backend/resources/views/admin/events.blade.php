@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الفعاليات' : 'Manage Events')

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
    
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
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

    .beautiful-empty-state {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 5rem 2rem; text-align: center; background: var(--bg-surface);
        border: 1px dashed var(--border-strong); border-radius: var(--radius-xl);
        margin-top: 2rem; grid-column: 1 / -1;
    }

    .stagger-item { opacity: 0; animation: slideUpFade 0.6s ease forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .visual-invitation-card {
        width: 360px;
        min-height: 480px;
        background: linear-gradient(135deg, #161616 0%, #0c0c0c 100%);
        border: 2px solid #c4a477;
        border-radius: 20px;
        padding: 24px;
        color: #fff;
        position: relative;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-sizing: border-box;
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8 flex-wrap gap-4">
        <div>
            <h1 class="text-h2" style="font-weight: 700; letter-spacing: -0.5px;">{{ app()->getLocale() == 'ar' ? 'إدارة الفعاليات' : 'Manage Events' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'قم بإنشاء وتعديل وإدارة الفعاليات الخاصة بالمنصة.' : 'Create, edit, and manage platform events.' }}</p>
        </div>
        <div class="d-flex gap-4 items-center">
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="eventSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن فعالية...' : 'Search events...' }}" onkeyup="filterEvents()">
            </div>
            <button class="btn btn-primary" style="padding: 0.8rem 1.5rem; border-radius: var(--radius-full); white-space: nowrap;" onclick="openModal('addEventModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                {{ app()->getLocale() == 'ar' ? 'إضافة فعالية' : 'Add Event' }}
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div style="background: var(--color-error-bg); color: var(--color-error); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; border: 1px solid rgba(239, 68, 68, 0.2);">
        <div style="display:flex; align-items:center; gap: 1rem; margin-bottom: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'يوجد أخطاء في الإدخال:' : 'There are input errors:' }}</span>
        </div>
        <ul style="margin: 0; padding-inline-start: 2rem; font-size: 0.9rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="projects-grid" id="eventsContainer">
        @forelse($events as $index => $event)
        <div class="project-card stagger-item" style="animation-delay: {{ 0.1 * ($index + 1) }}s;" data-title="{{ strtolower($event->title) }}">
            <div class="project-card-header">
                <div>
                    <h3 class="text-h4 m-0" style="font-weight: 700; line-height: 1.3;">{{ $event->title }}</h3>
                    <div class="text-caption text-tertiary mt-1 d-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                    </div>
                </div>
                <span class="badge badge-{{ strtolower($event->status) == 'active' ? 'active' : 'pending' }}">{{ ucfirst($event->status) }}</span>
            </div>
            
            <div class="project-card-body">
                <p class="text-body text-secondary" style="line-height: 1.6;">
                    <strong style="color:var(--text-primary);">{{ app()->getLocale() == 'ar' ? 'المتحدث:' : 'Speaker:' }}</strong> {{ $event->speaker_name ?? '--' }}<br>
                    <strong style="color:var(--text-primary);">{{ app()->getLocale() == 'ar' ? 'المكان:' : 'Location:' }}</strong> {{ $event->location }}<br>
                    <strong style="color:var(--text-primary);">{{ app()->getLocale() == 'ar' ? 'وقت الفعالية:' : 'Duration:' }}</strong> {{ $event->duration ?? $event->time ?? '--' }}
                </p>
            </div>

            <div class="project-card-footer">
                <div class="d-flex gap-2">
                    <button type="button" class="action-icon-btn edit" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}" onclick='showEditEventModal(@json($event))'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button type="button" class="action-icon-btn edit" style="color:var(--accent-gold); border-color:rgba(196,164,119,0.3);" title="{{ app()->getLocale() == 'ar' ? 'بطاقة الدعوة والـ QR' : 'Invitation Card & QR' }}" onclick='previewInvitationCard(@json($event))'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><rect x="7" y="7" width="3" height="3"/><rect x="14" y="7" width="3" height="3"/><rect x="7" y="14" width="3" height="3"/><rect x="14" y="14" width="3" height="3"/></svg>
                    </button>
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Are you sure?' }}');">
                        @csrf
                        @method('DELETE')
                        <button class="action-icon-btn reject" title="{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
                
                @if($event->speaker_profile || $event->invitation_card || $event->qr_code)
                <div class="text-caption text-tertiary">
                    {{ app()->getLocale() == 'ar' ? 'توجد مرفقات' : 'Has attachments' }}
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="beautiful-empty-state">
            <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد فعاليات حتى الآن' : 'No events yet' }}</h3>
            <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'قم بإضافة فعاليات جديدة للمنصة.' : 'Add new events to the platform.' }}</p>
        </div>
        @endforelse
    </div>

    <!-- Add Event Modal -->
    <div id="addEventModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease; overflow-y:auto;">
        <div class="glass-card" style="width:100%; max-width:650px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 90vh; overflow-y: auto;">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إضافة فعالية جديدة' : 'Add New Event' }}</h3>
                <button type="button" onclick="closeModal('addEventModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex flex-col gap-4">
                    <div style="flex:1">
                        <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'عنوان الفعالية' : 'Title' }}</label>
                        <input type="text" name="title" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</label>
                            <input type="date" name="event_date" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الوقت' : 'Time' }}</label>
                            <input type="time" name="time" class="form-input" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'توقيت الفعالية' : 'Duration' }}</label>
                            <input type="text" name="duration" class="form-input" placeholder="مثال: 3 ساعات" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:2">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</label>
                            <input type="text" name="location" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'عدد الحضور' : 'Attendees' }}</label>
                            <input type="number" name="attendees_count" class="form-input" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                            <input type="text" name="status" class="form-input" value="Active" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'نوع الدخول' : 'Access Type' }}</label>
                            <input type="text" name="access_type" class="form-input" value="Open" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>

                    <!-- Speakers list builder -->
                    <div style="border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.25rem; background: var(--bg-secondary); margin-top: 0.5rem; margin-bottom: 0.5rem;">
                        <div class="d-flex justify-between items-center mb-3">
                            <h4 class="text-body m-0" style="font-weight: 700; color: var(--text-primary);">{{ app()->getLocale() == 'ar' ? 'المتحدثون (Speakers)' : 'Speakers' }}</h4>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addSpeakerInput('addEventModal')" style="border-radius: var(--radius-md); font-size: 11px; padding: 4px 10px;">
                                + {{ app()->getLocale() == 'ar' ? 'إضافة متحدث' : 'Add Speaker' }}
                            </button>
                        </div>
                        <div id="addEventModal_speakersContainer" class="d-flex flex-col gap-3">
                            <!-- Dynamic speaker fields go here -->
                        </div>
                    </div>

                    <div style="background: rgba(196,164,119,0.1); border: 1px solid rgba(196,164,119,0.3); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 0.5rem;">
                        <h4 class="text-h5" style="margin-bottom: 1rem;">{{ app()->getLocale() == 'ar' ? 'مرفقات الفعالية' : 'Event Attachments' }}</h4>
                        <div class="d-flex gap-4 flex-wrap">
                            <div style="flex:1; min-width: 180px;">
                                <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الـ QR Code' : 'QR Code' }}</label>
                                <input type="file" name="qr_code" class="form-input" accept="image/*" style="width:100%; padding:0.5rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 d-flex justify-end gap-3">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addEventModal')" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Event Modal -->
    <div id="editEventModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease; overflow-y:auto;">
        <div class="glass-card" style="width:100%; max-width:650px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); max-height: 90vh; overflow-y: auto;">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل الفعالية' : 'Edit Event' }}</h3>
                <button type="button" onclick="closeModal('editEventModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <form id="editEventForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="d-flex flex-col gap-4">
                    <div style="flex:1">
                        <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Title' }}</label>
                        <input type="text" name="title" id="editTitle" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</label>
                            <input type="date" name="event_date" id="editDate" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الوقت' : 'Time' }}</label>
                            <input type="time" name="time" id="editTime" class="form-input" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'توقيت الفعالية' : 'Duration' }}</label>
                            <input type="text" name="duration" id="editDuration" class="form-input" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:2">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</label>
                            <input type="text" name="location" id="editLocation" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'عدد الحضور' : 'Attendees' }}</label>
                            <input type="number" name="attendees_count" id="editAttendees" class="form-input" style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>
                    <div class="d-flex gap-4">
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'الحالة' : 'Status' }}</label>
                            <input type="text" name="status" id="editStatus" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                        <div style="flex:1">
                            <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'نوع الدخول' : 'Access Type' }}</label>
                            <input type="text" name="access_type" id="editAccess" class="form-input" required style="width:100%; padding:0.8rem 1rem;">
                        </div>
                    </div>

                    <!-- Speakers list builder -->
                    <div style="border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.25rem; background: var(--bg-secondary); margin-top: 0.5rem; margin-bottom: 0.5rem;">
                        <div class="d-flex justify-between items-center mb-3">
                            <h4 class="text-body m-0" style="font-weight: 700; color: var(--text-primary);">{{ app()->getLocale() == 'ar' ? 'المتحدثون (Speakers)' : 'Speakers' }}</h4>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addSpeakerInput('editEventModal')" style="border-radius: var(--radius-md); font-size: 11px; padding: 4px 10px;">
                                + {{ app()->getLocale() == 'ar' ? 'إضافة متحدث' : 'Add Speaker' }}
                            </button>
                        </div>
                        <div id="editEventModal_speakersContainer" class="d-flex flex-col gap-3">
                            <!-- Dynamic speaker fields go here -->
                        </div>
                    </div>

                    <div style="background: rgba(196,164,119,0.1); border: 1px solid rgba(196,164,119,0.3); border-radius: var(--radius-lg); padding: 1.5rem; margin-top: 0.5rem;">
                        <h4 class="text-h5" style="margin-bottom: 1rem;">{{ app()->getLocale() == 'ar' ? 'مرفقات الفعالية (اختياري للتبديل)' : 'Event Attachments (Optional to replace)' }}</h4>
                        <div class="d-flex gap-4 flex-wrap">
                            <div style="flex:1; min-width: 180px;">
                                <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'تغيير الـ QR Code' : 'Change QR Code' }}</label>
                                <input type="file" name="qr_code" class="form-input" accept="image/*" style="width:100%; padding:0.5rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 d-flex justify-end gap-3">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editEventModal')" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'تحديث الفعالية' : 'Update Event' }}</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Invitation Card Modal -->
    <div id="invitationCardModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); backdrop-filter: blur(12px); z-index:9999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease; overflow-y:auto;">
        <div class="glass-card" style="width:100%; max-width:440px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); display: flex; flex-direction: column; align-items: center; border: 1px solid var(--border-default);">
            <div class="d-flex justify-between items-center mb-6 w-full">
                <h3 class="text-h3 m-0" style="font-weight: 700; font-size: 1.25rem;">{{ app()->getLocale() == 'ar' ? 'بطاقة الدعوة والـ QR' : 'Invitation Card & QR' }}</h3>
                <button type="button" onclick="closeModal('invitationCardModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div id="captureCardArea" style="padding: 10px; background: transparent;">
                <div class="visual-invitation-card" id="previewCard" style="width: 380px; min-height: 520px; background: linear-gradient(135deg, #0d0b07 0%, #1c1710 50%, #0d0b07 100%); border: 2px solid transparent; background-origin: border-box; background-clip: content-box, border-box; background-image: linear-gradient(135deg, #0d0b07, #0d0b07), linear-gradient(135deg, #c4a477, #f7e7c4, #c4a477); border-radius: 24px; padding: 28px; color: #fff; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.8); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box; font-family: 'Cairo', 'Inter', sans-serif;">
                    
                    <!-- Decorative Gold Accents inside card -->
                    <div style="position: absolute; top: 12px; left: 12px; right: 12px; bottom: 12px; border: 1px solid rgba(196, 164, 119, 0.15); border-radius: 16px; pointer-events: none; z-index: 1;"></div>
                    
                    <!-- Card Top Header -->
                    <div style="text-align: center; border-bottom: 1px solid rgba(196, 164, 119, 0.25); padding-bottom: 16px; margin-bottom: 16px; z-index: 2; position: relative;">
                        <div style="font-size: 22px; font-weight: 900; color: #c4a477; letter-spacing: 4px; font-family: 'Cairo', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.4);">CAPITAL</div>
                        <div style="font-size: 9px; color: #a89f91; letter-spacing: 2.5px; margin-top: 4px; text-transform: uppercase; font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'منصة الاستثمار الحصرية' : 'Exclusive Investment Platform' }}</div>
                    </div>

                    <!-- Event Description & Title -->
                    <div style="text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; padding: 8px 0; z-index: 2; position: relative;">
                        <span style="font-size: 10px; color: #c4a477; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; display: block; margin-bottom: 8px; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">{{ app()->getLocale() == 'ar' ? 'دعوة رسمية لحضور' : 'OFFICIAL INVITATION TO' }}</span>
                        <h4 id="cardEventTitle" style="font-size: 19px; font-weight: 800; color: #fff; margin: 0 0 16px 0; line-height: 1.4; font-family: 'Cairo', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.5);"> - </h4>
                        
                        <!-- Speakers list container inside Card -->
                        <div id="cardSpeakerSection" style="margin-bottom: 16px; display: flex; flex-direction: column; align-items: center; width: 100%;">
                            <span style="font-size: 11px; color: #a89f91; margin-bottom: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ app()->getLocale() == 'ar' ? 'المتحدثون المميزون' : 'Featured Speakers' }}</span>
                            <div id="cardSpeakersList" style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; width: 100%;">
                                <!-- Speakers items will be dynamically populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; border-top: 1px dashed rgba(196, 164, 119, 0.25); border-bottom: 1px dashed rgba(196, 164, 119, 0.25); padding: 14px 0; margin-bottom: 16px; text-align: center; z-index: 2; position: relative;">
                        <div>
                            <div style="font-size: 9px; color: #a89f91; font-weight: 700; letter-spacing: 0.5px;">{{ app()->getLocale() == 'ar' ? 'التاريخ والوقت' : 'DATE & TIME' }}</div>
                            <div id="cardDateTime" style="font-size: 11px; font-weight: 700; color: #eaeaea; margin-top: 4px;"> - </div>
                        </div>
                        <div>
                            <div style="font-size: 9px; color: #a89f91; font-weight: 700; letter-spacing: 0.5px;">{{ app()->getLocale() == 'ar' ? 'الموقع' : 'LOCATION' }}</div>
                            <div id="cardLocation" style="font-size: 11px; font-weight: 700; color: #eaeaea; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"> - </div>
                        </div>
                    </div>

                    <!-- QR Section -->
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 6px; z-index: 2; position: relative;">
                        <div style="background: #fff; padding: 8px; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; border: 2px solid #c4a477;">
                            <img id="cardQrImage" src="" alt="QR Code" style="width: 110px; height: 110px; display: block; border: none;">
                        </div>
                        <span style="font-size: 9px; color: #c4a477; margin-top: 10px; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 700; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">{{ app()->getLocale() == 'ar' ? 'يرجى إبراز الرمز عند الدخول' : 'SCAN QR AT THE ENTRANCE' }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 d-flex justify-stretch gap-3 w-full" style="padding: 0 10px 10px 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('invitationCardModal')" style="flex: 1; padding: 0.75rem 1.5rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
                <button type="button" class="btn btn-primary" id="downloadCardBtn" style="flex: 1; padding: 0.75rem 1.5rem; border-radius: var(--radius-full); display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                    <span>{{ app()->getLocale() == 'ar' ? 'تحميل كارت الدعوة' : 'Download Card' }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
let speakerIndexCount = {
    addEventModal: 0,
    editEventModal: 0
};

function addSpeakerInput(modalId, data = null) {
    const container = document.getElementById(modalId + '_speakersContainer');
    if (!container) return;

    const index = speakerIndexCount[modalId]++;
    const rowId = `${modalId}_speaker_row_${index}`;

    const nameVal = data ? (data.name || '') : '';
    const nameArVal = data ? (data.name_ar || '') : '';
    const roleVal = data ? (data.role || '') : '';
    const roleArVal = data ? (data.role_ar || '') : '';
    const existingImage = data ? (data.image || '') : '';

    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";

    let imgPreview = '';
    if (existingImage) {
        imgPreview = `<img src="${existingImage}" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1px solid var(--border-default); margin-inline-end: 8px;">`;
    }

    const html = `
        <div class="speaker-row p-3" id="${rowId}" style="background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-lg); position: relative; animation: fadeIn 0.2s ease; margin-bottom: 8px;">
            <button type="button" class="action-icon-btn reject" onclick="document.getElementById('${rowId}').remove()" style="position: absolute; top: 8px; ${isAr?'left:8px':'right:8px'}; width: 24px; height: 24px; border-radius: 50%; border:1px solid var(--border-default); display:flex; align-items:center; justify-content:center; cursor:pointer; background:var(--bg-secondary); color:var(--text-secondary);">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 5px;">
                <div>
                    <label style="font-size: 10px; font-weight:600; display: block; margin-bottom: 2px; color:var(--text-secondary);">${isAr ? 'الاسم (EN)' : 'Name (EN)'}</label>
                    <input type="text" name="speakers[${index}][name]" value="${nameVal}" class="form-input" style="width: 100%; padding: 0.4rem 0.6rem; font-size: 12px;" required>
                </div>
                <div>
                    <label style="font-size: 10px; font-weight:600; display: block; margin-bottom: 2px; color:var(--text-secondary);">${isAr ? 'الاسم (AR)' : 'Name (AR)'}</label>
                    <input type="text" name="speakers[${index}][name_ar]" value="${nameArVal}" class="form-input" style="width: 100%; padding: 0.4rem 0.6rem; font-size: 12px;" required>
                </div>
                <div>
                    <label style="font-size: 10px; font-weight:600; display: block; margin-bottom: 2px; color:var(--text-secondary);">${isAr ? 'الدور (EN)' : 'Role (EN)'}</label>
                    <input type="text" name="speakers[${index}][role]" value="${roleVal}" class="form-input" style="width: 100%; padding: 0.4rem 0.6rem; font-size: 12px;">
                </div>
                <div>
                    <label style="font-size: 10px; font-weight:600; display: block; margin-bottom: 2px; color:var(--text-secondary);">${isAr ? 'الدور (AR)' : 'Role (AR)'}</label>
                    <input type="text" name="speakers[${index}][role_ar]" value="${roleArVal}" class="form-input" style="width: 100%; padding: 0.4rem 0.6rem; font-size: 12px;">
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-size: 10px; font-weight:600; display: block; margin-bottom: 2px; color:var(--text-secondary);">${isAr ? 'صورة المتحدث' : 'Speaker Photo'}</label>
                    <div class="d-flex items-center gap-2">
                        ${imgPreview}
                        <input type="file" name="speakers[${index}][image_file]" class="form-input" accept="image/*" style="width: 100%; padding: 0.3rem 0.5rem; font-size: 11px;">
                        <input type="hidden" name="speakers[${index}][existing_image]" value="${existingImage}">
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function previewInvitationCard(eventObj) {
    document.getElementById('cardEventTitle').innerText = eventObj.title;
    
    // Clear speakers
    const speakersList = document.getElementById('cardSpeakersList');
    speakersList.innerHTML = '';
    
    let speakers = [];
    if (eventObj.speakers) {
        try {
            speakers = typeof eventObj.speakers === 'string' ? JSON.parse(eventObj.speakers) : eventObj.speakers;
        } catch (e) {
            console.error('Error parsing speakers:', e);
        }
    }
    
    const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";

    if (Array.isArray(speakers) && speakers.length > 0) {
        speakers.forEach(sp => {
            const name = isAr ? (sp.name_ar || sp.name) : (sp.name || sp.name_ar);
            const role = isAr ? (sp.role_ar || sp.role) : (sp.role || sp.role_ar);
            const avatar = sp.image || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop';
            
            const speakerHtml = `
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(196,164,119,0.1); border: 1px solid rgba(196,164,119,0.35); border-radius: 30px; padding: 4px 12px; margin: 2px; min-width: 120px;">
                    <img src="${avatar}" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover; border: 1.5px solid #c4a477; flex-shrink: 0;">
                    <div style="text-align: ${isAr?'right':'left'};">
                        <div style="font-size: 9px; font-weight: 700; color: #fff; line-height: 1.1; font-family:'Cairo','Inter';">${name}</div>
                        <div style="font-size: 8px; color: #c4a477; line-height: 1.1; font-family:'Cairo','Inter';">${role}</div>
                    </div>
                </div>
            `;
            speakersList.insertAdjacentHTML('beforeend', speakerHtml);
        });
    } else if (eventObj.speaker_name) {
        // Fallback for single speaker name
        const avatar = eventObj.speaker_profile ? ('/storage/' + eventObj.speaker_profile) : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&h=100&fit=crop';
        const speakerHtml = `
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(196,164,119,0.1); border: 1px solid rgba(196,164,119,0.35); border-radius: 30px; padding: 4px 12px; margin: 2px; min-width: 120px;">
                <img src="${avatar}" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover; border: 1.5px solid #c4a477; flex-shrink: 0;">
                <div style="text-align: ${isAr?'right':'left'};">
                    <div style="font-size: 9px; font-weight: 700; color: #fff; line-height: 1.1; font-family:'Cairo','Inter';">${eventObj.speaker_name}</div>
                    <div style="font-size: 8px; color: #c4a477; line-height: 1.1; font-family:'Cairo','Inter';">${isAr ? 'متحدث' : 'Speaker'}</div>
                </div>
            </div>
        `;
        speakersList.insertAdjacentHTML('beforeend', speakerHtml);
    } else {
        speakersList.innerHTML = `<span style="font-size: 11px; color:#666;">--</span>`;
    }
    
    const eventDate = eventObj.event_date ? eventObj.event_date.split(' ')[0] : '';
    const eventTime = eventObj.time ? (' | ' + eventObj.time) : '';
    document.getElementById('cardDateTime').innerText = eventDate + eventTime;
    document.getElementById('cardLocation').innerText = eventObj.location;
    
    const qrUrl = eventObj.qr_code 
        ? ('/storage/' + eventObj.qr_code) 
        : ('https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent('Event: ' + eventObj.title + '\nDate: ' + eventDate + '\nLocation: ' + eventObj.location));
    
    document.getElementById('cardQrImage').src = qrUrl;
    
    // Default background style
    document.getElementById('previewCard').style.backgroundImage = 'linear-gradient(135deg, #0d0b07 0%, #1c1710 50%, #0d0b07 100%)';
    
    openModal('invitationCardModal');
}

// Download Card listener
document.addEventListener('DOMContentLoaded', () => {
    const downloadCardBtn = document.getElementById('downloadCardBtn');
    if (downloadCardBtn) {
        downloadCardBtn.addEventListener('click', () => {
            const card = document.getElementById('captureCardArea');
            const btnText = downloadCardBtn.querySelector('span');
            const originalText = btnText.innerText;
            
            downloadCardBtn.disabled = true;
            btnText.innerText = "{{ app()->getLocale() == 'ar' ? 'جاري التحميل...' : 'Downloading...' }}";
            
            html2canvas(card, {
                useCORS: true,
                backgroundColor: null,
                scale: 2
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `Invitation_${document.getElementById('cardEventTitle').innerText.replace(/\s+/g, '_')}.png`;
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                downloadCardBtn.disabled = false;
                btnText.innerText = originalText;
            }).catch(err => {
                console.error('Canvas capture error:', err);
                downloadCardBtn.disabled = false;
                btnText.innerText = originalText;
                alert('حدث خطأ أثناء تحميل الكارت.');
            });
        });
    }
});

function filterEvents() {
    const query = document.getElementById('eventSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.project-card');

    cards.forEach(card => {
        const title = card.getAttribute('data-title');
        if (title.includes(query)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function showEditEventModal(eventObj) {
    document.getElementById('editEventForm').action = '/admin/events/' + eventObj.id;
    document.getElementById('editTitle').value = eventObj.title;
    document.getElementById('editDate').value = eventObj.event_date ? eventObj.event_date.split(' ')[0] : '';
    document.getElementById('editTime').value = eventObj.time;
    document.getElementById('editDuration').value = eventObj.duration;
    document.getElementById('editLocation').value = eventObj.location;
    document.getElementById('editAttendees').value = eventObj.attendees_count;
    document.getElementById('editStatus').value = eventObj.status;
    document.getElementById('editAccess').value = eventObj.access_type;
    
    // Clear and fill dynamic speakers
    const speakersContainer = document.getElementById('editEventModal_speakersContainer');
    if (speakersContainer) {
        speakersContainer.innerHTML = '';
        speakerIndexCount.editEventModal = 0;
    }
    
    if (eventObj.speakers) {
        try {
            const speakers = typeof eventObj.speakers === 'string' ? JSON.parse(eventObj.speakers) : eventObj.speakers;
            if (Array.isArray(speakers)) {
                speakers.forEach(sp => {
                    addSpeakerInput('editEventModal', sp);
                });
            }
        } catch (e) {
            console.error('Error parsing event speakers:', e);
        }
    }
    
    openModal('editEventModal');
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
