@extends('layouts.app')
@section('title', 'Kullanıcı Yönetimi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.dashboard') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Admin Panel</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">KULLANICI YÖNETİMİ</h1>
    </div>
    <a href="{{ route('admin.users.progress') }}"
      class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-6 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors"
      style="box-shadow:4px 4px 0 #0A0A0A">📊 İlerleme Raporu</a>
  </div>

  {{-- Filtre --}}
  <form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="İsim veya e-posta ara..."
      class="border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors w-72">
    <select name="role" class="border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors">
      <option value="">Tüm Roller</option>
      <option value="STUDENT" {{ request('role')==='STUDENT'?'selected':'' }}>Öğrenci</option>
      <option value="INSTRUCTOR" {{ request('role')==='INSTRUCTOR'?'selected':'' }}>Eğitmen</option>
      <option value="ADMIN" {{ request('role')==='ADMIN'?'selected':'' }}>Admin</option>
      <option value="SUPERVISOR" {{ request('role')==='SUPERVISOR'?'selected':'' }}>Süpervizör</option>
    </select>
    <button type="submit" class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors">Ara</button>
    @if(request()->hasAny(['q','role']))
    <a href="{{ route('admin.users.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest self-center">× Temizle</a>
    @endif
  </form>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-3">
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">KULLANICI</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">ROL</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">DEPARTMAN</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">KAYIT</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">SERTİFİKA</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">KAYIT TARİHİ</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">İŞLEM</span>
    </div>

    @forelse($users as $user)
    @php
      $roleColors = ['ADMIN'=>'#FF2D2D','INSTRUCTOR'=>'#0047FF','STUDENT'=>'#0A0A0A','SUPERVISOR'=>'#FF3CAC'];
      $roleLabels = ['ADMIN'=>'YÖNETİCİ','INSTRUCTOR'=>'EĞİTMEN','STUDENT'=>'ÖĞRENCİ','SUPERVISOR'=>'SÜPERVIZÖR'];
    @endphp
    <div class="grid grid-cols-12 gap-3 px-5 py-4 border-t border-[#ddd] hover:bg-[#fafaf5] items-center">
      <div class="col-span-3">
        <p class="font-black text-sm text-[#0A0A0A]">{{ $user->name ?? '—' }}</p>
        <p class="font-mono text-[10px] text-[#888] mt-0.5 truncate">{{ $user->email }}</p>
      </div>
      <div class="col-span-2">
        <span class="font-black text-[9px] uppercase px-2 py-0.5 text-white"
          style="background:{{ $roleColors[$user->role] ?? '#0A0A0A' }}">
          {{ $roleLabels[$user->role] ?? $user->role }}
        </span>
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $user->department ?? '—' }}</span>
      </div>
      <div class="col-span-1">
        <span class="font-mono text-[10px] text-[#888]">{{ $user->enrollments_count }}</span>
      </div>
      <div class="col-span-1">
        <span class="font-mono text-[10px] text-[#888]">{{ $user->certificates_count }}</span>
      </div>
      <div class="col-span-1">
        <span class="font-mono text-[10px] text-[#888]">{{ $user->created_at?->format('d.m.Y') }}</span>
      </div>
      <div class="col-span-2 flex flex-wrap gap-2">
        <a href="{{ route('admin.users.show', $user->id) }}"
          class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Detay</a>
        <a href="{{ route('admin.users.edit', $user->id) }}"
          class="font-mono text-[10px] text-[#888] hover:underline uppercase tracking-widest">Düzenle</a>
      </div>
    </div>
    @empty
    <div class="px-5 py-12 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase">Kullanıcı bulunamadı</p>
    </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $users->links() }}</div>

</div>
</div>
@endsection
