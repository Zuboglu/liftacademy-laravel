@extends('layouts.app')
@section('title', 'Kurs Yönetimi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8">
    <div>
      <a href="{{ route('admin.dashboard') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A] transition-colors">← Admin Panel</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">KURS YÖNETİMİ</h1>
    </div>
    <a href="{{ route('admin.courses.create') }}"
      class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-6 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
      style="box-shadow:4px 4px 0 #0A0A0A">+ Yeni Kurs</a>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-bold text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-4">
      <span class="col-span-4 font-mono text-[10px] text-[#555] uppercase tracking-widest">KURS</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">KATEGORİ</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">DURUM</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">KAYIT</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">İŞLEM</span>
    </div>
    @forelse($courses as $c)
    <div class="grid grid-cols-12 gap-4 px-5 py-4 border-t border-[#eee] hover:bg-[#fafaf5] items-center">
      <div class="col-span-4">
        <p class="font-black text-sm text-[#0A0A0A] leading-snug">{{ $c->title }}</p>
        <p class="font-mono text-[10px] text-[#888] mt-0.5">{{ $c->instructor->name ?? '—' }} · {{ $c->sections_count }} bölüm</p>
      </div>
      <div class="col-span-2">
        <span class="tag-black text-[9px]">{{ $c->category }}</span>
      </div>
      <div class="col-span-2 flex flex-wrap gap-1">
        @if($c->published)<span class="tag-lime text-[9px]">YAYINDA</span>@else<span class="tag-red text-[9px]">TASLAK</span>@endif
        @if($c->is_mandatory)<span class="tag-yellow text-[9px]">ZORUNLU</span>@endif
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $c->enrollments_count }} öğrenci</span>
      </div>
      <div class="col-span-2 flex flex-wrap gap-2">
        <a href="{{ route('admin.courses.show', $c->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Yönet</a>
        <a href="{{ route('admin.courses.edit', $c->id) }}" class="font-mono text-[10px] text-[#888] hover:underline uppercase tracking-widest">Düzenle</a>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase">Henüz kurs yok</p>
    </div>
    @endforelse
  </div>
  <div class="mt-6">{{ $courses->links() }}</div>

</div>
</div>
@endsection
