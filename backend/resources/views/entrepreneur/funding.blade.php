@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'التمويل' : 'Funding')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-between items-center mb-6">
        <div>
            <h1 class="text-h2">{{ app()->getLocale() == 'ar' ? 'جولات التمويل' : 'Funding Rounds' }}</h1>
            <p class="text-secondary">{{ app()->getLocale() == 'ar' ? 'متابعة جولات التمويل والتزامات المستثمرين' : 'Track your funding rounds and investor commitments' }}</p>
        </div>
    </div>

    <div class="card p-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent-gold)" stroke-width="2" class="mb-4 mx-auto"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <h3 class="text-h4 mb-2">{{ app()->getLocale() == 'ar' ? 'لا توجد جولات تمويل نشطة' : 'No Active Funding Rounds' }}</h3>
        <p class="text-secondary mb-4">{{ app()->getLocale() == 'ar' ? 'يمكنك بدء جولة تمويل جديدة عند اكتمال تقييم المشروع.' : 'You can start a new funding round once project evaluation is complete.' }}</p>
        <button class="btn btn-primary">{{ app()->getLocale() == 'ar' ? 'طلب تقييم' : 'Request Evaluation' }}</button>
    </div>
</div>
@endsection
