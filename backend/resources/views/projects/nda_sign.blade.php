@extends('layouts.app')

@section('title', app()->getLocale() == 'ar' ? 'توقيع اتفاقية السرية' : 'Sign NDA')

@section('content')
<style>
/* Premium styling for NDA Signature gate */
.nda-gate-container {
    max-width: 800px;
    margin: 0 auto;
    background: var(--bg-surface);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    box-shadow: var(--shadow-xl);
}
.nda-paper {
    background: var(--bg-primary);
    border: 1px solid var(--border-default);
    border-radius: var(--radius-lg);
    padding: var(--space-6);
    height: 350px;
    overflow-y: auto;
    margin-bottom: var(--space-6);
    font-size: var(--text-body-sm);
    line-height: 1.8;
}
.signature-area {
    background: var(--bg-primary);
    border: 2px dashed var(--border-default);
    border-radius: var(--radius-lg);
    height: 180px;
    position: relative;
    cursor: crosshair;
    touch-action: none;
}
.signature-area:hover {
    border-color: var(--color-primary);
}
#signature-canvas {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 10;
}
</style>

<div class="fade-in">
  <div class="d-flex justify-between items-center mb-6 max-w-[800px] mx-auto">
    <a href="{{ url('/dashboard/projects') }}" class="btn btn-ghost" style="width:40px;height:40px;padding:0;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--bg-secondary)">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </a>
    <div style="text-align:end">
        <h2 class="text-h4" style="font-weight:var(--weight-bold)">{{ $project->title }}</h2>
        <p class="text-secondary">{{ app()->getLocale() == 'ar' ? 'يتطلب توقيع اتفاقية سرية (NDA) قبل العرض' : 'Requires NDA signature before viewing' }}</p>
    </div>
  </div>

  <div class="nda-gate-container">
    <div style="text-align:center; margin-bottom:var(--space-6)">
        <div style="width:64px;height:64px;border-radius:50%;background:var(--color-primary-light);color:var(--color-primary);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-4);font-size:24px">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <h3 class="text-h4" style="font-weight:var(--weight-bold)">{{ app()->getLocale() == 'ar' ? 'اتفاقية عدم الإفصاح (NDA)' : 'Non-Disclosure Agreement' }}</h3>
        <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'الرجاء قراءة الوثيقة بعناية وتوقيعها لتتمكن من الوصول لبيانات ومعلومات المشروع التفصيلية.' : 'Please read carefully and sign to access the confidential details of this project.' }}</p>
    </div>

    <div class="nda-paper">
        <h4 style="font-weight:bold; margin-bottom:1rem; text-align:center">{{ app()->getLocale() == 'ar' ? 'اتفاقية سرية المعلومات (NDA)' : 'CONFIDENTIALITY AGREEMENT (NDA)' }}</h4>
        @if(app()->getLocale() == 'ar')
            <p><strong>الطرف الأول:</strong> شركة سفن تك كابيتال (المفصح).</p>
            <p><strong>الطرف الثاني:</strong> المستثمر (المتلقي).</p>
            <p>يتفق الطرفان على ما يلي:</p>
            <ol style="margin-inline-start:20px; margin-top:10px">
                <li>يتعهد المتلقي بالحفاظ على السرية التامة لجميع المعلومات والبيانات الخاصة بمشروع <strong>{{ $project->title }}</strong> التي يتم الاطلاع عليها من خلال المنصة.</li>
                <li>لا يحق للمتلقي استنساخ، أو تصوير، أو مشاركة، أو طباعة أي من المستندات المتعلقة بالمشروع مع أطراف ثالثة دون موافقة كتابية مسبقة.</li>
                <li>تستخدم المعلومات السرية حصرياً لغرض تقييم الفرصة الاستثمارية ولن تُستخدم في أي غرض آخر منافس أو يلحق ضرراً بالطرف الأول.</li>
                <li>يستمر الالتزام بالسرية لمدة (5) سنوات من تاريخ التوقيع الإلكتروني أدناه حتى وإن لم يتم الاستثمار في المشروع.</li>
            </ol>
            <p style="margin-top:10px">بتوقيعي أدناه، أقر بأني قرأت وفهمت كافة بنود الاتفاقية وموافق عليها بالكامل.</p>
        @else
            <p><strong>Party A:</strong> Seven Tech Capital (Disclosing Party).</p>
            <p><strong>Party B:</strong> The Investor (Receiving Party).</p>
            <p>The parties agree to the following:</p>
            <ol style="margin-inline-start:20px; margin-top:10px">
                <li>The Receiving Party agrees to maintain the strict confidentiality of all information and data regarding <strong>{{ $project->title }}</strong> accessed through this platform.</li>
                <li>The Receiving Party shall not reproduce, photograph, share, or print any project-related documents with third parties without prior written consent.</li>
                <li>Confidential Information shall be used exclusively for the purpose of evaluating the investment opportunity and shall not be used for any competing purpose.</li>
                <li>The confidentiality obligations shall survive for a period of five (5) years from the date of the electronic signature below, regardless of investment outcome.</li>
            </ol>
            <p style="margin-top:10px">By signing below, I acknowledge that I have read, understood, and agree to all terms of this Agreement.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('projects.sign_nda', $project->id) }}" id="nda-form">
        @csrf
        <div class="mb-4">
            <label style="font-weight:var(--weight-bold); display:block; margin-bottom:var(--space-2)">{{ app()->getLocale() == 'ar' ? 'توقيع المستثمر (الرقمي)' : 'Investor Signature (Digital)' }}</label>
            <div class="signature-area" id="sig-area">
                <div id="nda-sig-prompt" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:var(--text-tertiary); pointer-events:none">
                    {{ app()->getLocale() == 'ar' ? 'وقع هنا باستخدام الفأرة أو اللمس' : 'Sign here using mouse or touch' }}
                </div>
                <canvas id="signature-canvas"></canvas>
            </div>
            <div style="text-align:end; margin-top:8px">
                <button type="button" class="btn btn-ghost btn-sm" onclick="clearSignature()">{{ app()->getLocale() == 'ar' ? 'مسح التوقيع' : 'Clear Signature' }}</button>
            </div>
        </div>

        <div style="display:flex; justify-content:center">
            <button type="button" class="btn btn-primary" id="nda-submit-btn" style="padding:var(--space-3) var(--space-8); border-radius:var(--radius-lg); opacity:0.6; cursor:not-allowed" disabled onclick="submitForm()">
                {{ app()->getLocale() == 'ar' ? 'اعتماد التوقيع وفتح المشروع' : 'Confirm Signature & Unlock Project' }}
            </button>
        </div>
    </form>
  </div>
</div>

<script>
    let hasSigned = false;
    let isDrawing = false;
    let canvasCtx = null;
    let lastX = 0;
    let lastY = 0;

    document.addEventListener('DOMContentLoaded', () => {
        initSignaturePad();
    });

    function initSignaturePad() {
        const canvas = document.getElementById('signature-canvas');
        if(!canvas) return;
        
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        
        canvasCtx = canvas.getContext('2d');
        canvasCtx.strokeStyle = document.documentElement.getAttribute('data-theme') === 'dark' ? '#fff' : '#000';
        canvasCtx.lineWidth = 3;
        canvasCtx.lineCap = 'round';
        canvasCtx.lineJoin = 'round';

        const drawStart = (e) => {
            isDrawing = true;
            document.getElementById('nda-sig-prompt').style.display = 'none';
            const pos = getPos(canvas, e);
            lastX = pos.x;
            lastY = pos.y;
        };

        const drawMove = (e) => {
            if(!isDrawing) return;
            e.preventDefault(); 
            const pos = getPos(canvas, e);
            canvasCtx.beginPath();
            canvasCtx.moveTo(lastX, lastY);
            canvasCtx.lineTo(pos.x, pos.y);
            canvasCtx.stroke();
            lastX = pos.x;
            lastY = pos.y;
            hasSigned = true;
            
            const submitBtn = document.getElementById('nda-submit-btn');
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        };

        const drawEnd = () => {
            isDrawing = false;
        };

        canvas.addEventListener('mousedown', drawStart);
        canvas.addEventListener('mousemove', drawMove);
        canvas.addEventListener('mouseup', drawEnd);
        canvas.addEventListener('mouseout', drawEnd);
        canvas.addEventListener('touchstart', drawStart, {passive: false});
        canvas.addEventListener('touchmove', drawMove, {passive: false});
        canvas.addEventListener('touchend', drawEnd);
    }

    function getPos(canvas, e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function clearSignature() {
        const canvas = document.getElementById('signature-canvas');
        if(!canvas || !canvasCtx) return;
        canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
        hasSigned = false;
        document.getElementById('nda-sig-prompt').style.display = 'block';
        const submitBtn = document.getElementById('nda-submit-btn');
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
        submitBtn.style.cursor = 'not-allowed';
    }

    function submitForm() {
        if(!hasSigned) return;
        
        const btn = document.getElementById('nda-submit-btn');
        btn.disabled = true;
        const isAr = "{{ app()->getLocale() == 'ar' }}" === "1";
        btn.innerHTML = `<span class="btn-spinner"></span> ${isAr ? 'جاري الفتح...' : 'Unlocking...'}`;
        
        setTimeout(() => {
            document.getElementById('nda-form').submit();
        }, 800);
    }
</script>
@endsection
