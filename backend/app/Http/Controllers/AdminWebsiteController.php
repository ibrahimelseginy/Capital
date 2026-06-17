<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Article;
use App\Models\JobPosting;
use App\Models\Metric;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class AdminWebsiteController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        $articles = Article::orderBy('created_at', 'desc')->get();
        $jobs = JobPosting::orderBy('created_at', 'desc')->get();
        $metrics = Metric::orderBy('order_index', 'asc')->get();
        $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
        
        return view('admin.website.index', compact('events', 'articles', 'jobs', 'metrics', 'testimonials'));
    }

    public function storeArticle(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'excerpt' => 'required|string',
            'author_name' => 'required|string',
            'author_meta' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        $article = new Article($data);
        $article->published_at = now();
        
        if ($request->hasFile('image')) {
            $article->image_path = $request->file('image')->store('website', 'public');
        }
        
        $article->save();
        return back()->with('success', 'Article added successfully.');
    }

    public function storeJob(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'location' => 'required|string',
            'type' => 'required|string',
            'department' => 'required|string',
            'apply_link' => 'nullable|url'
        ]);
        
        JobPosting::create($data);
        return back()->with('success', 'Job added successfully.');
    }

    public function storeMetric(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string',
            'value' => 'required|numeric',
            'prefix' => 'nullable|string',
            'suffix' => 'nullable|string'
        ]);
        
        Metric::create($data);
        return back()->with('success', 'Metric added successfully.');
    }

    public function storeTestimonial(Request $request)
    {
        $data = $request->validate([
            'quote' => 'required|string',
            'author_name' => 'required|string',
            'author_role' => 'required|string',
            'image' => 'nullable|image'
        ]);

        $testimonial = new Testimonial($data);
        
        if ($request->hasFile('image')) {
            $testimonial->image_path = $request->file('image')->store('website', 'public');
        }
        
        $testimonial->save();
        return back()->with('success', 'Testimonial added successfully.');
    }

    public function destroy($type, $id)
    {
        if ($type === 'article') Article::findOrFail($id)->delete();
        if ($type === 'job') JobPosting::findOrFail($id)->delete();
        if ($type === 'metric') Metric::findOrFail($id)->delete();
        if ($type === 'testimonial') Testimonial::findOrFail($id)->delete();
        
        return back()->with('success', 'Item deleted successfully.');
    }
}
