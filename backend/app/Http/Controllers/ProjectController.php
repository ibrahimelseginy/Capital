<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            $project = new Project(['id' => $id, 'title' => 'Sample Project ' . $id, 'description' => 'This is a sample project details view.', 'budget' => 500000, 'status' => 'Active']);
        }
        
        $signedNdas = session('signed_ndas', []);
        
        if (!in_array($id, $signedNdas)) {
            if (count($signedNdas) >= 5) {
                return view('projects.limit_reached');
            }
            return view('projects.nda_sign', compact('project'));
        }

        return view('projects.show', compact('project'));
    }

    public function signNda(Request $request, $id)
    {
        $signedNdas = session('signed_ndas', []);
        
        if (!in_array($id, $signedNdas)) {
            if (count($signedNdas) >= 5) {
                return redirect()->route('dashboard.projects');
            }
            $signedNdas[] = $id;
            session(['signed_ndas' => $signedNdas]);
        }
        
        return redirect()->route('projects.show', $id);
    }
}
