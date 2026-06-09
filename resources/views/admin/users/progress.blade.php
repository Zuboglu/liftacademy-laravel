@extends('layouts.app')
@section('title', 'Öğrenci İlerleme Takibi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.users.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Kullanıcı Listesi</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">ÖĞRENCİ İLERLEME TAKİBİ</h1>
    </div>
  </div>

  {{-- Filtre --}}
  <form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="course_id" class="border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors">
      <option value="">Tüm Kurslar</option>
      @foreach($courses as $c)
      <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
      @endforeach
    </select>
    <button type="submit" class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors">Filtrele</button>
    @if(request('course_id'))
    <a href="{{ route('admin.users.progress') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest self-center">× Temizle</a>
    @endif
  </form>

  {{-- Özet istatistikler --}}
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
      $totalEnrollments = $enrollments->total();
      $completed = $enrollments->getCollection()->filter(fn($e) => $e->status === 'COMPLETED')->count();
      $active    = $enrollments->getCollection()->filter(fn($e) => $e->status === 'ACTIVE')->count();
      $avgPct    = $enrollments->getCollection()->avg('progress_pct');
    @endphp
    <div class="border-[3px] border-[#0A0A0A] bg-[#FFE000] p-5 text-center">
      <p class="font-black text-3xl text-[#0A0A0A]">{{ $totalEnrollments }}</p>
      <p class="font-mono text-[9px] uppercase tracking-widest text-[#0A0A0A] mt-1">Toplam Kayıt</p>
    </div>
    <div class="border-[3px] border-[#0A0A0A] bg-[#CCFF00] p-5 text-center">
      <p class="font-black text-3xl text-[#0A0A0A]">{{ $active }}</p>
      <p class="font-mono text-[9px] uppercase tracking-widest text-[#0A0A0A] mt-1">Aktif Öğrenci</p>
    </div>
    <div class="border-[3px] border-[#0A0A0A] bg-[#0A0A0A] p-5 text-center">
      <p class="font-black text-3xl text-[#FFE000]">{{ $completed }}</p>
      <p class="font-mono text-[9px] uppercase tracking-widest text-[#FFE000] mt-1">Tamamlayan</p>
    </div>
    <div class="border-[3px] border-[#0A0A0A] bg-white p-5 text-center">
      <p class="font-black text-3xl text-[#0A0A0A]">%{{ round($avgPct ?? 0) }}</p>
      <p class="font-mono text-[9px] uppercase tracking-widest text-[#888] mt-1">Ortalama İlerleme</p>
    </div>
  </div>

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-3">
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">ÖĞRENCİ</span>
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">KURS</span>
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">İLERLEME</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">DURUM</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">KAYIT TARİHİ</span>
    </div>

    @forelse($enrollments as $enrollment)
    @php
      $pct = $enrollment->progress_pct;
      $barColor = $pct >= 100 ? '#CCFF00' : ($pct >= 50 ? '#FFE000' : '#FF6B35');
    @endphp
    <div class="grid grid-cols-12 gap-3 px-5 py-4 border-t border-[#ddd] hover:bg-[#fafaf5] items-center">
      <div class="col-span-3">
        <p class="font-black text-sm text-[#0A0A0A]">{{ $enrollment->user->name ?? '—' }}</p>
        <p class="font-mono text-[10px] text-[#888] mt-0.5 truncate">{{ $enrollment->user->email ?? '' }}</p>
      </div>
      <div class="col-span-3">
        <p class="font-bold text-sm text-[#0A0A0A] truncate">{{ $enrollment->course->title ?? '—' }}</p>
      </div>
      <div class="col-span-3">
        <div class="flex items-center gap-2">
          <div class="flex-1 h-2 bg-[#eee]">
            <div class="h-full transition-all" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
          </div>
          <span class="font-black text-xs shrink-0">%{{ $pct }}</span>
        </div>
        <p class="font-mono text-[9px] text-[#888] mt-1">{{ $enrollment->progress_done }}/{{ $enrollment->progress_total }} ders</p>
      </div>
      <div class="col-span-1">
        @php
          $stColors = ['ACTIVE'=>'#FFE000','COMPLETED'=>'#CCFF00','INACTIVE'=>'#888'];
          $stColor  = $stColors[$enrollment->status] ?? '#888';
        @endphp
        <span class="font-black text-[9px] uppercase px-2 py-0.5 border text-[#0A0A0A]"
          style="background:{{ $stColor }};border-color:{{ $stColor }}">
          {{ $enrollment->status }}
        </span>
      </div>
      <div class="col-span-2">
        <p class="font-mono text-[10px] text-[#888]">{{ $enrollment->created_at?->format('d.m.Y') }}</p>
        <a href="{{ route('admin.users.show', $enrollment->user_id) }}"
          class="font-mono text-[9px] text-[#0047FF] hover:underline uppercase tracking-widest">Profil →</a>
      </div>
    </div>
    @empty
    <div class="px-5 py-12 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase">Kayıt bulunamadı</p>
    </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $enrollments->links() }}</div>

</div>
</div>
@endsection
