@extends('layouts.app')
@section('title', 'Sınav Düzenle – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[800px] mx-auto px-6 py-10">
  <div class="mb-8">
    <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Sınav Detay</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">SINAVI DÜZENLE</h1>
  </div>
  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 text-sm font-bold">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif
  <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST" class="space-y-5">
    @csrf @method('PUT')
    @include('admin.quizzes._form', ['quiz' => $quiz, 'courses' => $courses])
    <div class="flex gap-3 pt-4 border-t-[3px] border-[#0A0A0A]">
      <button type="submit"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">Kaydet ↗</button>
      <a href="{{ route('admin.quizzes.show', $quiz->id) }}"
        class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">İptal</a>
    </div>
  </form>
</div>
</div>
@endsection
