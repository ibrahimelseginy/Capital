@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'مشاريعي' : 'My Projects')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-between items-center mb-6">
        <div>
            <h1 class="text-h2">{{ app()->getLocale() == 'ar' ? 'مشاريعي' : 'My Projects' }}</h1>
            <p class="text-secondary">{{ app()->getLocale() == 'ar' ? 'إدارة تفاصيل مشاريعك وتحديثاتها' : 'Manage your project details and updates' }}</p>
        </div>
        <button class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'رفع تحديث جديد' : 'Upload New Update' }}</button>
    </div>

    <div class="grid-2">
        @foreach($projects as $project)
        <div class="card p-4">
            <h3 class="text-h4 mb-2">{{ $project->title }}</h3>
            <p class="text-secondary mb-4">{{ $project->description }}</p>
            <div class="d-flex justify-between items-center pt-4" style="border-top: 1px solid var(--border-subtle);">
                <span class="badge badge-success">{{ $project->status }}</span>
                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary btn-sm">{{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
