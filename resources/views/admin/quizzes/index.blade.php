@extends('layouts.app')
@section('title', 'Sınav Yönetimi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.dashboard') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Admin Panel</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">SINAV YÖNETİMİ</h1>
    </div>
    <a href="{{ route('admin.quizzes.create') }}"
      class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-6 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
      style="box-shadow:4px 4px 0 #0A0A0A">+ Yeni Sınav</a>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-4">
      <span class="col-span-4 font-mono text-[10px] text-[#555] uppercase tracking-widest">SINAV ADI</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">BAĞLI KURS</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">SORU / GEÇER</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">HAK</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">DURUM</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">İŞLEM</span>
    </div>

    @forelse($quizzes as $q)
    <div class="grid grid-cols-12 gap-4 px-5 py-4 border-t border-[#ddd] hover:bg-[#fafaf5] items-center">
      <div class="col-span-4">
        <p class="font-black text-sm text-[#0A0A0A]">{{ $q->title }}</p>
        @if($q->description)
        <p class="font-mono text-[10px] text-[#888] mt-0.5 truncate">{{ Str::limit($q->description, 50) }}</p>
        @endif
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->course->title ?? '— Genel —' }}</span>
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->questions_count }} soru / %{{ $q->passing_score }}</span>
      </div>
      <div class="col-span-1">
        <span class="font-mono text-[10px] text-[#888]">{{ $q->attempts }}x</span>
      </div>
      <div class="col-span-1">
        @if($q->is_active)
        <span class="bg-[#CCFF00] text-[#0A0A0A] font-black text-[9px] uppercase px-2 py-0.5 border border-[#0A0A0A]">AKTİF</span>
        @else
        <span class="bg-[#FF2D2D] text-white font-black text-[9px] uppercase px-2 py-0.5">PASİF</span>
        @endif
      </div>
      <div class="col-span-2 flex flex-wrap gap-2">
        <a href="{{ route('admin.quizzes.show', $q->id) }}"
          class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Yönet</a>
        <a href="{{ route('admin.quizzes.edit', $q->id) }}"
          class="font-mono text-[10px] text-[#888] hover:underline uppercase tracking-widest">Düzenle</a>
        <form action="{{ route('admin.quizzes.toggle', $q->id) }}" method="POST" class="inline">
          @csrf
          <button type="submit"
            class="font-mono text-[10px] uppercase tracking-widest {{ $q->is_active ? 'text-[#FF2D2D]' : 'text-[#00a000]' }} hover:underline">
            {{ $q->is_active ? 'Kapat' : 'Aç' }}
          </button>
        </form>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase">Henüz sınav yok</p>
      <a href="{{ route('admin.quizzes.create') }}" class="inline-block mt-4 bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase px-6 py-3 border-[3px] border-[#0A0A0A]">İlk Sınavı Oluştur</a>
    </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $quizzes->links() }}</div>

</div>
</div>
@endsection
