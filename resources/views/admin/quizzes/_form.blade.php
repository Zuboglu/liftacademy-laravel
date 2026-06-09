@php $v = fn($f) => old($f, $quiz?->{$f}); @endphp

<div class="border-[3px] border-[#0A0A0A] p-6 space-y-5 bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
  <div>
    <label class="text-mono-sm mb-1.5 block">SINAV ADI *</label>
    <input type="text" name="title" value="{{ $v('title') }}" required class="input-brut" placeholder="Sınav başlığı">
  </div>
  <div>
    <label class="text-mono-sm mb-1.5 block">AÇIKLAMA</label>
    <textarea name="description" rows="3" class="input-brut resize-none" placeholder="Sınav hakkında kısa açıklama">{{ $v('description') }}</textarea>
  </div>
  <div>
    <label class="text-mono-sm mb-1.5 block">BAĞLI KURS (opsiyonel)</label>
    <select name="course_id" class="input-brut">
      <option value="">— Kursa bağlamayın (genel sınav) —</option>
      @foreach($courses as $c)
      <option value="{{ $c->id }}" {{ (string)$v('course_id') === (string)$c->id || request('course_id') == $c->id ? 'selected' : '' }}>
        {{ $c->title }}
      </option>
      @endforeach
    </select>
    <p class="text-[10px] text-[#888] mt-1 font-mono">Kursa bağlanırsa: Video tamamlanınca sınav açılır, geçince sertifika otomatik oluşur.</p>
  </div>
  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="text-mono-sm mb-1.5 block">GEÇER NOT (%) *</label>
      <input type="number" name="passing_score" value="{{ $v('passing_score') ?? 70 }}" min="0" max="100" required class="input-brut">
    </div>
    <div>
      <label class="text-mono-sm mb-1.5 block">SÜRE LİMİTİ (dak.)</label>
      <input type="number" name="time_limit" value="{{ $v('time_limit') }}" min="1" class="input-brut" placeholder="Süresiz">
    </div>
    <div>
      <label class="text-mono-sm mb-1.5 block">DENEME HAKKI *</label>
      <input type="number" name="attempts" value="{{ $v('attempts') ?? 3 }}" min="1" max="10" required class="input-brut">
    </div>
  </div>
</div>
