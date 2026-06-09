@extends('layouts.app')
@section('title', 'Yeni Sınav – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1000px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.quizzes.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sınav Listesi</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">YENİ SINAV OLUŞTUR</h1>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 text-sm font-bold">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  <form action="{{ route('admin.quizzes.store') }}" method="POST" id="quiz-create-form">
    @csrf

    {{-- Sınav Bilgileri --}}
    <div class="border-[3px] border-[#0A0A0A] p-6 space-y-5 bg-white mb-6" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] -m-6 mb-6 px-5 py-3">
        <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">SINAV BİLGİLERİ</p>
      </div>

      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SINAV ADI *</label>
        <input type="text" name="title" value="{{ old('title') }}" required
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Sınav başlığı">
      </div>

      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">AÇIKLAMA</label>
        <textarea name="description" rows="2"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors resize-none"
          placeholder="Sınav hakkında kısa açıklama">{{ old('description') }}</textarea>
      </div>

      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">BAĞLI KURS (opsiyonel)</label>
        <select name="course_id"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          <option value="">— Kursa bağlamayın (genel sınav) —</option>
          @foreach($courses as $c)
          <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
          @endforeach
        </select>
        <p class="font-mono text-[10px] text-[#888] mt-1">Kursa bağlanırsa: Video tamamlanınca sınav açılır, geçince sertifika otomatik oluşur.</p>
      </div>

      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">GEÇER NOT (%) *</label>
          <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" required
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
        </div>
        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SÜRE (dak.)</label>
          <input type="number" name="time_limit" value="{{ old('time_limit') }}" min="1"
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
            placeholder="Süresiz">
        </div>
        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">DENEME HAKKI *</label>
          <input type="number" name="attempts" value="{{ old('attempts', 3) }}" min="1" max="10" required
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
        </div>
      </div>
    </div>

    {{-- Soru Editörü --}}
    <div class="border-[3px] border-[#0A0A0A] bg-white mb-6" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#FFE000] border-b-[3px] border-[#0A0A0A] px-5 py-3 flex items-center justify-between">
        <p class="font-black text-[#0A0A0A] text-xs uppercase tracking-widest">SORULAR <span id="q-count" class="ml-2 bg-[#0A0A0A] text-[#FFE000] px-2 py-0.5 text-[10px]">0</span></p>
        <button type="button" onclick="addQuestion()"
          class="bg-[#0A0A0A] text-[#FFE000] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#FFE000] hover:text-[#0A0A0A] transition-colors">
          + Soru Ekle
        </button>
      </div>

      <div id="questions-container" class="p-5 space-y-5">
        <p id="no-questions" class="font-mono text-[10px] text-[#888] uppercase text-center py-6">
          Henüz soru yok — "Soru Ekle" butonuna tıklayın
        </p>
      </div>
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">
        Sınav Oluştur ↗
      </button>
      <a href="{{ route('admin.quizzes.index') }}"
        class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">
        İptal
      </a>
    </div>
  </form>

</div>
</div>

@push('scripts')
<script>
let qIndex = 0;

function addQuestion() {
  const container = document.getElementById('questions-container');
  const noQ = document.getElementById('no-questions');
  if (noQ) noQ.remove();

  const i = qIndex++;
  const block = document.createElement('div');
  block.id = 'q-block-' + i;
  block.className = 'border-[3px] border-[#0A0A0A] bg-[#F5F0E8]';
  block.innerHTML = `
    <div class="bg-[#0A0A0A] px-4 py-2.5 flex items-center justify-between">
      <span class="font-mono text-[10px] text-[#555] uppercase tracking-widest">SORU ${i+1}</span>
      <button type="button" onclick="removeQuestion(${i})"
        class="font-mono text-[10px] text-[#FF5555] hover:text-white uppercase tracking-widest">× Kaldır</button>
    </div>
    <div class="p-4 space-y-3">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SORU METNİ *</label>
        <textarea name="questions[${i}][question]" rows="2" required
          class="w-full border-[3px] border-[#0A0A0A] px-3 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors resize-none"
          placeholder="Soru metnini buraya yazın..."></textarea>
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SEÇENEKLER — Doğru cevabı seçin</label>
        <div class="space-y-2">
          ${['A','B','C','D'].map((lbl, oi) => `
          <div class="flex items-center gap-3">
            <input type="radio" name="questions[${i}][correct_answer]" value="${oi}"
              ${oi===0?'checked':''} required class="w-4 h-4 shrink-0" style="accent-color:#CCFF00">
            <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">${lbl}</span>
            <input type="text" name="questions[${i}][options][]"
              class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors"
              placeholder="Seçenek ${lbl}" ${oi<2?'required':''}>
          </div>`).join('')}
        </div>
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">AÇIKLAMA (opsiyonel)</label>
        <input type="text" name="questions[${i}][explanation]"
          class="w-full border-[3px] border-[#0A0A0A] px-3 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Doğru cevabın açıklaması...">
      </div>
    </div>`;

  container.appendChild(block);
  updateCount();
}

function removeQuestion(i) {
  const block = document.getElementById('q-block-' + i);
  if (block) block.remove();
  updateCount();
  if (document.getElementById('questions-container').children.length === 0) {
    const p = document.createElement('p');
    p.id = 'no-questions';
    p.className = 'font-mono text-[10px] text-[#888] uppercase text-center py-6';
    p.textContent = 'Henüz soru yok — "Soru Ekle" butonuna tıklayın';
    document.getElementById('questions-container').appendChild(p);
  }
}

function updateCount() {
  const count = document.querySelectorAll('[id^="q-block-"]').length;
  document.getElementById('q-count').textContent = count;
}
</script>
@endpush
@endsection
