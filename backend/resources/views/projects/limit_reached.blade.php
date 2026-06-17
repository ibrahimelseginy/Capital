@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'الحد الأقصى للمشاريع' : 'Project Limit Reached')

@section('content')
<div class="fade-in" style="min-height: 70vh; display: flex; align-items: center; justify-content: center;">
    <div style="max-width: 500px; text-align: center; background: var(--bg-surface); padding: var(--space-8); border-radius: var(--radius-xl); border: 1px solid var(--border-default); box-shadow: var(--shadow-xl);">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(231, 76, 60, 0.1); color: var(--color-error); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-6); font-size: 32px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        
        <h2 class="text-h3 mb-4" style="font-weight: var(--weight-bold); color: var(--text-primary);">
            {{ app()->getLocale() == 'ar' ? 'تم الوصول للحد الأقصى' : 'Limit Reached' }}
        </h2>
        
        <p class="text-secondary mb-6" style="line-height: 1.6;">
            {{ app()->getLocale() == 'ar' ? 'لقد استنفدت الحد الأقصى للمشاريع المسموح لك بفتحها وتوقيع اتفاقية السرية الخاصة بها (5 مشاريع). يُرجى التواصل مع الدعم الفني أو مدير الحساب لزيادة صلاحياتك.' : 'You have reached the maximum allowed limit of unlocking and signing NDAs for 5 projects. Please contact support or your account manager to extend your access.' }}
        </p>

        <a href="{{ url('/dashboard/projects') }}" class="btn btn-primary" style="border-radius: var(--radius-lg); padding: var(--space-3) var(--space-6);">
            {{ app()->getLocale() == 'ar' ? 'العودة للمشاريع' : 'Back to Projects' }}
        </a>
    </div>
</div>
@endsection
