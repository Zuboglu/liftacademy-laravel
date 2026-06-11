@extends('layouts.app')
@section('title', $certificate->cert_number . ' – Sertifika')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1100px] mx-auto px-6 py-10">

  {{-- Başlık --}}
  <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
    <div>
      <a href="{{ route('admin.certificates.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sertifika Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1">{{ $certificate->cert_number }}</h1>
      <div class="flex flex-wrap gap-2 mt-2">
        @php $sc = match($certificate->status){ 'ACTIVE'=>'#CCFF00','EXPIRED'=>'#FF2D2D','REVOKED'=>'#888', default=>'#888' }; @endphp
        <span class="font-black text-[9px] uppercase px-3 py-1 border-[2px] text-[#0A0A0A]"
          style="background:{{ $sc }};border-color:{{ $sc }}">{{ $certificate->status }}</span>
        <span class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-[9px] uppercase px-3 py-1">{{ $certificate->level }}</span>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 shrink-0">
      <button onclick="window.print()"
        class="bg-white text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-white transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">🖨 Yazdır</button>
      <a href="{{ route('admin.certificates.edit', $certificate->id) }}"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">Düzenle</a>
      <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST"
        onsubmit="return confirm('Bu sertifikayı silmek istediğinizden emin misiniz?')">
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

  {{-- GÖRSEl SERTİFİKA --}}
  <div id="cert-visual" class="mb-8">
    <div class="relative overflow-hidden" style="background:linear-gradient(135deg,#0A1628 0%,#0A2A5C 40%,#0A1628 100%);border:6px solid #1a3a6e;box-shadow:0 20px 60px rgba(0,0,0,0.4),inset 0 1px 0 rgba(255,255,255,0.1);min-height:420px;padding:0">

      {{-- Dekoratif arka plan deseni --}}
      <div class="absolute inset-0 opacity-[0.04]" style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px"></div>

      {{-- Köşe süslemeleri --}}
      <div class="absolute top-4 left-4 w-12 h-12 border-l-4 border-t-4 border-[#FFE000] opacity-80"></div>
      <div class="absolute top-4 right-4 w-12 h-12 border-r-4 border-t-4 border-[#FFE000] opacity-80"></div>
      <div class="absolute bottom-4 left-4 w-12 h-12 border-l-4 border-b-4 border-[#FFE000] opacity-80"></div>
      <div class="absolute bottom-4 right-4 w-12 h-12 border-r-4 border-b-4 border-[#FFE000] opacity-80"></div>

      {{-- Üst şerit --}}
      <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#FFE000,#FFA500,#FFE000)"></div>
      <div class="absolute bottom-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#FFE000,#FFA500,#FFE000)"></div>

      <div class="relative z-10 px-14 py-10 text-white text-center">

        {{-- Kuruluş logosu --}}
        <div class="flex justify-center mb-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 flex items-center justify-center font-black text-xl border-[3px]"
              style="background:#FFE000;color:#0A0A0A;border-color:#FFE000">L</div>
            <div class="text-left">
              <p class="font-black text-base uppercase tracking-[0.15em] text-[#FFE000]">LiftAcademy</p>
              <p class="font-mono text-[9px] uppercase tracking-[0.2em] text-[#8aadff] mt-0.5">Vinç Operatörü Eğitim Platformu</p>
            </div>
          </div>
        </div>

        {{-- Başlık --}}
        <div class="mb-6">
          <p class="font-mono text-[10px] uppercase tracking-[0.3em] text-[#8aadff] mb-2">Bu belge teslim eder</p>
          <div style="background:rgba(255,224,0,0.08);border:1px solid rgba(255,224,0,0.25);padding:1rem 2rem;display:inline-block;margin-bottom:0.75rem">
            <p class="font-black text-3xl tracking-tight" style="color:#FFE000;text-shadow:0 0 30px rgba(255,224,0,0.4)">
              {{ $certificate->recipient_name }}
            </p>
          </div>
          @if($certificate->employee_id || $certificate->department)
          <p class="font-mono text-[10px] text-[#8aadff] uppercase tracking-widest">
            {{ collect([$certificate->employee_id, $certificate->department, $certificate->site])->filter()->implode(' · ') }}
          </p>
          @endif
        </div>

        {{-- Sertifika türü --}}
        <div class="mb-6">
          <p class="font-mono text-[10px] uppercase tracking-[0.3em] text-[#8aadff] mb-2">aşağıdaki eğitimi başarıyla tamamlamıştır</p>
          <p class="font-black text-xl uppercase tracking-wider text-white mb-1">
            {{ $certificate->course->title ?? 'Vinç Operatörlüğü Temel Eğitimi' }}
          </p>
          @if($certificate->training_hours)
          <p class="font-mono text-[10px] text-[#8aadff] uppercase tracking-widest">{{ $certificate->training_hours }} Saatlik Eğitim Programı</p>
          @endif
        </div>

        {{-- Seviye rozeti --}}
        <div class="flex justify-center mb-6">
          <div class="px-8 py-2 font-black text-sm uppercase tracking-[0.2em]"
            style="background:rgba(255,224,0,0.15);border:2px solid #FFE000;color:#FFE000;letter-spacing:0.3em">
            {{ $certificate->level }} SEVİYE
          </div>
        </div>

        {{-- Alt bilgiler --}}
        <div class="grid grid-cols-3 gap-6 mt-4">
          <div class="text-center">
            <div style="border-top:1px solid rgba(255,255,255,0.2);padding-top:0.75rem">
              <p class="font-black text-sm text-white">
                {{ $certificate->issued_at ? $certificate->issued_at->format('d.m.Y') : $certificate->created_at?->format('d.m.Y') }}
              </p>
              <p class="font-mono text-[9px] uppercase tracking-widest text-[#8aadff] mt-1">Düzenleme Tarihi</p>
            </div>
          </div>
          <div class="text-center">
            <div style="border-top:1px solid rgba(255,255,255,0.2);padding-top:0.75rem">
              <p class="font-black text-sm text-[#FFE000] tracking-widest">{{ $certificate->cert_number }}</p>
              <p class="font-mono text-[9px] uppercase tracking-widest text-[#8aadff] mt-1">Sertifika No</p>
            </div>
          </div>
          <div class="text-center">
            <div style="border-top:1px solid rgba(255,255,255,0.2);padding-top:0.75rem">
              @if($certificate->expires_at)
              <p class="font-black text-sm {{ $certificate->isExpired() ? 'text-[#FF2D2D]' : 'text-white' }}">
                {{ $certificate->expires_at->format('d.m.Y') }}
              </p>
              @else
              <p class="font-black text-sm text-[#8aadff]">—</p>
              @endif
              <p class="font-mono text-[9px] uppercase tracking-widest text-[#8aadff] mt-1">Geçerlilik Bitiş</p>
            </div>
          </div>
        </div>

        {{-- Eğitmen imzası --}}
        @if($certificate->instructor_name)
        <div class="mt-6 flex justify-end">
          <div class="text-right">
            <div style="border-top:1px solid rgba(255,255,255,0.3);padding-top:0.5rem;min-width:150px">
              <p class="font-bold text-sm text-white">{{ $certificate->instructor_name }}</p>
              <p class="font-mono text-[9px] uppercase tracking-widest text-[#8aadff]">Eğitmen / Instructor</p>
            </div>
          </div>
        </div>
        @endif

      </div>
    </div>
  </div>

  {{-- Sertifika Ön Koşulları --}}
  <div class="border-[3px] border-[#0A0A0A] bg-white p-5 mb-6" style="box-shadow:4px 4px 0 #0A0A0A" id="prereq-panel">
    <div class="flex items-center justify-between mb-1">
      <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest">BU SERTİFİKA İÇİN TAMAMLANMASI GEREKEN KURSLAR</p>
      <span class="font-mono text-[9px] text-[#888]">{{ count($prereqIds) > 0 ? count($prereqIds).' kurs seçili' : 'Seçili kurs yok' }}</span>
    </div>
    <p class="text-[10px] text-[#888] mb-4">Bu sertifikayı alabilmek için önce tamamlanması gereken kursları seçin.</p>

    @if(!$certificate->course_id)
    <div class="bg-[#FFF3CD] border border-[#FFE000] p-3 mb-3 text-xs font-bold text-[#856404]">
      ⚠ Bu sertifikaya kurs atanmamış. Ön koşul eklemek için önce sertifikayı düzenleyip bir kurs seçin.
    </div>
    @endif

    <form id="prereq-form" action="{{ route('admin.certificates.prerequisites', $certificate->id) }}" method="POST">
      @csrf
      <div class="space-y-1 max-h-56 overflow-y-auto mb-4 border border-[#e0e0e0] p-2 bg-[#fafaf5]">
        @forelse($allCourses as $c)
        <label class="flex items-center gap-2 cursor-pointer py-1.5 hover:bg-white px-2">
          <input type="checkbox" name="prerequisites[]" value="{{ $c->id }}"
            class="w-3.5 h-3.5 shrink-0"
            {{ in_array($c->id, $prereqIds) ? 'checked' : '' }}>
          <span class="text-xs font-medium text-[#0A0A0A] leading-tight">{{ $c->title }}</span>
          @if(in_array($c->id, $prereqIds))
          <span class="ml-auto tag-lime text-[8px] shrink-0">SEÇİLİ</span>
          @endif
        </label>
        @empty
        <p class="text-xs text-[#888] p-2">Henüz kurs yok.</p>
        @endforelse
      </div>
      <div class="flex items-center gap-3">
        <button type="submit" id="prereq-save-btn"
          {{ !$certificate->course_id ? 'disabled' : '' }}
          class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
          style="box-shadow:3px 3px 0 #0A0A0A">Kaydet</button>
        @if(count($prereqIds) > 0)
        <p class="text-xs text-[#888]">Seçili: {{ $allCourses->whereIn('id', $prereqIds)->pluck('title')->implode(', ') }}</p>
        @endif
      </div>
    </form>
  </div>

  {{-- Meta bilgiler --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
      <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">KULLANICI BİLGİLERİ</p>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-[#888]">Ad Soyad</span>
          <span class="font-bold">{{ $certificate->user->name ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">E-posta</span>
          <span class="font-bold">{{ $certificate->user->email ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">Personel No</span>
          <span class="font-bold">{{ $certificate->employee_id ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">Departman</span>
          <span class="font-bold">{{ $certificate->department ?? '—' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">Saha</span>
          <span class="font-bold">{{ $certificate->site ?? '—' }}</span>
        </div>
      </div>
    </div>

    <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
      <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">SERTİFİKA DETAYLARI</p>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-[#888]">Kurs</span>
          <span class="font-bold">{{ $certificate->course->title ?? 'Genel' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">Eğitim Saati</span>
          <span class="font-bold">{{ $certificate->training_hours ?? '—' }} saat</span>
        </div>
        <div class="flex justify-between">
          <span class="text-[#888]">Tamamlanma</span>
          <span class="font-bold">{{ $certificate->completed_at?->format('d.m.Y') ?? '—' }}</span>
        </div>
        @if($certificate->notes)
        <div class="pt-2 border-t border-[#eee]">
          <span class="text-[#888] block mb-1">Notlar</span>
          <p class="font-bold text-xs">{{ $certificate->notes }}</p>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>
</div>

<style>
@media print {
  nav, header, .no-print { display:none !important; }
  body { background:white; }
  #cert-visual { margin:0; }
}
</style>

@push('scripts')
<script>
(function(){
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

function toast(msg, ok) {
    let t = document.getElementById('prereq-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'prereq-toast';
        t.style = 'position:fixed;top:16px;right:16px;z-index:9999;padding:12px 20px;font-weight:900;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;border:3px solid;transition:opacity .3s';
        document.body.appendChild(t);
    }
    t.textContent = (ok !== false ? '✓ ' : '✗ ') + msg;
    t.style.background  = ok !== false ? '#CCFF00' : '#FF2D2D';
    t.style.color       = ok !== false ? '#0A0A0A' : '#fff';
    t.style.borderColor = ok !== false ? '#0A0A0A' : '#FF2D2D';
    t.style.opacity     = '1';
    setTimeout(() => t.style.opacity = '0', 2500);
}

const prereqForm = document.getElementById('prereq-form');
if (prereqForm) {
    prereqForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('prereq-save-btn');
        btn.disabled = true; btn._txt = btn.textContent; btn.textContent = '...';
        try {
            const res  = await fetch(this.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: new FormData(this)
            });
            const data = await res.json();
            if (data.success) toast(data.message);
            else toast(data.message || 'Hata.', false);
        } catch { toast('Bağlantı hatası.', false); }
        btn.disabled = false; btn.textContent = btn._txt;
    });
}
})();
</script>
@endpush
@endsection
