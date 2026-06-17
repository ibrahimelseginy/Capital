<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Article;
use App\Models\JobPosting;
use App\Models\Metric;
use App\Models\Testimonial;

class WebsiteApiController extends Controller
{
    public function home()
    {
        $events = Event::where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        $articles = Article::orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        $jobs = JobPosting::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $metrics = Metric::orderBy('order_index', 'asc')
            ->get();

        $testimonials = Testimonial::orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'events' => $events,
                'articles' => $articles,
                'jobs' => $jobs,
                'metrics' => $metrics,
                'testimonials' => $testimonials
            ]
        ]);
    }
}
