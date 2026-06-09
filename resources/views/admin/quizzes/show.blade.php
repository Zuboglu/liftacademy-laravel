@extends('layouts.app')
@section('title', $quiz->title . ' – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1000px] mx-auto px-6 py-10">

  <div class="flex items-start justify-between mb-8 gap-4 flex-wrap">
    <div>
      <a href="{{ route('admin.quizzes.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sınav Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1 leading-snug">{{ $quiz->title }}</h1>
      <div class="flex flex-wrap gap-2 mt-2">
        <span class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-[9px] uppercase tracking-widest px-2.5 py-1">{{ $quiz->questions->count() }} SORU</span>
        <span class="bg-[#FFE000] text-[#0A0A0A] font-black text-[9px] uppercase tracking-widest px-2.5 py-1 border-[2px] border-[#0A0A0A]">GEÇER: %{{ $quiz->passing_score }}</span>
        @if($quiz->course)
        <span class="bg-[#0047FF] text-white font-black text-[9px] uppercase tracking-widest px-2.5 py-1">{{ Str::limit($quiz->course->title, 30) }}</span>
        @endif
        @if($quiz->is_active)
        <span class="bg-[#CCFF00] text-[#0A0A0A] font-black text-[9px] uppercase tracking-widest px-2.5 py-1 border-[2px] border-[#0A0A0A]">● AKTİF</span>
        @else
        <span class="bg-[#FF2D2D] text-white font-black text-[9px] uppercase tracking-widest px-2.5 py-1">● PASİF</span>
        @endif
      </div>
    </div>
    <div class="flex flex-wrap gap-2 shrink-0">
      {{-- Aktif/Pasif toggle --}}
      <form action="{{ route('admin.quizzes.toggle', $quiz->id) }}" method="POST">
        @csrf
        <button type="submit"
          class="font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] transition-colors {{ $quiz->is_active ? 'bg-[#FF2D2D] text-white hover:bg-[#cc0000]' : 'bg-[#CCFF00] text-[#0A0A0A] hover:bg-[#aadd00]' }}"
          style="box-shadow:3px 3px 0 #0A0A0A">
          {{ $quiz->is_active ? '⏸ Pasif Et' : '▶ Aktif Et' }}
        </button>
      </form>
      <a href="{{ route('admin.quizzes.edit', $quiz->id) }}"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">Düzenle</a>
      <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
        onsubmit="return confirm('Bu sınavı silmek istediğinizden emin misiniz?')">
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

  {{-- Mevcut sorular --}}
  <div class="border-[3px] border-[#0A0A0A] mb-6" style="box-shadow:4px 4px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
      <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">SORULAR</p>
      <span class="font-mono text-[10px] text-[#555]">{{ $quiz->questions->count() }} soru</span>
    </div>

    @forelse($quiz->questions->sortBy('order') as $qi => $q)
    <div class="border-t border-[#eee]">
      <div class="px-5 py-4 flex items-start gap-4">
        <span class="font-mono text-[10px] text-[#888] w-6 shrink-0 mt-0.5">{{ str_pad($qi+1,2,'0',STR_PAD_LEFT) }}</span>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-sm text-[#0A0A0A] leading-snug mb-2">{{ $q->question }}</p>
          <div class="grid grid-cols-2 gap-1.5">
            @foreach($q->options as $oi => $opt)
            <div class="flex items-center gap-2 text-xs {{ $oi === $q->correct_answer ? 'text-[#00a000] font-black' : 'text-[#888]' }}">
              <span class="font-mono shrink-0">{{ chr(65+$oi) }})</span>
              <span>{{ $opt }}</span>
              @if($oi === $q->correct_answer)<span class="shrink-0 text-[#00a000]">✓</span>@endif
            </div>
            @endforeach
          </div>
          @if($q->explanation)
          <p class="text-[10px] text-[#888] mt-2 font-mono italic">💡 {{ $q->explanation }}</p>
          @endif
        </div>
        <div class="flex gap-3 shrink-0">
          <button type="button" onclick="toggleQEdit({{ $q->id }})"
            class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Düzenle</button>
          <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST"
            onsubmit="return confirm('Soruyu sil?')" class="inline">
            @csrf @method('DELETE')
            <button type="submit" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase tracking-widest">Sil</button>
          </form>
        </div>
      </div>

      <div id="q-edit-{{ $q->id }}" class="hidden border-t border-[#f0f0e8] bg-[#fafaf5] p-5">
        <form action="{{ route('admin.questions.update', $q->id) }}" method="POST" class="space-y-4">
          @csrf @method('PUT')
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SORU METNİ *</label>
            <textarea name="question" rows="2" required
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] resize-none">{{ $q->question }}</textarea>
          </div>
          <div class="space-y-2">
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SEÇENEKLER — Doğru olanı işaretleyin</label>
            @foreach($q->options as $oi => $opt)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $oi }}"
                {{ $q->correct_answer === $oi ? 'checked' : '' }} required
                class="w-4 h-4 shrink-0" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$oi) }}</span>
              <input type="text" name="options[]" value="{{ $opt }}" required
                class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]">
            </div>
            @endforeach
            @for($x = count($q->options); $x < 4; $x++)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $x }}"
                class="w-4 h-4 shrink-0" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$x) }}</span>
              <input type="text" name="options[]"
                class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]"
                placeholder="Boş bırakılabilir">
            </div>
            @endfor
          </div>
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">AÇIKLAMA (opsiyonel)</label>
            <input type="text" name="explanation" value="{{ $q->explanation }}"
              class="w-full border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]"
              placeholder="Neden doğru/yanlış?">
          </div>
          <div class="flex gap-2 pt-2">
            <button type="submit"
              class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">Kaydet</button>
            <button type="button" onclick="toggleQEdit({{ $q->id }})"
              class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors">İptal</button>
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
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SORU METNİ *</label>
          <textarea name="question" rows="3" required
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors resize-none"
            placeholder="Soru metnini buraya yazın..."></textarea>
        </div>

        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SEÇENEKLER — Doğru cevabı seçin</label>
          <div class="space-y-2">
            @foreach(['A','B','C','D'] as $li => $lbl)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $li }}"
                {{ $li===0?'checked':'' }} required
                class="w-4 h-4 shrink-0" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ $lbl }}</span>
              <input type="text" name="options[]"
                class="flex-1 border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
                placeholder="Seçenek {{ $lbl }}" {{ $li < 2 ? 'required' : '' }}>
            </div>
            @endforeach
          </div>
          <p class="font-mono text-[10px] text-[#888] mt-2">En az 2 seçenek zorunlu. Boş bırakılan seçenekler kaydedilmez.</p>
        </div>

        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">AÇIKLAMA (opsiyonel)</label>
          <input type="text" name="explanation"
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
            placeholder="Doğru cevabın açıklaması...">
        </div>

        <button type="submit"
          class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
          style="box-shadow:3px 3px 0 #0A0A0A">
          + Soru Ekle
        </button>
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
