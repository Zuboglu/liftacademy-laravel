@extends('layouts.app')
@section('title', $quiz->title . ' – Admin')
@section('content')

{{-- Toast --}}
<div id="ajax-toast" class="fixed top-4 right-4 z-50 px-5 py-3 font-black text-xs uppercase tracking-widest border-[3px] opacity-0 pointer-events-none" style="transition:opacity 0.3s,transform 0.3s;transform:translateY(-8px)"></div>

<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1000px] mx-auto px-6 py-10">

  <div class="flex items-start justify-between mb-8 gap-4 flex-wrap">
    <div>
      <a href="{{ route('admin.quizzes.index') }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Sınav Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1 leading-snug">{{ $quiz->title }}</h1>
      <div class="flex flex-wrap gap-2 mt-2" id="quiz-badges">
        <span class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-[9px] uppercase tracking-widest px-2.5 py-1" id="q-count-badge">{{ $quiz->questions->count() }} SORU</span>
        <span class="bg-[#FFE000] text-[#0A0A0A] font-black text-[9px] uppercase tracking-widest px-2.5 py-1 border-[2px] border-[#0A0A0A]">GEÇER: %{{ $quiz->passing_score }}</span>
        @if($quiz->course)
        <span class="bg-[#0047FF] text-white font-black text-[9px] uppercase tracking-widest px-2.5 py-1">{{ Str::limit($quiz->course->title, 30) }}</span>
        @endif
        <span id="active-badge" class="{{ $quiz->is_active ? 'bg-[#CCFF00] text-[#0A0A0A] border-[2px] border-[#0A0A0A]' : 'bg-[#FF2D2D] text-white' }} font-black text-[9px] uppercase tracking-widest px-2.5 py-1">
          {{ $quiz->is_active ? '● AKTİF' : '● PASİF' }}
        </span>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 shrink-0">
      <button id="toggle-btn"
        onclick="toggleActive('{{ route('admin.quizzes.toggle', $quiz->id) }}')"
        class="font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] transition-colors {{ $quiz->is_active ? 'bg-[#FF2D2D] text-white hover:bg-[#cc0000]' : 'bg-[#CCFF00] text-[#0A0A0A] hover:bg-[#aadd00]' }}"
        style="box-shadow:3px 3px 0 #0A0A0A">
        {{ $quiz->is_active ? '⏸ Pasif Et' : '▶ Aktif Et' }}
      </button>
      <a href="{{ route('admin.quizzes.edit', $quiz->id) }}"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">Düzenle</a>
      <button onclick="deleteQuiz('{{ route('admin.quizzes.destroy', $quiz->id) }}')"
        class="bg-white text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#FF2D2D] hover:bg-[#FF2D2D] hover:text-white transition-colors"
        style="box-shadow:3px 3px 0 #FF2D2D">Sil</button>
    </div>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  {{-- Mevcut sorular --}}
  <div class="border-[3px] border-[#0A0A0A] mb-6" style="box-shadow:4px 4px 0 #0A0A0A">
    <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
      <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">SORULAR</p>
      <span class="font-mono text-[10px] text-[#555]" id="question-count-text">{{ $quiz->questions->count() }} soru</span>
    </div>

    <div id="questions-container">
    @forelse($quiz->questions->sortBy('order') as $qi => $q)
    <div class="border-t border-[#eee]" id="question-{{ $q->id }}">
      <div class="px-5 py-4 flex items-start gap-4">
        <span class="font-mono text-[10px] text-[#888] w-6 shrink-0 mt-0.5 q-index">{{ str_pad($qi+1,2,'0',STR_PAD_LEFT) }}</span>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-sm text-[#0A0A0A] leading-snug mb-2 q-text">{{ $q->question }}</p>
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
          <button onclick="deleteQuestion({{ $q->id }}, '{{ route('admin.questions.destroy', $q->id) }}')"
            class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase tracking-widest">Sil</button>
        </div>
      </div>

      <div id="q-edit-{{ $q->id }}" class="hidden border-t border-[#f0f0e8] bg-[#fafaf5] p-5">
        <form class="q-update-form space-y-4" data-question-id="{{ $q->id }}" data-url="{{ route('admin.questions.update', $q->id) }}">
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SORU METNİ *</label>
            <textarea name="question" rows="2" required
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] resize-none">{{ $q->question }}</textarea>
          </div>
          <div class="space-y-2">
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SEÇENEKLER — Doğru olanı işaretleyin</label>
            @foreach($q->options as $oi => $opt)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $oi }}" {{ $q->correct_answer === $oi ? 'checked' : '' }} required class="w-4 h-4" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$oi) }}</span>
              <input type="text" name="options[]" value="{{ $opt }}" required class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]">
            </div>
            @endforeach
            @for($x = count($q->options); $x < 4; $x++)
            <div class="flex items-center gap-3">
              <input type="radio" name="correct_answer" value="{{ $x }}" class="w-4 h-4" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ chr(65+$x) }}</span>
              <input type="text" name="options[]" class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]" placeholder="Boş bırakılabilir">
            </div>
            @endfor
          </div>
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">AÇIKLAMA (opsiyonel)</label>
            <input type="text" name="explanation" value="{{ $q->explanation }}"
              class="w-full border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]">
          </div>
          <div class="flex gap-2 pt-2">
            <button type="submit" class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">Kaydet</button>
            <button type="button" onclick="toggleQEdit({{ $q->id }})" class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors">İptal</button>
          </div>
        </form>
      </div>
    </div>
    @empty
    <div class="px-5 py-10 text-center border-t border-[#eee]" id="empty-state">
      <p class="font-bold text-[#888] text-sm uppercase">Henüz soru eklenmedi</p>
    </div>
    @endforelse
    </div>
  </div>

  {{-- Yeni Soru Ekle --}}
  <div class="border-[3px] border-[#0A0A0A] bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
    <div class="bg-[#FFE000] border-b-[3px] border-[#0A0A0A] px-5 py-3">
      <p class="font-black text-[#0A0A0A] text-xs uppercase tracking-widest">+ Yeni Soru Ekle</p>
    </div>
    <div class="p-5">
      <form id="question-add-form" data-url="{{ route('admin.questions.store', $quiz->id) }}" class="space-y-4">
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
              <input type="radio" name="correct_answer" value="{{ $li }}" {{ $li===0?'checked':'' }} required class="w-4 h-4 shrink-0" style="accent-color:#CCFF00">
              <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">{{ $lbl }}</span>
              <input type="text" name="options[]"
                class="flex-1 border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
                placeholder="Seçenek {{ $lbl }}" {{ $li < 2 ? 'required' : '' }}>
            </div>
            @endforeach
          </div>
          <p class="font-mono text-[10px] text-[#888] mt-2">En az 2 seçenek zorunlu.</p>
        </div>
        <div>
          <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">AÇIKLAMA (opsiyonel)</label>
          <input type="text" name="explanation"
            class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
            placeholder="Doğru cevabın açıklaması...">
        </div>
        <button type="submit"
          class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-3 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
          style="box-shadow:3px 3px 0 #0A0A0A">+ Soru Ekle</button>
      </form>
    </div>
  </div>

</div>
</div>

@push('scripts')
<script>
(function () {
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const ESC  = s => String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

/* ── Toast ──────────────────────────────────────────────────── */
function toast(msg, ok = true) {
    const t = document.getElementById('ajax-toast');
    t.textContent = (ok ? '✓ ' : '✗ ') + msg;
    t.className = 'fixed top-4 right-4 z-50 px-5 py-3 font-black text-xs uppercase tracking-widest border-[3px] pointer-events-none ' +
        (ok ? 'bg-[#CCFF00] text-[#0A0A0A] border-[#0A0A0A]' : 'bg-[#FF2D2D] text-white border-[#FF2D2D]');
    t.style.transition = 'opacity 0.3s,transform 0.3s';
    t.style.opacity = '1'; t.style.transform = 'translateY(0)';
    setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(-8px)'; }, 2500);
}

function btnState(btn, loading) {
    if (loading) { btn.disabled = true; btn._txt = btn.textContent; btn.textContent = '...'; }
    else          { btn.disabled = false; btn.textContent = btn._txt || btn.textContent; }
}

function formToJson(form) {
    const opts  = [...form.querySelectorAll('[name="options[]"]')].map(i => i.value);
    const radio = form.querySelector('[name="correct_answer"]:checked');
    return {
        question:       form.querySelector('[name="question"]').value,
        options:        opts,
        correct_answer: radio ? parseInt(radio.value) : 0,
        explanation:    form.querySelector('[name="explanation"]')?.value || '',
    };
}

const jsonHeaders = { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF };
const baseHeaders = { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF };

/* ── TOGGLE ACTIVE ──────────────────────────────────────────── */
window.toggleActive = async function (url) {
    const btn = document.getElementById('toggle-btn');
    btnState(btn, true);
    try {
        const res  = await fetch(url, { method: 'POST', headers: baseHeaders });
        const data = await res.json();
        if (data.success) {
            const badge = document.getElementById('active-badge');
            if (data.is_active) {
                badge.textContent = '● AKTİF';
                badge.className   = 'bg-[#CCFF00] text-[#0A0A0A] border-[2px] border-[#0A0A0A] font-black text-[9px] uppercase tracking-widest px-2.5 py-1';
                btn._txt = '⏸ Pasif Et';
                btn.className = btn.className.replace('bg-[#CCFF00]','bg-[#FF2D2D]').replace('text-[#0A0A0A]','text-white').replace('hover:bg-[#aadd00]','hover:bg-[#cc0000]');
            } else {
                badge.textContent = '● PASİF';
                badge.className   = 'bg-[#FF2D2D] text-white font-black text-[9px] uppercase tracking-widest px-2.5 py-1';
                btn._txt = '▶ Aktif Et';
                btn.className = btn.className.replace('bg-[#FF2D2D]','bg-[#CCFF00]').replace('text-white','text-[#0A0A0A]').replace('hover:bg-[#cc0000]','hover:bg-[#aadd00]');
            }
            toast(data.message);
        } else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
    btnState(btn, false);
};

/* ── QUESTION ADD ───────────────────────────────────────────── */
document.getElementById('question-add-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = this.querySelector('button[type=submit]');
    btnState(btn, true);
    try {
        const res  = await fetch(this.dataset.url, {
            method: 'POST', headers: jsonHeaders, body: JSON.stringify(formToJson(this))
        });
        const data = await res.json();
        if (data.success) {
            appendQuestion(data.question);
            this.reset();
            this.querySelector('[name="correct_answer"]').checked = true;
            toast(data.message);
        } else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
    btnState(btn, false);
});

/* ── QUESTION UPDATE (event delegation) ─────────────────────── */
document.getElementById('questions-container').addEventListener('submit', async function (e) {
    const form = e.target.closest('.q-update-form');
    if (!form) return;
    e.preventDefault();
    const qId = form.dataset.questionId;
    const btn  = form.querySelector('button[type=submit]');
    btnState(btn, true);
    try {
        const res  = await fetch(form.dataset.url, {
            method: 'PUT', headers: jsonHeaders, body: JSON.stringify(formToJson(form))
        });
        const data = await res.json();
        if (data.success) {
            updateQuestionRow(qId, data.question);
            toggleQEdit(qId);
            toast(data.message);
        } else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
    btnState(btn, false);
});

/* ── QUESTION DELETE ────────────────────────────────────────── */
window.deleteQuestion = async function (id, url) {
    if (!confirm('Soruyu sil?')) return;
    try {
        const res  = await fetch(url, { method: 'DELETE', headers: baseHeaders });
        const data = await res.json();
        if (data.success) {
            document.getElementById('question-' + id)?.remove();
            reindexQuestions();
            toast(data.message);
        } else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
};

/* ── QUIZ DELETE ────────────────────────────────────────────── */
window.deleteQuiz = async function (url) {
    if (!confirm('Bu sınavı silmek istediğinizden emin misiniz?')) return;
    try {
        const res  = await fetch(url, { method: 'DELETE', headers: baseHeaders });
        const data = await res.json();
        if (data.success) window.location.href = data.redirect;
        else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
};

/* ── TOGGLE Q EDIT ──────────────────────────────────────────── */
window.toggleQEdit = function (id) {
    document.getElementById('q-edit-' + id)?.classList.toggle('hidden');
};

/* ── UPDATE QUESTION ROW ────────────────────────────────────── */
function updateQuestionRow(id, q) {
    const row = document.getElementById('question-' + id);
    if (!row) return;
    const textEl = row.querySelector('.q-text');
    if (textEl) textEl.textContent = q.question;
    const optsHtml = q.options.map((opt, oi) => {
        const isCorrect = oi === q.correct_answer;
        return `<div class="flex items-center gap-2 text-xs ${isCorrect ? 'text-[#00a000] font-black' : 'text-[#888]'}">
            <span class="font-mono shrink-0">${String.fromCharCode(65+oi)})</span>
            <span>${ESC(opt)}</span>
            ${isCorrect ? '<span class="shrink-0 text-[#00a000]">✓</span>' : ''}
        </div>`;
    }).join('');
    const grid = row.querySelector('.grid.grid-cols-2');
    if (grid) grid.innerHTML = optsHtml;
    // Update edit form values
    const form = row.querySelector('.q-update-form');
    if (form) {
        form.querySelector('[name="question"]').value = q.question;
        form.querySelectorAll('[name="options[]"]').forEach((inp, i) => { inp.value = q.options[i] || ''; });
        form.querySelectorAll('[name="correct_answer"]').forEach(r => { r.checked = parseInt(r.value) === q.correct_answer; });
        const expInp = form.querySelector('[name="explanation"]');
        if (expInp) expInp.value = q.explanation || '';
    }
}

/* ── APPEND QUESTION ────────────────────────────────────────── */
function appendQuestion(q) {
    document.getElementById('empty-state')?.remove();
    const container = document.getElementById('questions-container');
    const count     = container.querySelectorAll('[id^="question-"]').length + 1;
    const num       = String(count).padStart(2, '0');
    const updUrl    = '/admin/questions/' + q.id;
    const delUrl    = '/admin/questions/' + q.id;

    const optionsHTML = q.options.map((opt, oi) => {
        const isCorrect = oi === q.correct_answer;
        return `<div class="flex items-center gap-2 text-xs ${isCorrect ? 'text-[#00a000] font-black' : 'text-[#888]'}">
            <span class="font-mono shrink-0">${String.fromCharCode(65+oi)})</span>
            <span>${ESC(opt)}</span>
            ${isCorrect ? '<span class="shrink-0 text-[#00a000]">✓</span>' : ''}
        </div>`;
    }).join('');

    const editOpts = q.options.map((opt, oi) => `
        <div class="flex items-center gap-3">
            <input type="radio" name="correct_answer" value="${oi}" ${oi===q.correct_answer?'checked':''} required class="w-4 h-4" style="accent-color:#CCFF00">
            <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">${String.fromCharCode(65+oi)}</span>
            <input type="text" name="options[]" value="${ESC(opt)}" required class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]">
        </div>`).join('') +
        (q.options.length < 4 ? Array.from({length: 4 - q.options.length}, (_,x) => `
        <div class="flex items-center gap-3">
            <input type="radio" name="correct_answer" value="${q.options.length+x}" class="w-4 h-4" style="accent-color:#CCFF00">
            <span class="font-mono text-[10px] text-[#888] w-5 shrink-0">${String.fromCharCode(65+q.options.length+x)}</span>
            <input type="text" name="options[]" class="flex-1 border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]" placeholder="Boş bırakılabilir">
        </div>`).join('') : '');

    container.insertAdjacentHTML('beforeend', `
<div class="border-t border-[#eee]" id="question-${q.id}">
  <div class="px-5 py-4 flex items-start gap-4">
    <span class="font-mono text-[10px] text-[#888] w-6 shrink-0 mt-0.5 q-index">${num}</span>
    <div class="flex-1 min-w-0">
      <p class="font-bold text-sm text-[#0A0A0A] leading-snug mb-2 q-text">${ESC(q.question)}</p>
      <div class="grid grid-cols-2 gap-1.5">${optionsHTML}</div>
      ${q.explanation ? `<p class="text-[10px] text-[#888] mt-2 font-mono italic">💡 ${ESC(q.explanation)}</p>` : ''}
    </div>
    <div class="flex gap-3 shrink-0">
      <button type="button" onclick="toggleQEdit(${q.id})" class="font-mono text-[10px] text-[#0047FF] hover:underline uppercase tracking-widest">Düzenle</button>
      <button onclick="deleteQuestion(${q.id},'${delUrl}')" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase tracking-widest">Sil</button>
    </div>
  </div>
  <div id="q-edit-${q.id}" class="hidden border-t border-[#f0f0e8] bg-[#fafaf5] p-5">
    <form class="q-update-form space-y-4" data-question-id="${q.id}" data-url="${updUrl}">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SORU METNİ *</label>
        <textarea name="question" rows="2" required class="w-full border-[3px] border-[#0A0A0A] px-4 py-2 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000] resize-none">${ESC(q.question)}</textarea>
      </div>
      <div class="space-y-2">
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">SEÇENEKLER</label>
        ${editOpts}
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1 block">AÇIKLAMA</label>
        <input type="text" name="explanation" value="${ESC(q.explanation||'')}" class="w-full border-[3px] border-[#0A0A0A] px-3 py-1.5 font-bold text-sm bg-white focus:outline-none focus:bg-[#FFE000]">
      </div>
      <div class="flex gap-2 pt-2">
        <button type="submit" class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">Kaydet</button>
        <button type="button" onclick="toggleQEdit(${q.id})" class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors">İptal</button>
      </div>
    </form>
  </div>
</div>`);

    // Update counts
    reindexQuestions();
}

function reindexQuestions() {
    const container = document.getElementById('questions-container');
    const rows = container.querySelectorAll('[id^="question-"]');
    rows.forEach((row, i) => {
        const idx = row.querySelector('.q-index');
        if (idx) idx.textContent = String(i + 1).padStart(2, '0');
    });
    const n = rows.length;
    const badge = document.getElementById('q-count-badge');
    if (badge) badge.textContent = n + ' SORU';
    const txt = document.getElementById('question-count-text');
    if (txt) txt.textContent = n + ' soru';
}

})();
</script>
@endpush
@endsection
