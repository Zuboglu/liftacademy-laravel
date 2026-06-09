@php $v = fn($f) => old($f, $course?->{$f}); @endphp

<div class="border-[3px] border-[#0A0A0A] p-6 space-y-5 bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
  <div>
    <label class="text-mono-sm mb-1.5 block">KURS ADI *</label>
    <input type="text" name="title" value="{{ $v('title') }}" required class="input-brut" placeholder="Kurs başlığı">
  </div>
  <div>
    <label class="text-mono-sm mb-1.5 block">AÇIKLAMA</label>
    <textarea name="description" rows="4" class="input-brut resize-none" placeholder="Kurs hakkında kısa açıklama">{{ $v('description') }}</textarea>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-mono-sm mb-1.5 block">KATEGORİ *</label>
      <select name="category" class="input-brut" required>
        @foreach(['SAFETY'=>'İSG & Güvenlik','CRANE_TYPE'=>'Vinç Türleri','OPERATION'=>'Operasyon','TECHNICAL'=>'Teknik','RISK'=>'Risk Yönetimi','CERTIFICATION'=>'Sertifikasyon','COMPANY'=>'Şirket'] as $k => $l)
        <option value="{{ $k }}" {{ $v('category') === $k ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="text-mono-sm mb-1.5 block">SEVİYE *</label>
      <select name="level" class="input-brut" required>
        @foreach(['BEGINNER'=>'Başlangıç','INTERMEDIATE'=>'Orta','ADVANCED'=>'İleri','ALL_LEVELS'=>'Her Seviye'] as $k => $l)
        <option value="{{ $k }}" {{ $v('level') === $k ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-mono-sm mb-1.5 block">VİNÇ TÜRÜ</label>
      <select name="crane_type" class="input-brut">
        <option value="">— Seçiniz —</option>
        @foreach(['MOBILE'=>'Mobil','TOWER'=>'Kule','PORTAL'=>'Portal','HIAB'=>'HIAB','AERIAL'=>'Havai','TELESCOPIC'=>'Teleskopik'] as $k => $l)
        <option value="{{ $k }}" {{ $v('crane_type') === $k ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="text-mono-sm mb-1.5 block">EĞİTMEN *</label>
      <select name="instructor_id" class="input-brut" required>
        @foreach($instructors as $ins)
        <option value="{{ $ins->id }}" {{ (string)$v('instructor_id') === (string)$ins->id ? 'selected' : '' }}>
          {{ $ins->name }} ({{ $ins->role }})
        </option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="text-mono-sm mb-1.5 block">FİYAT (₺)</label>
      <input type="number" name="price" value="{{ $v('price') ?? 0 }}" min="0" step="0.01" class="input-brut" placeholder="0">
    </div>
    <div>
      <label class="text-mono-sm mb-1.5 block">GEÇER NOT (%)</label>
      <input type="number" name="passing_score" value="{{ $v('passing_score') ?? 70 }}" min="0" max="100" class="input-brut">
    </div>
  </div>
  <div class="flex gap-6">
    <label class="flex items-center gap-2 cursor-pointer">
      <input type="checkbox" name="published" value="1" class="w-4 h-4" {{ $course?->published ? 'checked' : '' }}>
      <span class="font-bold text-sm uppercase tracking-wide">Yayınla</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
      <input type="checkbox" name="is_mandatory" value="1" class="w-4 h-4" {{ $course?->is_mandatory ? 'checked' : '' }}>
      <span class="font-bold text-sm uppercase tracking-wide">Zorunlu Kurs</span>
    </label>
  </div>
</div>
