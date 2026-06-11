@extends('layouts.app')
@section('title', 'Sertifika – LiftAcademy')

@push('head')
<style>
@media print {
  @page { size: A4 landscape; margin: 0; }

  /* Navbar, chat, ekran içeriği gizle */
  header, #chat-widget, [id^="hook"] { display: none !important; }
  main > *:not(#cert-print-wrapper)                   { display: none !important; }

  /* Print wrapper tam sayfa */
  #cert-print-wrapper {
    display: block !important;
    position: fixed;
    inset: 0;
    width: 100vw;
    height: 100vh;
    background: #fff;
    padding: 0;
    margin: 0;
  }

  /* Sertifikanın kendisi — tam sığdır */
  #cert-printable {
    width: 100%;
    height: 100%;
    box-shadow: none !important;
    border-width: 0 !important;
    aspect-ratio: unset !important;
  }
}
</style>
@endpush

@section('content')

{{-- Ekran görünümü: arka plan + geri dön + yazdır butonu --}}
<div class="bg-[#F5F0E8] min-h-screen py-10 px-4 print:hidden">
  <div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
      <a href="{{ route('certificates.index') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Sertifikalarım</a>
      <button onclick="window.print()"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-6 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">🖨 Yazdır / PDF Kaydet</button>
    </div>

    {{-- Sertifika önizlemesi (ekranda göster) --}}
    <div class="overflow-x-auto">
      <div style="min-width:800px">
        @include('certificates._cert', ['cert' => $cert])
      </div>
    </div>
  </div>
</div>

{{-- Yazdırma wrapper'ı — ekranda gizli, print'te tam sayfa --}}
<div id="cert-print-wrapper" style="display:none">
  @include('certificates._cert', ['cert' => $cert, 'printId' => 'cert-printable'])
</div>

@endsection
