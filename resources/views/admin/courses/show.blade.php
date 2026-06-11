@extends('layouts.app')
@section('title', $course->title . ' – Admin')
@section('content')

{{-- Toast --}}
<div id="ajax-toast" class="fixed top-4 right-4 z-50 px-5 py-3 font-black text-xs uppercase tracking-widest border-[3px] opacity-0 pointer-events-none" style="transition:opacity 0.3s,transform 0.3s;transform:translateY(-8px)"></div>

<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[1100px] mx-auto px-6 py-10">

  {{-- Başlık --}}
  <div class="flex items-start justify-between mb-8 gap-4">
    <div>
      <a href="{{ route('admin.courses.index') }}" class="text-mono-sm text-[#888] hover:text-[#0A0A0A]">← Kurs Listesi</a>
      <h1 class="font-black text-2xl uppercase tracking-tight mt-1 leading-snug">{{ $course->title }}</h1>
      <div class="flex gap-2 mt-2">
        @if($course->published)<span class="tag-lime text-[9px]">YAYINDA</span>@else<span class="tag-red text-[9px]">TASLAK</span>@endif
        @if($course->is_mandatory)<span class="tag-yellow text-[9px]">ZORUNLU</span>@endif
        <span class="tag-black text-[9px]">{{ $course->category }}</span>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 shrink-0">
      <a href="{{ route('admin.courses.edit', $course->id) }}"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:3px 3px 0 #0A0A0A">Düzenle</a>
      <button onclick="deleteCourse({{ $course->id }}, '{{ route('admin.courses.destroy', $course->id) }}')"
        class="bg-white text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#FF2D2D] hover:bg-[#FF2D2D] hover:text-white transition-colors"
        style="box-shadow:3px 3px 0 #FF2D2D">Sil</button>
    </div>
  </div>

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-bold text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Sol: Bölümler ve Dersler --}}
    <div class="lg:col-span-2 space-y-4">

      {{-- Bölüm Ekle --}}
      <div class="border-[3px] border-[#0A0A0A] bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
        <div class="bg-[#0A0A0A] px-5 py-3 flex items-center justify-between">
          <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">Bölümler & Dersler</p>
          <span class="font-mono text-[10px] text-[#555]" id="section-count-badge">{{ $course->sections->count() }} bölüm</span>
        </div>
        <div class="p-4">
          <form id="section-add-form" action="{{ route('admin.sections.store', $course->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="title" placeholder="Yeni bölüm adı..." class="input-brut flex-1 py-2 text-sm">
            <input type="number" name="order" placeholder="Sıra" class="input-brut w-20 py-2 text-sm">
            <button type="submit"
              class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#0A0A0A] whitespace-nowrap hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
              style="box-shadow:3px 3px 0 #0A0A0A">+ Bölüm Ekle</button>
          </form>
        </div>
      </div>

      {{-- Bölümler listesi --}}
      <div id="sections-container" class="space-y-4">
        @foreach($course->sections->sortBy('order') as $section)
        <div class="border-[3px] border-[#0A0A0A] bg-white" id="section-{{ $section->id }}">
          <div class="bg-[#111] px-5 py-3 flex items-center justify-between">
            <p class="font-black text-[#F5F0E8] text-sm uppercase tracking-tight">{{ $section->title }}</p>
            <div class="flex items-center gap-3">
              <span class="font-mono text-[10px] text-[#555]" id="section-lesson-count-{{ $section->id }}">{{ $section->lessons->count() }} ders</span>
              <button onclick="deleteSection({{ $section->id }}, '{{ route('admin.sections.destroy', $section->id) }}')"
                class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
            </div>
          </div>

          {{-- Ders listesi --}}
          <div id="lessons-list-{{ $section->id }}">
            @foreach($section->lessons->sortBy('order') as $lesson)
            @php
              $ws = $watchStats[$lesson->id] ?? null;
              $watchPct = ($ws && $lesson->duration > 0)
                ? min(100, round($ws->avg_watched_sec / $lesson->duration * 100))
                : null;
            @endphp
            <div class="px-5 py-3 border-b border-[#f0f0f0] flex items-center justify-between" id="lesson-row-{{ $lesson->id }}">
              <div class="flex items-center gap-3 flex-1 min-w-0 flex-wrap">
                <span class="font-mono text-[9px] text-[#888] uppercase w-16 shrink-0">{{ $lesson->type }}</span>
                <span class="font-bold text-sm text-[#0A0A0A] truncate lesson-title-{{ $lesson->id }}">{{ $lesson->title }}</span>
                @if($lesson->video_path)<span class="tag-lime text-[9px] shrink-0">VIDEO YÜKLÜ</span>
                @elseif($lesson->video_url)<span class="tag-blue text-[9px] shrink-0">URL</span>@endif
                @if($ws)
                  <span class="font-mono text-[9px] text-[#888] shrink-0">👁 {{ $ws->viewer_count }} izleyici</span>
                  @if($watchPct !== null)
                    <span class="font-mono text-[9px] shrink-0 {{ $watchPct >= 90 ? 'text-green-600' : ($watchPct >= 50 ? 'text-orange-500' : 'text-red-500') }}">ort. %{{ $watchPct }}</span>
                  @endif
                  @if($ws->completed_count > 0)
                    <span class="tag-lime text-[9px] shrink-0">{{ $ws->completed_count }} tamamladı</span>
                  @endif
                @endif
              </div>
              <div class="flex gap-3 ml-3 shrink-0">
                <button onclick="toggleLessonEdit({{ $lesson->id }})" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase">Düzenle</button>
                <button onclick="deleteLesson({{ $lesson->id }}, '{{ route('admin.lessons.destroy', $lesson->id) }}')"
                  class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
              </div>
            </div>
            {{-- Ders Düzenleme Formu --}}
            <div id="lesson-edit-{{ $lesson->id }}" class="hidden border-t border-[#f0f0f0] bg-[#fafaf5] p-4">
              <form id="lesson-update-{{ $lesson->id }}" action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="text-mono-sm mb-1 block">BAŞLIK</label>
                    <input type="text" name="title" value="{{ $lesson->title }}" class="input-brut py-1.5 text-sm">
                  </div>
                  <div>
                    <label class="text-mono-sm mb-1 block">TİP</label>
                    <select name="type" class="input-brut py-1.5 text-sm">
                      @foreach(['VIDEO','DOCUMENT','QUIZ','SIMULATION'] as $t)
                      <option value="{{ $t }}" {{ $lesson->type === $t ? 'selected' : '' }}>{{ $t }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="text-mono-sm mb-1 block">VIDEO URL (harici)</label>
                    <input type="url" name="video_url" value="{{ $lesson->video_url }}" class="input-brut py-1.5 text-sm" placeholder="https://...">
                  </div>
                  <div>
                    <label class="text-mono-sm mb-1 block">VİDEO DOSYASI</label>
                    <input type="file" name="video" accept="video/mp4,video/mov,video/avi,video/webm" class="block text-sm font-medium text-[#0A0A0A] file:mr-3 file:py-1.5 file:px-4 file:border-[2px] file:border-[#0A0A0A] file:font-bold file:text-xs file:uppercase file:bg-[#FFE000] file:cursor-pointer">
                    @if($lesson->video_path)
                    <p class="text-[10px] text-[#888] font-mono mt-1">✓ Yüklü: {{ basename($lesson->video_path) }}</p>
                    @endif
                  </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                  <div>
                    <label class="text-mono-sm mb-1 block">SÜRE (sn)</label>
                    <input type="number" name="duration" value="{{ $lesson->duration }}" min="0" class="input-brut py-1.5 text-sm">
                  </div>
                  <div>
                    <label class="text-mono-sm mb-1 block">SIRA</label>
                    <input type="number" name="order" value="{{ $lesson->order }}" class="input-brut py-1.5 text-sm">
                  </div>
                </div>
                <div class="flex gap-2">
                  <button type="submit"
                    class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">Kaydet</button>
                  <button type="button" onclick="toggleLessonEdit({{ $lesson->id }})"
                    class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors">İptal</button>
                </div>
              </form>
            </div>
            @endforeach
          </div>{{-- /lessons-list --}}

          {{-- Ders Ekle --}}
          <div class="p-4 bg-[#fafaf5]">
            <form class="lesson-add-form" action="{{ route('admin.lessons.store', $section->id) }}" method="POST" enctype="multipart/form-data" data-section-id="{{ $section->id }}">
              @csrf
              <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">YENİ DERS EKLE</p>
              <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                  <label class="text-mono-sm mb-1 block">BAŞLIK *</label>
                  <input type="text" name="title" required class="input-brut py-1.5 text-sm" placeholder="Ders başlığı">
                </div>
                <div>
                  <label class="text-mono-sm mb-1 block">TİP *</label>
                  <select name="type" class="input-brut py-1.5 text-sm">
                    <option value="VIDEO">VIDEO</option>
                    <option value="DOCUMENT">DOCUMENT</option>
                    <option value="QUIZ">QUIZ</option>
                    <option value="SIMULATION">SIMULATION</option>
                  </select>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                  <label class="text-mono-sm mb-1 block">VİDEO URL (harici)</label>
                  <input type="url" name="video_url" class="input-brut py-1.5 text-sm" placeholder="https://...">
                </div>
                <div>
                  <label class="text-mono-sm mb-1 block">VİDEO DOSYASI YÜKLE</label>
                  <input type="file" name="video" accept="video/mp4,video/mov,video/avi,video/webm" class="block text-sm font-medium text-[#0A0A0A] file:mr-3 file:py-1.5 file:px-4 file:border-[2px] file:border-[#0A0A0A] file:font-bold file:text-xs file:uppercase file:bg-[#FFE000] file:cursor-pointer">
                </div>
              </div>
              <div class="grid grid-cols-3 gap-3 mb-3">
                <div>
                  <label class="text-mono-sm mb-1 block">SÜRE (sn)</label>
                  <input type="number" name="duration" min="0" class="input-brut py-1.5 text-sm">
                </div>
                <div>
                  <label class="text-mono-sm mb-1 block">SIRA</label>
                  <input type="number" name="order" value="{{ $section->lessons->count() }}" class="input-brut py-1.5 text-sm">
                </div>
              </div>
              <button type="submit"
                class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
                style="box-shadow:3px 3px 0 #0A0A0A">+ Ders Ekle</button>
            </form>
          </div>
        </div>
        @endforeach
      </div>{{-- /sections-container --}}

    </div>

    {{-- Sağ: Özet + Quizler --}}
    <div class="space-y-4">
      <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
        <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">KURS BİLGİLERİ</p>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between"><span class="text-[#888]">Eğitmen</span><span class="font-bold">{{ $course->instructor->name ?? '—' }}</span></div>
          <div class="flex justify-between"><span class="text-[#888]">Seviye</span><span class="font-bold">{{ $course->level }}</span></div>
          <div class="flex justify-between"><span class="text-[#888]">Geçer Not</span><span class="font-bold">%{{ $course->passing_score }}</span></div>
        </div>
      </div>

      <div class="border-[3px] border-[#0A0A0A] bg-white p-5">
        <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">BAĞLI SINAVLAR</p>
        @if($course->quizzes->isEmpty())
        <p class="text-xs text-[#888] mb-3">Bu kursa bağlı sınav yok.</p>
        @else
        @foreach($course->quizzes as $q)
        <div class="flex items-center justify-between py-2 border-b border-[#f0f0f0] last:border-b-0">
          <span class="font-bold text-xs text-[#0A0A0A]">{{ $q->title }}</span>
          <a href="{{ route('admin.quizzes.show', $q->id) }}" class="font-mono text-[10px] text-[#0047FF] hover:underline">Yönet</a>
        </div>
        @endforeach
        @endif
        <a href="{{ route('admin.quizzes.create') }}?course_id={{ $course->id }}"
          class="inline-flex bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2.5 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors mt-3"
          style="box-shadow:3px 3px 0 #0A0A0A">+ Sınav Ekle</a>
      </div>

      {{-- Sertifika Ön Koşulları --}}
      <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
        <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-1">SERTİFİKA ÖN KOŞULLARI</p>
        <p class="text-[10px] text-[#888] mb-3">Bu kursun sertifikası için hangi kursların sınavları geçilmeli?</p>
        <form id="prereq-form" action="{{ route('admin.courses.cert-config', $course->id) }}" method="POST">
          @csrf
          <div class="space-y-1 max-h-48 overflow-y-auto mb-3 border border-[#e0e0e0] p-2">
            @forelse($allCourses as $c)
            <label class="flex items-center gap-2 cursor-pointer py-1 hover:bg-[#fafaf5] px-1">
              <input type="checkbox" name="prerequisites[]" value="{{ $c->id }}"
                class="w-3.5 h-3.5 shrink-0"
                {{ in_array($c->id, $prereqIds) ? 'checked' : '' }}>
              <span class="text-xs font-medium text-[#0A0A0A] leading-tight">{{ $c->title }}</span>
            </label>
            @empty
            <p class="text-xs text-[#888]">Başka kurs yok.</p>
            @endforelse
          </div>
          <button type="submit" id="prereq-save-btn"
            class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors w-full"
            style="box-shadow:3px 3px 0 #0A0A0A">Kaydet</button>
        </form>
      </div>
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

/* ── Fetch helpers ──────────────────────────────────────────── */
const headers = (extra = {}) => ({ 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, ...extra });

async function xfetch(url, method, body) {
    return fetch(url, { method, headers: headers(), body });
}

/* ── SECTION ADD ────────────────────────────────────────────── */
document.getElementById('section-add-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = this.querySelector('button[type=submit]');
    btnState(btn, true);
    try {
        const res  = await xfetch(this.action, 'POST', new FormData(this));
        const data = await res.json();
        if (data.success) {
            appendSection(data.section);
            this.reset();
            const badge = document.getElementById('section-count-badge');
            if (badge) badge.textContent = (parseInt(badge.textContent) + 1) + ' bölüm';
            toast(data.message);
        } else { toast(data.message || 'Hata.', false); }
    } catch { toast('Bağlantı hatası.', false); }
    btnState(btn, false);
});

/* ── SECTION DELETE ─────────────────────────────────────────── */
window.deleteSection = async function (id, url) {
    if (!confirm('Bölümü sil? İçindeki dersler de silinir.')) return;
    try {
        const res  = await xfetch(url, 'DELETE');
        const data = await res.json();
        if (data.success) { document.getElementById('section-' + id)?.remove(); toast(data.message); }
        else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
};

/* ── LESSON ADD & UPDATE (event delegation) ─────────────────── */
document.getElementById('sections-container').addEventListener('submit', async function (e) {
    const form = e.target.closest('form');
    if (!form) return;

    if (form.classList.contains('lesson-add-form')) {
        e.preventDefault();
        const btn = form.querySelector('button[type=submit]');
        btnState(btn, true);
        try {
            const res  = await xfetch(form.action, 'POST', new FormData(form));
            const data = await res.json();
            if (data.success) {
                insertLessonRow(form.dataset.sectionId, data.lesson);
                form.reset();
                toast(data.message);
            } else toast(data.message || 'Hata.', false);
        } catch { toast('Bağlantı hatası.', false); }
        btnState(btn, false);

    } else if (form.id?.startsWith('lesson-update-')) {
        e.preventDefault();
        const lessonId = form.id.replace('lesson-update-', '');
        const btn = form.querySelector('button[type=submit]');
        btnState(btn, true);
        try {
            const res  = await xfetch(form.action, 'POST', new FormData(form));
            const data = await res.json();
            if (data.success) {
                const titleEl = document.querySelector('.lesson-title-' + lessonId);
                if (titleEl) titleEl.textContent = data.lesson.title;
                toggleLessonEdit(lessonId);
                toast(data.message);
            } else toast(data.message || 'Hata.', false);
        } catch { toast('Bağlantı hatası.', false); }
        btnState(btn, false);
    }
});

/* ── LESSON DELETE ──────────────────────────────────────────── */
window.deleteLesson = async function (id, url) {
    if (!confirm('Dersi sil?')) return;
    try {
        const res  = await xfetch(url, 'DELETE');
        const data = await res.json();
        if (data.success) {
            document.getElementById('lesson-row-' + id)?.remove();
            document.getElementById('lesson-edit-' + id)?.remove();
            toast(data.message);
        } else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
};

/* ── COURSE DELETE ──────────────────────────────────────────── */
window.deleteCourse = async function (id, url) {
    if (!confirm('Bu kursu silmek istediğinizden emin misiniz?')) return;
    try {
        const res  = await xfetch(url, 'DELETE');
        const data = await res.json();
        if (data.success) window.location.href = data.redirect;
        else toast(data.message || 'Hata.', false);
    } catch { toast('Bağlantı hatası.', false); }
};

/* ── TOGGLE LESSON EDIT ─────────────────────────────────────── */
window.toggleLessonEdit = function (id) {
    document.getElementById('lesson-edit-' + id)?.classList.toggle('hidden');
};

/* ── DOM: INSERT LESSON ROW ─────────────────────────────────── */
function insertLessonRow(sectionId, lesson) {
    const list = document.getElementById('lessons-list-' + sectionId);
    if (!list) return;
    list.insertAdjacentHTML('beforeend', lessonRowHTML(lesson));
    const cnt = document.getElementById('section-lesson-count-' + sectionId);
    if (cnt) cnt.textContent = (parseInt(cnt.textContent) || 0) + 1 + ' ders';
}

function lessonRowHTML(l) {
    const delUrl = '/admin/lessons/' + l.id;
    const updUrl = '/admin/lessons/' + l.id;
    const badges = (l.video_path ? '<span class="tag-lime text-[9px] shrink-0">VIDEO YÜKLÜ</span>'
                 : l.video_url  ? '<span class="tag-blue text-[9px] shrink-0">URL</span>' : '');
    const opts   = ['VIDEO','DOCUMENT','QUIZ','SIMULATION']
                    .map(t => `<option value="${t}"${l.type===t?' selected':''}>${t}</option>`).join('');
    return `
<div class="px-5 py-3 border-b border-[#f0f0f0] flex items-center justify-between" id="lesson-row-${l.id}">
  <div class="flex items-center gap-3 flex-1 min-w-0">
    <span class="font-mono text-[9px] text-[#888] uppercase w-16 shrink-0">${ESC(l.type)}</span>
    <span class="font-bold text-sm text-[#0A0A0A] truncate lesson-title-${l.id}">${ESC(l.title)}</span>
    ${badges}
  </div>
  <div class="flex gap-3 ml-3 shrink-0">
    <button onclick="toggleLessonEdit(${l.id})" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase">Düzenle</button>
    <button onclick="deleteLesson(${l.id},'${delUrl}')" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
  </div>
</div>
<div id="lesson-edit-${l.id}" class="hidden border-t border-[#f0f0f0] bg-[#fafaf5] p-4">
  <form id="lesson-update-${l.id}" action="${updUrl}" method="POST" enctype="multipart/form-data" class="space-y-3">
    <input type="hidden" name="_token" value="${CSRF}">
    <input type="hidden" name="_method" value="PUT">
    <div class="grid grid-cols-2 gap-3">
      <div><label class="text-mono-sm mb-1 block">BAŞLIK</label>
        <input type="text" name="title" value="${ESC(l.title)}" class="input-brut py-1.5 text-sm"></div>
      <div><label class="text-mono-sm mb-1 block">TİP</label>
        <select name="type" class="input-brut py-1.5 text-sm">${opts}</select></div>
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div><label class="text-mono-sm mb-1 block">VIDEO URL</label>
        <input type="url" name="video_url" value="${ESC(l.video_url||'')}" class="input-brut py-1.5 text-sm" placeholder="https://..."></div>
      <div><label class="text-mono-sm mb-1 block">VİDEO DOSYASI</label>
        <input type="file" name="video" accept="video/mp4,video/mov,video/avi,video/webm" class="block text-sm font-medium text-[#0A0A0A] file:mr-3 file:py-1.5 file:px-4 file:border-[2px] file:border-[#0A0A0A] file:font-bold file:text-xs file:uppercase file:bg-[#FFE000] file:cursor-pointer"></div>
    </div>
    <div class="grid grid-cols-2 gap-3">
      <div><label class="text-mono-sm mb-1 block">SÜRE (sn)</label>
        <input type="number" name="duration" value="${l.duration||''}" min="0" class="input-brut py-1.5 text-sm"></div>
      <div><label class="text-mono-sm mb-1 block">SIRA</label>
        <input type="number" name="order" value="${l.order||0}" class="input-brut py-1.5 text-sm"></div>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors">Kaydet</button>
      <button type="button" onclick="toggleLessonEdit(${l.id})" class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors">İptal</button>
    </div>
  </form>
</div>`;
}

/* ── DOM: APPEND SECTION ────────────────────────────────────── */
function appendSection(section) {
    const delUrl    = '/admin/sections/' + section.id;
    const addUrl    = '/admin/sections/' + section.id + '/lessons';
    const container = document.getElementById('sections-container');
    const html = `
<div class="border-[3px] border-[#0A0A0A] bg-white" id="section-${section.id}">
  <div class="bg-[#111] px-5 py-3 flex items-center justify-between">
    <p class="font-black text-[#F5F0E8] text-sm uppercase tracking-tight">${ESC(section.title)}</p>
    <div class="flex items-center gap-3">
      <span class="font-mono text-[10px] text-[#555]" id="section-lesson-count-${section.id}">0 ders</span>
      <button onclick="deleteSection(${section.id},'${delUrl}')" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
    </div>
  </div>
  <div id="lessons-list-${section.id}"></div>
  <div class="p-4 bg-[#fafaf5]">
    <form class="lesson-add-form" action="${addUrl}" method="POST" enctype="multipart/form-data" data-section-id="${section.id}">
      <input type="hidden" name="_token" value="${CSRF}">
      <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">YENİ DERS EKLE</p>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div><label class="text-mono-sm mb-1 block">BAŞLIK *</label>
          <input type="text" name="title" required class="input-brut py-1.5 text-sm" placeholder="Ders başlığı"></div>
        <div><label class="text-mono-sm mb-1 block">TİP *</label>
          <select name="type" class="input-brut py-1.5 text-sm">
            <option value="VIDEO">VIDEO</option><option value="DOCUMENT">DOCUMENT</option>
            <option value="QUIZ">QUIZ</option><option value="SIMULATION">SIMULATION</option>
          </select></div>
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div><label class="text-mono-sm mb-1 block">VİDEO URL</label>
          <input type="url" name="video_url" class="input-brut py-1.5 text-sm" placeholder="https://..."></div>
        <div><label class="text-mono-sm mb-1 block">VİDEO DOSYASI</label>
          <input type="file" name="video" accept="video/mp4,video/mov,video/avi,video/webm" class="block text-sm font-medium text-[#0A0A0A] file:mr-3 file:py-1.5 file:px-4 file:border-[2px] file:border-[#0A0A0A] file:font-bold file:text-xs file:uppercase file:bg-[#FFE000] file:cursor-pointer"></div>
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div><label class="text-mono-sm mb-1 block">SÜRE (sn)</label>
          <input type="number" name="duration" min="0" class="input-brut py-1.5 text-sm"></div>
        <div><label class="text-mono-sm mb-1 block">SIRA</label>
          <input type="number" name="order" value="0" class="input-brut py-1.5 text-sm"></div>
      </div>
      <button type="submit" class="bg-[#FFE000] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-5 py-2 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors" style="box-shadow:3px 3px 0 #0A0A0A">+ Ders Ekle</button>
    </form>
  </div>
</div>`;
    container.insertAdjacentHTML('beforeend', html);
}

/* ── CERT PREREQUISITES ─────────────────────────────────────── */
const prereqForm = document.getElementById('prereq-form');
if (prereqForm) {
    prereqForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('prereq-save-btn');
        btnState(btn, true);
        try {
            const res  = await xfetch(this.action, 'POST', new FormData(this));
            const data = await res.json();
            if (data.success) toast(data.message);
            else toast(data.message || 'Hata.', false);
        } catch { toast('Bağlantı hatası.', false); }
        btnState(btn, false);
    });
}

})();
</script>
@endpush
@endsection
