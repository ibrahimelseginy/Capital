@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'المستخدمين' : 'Users')

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
    .stc-table td { padding: 1rem; border-bottom: 1px solid var(--border-default); color: var(--text-primary); font-size: 0.95rem; transition: background 0.2s; }
    .stc-table tr.data-row:hover td { background: rgba(196, 164, 119, 0.03); }
    .stc-table tr:last-child td { border-bottom: none; }
    .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-investor { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .badge-entrepreneur { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-admin { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .action-icon-btn { background: transparent; border: none; padding: 0.5rem; border-radius: var(--radius-full); color: var(--text-secondary); cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; }
    .action-icon-btn:hover { background: var(--bg-secondary); color: var(--action-primary); }
    
    .search-container { position: relative; max-width: 300px; width: 100%; }
    .search-container input { width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: var(--radius-full); border: 1px solid var(--border-default); background: var(--bg-surface); color: var(--text-primary); font-size: 0.9rem; transition: all 0.3s; }
    html[dir="rtl"] .search-container input { padding: 0.6rem 2.5rem 0.6rem 1rem; }
    .search-container input:focus { outline: none; border-color: var(--action-primary); box-shadow: 0 0 0 3px rgba(196, 164, 119, 0.1); }
    .search-icon { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); color: var(--text-secondary); }
    html[dir="rtl"] .search-icon { left: auto; right: 1rem; }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-8">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة المستخدمين' : 'User Management' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'إدارة حسابات المستثمرين ورواد الأعمال' : 'Manage investor and entrepreneur accounts' }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.5rem; margin-bottom:2rem;">
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(196,164,119,0.1);color:var(--action-primary);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'إجمالي المسجلين' : 'Total Registered' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $users->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(59,130,246,0.1);color:#3b82f6;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'المستثمرين' : 'Investors' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $users->where('role', 'investor')->count() }}</div>
            </div>
        </div>
        <div class="glass-card d-flex items-center gap-4">
            <div style="width:48px;height:48px;border-radius:var(--radius-md);background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div>
                <div class="text-caption text-secondary">{{ app()->getLocale() == 'ar' ? 'رواد الأعمال' : 'Entrepreneurs' }}</div>
                <div class="text-h3" style="font-weight:700">{{ $users->where('role', 'entrepreneur')->count() }}</div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Users Table -->
    <div class="glass-card" style="padding:0; overflow:hidden">
        <div style="padding:1.5rem; border-bottom:1px solid var(--border-default); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; background:rgba(0,0,0,0.02)">
            <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قائمة المستخدمين' : 'Users List' }}</h3>
            <div class="search-container">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="userSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'ابحث عن مستخدم...' : 'Search users...' }}" onkeyup="filterUsers()">
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="stc-table">
                <thead>
                    <tr>
                        <th>{{ app()->getLocale() == 'ar' ? 'المستخدم' : 'User' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'الدور' : 'Role' }}</th>
                        <th>{{ app()->getLocale() == 'ar' ? 'تاريخ التسجيل' : 'Registration Date' }}</th>
                        <th style="text-align:center">{{ app()->getLocale() == 'ar' ? 'إجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $user)
                    <tr class="data-row">
                        <td>
                            <div class="d-flex items-center gap-3">
                                <div style="width:36px;height:36px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:12px;color:var(--text-secondary)">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div style="font-weight: 600;">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($user->role) }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="text-secondary">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align:center">
                            <button class="action-icon-btn" title="{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}" onclick="showUserDetails(`{{ addslashes($user->name) }}`, `{{ addslashes($user->email) }}`, `{{ ucfirst($user->role) }}`, `{{ $user->created_at->format('M d, Y') }}`)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                            <button class="action-icon-btn" title="{{ app()->getLocale() == 'ar' ? 'تعديل' : 'Edit' }}" style="color:var(--color-success)" onclick="showEditUser({{ $user->id }}, '{{ $user->role }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" style="opacity:0.2; margin-bottom:1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg><br>
                            {{ app()->getLocale() == 'ar' ? 'لا يوجد مستخدمين بعد' : 'No users found' }}
                        </td>
                    </tr>
                    @endforelse
                    <tr class="empty-row" id="noResultsRow" style="display:none">
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" style="opacity:0.2; margin-bottom:1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg><br>
                            {{ app()->getLocale() == 'ar' ? 'لا توجد نتائج مطابقة للبحث' : 'No matching results found' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Details Modal -->
    <div id="userDetailsModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
        <div class="glass-card" style="width:100%; max-width:400px; background:var(--bg-primary);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'بيانات المستخدم' : 'User Details' }}</h3>
                <button onclick="document.getElementById('userDetailsModal').style.display='none'" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div class="d-flex flex-col gap-4">
                <div class="d-flex gap-4 items-center">
                    <div id="modalUserInitials" style="width:64px;height:64px;border-radius:50%;background:var(--bg-secondary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:24px;color:var(--text-secondary)">
                        --
                    </div>
                    <div>
                        <div id="modalUserName" class="text-h4" style="font-weight:600"></div>
                        <div id="modalUserEmail" class="text-secondary"></div>
                    </div>
                </div>
                
                <hr style="border:none; border-top:1px solid var(--border-default); margin:0.5rem 0;">
                
                <div class="d-flex justify-between items-center">
                    <span class="text-secondary">{{ app()->getLocale() == 'ar' ? 'الدور:' : 'Role:' }}</span>
                    <span id="modalUserRole"></span>
                </div>
                <div class="d-flex justify-between items-center">
                    <span class="text-secondary">{{ app()->getLocale() == 'ar' ? 'تاريخ التسجيل:' : 'Joined:' }}</span>
                    <span id="modalUserDate" style="font-weight:500"></span>
                </div>
            </div>

            <div class="mt-6 d-flex justify-end">
                <button class="btn btn-secondary" onclick="document.getElementById('userDetailsModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إغلاق' : 'Close' }}</button>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
        <div class="glass-card" style="width:100%; max-width:400px; background:var(--bg-primary);">
            <div class="d-flex justify-between items-center mb-6">
                <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'تعديل المستخدم' : 'Edit User' }}</h3>
                <button onclick="document.getElementById('editUserModal').style.display='none'" style="background:transparent; border:none; cursor:pointer; color:var(--text-secondary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <form id="editUserForm" method="POST" class="d-flex flex-col gap-4">
                @csrf
                <div>
                    <label class="form-label mb-2" style="display:block">{{ app()->getLocale() == 'ar' ? 'دور المستخدم' : 'User Role' }}</label>
                    <select name="role" id="editUserRole" class="form-input" style="width:100%; padding:0.75rem; border-radius:var(--radius-md); border:1px solid var(--border-default); background:var(--bg-surface); color:var(--text-primary);">
                        <option value="investor">Investor / مستثمر</option>
                        <option value="entrepreneur">Entrepreneur / رائد أعمال</option>
                        <option value="admin">Admin / مدير</option>
                    </select>
                </div>

                <div class="mt-4 d-flex gap-3 justify-end">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('editUserModal').style.display='none'">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
                    <button type="submit" class="btn btn-primary" style="background:var(--color-success); border-color:var(--color-success)">{{ app()->getLocale() == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function filterUsers() {
    const query = document.getElementById('userSearch').value.toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr.data-row');
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

    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = visibleCount === 0 && query !== '' ? '' : 'none';
    }
}

function showUserDetails(name, email, role, date) {
    document.getElementById('modalUserInitials').innerText = name.substring(0, 2).toUpperCase();
    document.getElementById('modalUserName').innerText = name;
    document.getElementById('modalUserEmail').innerText = email;
    document.getElementById('modalUserRole').innerHTML = `<span class="badge badge-${role.toLowerCase()}">${role}</span>`;
    document.getElementById('modalUserDate').innerText = date;
    
    document.getElementById('userDetailsModal').style.display = 'flex';
}

function showEditUser(id, currentRole) {
    document.getElementById('editUserRole').value = currentRole;
    document.getElementById('editUserForm').action = `/users/${id}`;
    
    document.getElementById('editUserModal').style.display = 'flex';
}
</script>
@endsection
