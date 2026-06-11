@extends('layouts.app')
@section('title', __('ui.my_certificates') . ' – LiftAcademy')
@section('content')

<div id="hook-left" aria-hidden="true" style="position:fixed;top:-400px;left:75px;width:80px;z-index:20;pointer-events:none;opacity:1;">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>
<div id="hook-right" aria-hidden="true" style="position:fixed;top:-400px;right:75px;width:80px;z-index:20;pointer-events:none;opacity:1;transform:scaleX(-1);">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>

<div class="min-h-screen relative z-10">
  <section class="bg-[#0A0A0A] border-b-[3px] border-[#FFE000]">
    <div class="max-w-[1400px] mx-auto px-6 py-16">
      <span class="tag-yellow mb-4 inline-block">{{ __('ui.my_certificates') }}</span>
      <h1 class="text-display text-[#F5F0E8] leading-none">{{ __('ui.cert_center') }}</h1>
    </div>
  </section>
  <div class="max-w-[1400px] mx-auto px-6 py-12">
    @php
      $certs = \App\Models\Certificate::where('user_id', auth()->id())->with('course')->latest()->get();
    @endphp
    @forelse($certs as $cert)
    <div class="border-[3px] border-[#0A0A0A] mb-4 p-6 hover-lift-sm bg-white">
      <div class="flex items-center gap-5">
        <div class="w-16 h-16 bg-[#FFE000] border-[3px] border-[#0A0A0A] flex items-center justify-center text-3xl shrink-0">🏆</div>
        <div class="flex-1">
          <h3 class="font-black uppercase tracking-tight">{{ $cert->course->title ?? '–' }}</h3>
          <p class="text-mono-sm text-[#888] mt-1">{{ $cert->cert_number }}</p>
          <p class="text-xs font-medium text-[#888] mt-1">{{ $cert->created_at->format('d.m.Y') }}</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
          <span class="tag-lime">{{ __('ui.cert_active') }}</span>
          <a href="{{ route('certificates.show', $cert->id) }}"
             class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors"
             style="box-shadow:3px 3px 0 #FFE000">🖨 Yazdır</a>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center py-24 border-[3px] border-dashed border-[#ccc]">
      <p class="text-5xl mb-4">🏆</p>
      <p class="font-black text-2xl uppercase tracking-tight mb-2">{{ __('ui.no_certificates') }}</p>
      <p class="text-[#888] mb-6">{{ __('ui.no_certificates_sub') }}</p>
      <a href="{{ route('courses.index') }}" class="btn-brut">{{ __('ui.see_courses') }} ↗</a>
    </div>
    @endforelse
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
