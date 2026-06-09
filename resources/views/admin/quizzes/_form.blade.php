@php $v = fn($f) => old($f, $quiz?->{$f}); @endphp

<div class="border-[3px] border-[#0A0A0A] p-6 space-y-5 bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
  <div>
    <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SINAV ADI *</label>
    <input type="text" name="title" value="{{ $v('title') }}" required
      class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
      placeholder="Sınav başlığı">
  </div>
  <div>
    <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">AÇIKLAMA</label>
    <textarea name="description" rows="3"
      class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors resize-none"
      placeholder="Sınav hakkında kısa açıklama">{{ $v('description') }}</textarea>
  </div>
  <div>
    <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">BAĞLI KURS (opsiyonel)</label>
    <select name="course_id"
      class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
      <option value="">— Kursa bağlamayın (genel sınav) —</option>
      @foreach($courses as $c)
      <option value="{{ $c->id }}" {{ (string)$v('course_id') === (string)$c->id || request('course_id') == $c->id ? 'selected' : '' }}>
        {{ $c->title }}
      </option>
      @endforeach
    </select>
    <p class="font-mono text-[10px] text-[#888] mt-1">Kursa bağlanırsa: Video tamamlanınca sınav açılır, geçince sertifika otomatik oluşur.</p>
  </div>
  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">GEÇER NOT (%) *</label>
      <input type="number" name="passing_score" value="{{ $v('passing_score') ?? 70 }}" min="0" max="100" required
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
    </div>
    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SÜRE LİMİTİ (dak.)</label>
      <input type="number" name="time_limit" value="{{ $v('time_limit') }}" min="1"
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
        placeholder="Süresiz">
    </div>
    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">DENEME HAKKI *</label>
      <input type="number" name="attempts" value="{{ $v('attempts') ?? 3 }}" min="1" max="10" required
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
    </div>
  </div>
  @if($quiz)
  <div class="pt-2 border-t-[2px] border-[#eee]">
    <label class="flex items-center gap-3 cursor-pointer group">
      <input type="checkbox" name="is_active" value="1" {{ $quiz->is_active ? 'checked' : '' }}
        class="w-5 h-5 border-[3px] border-[#0A0A0A]" style="accent-color:#CCFF00">
      <div>
        <span class="font-black text-sm uppercase tracking-wide">Sınav Aktif</span>
        <p class="font-mono text-[10px] text-[#888] mt-0.5">Pasif yapıldığında öğrenciler sınava giremez. Süre biten sınavlar otomatik kapanır.</p>
      </div>
    </label>
  </div>
  @endif
</div>
