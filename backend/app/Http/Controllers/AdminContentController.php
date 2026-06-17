<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class AdminContentController extends Controller
{
    public function index()
    {
        $contents = Content::orderBy('section')->get();
        return view('admin.content', compact('contents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'nullable|string|max:255',
            'key' => 'required|string|unique:contents,key|max:255',
            'type' => 'required|in:text,textarea,image,video',
            'value' => 'nullable|string',
            'file' => 'nullable|file|max:51200', // max 50MB for video
        ]);

        $data = $request->only(['section', 'key', 'type', 'value']);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('content', 'public');
        }

        Content::create($data);

        return back()->with('success', app()->getLocale() == 'ar' ? 'تمت إضافة المحتوى بنجاح.' : 'Content added successfully.');
    }

    public function update(Request $request, $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'section' => 'nullable|string|max:255',
            'type' => 'required|in:text,textarea,image,video',
            'value' => 'nullable|string',
            'file' => 'nullable|file|max:51200',
        ]);

        $content->section = $request->section;
        $content->type = $request->type;
        $content->value = $request->value;

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($content->file_path && \Storage::disk('public')->exists($content->file_path)) {
                \Storage::disk('public')->delete($content->file_path);
            }
            $content->file_path = $request->file('file')->store('content', 'public');
        }

        $content->save();

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم تحديث المحتوى بنجاح.' : 'Content updated successfully.');
    }

    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        
        if ($content->file_path && \Storage::disk('public')->exists($content->file_path)) {
            \Storage::disk('public')->delete($content->file_path);
        }
        
        $content->delete();

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم حذف المحتوى بنجاح.' : 'Content deleted successfully.');
    }

    public function apiIndex()
    {
        $contents = Content::all()->mapWithKeys(function($item) {
            $val = $item->type === 'image' || $item->type === 'video' 
                ? ($item->file_path ? asset('storage/' . $item->file_path) : null) 
                : $item->value;
                
            return [$item->key => [
                'type' => $item->type,
                'value' => $val,
                'section' => $item->section
            ]];
        });

        return response()->json($contents);
    }
}
