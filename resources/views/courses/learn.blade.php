@extends('layouts.app')
@section('title', $course->title . ' – LiftAcademy')
@section('content')

<div class="bg-[#0A0A0A] min-h-screen text-[#F5F0E8]">
  <div class="max-w-[1400px] mx-auto px-4 py-8">

    {{-- Başlık --}}
    <div class="flex items-center justify-between mb-6">
      <div>
        <a href="{{ route('courses.show', $course->slug) }}" class="font-mono text-[10px] text-[#555] hover:text-[#FFE000] uppercase tracking-widest transition-colors">← Kursa Dön</a>
        <h1 class="font-black text-xl text-[#FFE000] uppercase tracking-tight mt-1">{{ $course->title }}</h1>
      </div>
      @if($allVideosCompleted && $quizzes->isNotEmpty())
      <div class="bg-[#CCFF00] text-[#0A0A0A] px-4 py-2 font-black text-xs uppercase tracking-widest border-[3px] border-[#CCFF00]">
        ✓ Videolar Tamamlandı — Sınav Açık!
      </div>
      @endif
    </div>

    <div class="flex flex-col lg:flex-row gap-5">

      {{-- SOL: Video player --}}
      <div class="flex-1">

        {{-- Video alanı --}}
        <div id="video-container" class="bg-[#111] border-[3px] border-[#1e1e1e] aspect-video flex items-center justify-center">
          <div id="video-placeholder" class="text-center">
            <p class="font-mono text-[10px] text-[#555] uppercase tracking-widest mb-3">Soldaki listeden ders seçin</p>
            <p class="text-4xl">▶</p>
          </div>
          <video id="video-player" class="hidden w-full h-full" controls controlsList="nodownload">
            <source id="video-source" src="" type="video/mp4">
          </video>
          <iframe id="video-iframe" class="hidden w-full h-full" frameborder="0" allowfullscreen></iframe>
        </div>

        {{-- Aktif ders bilgisi --}}
        <div id="lesson-info" class="hidden mt-4 border-[3px] border-[#1e1e1e] px-5 py-4">
          <div class="flex items-center justify-between">
            <div>
              <p id="lesson-title" class="font-black text-sm text-[#F5F0E8] uppercase tracking-tight"></p>
              <p id="lesson-type" class="font-mono text-[10px] text-[#555] uppercase mt-1"></p>
            </div>
            <button id="complete-btn" onclick="markComplete()"
              class="bg-[#CCFF00] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#CCFF00] hover:bg-transparent hover:text-[#CCFF00] transition-all hidden">
              ✓ Tamamlandı İşaretle
            </button>
          </div>
        </div>

        {{-- Sınav Kilidi / Açık Banner --}}
        @if($quizzes->isNotEmpty())
        <div id="quiz-banner" class="mt-4 border-[3px] {{ $allVideosCompleted ? 'border-[#CCFF00] bg-[#0d1a00]' : 'border-[#333] bg-[#111]' }} px-5 py-4">
          <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
              <p class="font-black text-sm uppercase tracking-tight {{ $allVideosCompleted ? 'text-[#CCFF00]' : 'text-[#555]' }}">
                {{ $allVideosCompleted ? '🔓 Sınavlar Açık!' : '🔒 Sınavlar Kilitli' }}
              </p>
              <p class="font-mono text-[10px] mt-1 {{ $allVideosCompleted ? 'text-[#888]' : 'text-[#333]' }}">
                {{ $allVideosCompleted ? 'Tüm videoları izlediniz. Sınava girebilirsiniz.' : 'Sınava girebilmek için tüm videoları izleyin.' }}
              </p>
            </div>
            @if($allVideosCompleted)
            <div class="flex flex-wrap gap-2">
              @foreach($quizzes as $q)
              <a href="{{ route('quizzes.show', $q->id) }}"
                class="bg-[#CCFF00] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#CCFF00] hover:bg-transparent hover:text-[#CCFF00] transition-all">
                {{ $q->title }} ↗
              </a>
              @endforeach
            </div>
            @endif
          </div>
        </div>
        @endif

      </div>

      {{-- SAĞ: Ders listesi --}}
      <div class="w-full lg:w-80 shrink-0">
        <div class="border-[3px] border-[#1e1e1e] bg-[#0d0d0d]">
          <div class="px-4 py-3 border-b border-[#1e1e1e] flex items-center justify-between">
            <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest">DERSLER</p>
            <span class="font-mono text-[10px] text-[#555]">
              {{ count($completedIds) }} / {{ $course->sections->flatMap(fn($s) => $s->lessons)->count() }} tamamlandı
            </span>
          </div>

          {{-- İlerleme çubuğu --}}
          @php
            $totalLessons = $course->sections->flatMap(fn($s) => $s->lessons)->count();
            $progressPct  = $totalLessons > 0 ? round(count($completedIds) / $totalLessons * 100) : 0;
          @endphp
          <div class="px-4 py-2 border-b border-[#1e1e1e]">
            <div class="h-1 bg-[#1a1a1a] w-full">
              <div class="h-full bg-[#CCFF00] transition-all duration-500" id="progress-bar" style="width:{{ $progressPct }}%"></div>
            </div>
          </div>

          <div class="overflow-y-auto" style="max-height:60vh">
            @foreach($course->sections->sortBy('order') as $section)
            <div>
              <div class="px-4 py-2.5 bg-[#111] border-b border-[#1e1e1e]">
                <p class="font-black text-[10px] uppercase tracking-widest text-[#888]">{{ $section->title }}</p>
              </div>
              @foreach($section->lessons->sortBy('order') as $lesson)
              @php $isDone = in_array($lesson->id, $completedIds); @endphp
              <button
                onclick="loadLesson({{ $lesson->id }}, '{{ addslashes($lesson->title) }}', '{{ $lesson->type }}', '{{ $lesson->video_src }}', '{{ $lesson->video_url }}')"
                class="w-full text-left px-4 py-3 border-b border-[#1a1a1a] flex items-center gap-3 hover:bg-[#161616] transition-colors lesson-btn"
                data-lesson-id="{{ $lesson->id }}">
                <div class="w-5 h-5 shrink-0 flex items-center justify-center border {{ $isDone ? 'bg-[#CCFF00] border-[#CCFF00]' : 'border-[#333]' }}">
                  @if($isDone)
                  <span class="text-[#0A0A0A] font-black text-[10px]">✓</span>
                  @else
                  <span class="font-mono text-[9px] text-[#555]">{{ $lesson->type === 'VIDEO' ? '▶' : '📄' }}</span>
                  @endif
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-bold text-xs text-[#ccc] truncate leading-snug {{ $isDone ? 'line-through opacity-60' : '' }}">{{ $lesson->title }}</p>
                  <p class="font-mono text-[9px] text-[#555] uppercase mt-0.5">
                    {{ $lesson->type }}
                    @if($lesson->duration) · {{ gmdate('i:s', $lesson->duration) }} @endif
                    @if($lesson->is_free)<span class="text-[#FFE000]">ÜCRETSİZ</span>@endif
                  </p>
                </div>
              </button>
              @endforeach
            </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
const COMPLETE_URLS = {
  @foreach($course->sections->flatMap(fn($s) => $s->lessons) as $lesson)
  {{ $lesson->id }}: "{{ route('courses.lesson.complete', [$course->slug, $lesson->id]) }}",
  @endforeach
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let currentLessonId = null;
let videoCompleteTimer = null;
let allVideosCompleted = {{ $allVideosCompleted ? 'true' : 'false' }};

function loadLesson(id, title, type, videoSrc, videoUrl) {
  currentLessonId = id;

  // Aktif ders highlight
  document.querySelectorAll('.lesson-btn').forEach(b => b.classList.remove('bg-[#1a1a1a]'));
  document.querySelector('[data-lesson-id="'+id+'"]')?.classList.add('bg-[#1a1a1a]');

  // Bilgi paneli
  document.getElementById('lesson-info').classList.remove('hidden');
  document.getElementById('lesson-title').textContent = title;
  document.getElementById('lesson-type').textContent  = type;

  const player   = document.getElementById('video-player');
  const iframe   = document.getElementById('video-iframe');
  const ph       = document.getElementById('video-placeholder');
  const complBtn = document.getElementById('complete-btn');

  // Hepsini gizle
  player.classList.add('hidden');
  iframe.classList.add('hidden');
  ph.classList.add('hidden');
  complBtn.classList.add('hidden');

  if (type === 'VIDEO') {
    if (videoSrc) {
      // Kendi sistemimizde yüklü
      document.getElementById('video-source').src = videoSrc;
      player.load();
      player.classList.remove('hidden');
      setupVideoCompletion(player);
    } else if (videoUrl) {
      // YouTube / harici URL
      const isYT = videoUrl.includes('youtube') || videoUrl.includes('youtu.be');
      if (isYT) {
        const ytId = extractYouTubeId(videoUrl);
        iframe.src = 'https://www.youtube.com/embed/' + ytId + '?enablejsapi=1';
        iframe.classList.remove('hidden');
        // YouTube iframe için tamamlama manuel
        complBtn.classList.remove('hidden');
      } else {
        document.getElementById('video-source').src = videoUrl;
        player.load();
        player.classList.remove('hidden');
        setupVideoCompletion(player);
      }
    } else {
      ph.classList.remove('hidden');
      complBtn.classList.remove('hidden');
    }
  } else {
    // Dokuman, quiz, simulasyon tipi
    ph.innerHTML = '<p class="font-mono text-[10px] text-[#555] uppercase tracking-widest mb-3">'+type+'</p><p class="text-2xl">📄</p>';
    ph.classList.remove('hidden');
    complBtn.classList.remove('hidden');
  }
}
window.loadLesson = loadLesson;

function setupVideoCompletion(player) {
  clearTimeout(videoCompleteTimer);
  player.onended = function() {
    markComplete();
  };
  // %90 izlenince de tamamlandı say
  player.ontimeupdate = function() {
    if (player.duration && (player.currentTime / player.duration) >= 0.9) {
      player.ontimeupdate = null;
      markComplete();
    }
  };
}

function markComplete() {
  if (!currentLessonId) return;
  const player = document.getElementById('video-player');
  const watchedSec = player.duration ? Math.round(player.currentTime) : 0;

  fetch(COMPLETE_URLS[currentLessonId], {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({ watched_sec: watchedSec })
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) return;

    // Checkbox işaretle
    const btn = document.querySelector('[data-lesson-id="'+currentLessonId+'"]');
    if (btn) {
      const dot = btn.querySelector('.w-5');
      if (dot) {
        dot.className = 'w-5 h-5 shrink-0 flex items-center justify-center border bg-[#CCFF00] border-[#CCFF00]';
        dot.innerHTML = '<span class="text-[#0A0A0A] font-black text-[10px]">✓</span>';
      }
      const lbl = btn.querySelector('.font-bold');
      if (lbl) { lbl.classList.add('line-through', 'opacity-60'); }
    }

    // İlerleme çubuğunu güncelle
    const doneCount = document.querySelectorAll('[data-lesson-id]').length;
    const checked   = document.querySelectorAll('.bg-\\[\\#CCFF00\\]').length;
    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.width = Math.round(checked/doneCount*100) + '%';

    // Sınav kilidi
    if (data.all_videos_completed && !allVideosCompleted) {
      allVideosCompleted = true;
      updateQuizBanner(data.quiz_ids);
    }
  })
  .catch(() => {});
}
window.markComplete = markComplete;

function updateQuizBanner(quizIds) {
  const banner = document.getElementById('quiz-banner');
  if (!banner) return;
  banner.className = 'mt-4 border-[3px] border-[#CCFF00] bg-[#0d1a00] px-5 py-4';
  banner.innerHTML = `
    <div class="flex items-center justify-between flex-wrap gap-3">
      <div>
        <p class="font-black text-sm uppercase tracking-tight text-[#CCFF00]">🔓 Sınavlar Açık!</p>
        <p class="font-mono text-[10px] mt-1 text-[#888]">Tüm videoları izlediniz. Sınava girebilirsiniz.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        ${quizIds.map(id => `<a href="/quizzes/${id}" class="bg-[#CCFF00] text-[#0A0A0A] font-black text-xs uppercase tracking-widest px-4 py-2 border-[3px] border-[#CCFF00] hover:bg-transparent hover:text-[#CCFF00] transition-all">Sınava Git ↗</a>`).join('')}
      </div>
    </div>`;

  // Header banner da güncelle
  const hdr = document.getElementById('header-quiz-banner');
  if (hdr) hdr.classList.remove('hidden');
}

function extractYouTubeId(url) {
  const m = url.match(/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
  return m ? m[1] : '';
}

})();
</script>
@endpush
@endsection
