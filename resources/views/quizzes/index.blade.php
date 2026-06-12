@extends('layouts.app')
@section('title', __('ui.quizzes') . ' – LiftAcademy')
@section('content')

<div id="hook-left" aria-hidden="true" style="position:fixed;top:-400px;left:75px;width:80px;z-index:0;pointer-events:none;opacity:1;">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>
<div id="hook-right" aria-hidden="true" style="position:fixed;top:-400px;right:75px;width:80px;z-index:0;pointer-events:none;opacity:1;transform:scaleX(-1);">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>

<div class="min-h-screen relative z-10">

  {{-- Hero --}}
  <div class="bg-[#0A0A0A] border-b-[3px] border-[#FFE000] px-6 py-12">
    <div class="max-w-[1400px] mx-auto">
      <div class="flex items-center gap-3 mb-8">
        <span class="tag-yellow text-[10px]">{{ $quizzes->total() }} {{ __('ui.quiz_count') }}</span>
        <span class="tag-lime text-[10px]">{{ __('ui.quiz_system') }}</span>
      </div>
      <h1 class="font-black text-[#F5F0E8] uppercase tracking-tight leading-none mb-4"
        style="font-size:clamp(2.5rem,5vw,5rem);letter-spacing:-0.04em">
        {{ __('ui.competency_exams') }}
      </h1>
      <p class="text-[#555] font-medium max-w-xl text-sm leading-relaxed">
        {{ __('ui.quiz_intro') }}
      </p>
    </div>
  </div>

  <div class="max-w-[1400px] mx-auto px-6 py-10">

    @if(session('success'))
    <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-4 mb-6 font-bold text-sm uppercase tracking-wide">
      ✓ {{ session('success') }}
    </div>
    @endif

    @if($quizzes->isEmpty())
    <div class="text-center py-20 border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
      <p class="font-black text-xl uppercase tracking-tight text-[#0A0A0A] mb-2">{{ __('ui.no_quizzes') }}</p>
      <a href="{{ route('courses.index') }}" class="btn-brut-dark text-xs py-2.5">{{ __('ui.see_courses') }} ↗</a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($quizzes as $quiz)
      @php
        $attempt = null;
        $bestScore = null;
        $passed = false;
        if(auth()->check()) {
          $attempts = \App\Models\QuizAttempt::where('user_id', auth()->id())->where('quiz_id', $quiz->id)->get();
          $bestScore = $attempts->max('score');
          $passed = $attempts->where('passed', true)->count() > 0;
        }
      @endphp
      <a href="{{ route('quizzes.show', $quiz->id) }}"
        class="group border-[3px] border-[#0A0A0A] bg-[#F5F0E8] flex flex-col overflow-hidden hover-lift-sm">
        {{-- Header --}}
        <div class="{{ $passed ? 'bg-[#CCFF00] text-[#0A0A0A]' : 'bg-[#0A0A0A] text-[#F5F0E8]' }} px-5 py-4 border-b-[3px] border-[#0A0A0A] flex items-center justify-between">
          <span class="font-mono text-[10px] uppercase tracking-widest opacity-60">
            {{ $quiz->course->title ?? __('ui.general') }}
          </span>
          @if($passed)
          <span class="font-black text-[10px] uppercase tracking-widest">✓ {{ __('ui.passed') }}</span>
          @elseif($bestScore !== null)
          <span class="font-black text-[10px] text-[#FFE000] uppercase">%{{ $bestScore }}</span>
          @else
          <span class="font-mono text-[10px] opacity-40">{{ __('ui.new') }}</span>
          @endif
        </div>

        {{-- İçerik --}}
        <div class="p-5 flex-1 flex flex-col gap-3">
          <h3 class="font-black text-sm uppercase tracking-tight text-[#0A0A0A] leading-snug group-hover:text-[#0047FF] transition-colors">
            {{ $quiz->title }}
          </h3>
          @if($quiz->description)
          <p class="text-mono-sm text-[#888] leading-relaxed line-clamp-2">{{ $quiz->description }}</p>
          @endif
          <div class="flex flex-wrap gap-3 mt-auto pt-3 border-t-[2px] border-[#eee]">
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->questions_count }} {{ __('ui.questions') }}</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ __('ui.passing') }}: %{{ $quiz->passing_score }}</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->attempts }} {{ __('ui.attempts') }}</span>
            </div>
            @if($quiz->time_limit)
            <div class="flex items-center gap-1.5">
              <span class="text-mono-sm text-[#888]">{{ $quiz->time_limit }} {{ __('ui.minutes') }}</span>
            </div>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
    <div class="mt-10">{{ $quizzes->links() }}</div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  var L=document.getElementById('hook-left'),R=document.getElementById('hook-right');
  if(!L||!R) return;
  var cur=0,tgt=0,raf=null;
  window.addEventListener('scroll',function(){
    tgt=Math.min(window.scrollY*0.25,400);
    if(!raf) raf=requestAnimationFrame(upd);
  },{passive:true});
  function upd(){
    cur+=(tgt-cur)*0.12;
    var y=cur.toFixed(1);
    L.style.transform='translateY('+y+'px)';
    R.style.transform='scaleX(-1) translateY('+y+'px)';
    if(Math.abs(tgt-cur)>0.1){raf=requestAnimationFrame(upd);}
    else{cur=tgt;raf=null;}
  }
})();
</script>
@endpush
