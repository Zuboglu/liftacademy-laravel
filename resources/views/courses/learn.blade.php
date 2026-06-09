@extends('layouts.app')
@section('title', '{{ $course->title }} – LiftAcademy')
@section('content')

<div class="bg-[#0A0A0A] min-h-screen text-[#F5F0E8]">
  <div class="max-w-[1400px] mx-auto px-6 py-8">
    <div class="flex items-center gap-3 mb-8">
      <a href="{{ route('courses.show', $course->slug) }}" class="text-mono-sm text-[#444] hover:text-[#FFE000]">← {{ $course->title }}</a>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">
      <div>
        <div class="bg-[#111] border-[3px] border-[#333] aspect-video flex items-center justify-center mb-6">
          <span class="text-6xl">▶</span>
        </div>
        <h1 class="text-2xl font-black uppercase tracking-tight mb-4">{{ $course->title }}</h1>
        <p class="text-[#888] font-medium leading-relaxed">{{ $course->description }}</p>
      </div>
      <div class="space-y-3">
        <h2 class="font-black uppercase tracking-tight text-[#F5F0E8] mb-4">DERSLER</h2>
        @foreach($course->sections as $section)
        <div class="border-[2px] border-[#333]">
          <div class="px-4 py-3 bg-[#1a1a1a] font-bold text-xs uppercase tracking-widest text-[#888]">{{ $section->title }}</div>
          @foreach($section->lessons as $lesson)
          <div class="px-4 py-3 border-t border-[#222] flex items-center gap-3 hover:bg-[#1a1a1a] cursor-pointer" id="lesson-{{ $lesson->id }}">
            <span class="text-[#888] text-sm">{{ $lesson->type === 'VIDEO' ? '▶' : '📝' }}</span>
            <span class="text-sm font-medium text-[#888]">{{ $lesson->title }}</span>
          </div>
          @endforeach
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
