@php $v = fn($f) => old($f, $certificate?->{$f}); @endphp

<div class="border-[3px] border-[#0A0A0A] bg-white" style="box-shadow:4px 4px 0 #0A0A0A">
  <div class="bg-[#0A0A0A] px-5 py-3 mb-0">
    <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">SERTİFİKA BİLGİLERİ</p>
  </div>
  <div class="p-6 space-y-5">

    @if(!$certificate)
    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">KULLANICI *</label>
      <select name="user_id" required
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
        <option value="">— Kullanıcı Seçin —</option>
        @foreach($users as $u)
        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
          {{ $u->name }} ({{ $u->email }})
        </option>
        @endforeach
      </select>
    </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">ALICI ADI SOYADI *</label>
        <input type="text" name="recipient_name" value="{{ $v('recipient_name') }}" required
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Ahmet Yılmaz">
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">EĞİTMEN ADI</label>
        <input type="text" name="instructor_name" value="{{ $v('instructor_name') }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Mehmet Kaya">
      </div>
    </div>

    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">BAĞLI KURS (opsiyonel)</label>
      <select name="course_id"
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
        <option value="">— Genel Sertifika —</option>
        @foreach($courses as $c)
        <option value="{{ $c->id }}" {{ (string)$v('course_id') === (string)$c->id ? 'selected' : '' }}>{{ $c->title }}</option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SEVİYE *</label>
        <select name="level" required
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          @foreach(['JUNIOR','OPERATOR','SENIOR','SUPERVISOR','TRAINER'] as $lvl)
          <option value="{{ $lvl }}" {{ $v('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">DURUM *</label>
        <select name="status" required
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          <option value="ACTIVE" {{ ($v('status') ?? 'ACTIVE') === 'ACTIVE' ? 'selected' : '' }}>AKTİF</option>
          <option value="EXPIRED" {{ $v('status') === 'EXPIRED' ? 'selected' : '' }}>SÜRESİ DOLMUŞ</option>
          <option value="REVOKED" {{ $v('status') === 'REVOKED' ? 'selected' : '' }}>İPTAL EDİLMİŞ</option>
        </select>
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">EĞİTİM SAATİ</label>
        <input type="number" name="training_hours" value="{{ $v('training_hours') }}" min="1"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="8">
      </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">DEPARTMAN</label>
        <input type="text" name="department" value="{{ $v('department') }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Lojistik">
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">SAHA / LOKASYON</label>
        <input type="text" name="site" value="{{ $v('site') }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="İstanbul Depo">
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">PERSONEL NO</label>
        <input type="text" name="employee_id" value="{{ $v('employee_id') }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="EMP-001">
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">TAMAMLANMA TARİHİ</label>
        <input type="date" name="completed_at"
          value="{{ $v('completed_at') ? \Carbon\Carbon::parse($v('completed_at'))->format('Y-m-d') : '' }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
      </div>
      <div>
        <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">GEÇERLİLİK BİTİŞ TARİHİ</label>
        <input type="date" name="expires_at"
          value="{{ $v('expires_at') ? \Carbon\Carbon::parse($v('expires_at'))->format('Y-m-d') : '' }}"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
      </div>
    </div>

    <div>
      <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">NOTLAR (opsiyonel)</label>
      <textarea name="notes" rows="2"
        class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors resize-none"
        placeholder="Ek notlar...">{{ $v('notes') }}</textarea>
    </div>
  </div>
</div>
