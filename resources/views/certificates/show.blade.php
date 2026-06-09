@extends('layouts.app')
@section('title', 'Sertifika – LiftAcademy')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen flex items-center justify-center p-8">
  <div class="w-full max-w-2xl border-[4px] border-[#0A0A0A] p-12 text-center" style="box-shadow:10px 10px 0 #FFE000">
    <p class="text-mono-sm text-[#888] mb-2">SERTİFİKA</p>
    <div class="text-4xl mb-6">🏆</div>
    <h1 class="text-3xl font-black uppercase tracking-tight mb-4">BAŞARI SERTİFİKASI</h1>
    <p class="text-[#888] mb-8">Bu sayfa yapım aşamasındadır.</p>
    <a href="{{ route('certificates.index') }}" class="btn-brut">← Geri Dön</a>
  </div>
</div>
@endsection
