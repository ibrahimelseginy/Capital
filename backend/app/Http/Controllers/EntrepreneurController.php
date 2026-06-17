<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class EntrepreneurController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            // For testing if auth is bypassed
            $projects = Project::latest()->limit(5)->get();
            $metrics = [
                'total_projects' => $projects->count(),
                'total_funding' => $projects->sum('budget'),
                'pending_reports' => 1
            ];
            return view('entrepreneur.dashboard', compact('projects', 'metrics'));
        }

        $projects = Project::latest()->limit(5)->get();
        $metrics = [
            'total_projects' => Project::count(),
            'total_funding' => Project::sum('budget'),
            'pending_reports' => \App\Models\Report::where('status', 'Draft')->count()
        ];
        
        return view('entrepreneur.dashboard', compact('projects', 'metrics'));
    }

    public function myProjects()
    {
        $projects = Project::limit(2)->get();
        return view('entrepreneur.projects', compact('projects'));
    }

    public function funding()
    {
        return view('entrepreneur.funding');
    }
}
