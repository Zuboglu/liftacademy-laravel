@extends('layouts.app')
@section('title', 'Sertifika Düzenle – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[900px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.certificates.show', $certificate->id) }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sertifika Detay</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">SERTİFİKA DÜZENLE</h1>
    <p class="font-mono text-[10px] text-[#888] mt-1">{{ $certificate->cert_number }}</p>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 font-bold text-sm">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.certificates.update', $certificate->id) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')
    @include('admin.certificates._form')
    <div class="flex gap-3 pt-2">
      <button type="submit"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">Kaydet ↗</button>
      <a href="{{ route('admin.certificates.show', $certificate->id) }}"
        class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">İptal</a>
    </div>
  </form>

</div>
</div>
@endsection
