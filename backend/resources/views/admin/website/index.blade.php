@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'إدارة الموقع العام' : 'Website Management')

@section('content')
<style>
    .glass-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-default);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .tabs-nav {
        display: flex;
        gap: 1rem;
        border-bottom: 1px solid var(--border-default);
        margin-bottom: 2rem;
        overflow-x: auto;
    }
    .tab-btn {
        background: transparent;
        border: none;
        padding: 1rem 1.5rem;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        white-space: nowrap;
    }
    .tab-btn.active {
        color: var(--action-primary);
        border-bottom-color: var(--action-primary);
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .stc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stc-table th {
        text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        padding: 1rem;
        border-bottom: 2px solid var(--border-default);
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.875rem;
    }
    .stc-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-default);
        color: var(--text-primary);
        font-size: 0.95rem;
    }
</style>

<div class="fade-in">
    <div class="d-flex justify-between items-center mb-6">
        <div>
            <h1 class="text-h2" style="font-weight: 700;">{{ app()->getLocale() == 'ar' ? 'إدارة محتوى الموقع العام' : 'Website Content Management' }}</h1>
            <p class="text-secondary mt-1">{{ app()->getLocale() == 'ar' ? 'قم بإدارة المقالات، الوظائف، الإحصائيات وقصص المؤسسين التي تظهر في الصفحة الرئيسية.' : 'Manage articles, jobs, metrics, and founder stories visible on the public homepage.' }}</p>
        </div>
    </div>

    @if(session('success'))
    <div style="background: var(--color-success-bg); color: var(--color-success); padding: 1rem 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; display:flex; align-items:center; gap: 1rem; border: 1px solid rgba(26, 135, 84, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span style="font-weight: 600;">{{ session('success') }}</span>
    </div>
    @endif

    <div class="glass-card" style="padding: 0;">
        <div class="tabs-nav" style="padding: 0 1.5rem;">
            <button class="tab-btn active" onclick="switchTab('articles')">{{ app()->getLocale() == 'ar' ? 'المقالات والمدونة' : 'Articles' }}</button>
            <button class="tab-btn" onclick="switchTab('jobs')">{{ app()->getLocale() == 'ar' ? 'الوظائف المتاحة' : 'Job Postings' }}</button>
            <button class="tab-btn" onclick="switchTab('metrics')">{{ app()->getLocale() == 'ar' ? 'الإحصائيات والأرقام' : 'Metrics' }}</button>
            <button class="tab-btn" onclick="switchTab('testimonials')">{{ app()->getLocale() == 'ar' ? 'قصص المؤسسين' : 'Founder Stories' }}</button>
        </div>

        <div style="padding: 1.5rem;">
            <!-- Articles Tab -->
            <div id="tab-articles" class="tab-content active">
                <div class="d-flex justify-between items-center mb-4">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قائمة المقالات' : 'Articles List' }}</h3>
                    <button class="btn btn-primary" onclick="document.getElementById('modal-article').style.display='flex'">+ {{ app()->getLocale() == 'ar' ? 'إضافة مقال' : 'Add Article' }}</button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="stc-table">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Title' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'القسم' : 'Category' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الكاتب' : 'Author' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الإجراء' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->author_name }}</td>
                                <td>
                                    <form action="{{ route('admin.website.destroy', ['type' => 'article', 'id' => $item->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="color: var(--color-error); padding: 0.25rem 0.5rem;" onclick="return confirm('Are you sure?')">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد بيانات.' : 'No data found.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jobs Tab -->
            <div id="tab-jobs" class="tab-content">
                <div class="d-flex justify-between items-center mb-4">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قائمة الوظائف' : 'Jobs List' }}</h3>
                    <button class="btn btn-primary" onclick="document.getElementById('modal-job').style.display='flex'">+ {{ app()->getLocale() == 'ar' ? 'إضافة وظيفة' : 'Add Job' }}</button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="stc-table">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'المسمى الوظيفي' : 'Title' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'المكان' : 'Location' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'القسم' : 'Department' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الإجراء' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobs as $item)
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->location }}</td>
                                <td>{{ $item->department }}</td>
                                <td>
                                    <form action="{{ route('admin.website.destroy', ['type' => 'job', 'id' => $item->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="color: var(--color-error); padding: 0.25rem 0.5rem;" onclick="return confirm('Are you sure?')">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد بيانات.' : 'No data found.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Metrics Tab -->
            <div id="tab-metrics" class="tab-content">
                <div class="d-flex justify-between items-center mb-4">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قائمة الإحصائيات' : 'Metrics List' }}</h3>
                    <button class="btn btn-primary" onclick="document.getElementById('modal-metric').style.display='flex'">+ {{ app()->getLocale() == 'ar' ? 'إضافة إحصائية' : 'Add Metric' }}</button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="stc-table">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'العنوان' : 'Label' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'القيمة' : 'Value' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الإجراء' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($metrics as $item)
                            <tr>
                                <td>{{ $item->label }}</td>
                                <td>{{ $item->prefix }}{{ $item->value }}{{ $item->suffix }}</td>
                                <td>
                                    <form action="{{ route('admin.website.destroy', ['type' => 'metric', 'id' => $item->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="color: var(--color-error); padding: 0.25rem 0.5rem;" onclick="return confirm('Are you sure?')">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد بيانات.' : 'No data found.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Testimonials Tab -->
            <div id="tab-testimonials" class="tab-content">
                <div class="d-flex justify-between items-center mb-4">
                    <h3 class="text-h4 m-0">{{ app()->getLocale() == 'ar' ? 'قصص المؤسسين' : 'Founder Stories' }}</h3>
                    <button class="btn btn-primary" onclick="document.getElementById('modal-testimonial').style.display='flex'">+ {{ app()->getLocale() == 'ar' ? 'إضافة قصة' : 'Add Story' }}</button>
                </div>
                <div style="overflow-x: auto;">
                    <table class="stc-table">
                        <thead>
                            <tr>
                                <th>{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'المنصب' : 'Role' }}</th>
                                <th>{{ app()->getLocale() == 'ar' ? 'الإجراء' : 'Actions' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $item)
                            <tr>
                                <td>{{ $item->author_name }}</td>
                                <td>{{ $item->author_role }}</td>
                                <td>
                                    <form action="{{ route('admin.website.destroy', ['type' => 'testimonial', 'id' => $item->id]) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-ghost" style="color: var(--color-error); padding: 0.25rem 0.5rem;" onclick="return confirm('Are you sure?')">{{ app()->getLocale() == 'ar' ? 'حذف' : 'Delete' }}</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-secondary py-4">{{ app()->getLocale() == 'ar' ? 'لا يوجد بيانات.' : 'No data found.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->

<!-- Article Modal -->
<div id="modal-article" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:600px; background:var(--bg-primary); max-height:90vh; overflow-y:auto;">
        <div class="d-flex justify-between mb-4">
            <h3 class="text-h3 m-0">Add Article</h3>
            <button onclick="document.getElementById('modal-article').style.display='none'" class="btn btn-ghost" style="padding:0.25rem;">X</button>
        </div>
        <form action="{{ route('admin.website.articles.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            <input type="text" name="title" class="form-input" placeholder="Title" required>
            <input type="text" name="category" class="form-input" placeholder="Category (e.g. Technology)" required>
            <textarea name="excerpt" class="form-input" placeholder="Excerpt (Short summary)" required></textarea>
            <input type="text" name="author_name" class="form-input" placeholder="Author Name" required>
            <input type="text" name="author_meta" class="form-input" placeholder="Author Role (e.g. CEO)">
            <label class="text-caption">Cover Image</label>
            <input type="file" name="image" class="form-input" accept="image/*">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<!-- Job Modal -->
<div id="modal-job" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
        <div class="d-flex justify-between mb-4">
            <h3 class="text-h3 m-0">Add Job Posting</h3>
            <button onclick="document.getElementById('modal-job').style.display='none'" class="btn btn-ghost" style="padding:0.25rem;">X</button>
        </div>
        <form action="{{ route('admin.website.jobs.store') }}" method="POST" class="d-flex flex-col gap-4">
            @csrf
            <input type="text" name="title" class="form-input" placeholder="Job Title" required>
            <input type="text" name="location" class="form-input" placeholder="Location (e.g. Remote, Riyadh)" required>
            <input type="text" name="type" class="form-input" placeholder="Type (e.g. Full-time)" required>
            <input type="text" name="department" class="form-input" placeholder="Department (e.g. Engineering)" required>
            <input type="url" name="apply_link" class="form-input" placeholder="Apply Link (URL)">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<!-- Metric Modal -->
<div id="modal-metric" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
        <div class="d-flex justify-between mb-4">
            <h3 class="text-h3 m-0">Add Metric</h3>
            <button onclick="document.getElementById('modal-metric').style.display='none'" class="btn btn-ghost" style="padding:0.25rem;">X</button>
        </div>
        <form action="{{ route('admin.website.metrics.store') }}" method="POST" class="d-flex flex-col gap-4">
            @csrf
            <input type="text" name="label" class="form-input" placeholder="Label (e.g. Ventures Built)" required>
            <input type="number" name="value" class="form-input" placeholder="Value (e.g. 12)" required>
            <input type="text" name="prefix" class="form-input" placeholder="Prefix (e.g. $)">
            <input type="text" name="suffix" class="form-input" placeholder="Suffix (e.g. M, +)">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<!-- Testimonial Modal -->
<div id="modal-testimonial" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center; padding:1rem;">
    <div class="glass-card" style="width:100%; max-width:500px; background:var(--bg-primary);">
        <div class="d-flex justify-between mb-4">
            <h3 class="text-h3 m-0">Add Founder Story</h3>
            <button onclick="document.getElementById('modal-testimonial').style.display='none'" class="btn btn-ghost" style="padding:0.25rem;">X</button>
        </div>
        <form action="{{ route('admin.website.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-col gap-4">
            @csrf
            <textarea name="quote" class="form-input" placeholder="Quote / Story" rows="4" required></textarea>
            <input type="text" name="author_name" class="form-input" placeholder="Author Name" required>
            <input type="text" name="author_role" class="form-input" placeholder="Author Role (e.g. CEO, Startup)" required>
            <label class="text-caption">Author Image</label>
            <input type="file" name="image" class="form-input" accept="image/*">
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    
    document.getElementById('tab-' + tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>
@endsection
