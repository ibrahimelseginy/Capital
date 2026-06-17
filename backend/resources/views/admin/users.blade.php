@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة المستخدمين' : 'User Management')

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
    .badge-investor { background: rgba(59, 130, 246, 0.15); color: #2563eb; }
    .badge-entrepreneur { background: rgba(16, 185, 129, 0.15); color: #059669; }
    .badge-admin { background: rgba(245, 158, 11, 0.15); color: #d97706; }
    
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

    /* Modern Users Directory Grid */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .user-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .user-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 60px;
        background: linear-gradient(to right, rgba(196,164,119,0.1), rgba(196,164,119,0.02));
        z-index: 0;
    }

    .user-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-card-hover);
        border-color: var(--border-strong);
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--bg-primary);
        border: 4px solid var(--bg-surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--text-secondary);
        z-index: 1;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .user-card:hover .user-avatar {
        transform: scale(1.05);
        color: var(--action-primary);
    }

    .user-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.5rem;
        width: 100%;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }

    .user-card:hover .user-actions {
        opacity: 1;
    }

    .user-actions button {
        flex: 1;
        padding: 0.5rem;
        border-radius: var(--radius-lg);
        background: var(--bg-secondary);
        border: 1px solid var(--border-default);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .user-actions button:hover {
        background: var(--action-primary);
        color: white;
        border-color: var(--action-primary);
    }

    /* Beautiful Empty State */
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
            <h1 class="text-h2" style="font-weight: 700; letter-spacing: -0.5px;">{{ app()->getLocale() == 'ar' ? 'إدارة المستخدمين' : 'User Directory' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'إدارة حسابات المستثمرين ورواد الأعمال وصلاحياتهم.' : 'Manage investor and entrepreneur accounts and permissions.' }}</p>
        </div>
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input type="text" id="userSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث بالاسم أو البريد...' : 'Search by name or email...' }}" onkeyup="filterUsers()">
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem; margin-bottom:3rem;">
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.1s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(196,164,119,0.15);color:var(--action-primary);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'إجمالي المسجلين' : 'Total Users' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $users->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.2s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(59,130,246,0.15);color:#2563eb;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'المستثمرين' : 'Investors' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $users->where('role', 'investor')->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4 stagger-item" style="animation-delay: 0.3s;">
            <div style="width:56px;height:56px;border-radius:var(--radius-full);background:rgba(16,185,129,0.15);color:#059669;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'رواد الأعمال' : 'Entrepreneurs' }}</div>
                <div class="text-h2 mt-1" style="font-weight:700">{{ $users->where('role', 'entrepreneur')->count() }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem; border: 1px solid rgba(16, 185, 129, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Modern Users Directory Grid -->
    <div class="users-grid" id="usersContainer">
        @forelse($users as $index => $user)
        <div class="user-card stagger-item" style="animation-delay: {{ 0.05 * ($index + 1) }}s;" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
            <div class="user-avatar" style="border-color: {{ $user->role === 'investor' ? 'rgba(59,130,246,0.2)' : ($user->role === 'entrepreneur' ? 'rgba(16,185,129,0.2)' : 'rgba(245,158,11,0.2)') }}">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            
            <h3 class="text-h4 m-0" style="font-weight: 700; z-index: 1;">{{ $user->name }}</h3>
            <p class="text-caption text-secondary mt-1" style="z-index: 1;">{{ $user->email }}</p>
            
            <div class="mt-3" style="z-index: 1;">
                <span class="badge badge-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
            </div>
            
            <div class="text-caption text-tertiary mt-4" style="z-index: 1;">
                {{ app()->getLocale() == 'ar' ? 'انضم في' : 'Joined' }} {{ $user->created_at->format('M d, Y') }}
            </div>

            <div class="user-actions" style="z-index: 1;">
                <button type="button" onclick="showUserDetails(`{{ addslashes($user->name) }}`, `{{ addslashes($user->email) }}`, `{{ ucfirst($user->role) }}`, `{{ $user->created_at->format('M d, Y') }}`)">
                    {{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'View' }}
                </button>
                <button type="button" onclick="showEditUser({{ $user->id }}, '{{ $user->role }}', `{{ addslashes($user->name) }}`)">
                    {{ app()->getLocale() == 'ar' ? 'تعديل البيانات' : 'Edit' }}
                </button>
            </div>
        </div>
        @empty
        <div class="beautiful-empty-state">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا يوجد مستخدمين بعد' : 'No users yet' }}</h3>
        </div>
        @endforelse
        
        <div class="beautiful-empty-state" id="noResultsState" style="display:none;">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--bg-secondary);color:var(--text-tertiary);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            <h3 class="text-h3" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة' : 'No matching results' }}</h3>
            <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'لم يتم العثور على أي مستخدم بهذا الاسم أو البريد.' : 'No user found with that name or email.' }}</p>
        </div>
    </div>

    <!-- User Details Modal (Modernized) -->
    <div id="userDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
        <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'الملف الشخصي' : 'User Profile' }}</h3>
                <button onclick="closeModal('userDetailsModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div class="d-flex flex-col items-center mb-6 text-center">
                <div id="modalUserInitials" style="width:88px;height:88px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:32px;color:var(--action-primary); box-shadow: var(--shadow-sm); margin-bottom: 1rem;">
                    --
                </div>
                <div id="modalUserName" class="text-h3" style="font-weight:700"></div>
                <div id="modalUserEmail" class="text-secondary mt-1"></div>
            </div>
            
            <div style="background: var(--bg-surface); border: 1px solid var(--border-default); border-radius: var(--radius-lg); padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                <div class="d-flex justify-between items-center">
                    <span class="text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'الصلاحية (الدور)' : 'Role' }}</span>
                    <span id="modalUserRole"></span>
                </div>
                <hr style="border:none; border-top:1px dashed var(--border-default); margin:0;">
                <div class="d-flex justify-between items-center">
                    <span class="text-secondary" style="font-weight: 600;">{{ app()->getLocale() == 'ar' ? 'تاريخ التسجيل' : 'Joined Date' }}</span>
                    <span id="modalUserDate" style="font-weight:700; color:var(--text-primary);"></span>
                </div>
            </div>

            <div class="mt-8 d-flex justify-end">
                <button class="btn btn-secondary" style="width: 100%; padding: 0.75rem; border-radius: var(--radius-full);" onclick="closeModal('userDetailsModal')">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
            </div>
        </div>
    </div>

    <!-- Edit User Modal (Modernized) -->
    <div id="editUserModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); backdrop-filter: blur(8px); z-index:999; align-items:center; justify-content:center; padding:1rem; opacity: 0; transition: opacity 0.3s ease;">
        <div class="glass-card" style="width:100%; max-width:450px; background:var(--bg-primary); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h3 m-0" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'تعديل بيانات المستخدم' : 'Edit User' }}</h3>
                <button onclick="closeModal('editUserModal')" style="background:var(--bg-secondary); border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; color:var(--text-primary); display:flex; align-items:center; justify-content:center; transition: all 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <form id="editUserForm" method="POST" class="d-flex flex-col gap-4">
                @csrf
                <div>
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'اسم المستخدم' : 'User Name' }}</label>
                    <input type="text" name="name" id="editUserName" class="form-input" style="width:100%; padding:0.8rem 1rem; border-radius:var(--radius-lg); background:var(--bg-surface); box-shadow: var(--shadow-sm);" required>
                </div>
                <div>
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'كلمة المرور الجديدة (اختياري)' : 'New Password (Optional)' }}</label>
                    <input type="password" name="password" id="editUserPassword" class="form-input" placeholder="********" style="width:100%; padding:0.8rem 1rem; border-radius:var(--radius-lg); background:var(--bg-surface); box-shadow: var(--shadow-sm);">
                </div>
                <div>
                    <label class="text-caption" style="font-weight: 600; margin-bottom: 0.5rem; display: block;">{{ app()->getLocale() == 'ar' ? 'دور المستخدم' : 'User Role' }}</label>
                    <select name="role" id="editUserRole" class="form-input" style="width:100%; padding:0.8rem 1rem; border-radius:var(--radius-lg); background:var(--bg-surface); box-shadow: var(--shadow-sm);">
                        <option value="investor">Investor / مستثمر</option>
                        <option value="entrepreneur">Entrepreneur / رائد أعمال</option>
                        <option value="admin">Admin / مدير</option>
                    </select>
                </div>

                <div class="mt-6 d-flex gap-3 justify-end">
                    <button type="button" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; border-radius: var(--radius-full);" onclick="closeModal('editUserModal')">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: var(--radius-full);">{{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterUsers() {
    const query = document.getElementById('userSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.user-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const email = card.getAttribute('data-email');
        
        if (name.includes(query) || email.includes(query)) {
            card.style.display = 'flex';
            visibleCount++;
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

function showUserDetails(name, email, role, date) {
    document.getElementById('modalUserInitials').innerText = name.substring(0, 2).toUpperCase();
    document.getElementById('modalUserName').innerText = name;
    document.getElementById('modalUserEmail').innerText = email;
    document.getElementById('modalUserRole').innerHTML = `<span class="badge badge-${role.toLowerCase()}">${role}</span>`;
    document.getElementById('modalUserDate').innerText = date;
    
    openModal('userDetailsModal');
}

function showEditUser(id, currentRole, currentName) {
    document.getElementById('editUserRole').value = currentRole;
    document.getElementById('editUserName').value = currentName;
    document.getElementById('editUserPassword').value = '';
    document.getElementById('editUserForm').action = `/admin/users/${id}`;
    
    openModal('editUserModal');
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
