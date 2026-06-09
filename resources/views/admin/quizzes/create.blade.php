@extends('layouts.app')
@section('title', 'Yeni Sınav – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[800px] mx-auto px-6 py-10">
  <div class="mb-8">
    <a href="{{ route('admin.quizzes.index') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Sınav Listesi</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">YENİ SINAV OLUŞTUR</h1>
  </div>
  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 text-sm font-bold">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif
  <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-5">
    @csrf
    @include('admin.quizzes._form', ['quiz' => null, 'courses' => $courses])
    <div class="flex gap-3 pt-4 border-t-[3px] border-[#0A0A0A]">
      <button type="submit" class="btn-brut text-sm px-8 py-4">Sınav Oluştur ↗</button>
      <a href="{{ route('admin.quizzes.index') }}" class="btn-brut-dark text-sm px-8 py-4">İptal</a>
    </div>
  </form>
</div>
</div>
@endsection
