<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $pending_ndas = \App\Models\Nda::where('status', 'pending')->get();
        $pending_exits = \App\Models\ExitRequest::where('status', 'Under Review')->get();
        $pending_projects = Project::where('status', 'Pending')->get();

        $metrics = [
            'total_users' => User::count(),
            'total_investors' => User::where('role', 'investor')->count(),
            'total_entrepreneurs' => User::where('role', 'entrepreneur')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'Active')->count(),
            'total_portfolio_value' => Project::sum('budget') ?? 0,
            'pending_ndas' => $pending_ndas->count(),
            'pending_exit_requests' => $pending_exits->count(),
            'total_exit_value' => \App\Models\ExitRequest::sum('amount') ?? 0,
            'total_content' => \App\Models\Content::count()
        ];

        // Smart Alerts
        $alerts = [];
        if ($pending_ndas->count() > 5) {
            $alerts[] = ['type' => 'warning', 'message' => app()->getLocale() == 'ar' ? 'يوجد تراكم في طلبات اتفاقية السرية (أكثر من 5 طلبات).' : 'There is a backlog of NDA requests (more than 5).'];
        }
        if ($pending_exits->count() > 0) {
            $alerts[] = ['type' => 'error', 'message' => app()->getLocale() == 'ar' ? "يوجد {$pending_exits->count()} طلبات تخارج بانتظار المراجعة." : "There are {$pending_exits->count()} exit requests pending review."];
        }
        if ($pending_projects->count() > 2) {
            $alerts[] = ['type' => 'info', 'message' => app()->getLocale() == 'ar' ? 'يوجد مشاريع جديدة بانتظار الاعتماد.' : 'There are new projects pending approval.'];
        }

        // Activity Log
        $recent_ndas = \App\Models\Nda::with(['user', 'project'])->latest()->take(5)->get()->map(function($i) { return ['type' => 'nda', 'model' => $i, 'date' => $i->created_at]; });
        $recent_docs = \App\Models\Document::with(['user'])->latest()->take(5)->get()->map(function($i) { return ['type' => 'document', 'model' => $i, 'date' => $i->created_at]; });
        $recent_exits = \App\Models\ExitRequest::with(['user', 'project'])->latest()->take(5)->get()->map(function($i) { return ['type' => 'exit', 'model' => $i, 'date' => $i->created_at]; });
        $activities = collect($recent_ndas)->merge($recent_docs)->merge($recent_exits)->sortByDesc('date')->take(6)->values();

        $recent_users = User::orderBy('created_at', 'desc')->take(5)->get();
        
        // Top Projects by NDAs
        $top_projects = Project::withCount('ndas')->orderByDesc('ndas_count')->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'recent_users', 'top_projects', 'activities', 'alerts'));
    }

    public function projects()
    {
        $projects = Project::with(['projectManager', 'accountManager', 'financialManager', 'executiveManager'])->get();
        $users = User::all();
        return view('admin.projects', compact('projects', 'users'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:investor,entrepreneur,admin',
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->email = $request->email;
        
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }
        
        $user->save();
        
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث بيانات المستخدم بنجاح.' : 'User details updated successfully.');
    }

    public function requests()
    {
        $ndas = \App\Models\Nda::with(['user', 'project'])->get()->map(function($n) {
            return [
                'id' => 'nda_' . $n->id,
                'real_id' => $n->id,
                'user_name' => $n->user->name ?? 'Unknown',
                'user_role' => $n->user->role ?? 'investor',
                'item_title' => $n->project->title ?? 'Unknown',
                'item_type' => 'nda',
                'request_type' => 'NDA',
                'reason' => 'Request to sign NDA',
                'status' => $n->status == 'pending' ? 'Pending' : ($n->status == 'signed' ? 'Approved' : 'Rejected'),
                'created_at' => $n->created_at->format('M d, Y'),
                'update_url' => route('admin.ndas.status', $n->id),
                'approve_val' => 'signed',
                'reject_val' => 'rejected',
                'pending_val' => 'pending'
            ];
        });

        $exits = \App\Models\ExitRequest::with(['user', 'project'])->get()->map(function($e) {
            return [
                'id' => 'exit_' . $e->id,
                'real_id' => $e->id,
                'user_name' => $e->user->name ?? 'Unknown',
                'user_role' => $e->user->role ?? 'investor',
                'item_title' => $e->project->title ?? 'Unknown',
                'item_type' => 'exit',
                'request_type' => 'Exit Request',
                'reason' => $e->type . ' - $' . number_format($e->amount),
                'status' => $e->status == 'Under Review' ? 'Pending' : ($e->status == 'Completed' ? 'Approved' : 'Rejected'),
                'created_at' => $e->created_at->format('M d, Y'),
                'update_url' => route('admin.exits.status', $e->id),
                'approve_val' => 'Completed',
                'reject_val' => 'Rejected',
                'pending_val' => 'Under Review'
            ];
        });

        $requests = $ndas->concat($exits)->sortByDesc('created_at')->values();
        return view('admin.requests', compact('requests'));
    }

    public function updateNdaStatus(Request $request, $id)
    {
        $nda = \App\Models\Nda::findOrFail($id);
        $nda->status = $request->status;
        $nda->save();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث حالة الـ NDA' : 'NDA status updated.');
    }

    public function updateExitStatus(Request $request, $id)
    {
        $exit = \App\Models\ExitRequest::findOrFail($id);
        $exit->status = $request->status;
        $exit->save();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث حالة طلب التخارج' : 'Exit request updated.');
    }

    public function updateProjectStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Rejected,Pending'
        ]);
        $project = Project::findOrFail($id);
        $project->status = $request->status;
        $project->save();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث حالة المشروع بنجاح.' : 'Project status updated successfully.');
    }

    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'capital' => 'nullable|numeric',
            'funding_ask' => 'nullable|numeric',
            'investors_count' => 'nullable|integer',
            'shareholders_count' => 'nullable|integer',
            'total_shares' => 'nullable|integer',
            'status' => 'required|in:Active,Rejected,Pending',
            'project_manager_id' => 'nullable|exists:users,id',
            'account_manager_id' => 'nullable|exists:users,id',
            'financial_manager_id' => 'nullable|exists:users,id',
            'executive_manager_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($data);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إضافة المشروع بنجاح.' : 'Project added successfully.');
    }

    public function updateProjectDetails(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'capital' => 'nullable|numeric',
            'funding_ask' => 'nullable|numeric',
            'investors_count' => 'nullable|integer',
            'shareholders_count' => 'nullable|integer',
            'total_shares' => 'nullable|integer',
            'status' => 'required|in:Active,Rejected,Pending',
            'project_manager_id' => 'nullable|exists:users,id',
            'account_manager_id' => 'nullable|exists:users,id',
            'financial_manager_id' => 'nullable|exists:users,id',
            'executive_manager_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|max:5120',
        ]);
        
        $project = Project::findOrFail($id);
        
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            if ($project->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($project->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);
        
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث بيانات المشروع بنجاح.' : 'Project details updated successfully.');
    }

    public function showProject($id)
    {
        $project = Project::with(['documents.user', 'reports.user', 'exitRequests.user', 'metrics', 'consultants'])->findOrFail($id);
        $users = \App\Models\User::all();
        return view('admin.projects.show', compact('project', 'users'));
    }

    public function storeProjectMetric(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:50',
            'suffix' => 'nullable|string|max:50',
        ]);
        $project = Project::findOrFail($id);
        $project->metrics()->create($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إضافة معدل النمو بنجاح.' : 'Metric added successfully.');
    }

    public function updateProjectMetric(Request $request, $id)
    {
        $request->validate(['label' => 'required|string', 'value' => 'required|string', 'prefix' => 'nullable|string', 'suffix' => 'nullable|string']);
        $metric = \App\Models\ProjectMetric::findOrFail($id);
        $metric->update($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث المؤشر بنجاح.' : 'Metric updated successfully.');
    }

    public function destroyProjectMetric($id)
    {
        \App\Models\ProjectMetric::findOrFail($id)->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المؤشر بنجاح.' : 'Metric deleted successfully.');
    }

    public function storeProjectConsultant(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'role' => 'nullable|string|max:255', 'description' => 'nullable|string']);
        $project = Project::findOrFail($id);
        $project->consultants()->create($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إضافة الاستشاري بنجاح.' : 'Consultant added successfully.');
    }

    public function updateProjectConsultant(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255', 'role' => 'nullable|string|max:255', 'description' => 'nullable|string']);
        $consultant = \App\Models\ProjectConsultant::findOrFail($id);
        $consultant->update($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث بيانات الاستشاري بنجاح.' : 'Consultant updated successfully.');
    }

    public function destroyProjectConsultant($id)
    {
        \App\Models\ProjectConsultant::findOrFail($id)->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف الاستشاري بنجاح.' : 'Consultant deleted successfully.');
    }

    public function storeProjectExitRequest(Request $request, $id)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'request_date' => 'required|date', 'type' => 'required|string', 'amount' => 'nullable|numeric', 'status' => 'required|string']);
        $project = Project::findOrFail($id);
        $project->exitRequests()->create($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إضافة طلب التخارج بنجاح.' : 'Exit request added successfully.');
    }

    public function updateProjectExitRequest(Request $request, $id)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'request_date' => 'required|date', 'type' => 'required|string', 'amount' => 'nullable|numeric', 'status' => 'required|string']);
        $exit = \App\Models\ExitRequest::findOrFail($id);
        $exit->update($request->all());
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث طلب التخارج بنجاح.' : 'Exit request updated successfully.');
    }

    public function destroyProjectExitRequest($id)
    {
        \App\Models\ExitRequest::findOrFail($id)->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف طلب التخارج بنجاح.' : 'Exit request deleted successfully.');
    }

    public function storeProjectDocument(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:255', 'type' => 'required|string|max:255', 'file' => 'required|file|max:10240', 'user_id' => 'nullable|exists:users,id']);
        $path = $request->file('file')->store('documents', 'public');
        \App\Models\Document::create(['project_id' => $id, 'user_id' => $request->user_id, 'title' => $request->title, 'type' => $request->type, 'file_path' => $path]);
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم رفع المستند بنجاح.' : 'Document uploaded successfully.');
    }

    public function destroyProjectDocument($id)
    {
        $doc = \App\Models\Document::findOrFail($id);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المستند بنجاح.' : 'Document deleted successfully.');
    }

    public function storeProjectReport(Request $request, $id)
    {
        $request->validate(['title' => 'required|string|max:255', 'period' => 'required|string|max:255', 'file' => 'required|file|max:10240', 'user_id' => 'nullable|exists:users,id']);
        $path = $request->file('file')->store('reports', 'public');
        \App\Models\Report::create(['project_id' => $id, 'user_id' => $request->user_id, 'title' => $request->title, 'period' => $request->period, 'file_path' => $path]);
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم رفع التقرير بنجاح.' : 'Report uploaded successfully.');
    }

    public function destroyProjectReport($id)
    {
        $rep = \App\Models\Report::findOrFail($id);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rep->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($rep->file_path);
        }
        $rep->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف التقرير بنجاح.' : 'Report deleted successfully.');
    }

    public function events()
    {
        $events = \App\Models\Event::latest()->get();
        return view('admin.events', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'time' => 'nullable|string',
            'location' => 'required|string',
            'status' => 'required|string',
            'access_type' => 'required|string',
            'attendees_count' => 'nullable|integer',
            'speaker_name' => 'nullable|string',
            'duration' => 'nullable|string',
            'speaker_profile' => 'nullable|image|max:5120',
            'invitation_card' => 'nullable|file|max:5120',
            'qr_code' => 'nullable|image|max:5120',
        ]);

        $data = $request->except(['speaker_profile', 'invitation_card', 'qr_code']);

        if ($request->hasFile('speaker_profile')) {
            $data['speaker_profile'] = $request->file('speaker_profile')->store('events/speakers', 'public');
        }
        if ($request->hasFile('invitation_card')) {
            $data['invitation_card'] = $request->file('invitation_card')->store('events/invitations', 'public');
        }
        if ($request->hasFile('qr_code')) {
            $data['qr_code'] = $request->file('qr_code')->store('events/qr', 'public');
        }

        \App\Models\Event::create($data);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إضافة الفعالية بنجاح.' : 'Event added successfully.');
    }

    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'time' => 'nullable|string',
            'location' => 'required|string',
            'status' => 'required|string',
            'access_type' => 'required|string',
            'attendees_count' => 'nullable|integer',
            'speaker_name' => 'nullable|string',
            'duration' => 'nullable|string',
            'speaker_profile' => 'nullable|image|max:5120',
            'invitation_card' => 'nullable|file|max:5120',
            'qr_code' => 'nullable|image|max:5120',
        ]);

        $event = \App\Models\Event::findOrFail($id);
        $data = $request->except(['speaker_profile', 'invitation_card', 'qr_code']);

        if ($request->hasFile('speaker_profile')) {
            if ($event->speaker_profile) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->speaker_profile);
            $data['speaker_profile'] = $request->file('speaker_profile')->store('events/speakers', 'public');
        }
        if ($request->hasFile('invitation_card')) {
            if ($event->invitation_card) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->invitation_card);
            $data['invitation_card'] = $request->file('invitation_card')->store('events/invitations', 'public');
        }
        if ($request->hasFile('qr_code')) {
            if ($event->qr_code) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->qr_code);
            $data['qr_code'] = $request->file('qr_code')->store('events/qr', 'public');
        }

        $event->update($data);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث الفعالية بنجاح.' : 'Event updated successfully.');
    }

    public function destroyEvent($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        if ($event->speaker_profile) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->speaker_profile);
        if ($event->invitation_card) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->invitation_card);
        if ($event->qr_code) \Illuminate\Support\Facades\Storage::disk('public')->delete($event->qr_code);
        $event->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف الفعالية بنجاح.' : 'Event deleted successfully.');
    }

    public function destroyProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المشروع بنجاح.' : 'Project deleted successfully.');
    }

    public function files()
    {
        $users = User::all();
        $projects = Project::all();
        $documents = \App\Models\Document::with(['project', 'user'])->latest()->get();
        $reports = \App\Models\Report::with(['project', 'user'])->latest()->get();
        
        $metrics = [
            'total_documents' => $documents->count(),
            'total_reports' => $reports->count(),
            'active_documents' => $documents->where('status', 'Active')->count(),
            'published_reports' => $reports->where('status', 'Published')->count(),
        ];

        return view('admin.files', compact('users', 'projects', 'documents', 'reports', 'metrics'));
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'file' => 'required|file|max:10240',
            'status' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        \App\Models\Document::create([
            'title' => $request->title,
            'type' => $request->type,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'file_path' => $path,
        ]);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم رفع المستند بنجاح.' : 'Document uploaded successfully.');
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'period' => 'required|string',
            'type' => 'required|string',
            'file' => 'required|file|max:10240',
            'status' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $path = $request->file('file')->store('reports', 'public');

        \App\Models\Report::create([
            'title' => $request->title,
            'period' => $request->period,
            'type' => $request->type,
            'status' => $request->status,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'file_path' => $path,
        ]);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم رفع التقرير بنجاح.' : 'Report uploaded successfully.');
    }

    public function showDocument($id)
    {
        $file = \App\Models\Document::with(['project', 'user'])->findOrFail($id);
        $file->is_document = true;
        return view('admin.file-show', compact('file'));
    }

    public function showReport($id)
    {
        $file = \App\Models\Report::with(['project', 'user'])->findOrFail($id);
        $file->is_document = false;
        return view('admin.file-show', compact('file'));
    }
    public function destroyDocument($id)
    {
        $document = \App\Models\Document::findOrFail($id);
        if ($document->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
        return redirect()->route('admin.files')->with('success', app()->getLocale() == 'ar' ? 'تم حذف المستند بنجاح.' : 'Document deleted successfully.');
    }

    public function destroyReport($id)
    {
        $report = \App\Models\Report::findOrFail($id);
        if ($report->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($report->file_path);
        }
        $report->delete();
        return redirect()->route('admin.files')->with('success', app()->getLocale() == 'ar' ? 'تم حذف التقرير بنجاح.' : 'Report deleted successfully.');
    }
}
