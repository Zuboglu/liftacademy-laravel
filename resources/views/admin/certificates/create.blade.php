@extends('layouts.app')
@section('title', 'Yeni Sertifika – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[900px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.certificates.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sertifika Listesi</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">YENİ SERTİFİKA OLUŞTUR</h1>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 font-bold text-sm">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.certificates.store') }}" method="POST" class="space-y-5">
    @csrf
    @include('admin.certificates._form', ['certificate' => null])
    <div class="flex gap-3 pt-2">
      <button type="submit"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">Sertifika Oluştur ↗</button>
      <a href="{{ route('admin.certificates.index') }}"
        class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">İptal</a>
    </div>
  </form>

</div>
</div>
@endsection
