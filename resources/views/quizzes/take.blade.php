@extends('layouts.app')
@section('title', $quiz->title . ' – Sınav – LiftAcademy')
@section('content')

<div class="min-h-screen bg-[#0A0A0A] text-[#F5F0E8]">
  <div class="max-w-[800px] mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
      <div>
        <p class="font-mono text-[10px] text-[#555] uppercase tracking-widest mb-1">SINAV</p>
        <h1 class="font-black text-[#FFE000] uppercase tracking-tight text-xl">{{ $quiz->title }}</h1>
      </div>
      <div class="text-right">
        @if($quiz->time_limit)
        <div id="timer-box" class="border-[2px] border-[#FFE000] px-4 py-2 text-center">
          <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest">KALAN SÜRE</p>
          <p id="timer" class="font-black text-[#FFE000] text-xl tabular-nums">{{ sprintf('%02d:%02d', $quiz->time_limit, 0) }}</p>
        </div>
        @endif
      </div>
    </div>

    {{-- İlerleme --}}
    <div class="flex items-center gap-3 mb-8">
      <div class="flex-1 h-1.5 bg-[#1a1a1a]">
        <div id="progress-bar" class="h-full bg-[#FFE000] transition-all duration-300" style="width:0%"></div>
      </div>
      <span id="progress-text" class="font-mono text-[10px] text-[#555] whitespace-nowrap">0 / {{ $quiz->questions->count() }}</span>
    </div>

    {{-- Form --}}
    <form id="quiz-form" action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
      @csrf

      @foreach($quiz->questions as $qi => $q)
      <div class="question-block mb-8 border-[3px] border-[#1e1e1e] {{ $qi > 0 ? 'opacity-60' : '' }}" data-q="{{ $qi }}" id="q-{{ $qi }}">
        <div class="bg-[#111] px-5 py-4 border-b border-[#1e1e1e] flex items-start gap-4">
          <span class="font-mono text-[10px] text-[#555] uppercase shrink-0 mt-0.5">{{ str_pad($qi+1, 2, '0', STR_PAD_LEFT) }}</span>
          <p class="font-bold text-sm text-[#F5F0E8] leading-relaxed">{{ $q->question }}</p>
        </div>
        <div class="p-4 space-y-2">
          @foreach($q->options as $oi => $opt)
          <label class="option-label flex items-center gap-3 p-3 border-[2px] border-[#1e1e1e] cursor-pointer hover:border-[#FFE000] transition-all group">
            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $oi }}"
              class="sr-only" onchange="onAnswer({{ $qi }}, {{ $q->id }})">
            <div class="option-dot w-5 h-5 border-[2px] border-[#333] shrink-0 flex items-center justify-center">
              <div class="w-2.5 h-2.5 bg-[#FFE000] scale-0 transition-transform option-fill"></div>
            </div>
            <span class="font-mono text-[10px] text-[#888] uppercase shrink-0">{{ chr(65+$oi) }}</span>
            <span class="text-sm font-medium text-[#ccc]">{{ $opt }}</span>
          </label>
          @endforeach
        </div>
      </div>
      @endforeach

      {{-- Gönder --}}
      <div class="flex items-center justify-between pt-4 border-t border-[#1e1e1e]">
        <p id="unanswered-msg" class="font-mono text-[10px] text-[#FF2D2D] uppercase tracking-wide hidden">
          Lütfen tüm soruları cevaplayın
        </p>
        <button type="button" onclick="submitQuiz()" id="submit-btn"
          class="ml-auto btn-brut text-sm px-8 py-4 opacity-40 cursor-not-allowed" disabled>
          Sınavı Bitir ↗
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
const totalQ = {{ $quiz->questions->count() }};
const answered = new Set();

function onAnswer(qi, qid) {
  answered.add(qid);

  // Seçilen option'ı göster
  const block = document.getElementById('q-' + qi);
  block.querySelectorAll('.option-label').forEach(l => {
    const inp = l.querySelector('input[type=radio]');
    l.classList.toggle('border-[#FFE000]', inp.checked);
    l.classList.toggle('bg-[#111]', inp.checked);
    l.querySelector('.option-fill').style.transform = inp.checked ? 'scale(1)' : 'scale(0)';
    l.querySelector('.option-dot').style.borderColor = inp.checked ? '#FFE000' : '#333';
  });

  // Opacity kaldır
  block.classList.remove('opacity-60');

  // Sonraki soruyu aktif et
  const next = document.querySelector('[data-q="' + (qi+1) + '"]');
  if (next) next.classList.remove('opacity-60');

  // İlerleme
  const pct = Math.round(answered.size / totalQ * 100);
  document.getElementById('progress-bar').style.width = pct + '%';
  document.getElementById('progress-text').textContent = answered.size + ' / ' + totalQ;

  // Tümü cevaplandıysa butonu aktif et
  if (answered.size === totalQ) {
    const btn = document.getElementById('submit-btn');
    btn.disabled = false;
    btn.classList.remove('opacity-40','cursor-not-allowed');
  }
}
window.onAnswer = onAnswer;

function submitQuiz() {
  if (answered.size < totalQ) {
    document.getElementById('unanswered-msg').classList.remove('hidden');
    return;
  }
  document.getElementById('quiz-form').submit();
}
window.submitQuiz = submitQuiz;

@if($quiz->time_limit)
// Sayaç
let totalSec = {{ $quiz->time_limit * 60 }};
const timerEl = document.getElementById('timer');
const interval = setInterval(() => {
  totalSec--;
  if (totalSec <= 0) {
    clearInterval(interval);
    document.getElementById('quiz-form').submit();
    return;
  }
  const m = Math.floor(totalSec/60);
  const s = totalSec % 60;
  timerEl.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
  if (totalSec <= 60) timerEl.style.color = '#FF2D2D';
}, 1000);
@endif

})();
</script>
@endpush
@endsection
