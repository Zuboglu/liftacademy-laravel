@extends('layouts.app')
@section('title', 'Sertifikalarım – LiftAcademy')
@section('content')

<div class="bg-[#F5F0E8] min-h-screen">
  <section class="bg-[#0A0A0A] border-b-[3px] border-[#FFE000]">
    <div class="max-w-[1400px] mx-auto px-6 py-16">
      <span class="tag-yellow mb-4 inline-block">SERTİFİKALARIM</span>
      <h1 class="text-display text-[#F5F0E8] leading-none">SERTİFİKA<br>MERKEZİ</h1>
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
        <span class="tag-lime">AKTİF</span>
      </div>
    </div>
    @empty
    <div class="text-center py-24 border-[3px] border-dashed border-[#ccc]">
      <p class="text-5xl mb-4">🏆</p>
      <p class="font-black text-2xl uppercase tracking-tight mb-2">Henüz sertifikan yok</p>
      <p class="text-[#888] mb-6">Kurs tamamla, otomatik sertifika kazan.</p>
      <a href="{{ route('courses.index') }}" class="btn-brut">Kurslara Bak ↗</a>
    </div>
    @endforelse
  </div>
</div>
@endsection
