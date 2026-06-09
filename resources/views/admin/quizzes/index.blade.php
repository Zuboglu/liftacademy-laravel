@extends('layouts.app')
@section('title', 'Sınav Yönetimi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8">
    <div>
      <a href="{{ route('admin.dashboard') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Admin Panel</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">SINAV YÖNETİMİ</h1>
    </div>
    <a href="{{ route('admin.quizzes.create') }}" class="btn-brut text-xs py-2.5 px-6">+ Yeni Sınav</a>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-bold text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-4">
      <span class="col-span-4 font-mono text-[10px] text-[#555] uppercase tracking-widest">SINAV ADI</span>
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">BAĞLI KURS</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">SORU / GEÇER</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">HAK</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">İŞLEM</span>
    </div>
    @forelse($quizzes as $q)
    <div class="grid grid-cols-12 gap-4 px-5 py-4 border-t border-[#eee] hover:bg-[#fafaf5] items-center">
      <div class="col-span-4">
        <p class="font-black text-sm text-[#0A0A0A]">{{ $q->title }}</p>
        @if($q->description)<p class="font-mono text-[10px] text-[#888] mt-0.5 truncate">{{ Str::limit($q->description, 50) }}</p>@endif
      </div>
      <div class="col-span-3">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->course->title ?? '— Genel —' }}</span>
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->questions_count }} soru / %{{ $q->passing_score }}</span>
      </div>
      <div class="col-span-1">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->attempts }}x</span>
      </div>
      <div class="col-span-2 flex gap-2">
        <a href="{{ route('admin.quizzes.show', $q->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase">Yönet</a>
        <a href="{{ route('admin.quizzes.edit', $q->id) }}" class="font-mono text-[10px] text-[#888] hover:underline uppercase">Düzenle</a>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase">Henüz sınav yok</p>
    </div>
    @endforelse
  </div>
  <div class="mt-6">{{ $quizzes->links() }}</div>

</div>
</div>
@endsection
