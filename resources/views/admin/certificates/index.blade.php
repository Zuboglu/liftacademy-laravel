@extends('layouts.app')
@section('title', 'Sertifika Yönetimi – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1400px] mx-auto px-6 py-10">

  <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.dashboard') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Admin Panel</a>
      <h1 class="font-black text-3xl uppercase tracking-tight mt-1">SERTİFİKA YÖNETİMİ</h1>
    </div>
    <a href="{{ route('admin.certificates.create') }}"
      class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-6 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
      style="box-shadow:4px 4px 0 #0A0A0A">+ Yeni Sertifika</a>
  </div>

  {{-- Filtre --}}
  <form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="İsim veya sertifika no ara..."
      class="border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors w-72">
    <select name="status" class="border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors">
      <option value="">Tüm Durumlar</option>
      <option value="ACTIVE" {{ request('status')==='ACTIVE'?'selected':'' }}>Aktif</option>
      <option value="EXPIRED" {{ request('status')==='EXPIRED'?'selected':'' }}>Süresi Dolmuş</option>
      <option value="REVOKED" {{ request('status')==='REVOKED'?'selected':'' }}>İptal Edilmiş</option>
    </select>
    <button type="submit" class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors">Ara</button>
    @if(request()->hasAny(['q','status']))
    <a href="{{ route('admin.certificates.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest self-center">× Temizle</a>
    @endif
  </form>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 grid grid-cols-12 gap-3">
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">ALICI</span>
      <span class="col-span-3 font-mono text-[10px] text-[#555] uppercase tracking-widest">SERTİFİKA NO</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">KURS</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">SEVİYE</span>
      <span class="col-span-1 font-mono text-[10px] text-[#555] uppercase tracking-widest">DURUM</span>
      <span class="col-span-2 font-mono text-[10px] text-[#555] uppercase tracking-widest">İŞLEM</span>
    </div>

    @forelse($certificates as $cert)
    <div class="grid grid-cols-12 gap-3 px-5 py-4 border-t border-[#ddd] hover:bg-[#fafaf5] items-center">
      <div class="col-span-3">
        <p class="font-black text-sm text-[#0A0A0A]">{{ $cert->recipient_name }}</p>
        <p class="font-mono text-[10px] text-[#888] mt-0.5">{{ $cert->user->email ?? '—' }}</p>
      </div>
      <div class="col-span-3">
        <p class="font-mono text-[11px] text-[#0A0A0A] font-bold tracking-widest">{{ $cert->cert_number }}</p>
        <p class="font-mono text-[10px] text-[#888] mt-0.5">
          {{ $cert->issued_at ? $cert->issued_at->format('d.m.Y') : ($cert->created_at?->format('d.m.Y') ?? '—') }}
          @if($cert->expires_at) · Bitiş: {{ $cert->expires_at->format('d.m.Y') }}@endif
        </p>
      </div>
      <div class="col-span-2">
        <span class="font-mono text-[10px] text-[#888]">{{ $cert->course->title ?? '— Genel —' }}</span>
      </div>
      <div class="col-span-1">
        <span class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-[9px] uppercase px-2 py-0.5">{{ $cert->level }}</span>
      </div>
      <div class="col-span-1">
        @php $sc = match($cert->status){ 'ACTIVE'=>'#CCFF00','EXPIRED'=>'#FF2D2D','REVOKED'=>'#888', default=>'#888' }; @endphp
        <span class="font-black text-[9px] uppercase px-2 py-0.5 border text-[#0A0A0A]"
          style="background:{{ $sc }};border-color:{{ $sc }}">{{ $cert->status }}</span>
      </div>
      <div class="col-span-2 flex flex-wrap gap-2">
        <a href="{{ route('admin.certificates.show', $cert->id) }}"
          class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Görüntüle</a>
        <a href="{{ route('admin.certificates.edit', $cert->id) }}"
          class="font-mono text-[10px] text-[#888] hover:underline uppercase tracking-widest">Düzenle</a>
      </div>
    </div>
    @empty
    <div class="px-5 py-12 text-center border-t border-[#eee]">
      <p class="font-bold text-[#888] text-sm uppercase mb-4">Henüz sertifika yok</p>
      <a href="{{ route('admin.certificates.create') }}"
        class="inline-flex bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase px-6 py-3 border-[3px] border-[#0A0A0A]">İlk Sertifikayı Oluştur</a>
    </div>
    @endforelse
  </div>

  <div class="mt-6">{{ $certificates->links() }}</div>

</div>
</div>
@endsection
