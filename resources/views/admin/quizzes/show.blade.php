@extends('layouts.app')
@section('title', $quiz->title . ' – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1000px] mx-auto px-6 py-10">

  <div class="flex items-start justify-between mb-8 gap-4">
    <div>
      <a href="{{ route('admin.quizzes.index') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Sınav Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1 leading-snug">{{ $quiz->title }}</h1>
      <div class="flex gap-2 mt-2">
        <span class="tag-black text-[9px]">{{ $quiz->questions->count() }} SORU</span>
        <span class="tag-yellow text-[9px]">GEÇER: %{{ $quiz->passing_score }}</span>
        @if($quiz->course)<span class="tag-blue text-[9px]">{{ Str::limit($quiz->course->title, 30) }}</span>@endif
      </div>
    </div>
    <div class="flex gap-2 shrink-0">
      <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="btn-brut text-xs py-2 px-4">Düzenle</a>
      <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Bu sınavı silmek istediğinizden emin misiniz?')">
        @csrf @method('DELETE')
        <button type="submit" class="border-[3px] border-[#FF2D2D] text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2 hover:bg-[#FF2D2D] hover:text-white transition-colors">Sil</button>
      </form>
    </div>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-bold text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  {{-- Mevcut sorular --}}
  <div class="border-[3px] border-[#0A0A0A] mb-6" style="box-shadow:4px 4px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
      <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Sorular</p>
      <span class="font-mono text-[10px] text-[#555]">{{ $quiz->questions->count() }} soru</span>
    </div>

    @forelse($quiz->questions->sortBy('order') as $qi => $q)
    <div class="border-t border-[#eee]">
      {{-- Soru satırı --}}
      <div class="px-5 py-4 flex items-start gap-4">
        <span class="font-mono text-[10px] text-[#888] w-6 shrink-0 mt-0.5">{{ str_pad($qi+1,2,'0',STR_PAD_LEFT) }}</span>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-sm text-[#0A0A0A] leading-snug mb-2">{{ $q->question }}</p>
          <div class="grid grid-cols-2 gap-1.5">
            @foreach($q->options as $oi => $opt)
            <div class="flex items-center gap-2 text-xs {{ $oi === $q->correct_answer ? 'text-[#00a000] font-bold' : 'text-[#888]' }}">
              <span class="font-mono shrink-0">{{ chr(65+$oi) }})</span>
              <span>{{ $opt }}</span>
              @if($oi === $q->correct_answer)<span class="shrink-0">✓</span>@endif
            </div>
            @endforeach
          </div>
          @if($q->explanation)
          <p class="text-[10px] text-[#888] mt-2 font-mono">💡 {{ $q->explanation }}</p>
          @endif
        </div>
        <div class="flex gap-3 shrink-0">
          <button onclick="toggleQEdit({{ $q->id }})" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase">Düzenle</button>
          <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Soruyu sil?')" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
          </form>
        </div>
      </div>

      {{-- Düzenleme formu (gizli) --}}
      <div id="q-edit-{{ $q->id }}" class="hidden border-t border-[#f0f0e8] bg-[#fafaf5] p-5">
        <form action="{{ route('admin.questions.update', $q->id) }}" method="POST" class="space-y-4">
          @csrf @method('PUT')
          <div>
            <label class="text-mono-sm mb-1 block">SORU METNİ *</label>
            <textarea name="question" rows="2" required class="input-brut resize-none text-sm">{{ $q->question }}</textarea>
          </div>
          <div class="space-y-2">
            <label class="text-mono-sm mb-1 block">SEÇENEKLER (doğru olanı işaretleyin)</label>
            @foreach($q->options as $oi => $opt)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $oi }}" {{ $q->correct_answer === $oi ? 'checked' : '' }} required class="w-4 h-4 accent-[#CCFF00]">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$oi) }}</span>
              <input type="text" name="options[]" value="{{ $opt }}" required class="input-brut flex-1 py-1.5 text-sm">
            </div>
            @endforeach
            @for($x = count($q->options); $x < 4; $x++)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $x }}" class="w-4 h-4 accent-[#CCFF00]">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$x) }}</span>
              <input type="text" name="options[]" class="input-brut flex-1 py-1.5 text-sm" placeholder="Boş bırakılabilir">
            </div>
            @endfor
          </div>
          <div>
            <label class="text-mono-sm mb-1 block">AÇIKLAMA (opsiyonel)</label>
            <input type="text" name="explanation" value="{{ $q->explanation }}" class="input-brut py-1.5 text-sm" placeholder="Neden doğru/yanlış?">
          </div>
          <div class="flex gap-2">
            <button type="submit" class="btn-brut text-xs py-1.5 px-5">Kaydet</button>
            <button type="button" onclick="toggleQEdit({{ $q->id }})" class="btn-brut-dark text-xs py-1.5 px-5">İptal</button>
          </div>
        </form>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center">
      <p class="font-bold text-[#888] text-sm uppercase">Henüz soru eklenmedi</p>
    </div>
    @endforelse
  </div>

  {{-- Yeni Soru Ekle --}}
  <div class="border-[3px] border-[#0A0A0A] bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
    <div class="bg-[#FFE000] border-b-[3px] border-[#0A0A0A] px-5 py-3">
      <p class="font-black text-[#0A0A0A] text-xs uppercase tracking-widest">+ Yeni Soru Ekle</p>
    </div>
    <div class="p-5">
      <form action="{{ route('admin.questions.store', $quiz->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="text-mono-sm mb-1.5 block">SORU METNİ *</label>
          <textarea name="question" rows="3" required class="input-brut resize-none" placeholder="Soru metnini buraya yazın..."></textarea>
        </div>

        <div>
          <label class="text-mono-sm mb-1.5 block">SEÇENEKLER — Doğru cevabı sol taraftaki butonla işaretleyin</label>
          <div class="space-y-2" id="options-list">
            @foreach(['A','B','C','D'] as $li => $lbl)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $li }}" {{ $li===0?'checked':'' }} required class="w-4 h-4 accent-[#CCFF00] shrink-0">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ $lbl }}</span>
              <input type="text" name="options[]" class="input-brut flex-1 py-2 text-sm" placeholder="Seçenek {{ $lbl }}" {{ $li < 2 ? 'required' : '' }}>
            </div>
            @endforeach
          </div>
          <p class="text-[10px] text-[#888] font-mono mt-2">En az 2 seçenek zorunlu. Boş bırakılan seçenekler eklenmez.</p>
        </div>

        <div>
          <label class="text-mono-sm mb-1.5 block">AÇIKLAMA (opsiyonel)</label>
          <input type="text" name="explanation" class="input-brut py-2 text-sm" placeholder="Doğru cevabın açıklaması...">
        </div>

        <button type="submit" class="btn-brut text-sm px-8 py-3">+ Soru Ekle</button>
      </form>
    </div>
  </div>

</div>
</div>

@push('scripts')
<script>
function toggleQEdit(id) {
  document.getElementById('q-edit-' + id).classList.toggle('hidden');
}
</script>
@endpush
@endsection
