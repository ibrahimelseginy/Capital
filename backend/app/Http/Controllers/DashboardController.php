<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class DashboardController extends Controller
{
    public function index()
    {
        $activeProjectsCount = \App\Models\Project::where('status', 'active')->count() ?: 5; 
        return view('dashboard.investor', compact('activeProjectsCount'));
    }

    public function projects()
    {
        $projects = \App\Models\Project::withCount('reports')->get();
        return view('dashboard.projects', compact('projects'));
    }

    public function reports(Request $request) { 
        $projectId = $request->query('project_id');
        $query = \App\Models\Report::with('project');
        if ($projectId) {
            $query->where('project_id', $projectId);
        }
        $reports = $query->get();
        $projects = \App\Models\Project::all();
        return view('dashboard.reports', compact('reports', 'projects', 'projectId')); 
    }
    
    public function documents() { 
        $documents = \App\Models\Document::with('project')->get();
        return view('dashboard.documents', compact('documents')); 
    }
    
    public function ndas() { 
        $ndas = \App\Models\Nda::with('project')->get();
        return view('dashboard.ndas', compact('ndas')); 
    }
    
    public function exitRequests() { 
        $requests = \App\Models\ExitRequest::with('project')->get();
        return view('dashboard.exit-requests', compact('requests')); 
    }
    
    public function exitRecords() { 
        $records = \App\Models\ExitRecord::with('project')->get();
        return view('dashboard.exit-records', compact('records')); 
    }
    
    public function consultations() { 
        $consultations = \App\Models\Consultation::all();
        return view('dashboard.consultations', compact('consultations')); 
    }
    
    public function events() { 
        $events = \App\Models\Event::all();
        return view('dashboard.events', compact('events')); 
    }
    
    public function profile() { return view('dashboard.profile'); }
    
    public function adminRequests() {
        return view('dashboard.admin-requests');
    }
}
