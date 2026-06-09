@extends('layouts.app')
@section('title', $course->title . ' – Admin')
@section('content')
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
    <div class="flex gap-2 shrink-0">
      <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn-brut text-xs py-2 px-4">Düzenle</a>
      <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Bu kursu silmek istediğinizden emin misiniz?')">
        @csrf @method('DELETE')
        <button type="submit" class="border-[3px] border-[#FF2D2D] text-[#FF2D2D] font-black text-xs uppercase tracking-widest px-4 py-2 hover:bg-[#FF2D2D] hover:text-white transition-colors">Sil</button>
      </form>
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
          <span class="font-mono text-[10px] text-[#555]">{{ $course->sections->count() }} bölüm</span>
        </div>
        <div class="p-4">
          <form action="{{ route('admin.sections.store', $course->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="title" placeholder="Yeni bölüm adı..." class="input-brut flex-1 py-2 text-sm">
            <input type="number" name="order" placeholder="Sıra" class="input-brut w-20 py-2 text-sm">
            <button type="submit" class="btn-brut text-xs py-2 px-4 whitespace-nowrap">+ Bölüm Ekle</button>
          </form>
        </div>
      </div>

      {{-- Bölümler listesi --}}
      @foreach($course->sections->sortBy('order') as $section)
      <div class="border-[3px] border-[#0A0A0A] bg-white">
        <div class="bg-[#111] px-5 py-3 flex items-center justify-between">
          <p class="font-black text-[#F5F0E8] text-sm uppercase tracking-tight">{{ $section->title }}</p>
          <div class="flex items-center gap-3">
            <span class="font-mono text-[10px] text-[#555]">{{ $section->lessons->count() }} ders</span>
            <form action="{{ route('admin.sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Bölümü sil?')">
              @csrf @method('DELETE')
              <button type="submit" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
            </form>
          </div>
        </div>

        {{-- Mevcut dersler --}}
        @foreach($section->lessons->sortBy('order') as $lesson)
        <div class="px-5 py-3 border-b border-[#f0f0f0] flex items-center justify-between">
          <div class="flex items-center gap-3 flex-1 min-w-0">
            <span class="font-mono text-[9px] text-[#888] uppercase w-16 shrink-0">{{ $lesson->type }}</span>
            <span class="font-bold text-sm text-[#0A0A0A] truncate">{{ $lesson->title }}</span>
            @if($lesson->video_path)
            <span class="tag-lime text-[9px] shrink-0">VIDEO YÜKLÜ</span>
            @elseif($lesson->video_url)
            <span class="tag-blue text-[9px] shrink-0">URL</span>
            @endif
            @if($lesson->is_free)<span class="tag-yellow text-[9px] shrink-0">ÜCRETSİZ</span>@endif
          </div>
          <div class="flex gap-3 ml-3 shrink-0">
            <button onclick="toggleLessonEdit({{ $lesson->id }})" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase">Düzenle</button>
            <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Dersi sil?')" class="inline">
              @csrf @method('DELETE')
              <button type="submit" class="font-mono text-[10px] text-[#FF2D2D] hover:underline uppercase">Sil</button>
            </form>
          </div>
        </div>
        {{-- Ders Düzenleme Formu (gizli) --}}
        <div id="lesson-edit-{{ $lesson->id }}" class="hidden border-t border-[#f0f0f0] bg-[#fafaf5] p-4">
          <form action="{{ route('admin.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
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
                <label class="text-mono-sm mb-1 block">VİDEO DOSYASI YÜKLEYEBİLİRSİNİZ</label>
                <input type="file" name="video" accept="video/mp4,video/mov,video/avi,video/webm" class="block text-sm font-medium text-[#0A0A0A] file:mr-3 file:py-1.5 file:px-4 file:border-[2px] file:border-[#0A0A0A] file:font-bold file:text-xs file:uppercase file:bg-[#FFE000] file:cursor-pointer">
                @if($lesson->video_path)
                <p class="text-[10px] text-[#CCFF00] font-mono mt-1">✓ Yüklü: {{ basename($lesson->video_path) }}</p>
                @endif
              </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
              <div>
                <label class="text-mono-sm mb-1 block">SÜRE (saniye)</label>
                <input type="number" name="duration" value="{{ $lesson->duration }}" min="0" class="input-brut py-1.5 text-sm">
              </div>
              <div>
                <label class="text-mono-sm mb-1 block">SIRA</label>
                <input type="number" name="order" value="{{ $lesson->order }}" class="input-brut py-1.5 text-sm">
              </div>
              <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" name="is_free" value="1" {{ $lesson->is_free ? 'checked' : '' }} class="w-4 h-4">
                  <span class="font-bold text-xs uppercase">Ücretsiz</span>
                </label>
              </div>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="btn-brut text-xs py-1.5 px-4">Kaydet</button>
              <button type="button" onclick="toggleLessonEdit({{ $lesson->id }})" class="btn-brut-dark text-xs py-1.5 px-4">İptal</button>
            </div>
          </form>
        </div>
        @endforeach

        {{-- Ders Ekle --}}
        <div class="p-4 bg-[#fafaf5]">
          <form action="{{ route('admin.lessons.store', $section->id) }}" method="POST" enctype="multipart/form-data">
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
              <div class="flex items-end pb-1">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" name="is_free" value="1" class="w-4 h-4">
                  <span class="font-bold text-xs uppercase">Ücretsiz</span>
                </label>
              </div>
            </div>
            <button type="submit" class="btn-brut text-xs py-1.5 px-5">+ Ders Ekle</button>
          </form>
        </div>
      </div>
      @endforeach

    </div>

    {{-- Sağ: Özet + Quizler --}}
    <div class="space-y-4">
      <div class="border-[3px] border-[#0A0A0A] bg-white p-5" style="box-shadow:4px 4px 0 #0A0A0A">
        <p class="font-mono text-[9px] text-[#888] uppercase tracking-widest mb-3">KURS BİLGİLERİ</p>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between"><span class="text-[#888]">Eğitmen</span><span class="font-bold">{{ $course->instructor->name ?? '—' }}</span></div>
          <div class="flex justify-between"><span class="text-[#888]">Seviye</span><span class="font-bold">{{ $course->level }}</span></div>
          <div class="flex justify-between"><span class="text-[#888]">Geçer Not</span><span class="font-bold">%{{ $course->passing_score }}</span></div>
          <div class="flex justify-between"><span class="text-[#888]">Fiyat</span><span class="font-bold">{{ $course->price > 0 ? '₺'.$course->price : 'Ücretsiz' }}</span></div>
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
        <a href="{{ route('admin.quizzes.create') }}?course_id={{ $course->id }}" class="btn-brut text-xs py-2 px-4 mt-3 inline-flex">+ Sınav Ekle</a>
      </div>
    </div>

  </div>
</div>
</div>

@push('scripts')
<script>
function toggleLessonEdit(id) {
  const el = document.getElementById('lesson-edit-' + id);
  el.classList.toggle('hidden');
}
</script>
@endpush
@endsection
