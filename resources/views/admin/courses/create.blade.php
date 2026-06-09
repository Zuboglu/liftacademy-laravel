@extends('layouts.app')
@section('title', 'Yeni Kurs – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[800px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.courses.index') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Kurs Listesi</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">YENİ KURS OLUŞTUR</h1>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 text-sm font-bold">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-5">
    @csrf
    @include('admin.courses._form', ['course' => null, 'instructors' => $instructors])
    <div class="flex gap-3 pt-4 border-t-[3px] border-[#0A0A0A]">
      <button type="submit" class="btn-brut text-sm px-8 py-4">Kurs Oluştur ↗</button>
      <a href="{{ route('admin.courses.index') }}" class="btn-brut-dark text-sm px-8 py-4">İptal</a>
    </div>
  </form>

</div>
</div>
@endsection
