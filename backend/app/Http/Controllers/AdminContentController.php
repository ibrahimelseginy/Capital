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
            'type' => 'required|in:image,video,text',
            'section' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,avi|max:50000',
            'text_content' => 'nullable|string',
        ]);

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('content', 'public');
        } elseif ($request->type === 'text') {
            $filePath = $request->text_content;
        }

        Content::create([
            'title' => $request->title,
            'type' => $request->type,
            'section' => $request->section,
            'file_path' => $filePath,
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
        
        if ($content->type !== 'text' && $content->file_path) {
            Storage::disk('public')->delete($content->file_path);
        }

        $content->delete();

        return redirect()->back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المحتوى بنجاح' : 'Content deleted successfully');
    }

    public function apiIndex()
    {
        $contents = Content::all();
        // format them maybe by section
        $grouped = $contents->groupBy('section');
        return response()->json($grouped);
    }
}
