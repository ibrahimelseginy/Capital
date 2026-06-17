<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $metrics = [
            'total_users' => User::count(),
            'total_investors' => User::where('role', 'investor')->count(),
            'total_entrepreneurs' => User::where('role', 'entrepreneur')->count(),
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', 'Active')->count(),
            'total_portfolio_value' => Project::sum('budget') ?? 0,
            'pending_ndas' => \App\Models\Nda::where('status', 'pending')->count(),
            'pending_exit_requests' => \App\Models\ExitRequest::where('status', 'Under Review')->count(),
        ];

        $recent_users = User::orderBy('created_at', 'desc')->take(5)->get();
        $recent_projects = Project::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'recent_users', 'recent_projects'));
    }

    public function projects()
    {
        $projects = Project::all();
        return view('admin.projects', compact('projects'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:investor,entrepreneur,admin'
        ]);
        
        $user = User::findOrFail($id);
        $user->role = $request->role;
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

    public function updateProjectDetails(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric'
        ]);
        $project = Project::findOrFail($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->budget = $request->budget;
        $project->save();
        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث بيانات المشروع بنجاح.' : 'Project details updated successfully.');
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
}
