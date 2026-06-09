@extends('layouts.app')
@section('title', ($user->name ?? $user->email) . ' – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1100px] mx-auto px-6 py-10">

  <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.users.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Kullanıcı Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1">{{ $user->name ?? $user->email }}</h1>
      <div class="flex flex-wrap gap-2 mt-2">
        @php
          $roleColors = ['ADMIN'=>'#FF2D2D','INSTRUCTOR'=>'#0047FF','STUDENT'=>'#0A0A0A','SUPERVISOR'=>'#FF3CAC'];
          $roleLabels = ['ADMIN'=>'YÖNETİCİ','INSTRUCTOR'=>'EĞİTMEN','STUDENT'=>'ÖĞRENCİ','SUPERVISOR'=>'SÜPERVIZÖR'];
        @endphp
        <span class="font-black text-[9px] uppercase px-3 py-1 text-white"
          style="background:{{ $roleColors[$user->role] ?? '#0A0A0A' }}">
          {{ $roleLabels[$user->role] ?? $user->role }}
        </span>
        <span class="font-mono text-[10px] text-[#888]">{{ $user->email }}</span>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 shrink-0">
      <a href="{{ route('admin.users.edit', $user->id) }}"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">Düzenle</a>
      <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
        onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">
        @csrf @method('DELETE')
        <button type="submit"
          class="bg-white text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#FF2D2D] hover:bg-[#FF2D2D] hover:text-white transition-colors"
          style="box-shadow:3px 3px 0 #FF2D2D">Sil</button>
      </form>
    </div>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Sol: Profil bilgileri --}}
    <div class="space-y-4">
      <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
        <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">PROFİL</p>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between gap-2">
            <span class="text-[#888] shrink-0">E-posta</span>
            <span class="font-bold text-right break-all">{{ $user->email }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-[#888]">Telefon</span>
            <span class="font-bold">{{ $user->phone ?? '—' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-[#888]">Departman</span>
            <span class="font-bold">{{ $user->department ?? '—' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-[#888]">Personel No</span>
            <span class="font-bold">{{ $user->employee_id ?? '—' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-[#888]">Kayıt Tarihi</span>
            <span class="font-bold">{{ $user->created_at?->format('d.m.Y') }}</span>
          </div>
        </div>
      </div>

      {{-- İstatistikler --}}
      <div class="grid grid-cols-2 gap-3">
        <div class="border-[3px] border-[#0A0A0A] bg-[#FFE000] p-4 text-center">
          <p class="font-black text-2xl">{{ $user->enrollments->count() }}</p>
          <p class="font-mono text-[9px] uppercase tracking-widest text-[#0A0A0A] mt-1">Kurs Kaydı</p>
        </div>
        <div class="border-[3px] border-[#0A0A0A] bg-[#CCFF00] p-4 text-center">
          <p class="font-black text-2xl">{{ $user->certificates->count() }}</p>
          <p class="font-mono text-[9px] uppercase tracking-widest text-[#0A0A0A] mt-1">Sertifika</p>
        </div>
      </div>
    </div>

    {{-- Sağ: Kurslar ve ilerleme --}}
    <div class="lg:col-span-2 space-y-4">

      {{-- Kayıtlı kurslar ve ilerleme --}}
      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3">
          <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Kayıtlı Kurslar & İlerleme</p>
        </div>
        @forelse($enrollmentsWithProgress as $enrollment)
        <div class="px-5 py-4 border-t border-[#eee]">
          <div class="flex items-center justify-between gap-3 mb-2">
            <div class="flex-1 min-w-0">
              <p class="font-black text-sm text-[#0A0A0A] truncate">{{ $enrollment->course->title ?? '—' }}</p>
              <p class="font-mono text-[10px] text-[#888] mt-0.5">
                {{ $enrollment->progress_detail['completed'] }}/{{ $enrollment->progress_detail['total'] }} ders ·
                <span class="{{ $enrollment->status === 'COMPLETED' ? 'text-[#00a000]' : 'text-[#888]' }} font-bold">
                  {{ $enrollment->status }}
                </span>
              </p>
            </div>
            <span class="font-black text-lg {{ $enrollment->progress_detail['percent'] === 100 ? 'text-[#00a000]' : 'text-[#0A0A0A]' }} shrink-0">
              %{{ $enrollment->progress_detail['percent'] }}
            </span>
          </div>
          <div class="h-2 bg-[#eee] w-full">
            <div class="h-full bg-[#CCFF00] transition-all duration-500"
              style="width:{{ $enrollment->progress_detail['percent'] }}%"></div>
          </div>
        </div>
        @empty
        <div class="px-5 py-6 text-center">
          <p class="font-mono text-[10px] text-[#888] uppercase">Henüz kurs kaydı yok</p>
        </div>
        @endforelse
      </div>

      {{-- Sertifikalar --}}
      @if($user->certificates->isNotEmpty())
      <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3">
          <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Sertifikalar</p>
        </div>
        @foreach($user->certificates as $cert)
        @php $sc = match($cert->status){ 'ACTIVE'=>'#CCFF00','EXPIRED'=>'#FF2D2D', default=>'#888' }; @endphp
        <div class="px-5 py-3 border-t border-[#eee] flex items-center justify-between">
          <div>
            <p class="font-bold text-sm text-[#0A0A0A]">{{ $cert->cert_number }}</p>
            <p class="font-mono text-[10px] text-[#888] mt-0.5">{{ $cert->course->title ?? 'Genel' }} · {{ $cert->level }}</p>
          </div>
          <div class="flex items-center gap-3">
            <span class="font-black text-[9px] uppercase px-2 py-0.5 border text-[#0A0A0A]"
              style="background:{{ $sc }};border-color:{{ $sc }}">{{ $cert->status }}</span>
            <a href="{{ route('admin.certificates.show', $cert->id) }}"
              class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase">Görüntüle</a>
          </div>
        </div>
        @endforeach
      </div>
      @endif

    </div>
  </div>

</div>
</div>
@endsection
