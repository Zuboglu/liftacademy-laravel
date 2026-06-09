@extends('layouts.app')
@section('title', 'Kurs Düzenle – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[800px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.courses.show', $course->id) }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Kurs Detay</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">KURSU DÜZENLE</h1>
    <p class="text-mono-sm text-[#888] mt-1">{{ $course->title }}</p>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 text-sm font-bold">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="space-y-5">
    @csrf
    @method('PUT')
    @include('admin.courses._form', ['course' => $course, 'instructors' => $instructors])
    <div class="flex gap-3 pt-4 border-t-[3px] border-[#0A0A0A]">
      <button type="submit" class="btn-brut text-sm px-8 py-4">Kaydet ↗</button>
      <a href="{{ route('admin.courses.show', $course->id) }}" class="btn-brut-dark text-sm px-8 py-4">İptal</a>
    </div>
  </form>

</div>
</div>
@endsection
