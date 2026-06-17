<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;

class AdminContentController extends Controller
{
    public function index()
    {
        $contents = Content::latest()->get();
        return view('admin.content', compact('contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video,text,youtube',
            'section' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:50000',
            'youtube_urls' => 'nullable|array',
            'youtube_urls.*' => 'nullable|url',
            'text_content' => 'nullable|string',
        ]);

        $filePaths = [];

        if (in_array($request->type, ['image', 'video'])) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePaths[] = $file->store('content', 'public');
                }
            }
        } elseif ($request->type === 'youtube') {
            if ($request->has('youtube_urls')) {
                // Filter out empty URLs
                $filePaths = array_filter($request->youtube_urls, function($val) {
                    return !empty($val);
                });
                // Reindex array
                $filePaths = array_values($filePaths);
            }
        } elseif ($request->type === 'text') {
            $filePaths = [$request->text_content]; // Keep it as an array to maintain consistency
        }

        Content::create([
            'title' => $request->title,
            'type' => $request->type,
            'section' => $request->section,
            'file_path' => $filePaths, // now cast to array JSON natively
        ]);

        return redirect()->back()->with('success', app()->getLocale() == 'ar' ? 'تمت إضافة المحتوى بنجاح' : 'Content added successfully');
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'section' => 'nullable|string',
        ]);

        $content->update([
            'title' => $request->title,
            'section' => $request->section,
        ]);

        return redirect()->back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث المحتوى بنجاح' : 'Content updated successfully');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        
        // Handle deletion of actual files
        if (in_array($content->type, ['image', 'video']) && is_array($content->file_path)) {
            foreach ($content->file_path as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $content->delete();

        return redirect()->back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المحتوى بنجاح' : 'Content deleted successfully');
    }

    public function apiIndex()
    {
        $contents = Content::all();
        $grouped = $contents->groupBy('section');
        return response()->json($grouped);
    }
}
