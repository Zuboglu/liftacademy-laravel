@php
  $recipientName  = $cert->recipient_name  ?: ($cert->user->name  ?? '—');
  $instructorName = $cert->instructor_name ?: '—';
  $courseTitle    = $cert->course->title   ?? '—';
  $issueDate      = $cert->completed_at    ? $cert->completed_at->format('d.m.Y') : $cert->created_at->format('d.m.Y');
  $expiryDate     = $cert->expires_at      ? $cert->expires_at->format('d.m.Y')   : null;
  $trainingHours  = $cert->training_hours;
  $level          = $cert->level;
  $levelLabels    = ['JUNIOR'=>'Yardımcı','OPERATOR'=>'Operatör','SENIOR'=>'Kıdemli','SUPERVISOR'=>'Süpervizör','TRAINER'=>'Eğitmen'];
  $levelLabel     = $levelLabels[$level] ?? $level;
  $certId         = $printId ?? 'cert-preview';
@endphp

<div id="{{ $certId }}" style="
  width: 100%;
  aspect-ratio: 297 / 210;
  background: #fff;
  border: 4px solid #0A0A0A;
  box-shadow: 8px 8px 0 #FFE000;
  display: flex;
  flex-direction: column;
  font-family: 'Inter', 'Space Grotesk', system-ui, sans-serif;
  overflow: hidden;
  position: relative;
">

  {{-- Sol şerit --}}
  <div style="position:absolute;left:0;top:0;bottom:0;width:14px;background:#0A0A0A;"></div>

  {{-- Sağ şerit --}}
  <div style="position:absolute;right:0;top:0;bottom:0;width:14px;background:#0A0A0A;"></div>

  {{-- Üst sarı şerit --}}
  <div style="position:absolute;top:0;left:14px;right:14px;height:8px;background:#FFE000;"></div>

  {{-- Alt sarı şerit --}}
  <div style="position:absolute;bottom:0;left:14px;right:14px;height:8px;background:#FFE000;"></div>

  {{-- İçerik alanı --}}
  <div style="flex:1;display:flex;flex-direction:column;padding:32px 48px 28px;margin:0 14px;">

    {{-- Üst satır: Logo + Sertifika No --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:44px;height:44px;background:#0A0A0A;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <span style="color:#FFE000;font-size:22px;">🏗</span>
        </div>
        <div>
          <p style="font-family:'Inter',sans-serif;font-weight:900;font-size:18px;letter-spacing:-0.03em;color:#0A0A0A;line-height:1;">LIFT<span style="color:#FFE000;background:#0A0A0A;padding:0 4px;">ACADEMY</span></p>
          <p style="font-family:monospace;font-size:8px;letter-spacing:0.15em;text-transform:uppercase;color:#888;margin-top:2px;">VİNÇ EĞİTİM MERKEZİ</p>
        </div>
      </div>
      <div style="text-align:right;">
        <p style="font-family:monospace;font-size:8px;letter-spacing:0.12em;text-transform:uppercase;color:#888;">SERTİFİKA NO</p>
        <p style="font-family:monospace;font-size:11px;font-weight:700;color:#0A0A0A;margin-top:2px;">{{ $cert->cert_number }}</p>
      </div>
    </div>

    {{-- Orta: Başlık + Alıcı --}}
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:8px;">

      <p style="font-family:monospace;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:#888;border:1px solid #ccc;padding:3px 14px;">BAŞARI SERTİFİKASI</p>

      <p style="font-size:13px;color:#444;margin-top:4px;">Bu belge,</p>

      <p style="font-family:'Inter',sans-serif;font-weight:900;font-size:clamp(22px,4vw,34px);letter-spacing:-0.03em;color:#0A0A0A;line-height:1.1;margin:2px 0;">{{ $recipientName }}</p>

      @if($cert->employee_id || $cert->department)
      <p style="font-family:monospace;font-size:9px;letter-spacing:0.1em;color:#888;">
        @if($cert->employee_id) Sicil: {{ $cert->employee_id }} @endif
        @if($cert->employee_id && $cert->department) · @endif
        @if($cert->department) {{ $cert->department }} @endif
      </p>
      @endif

      <p style="font-size:13px;color:#444;margin-top:2px;">kişinin</p>

      <div style="background:#0A0A0A;padding:6px 24px;margin:4px 0;">
        <p style="font-family:'Inter',sans-serif;font-weight:900;font-size:15px;letter-spacing:-0.02em;color:#FFE000;text-transform:uppercase;">{{ $courseTitle }}</p>
      </div>

      <p style="font-size:13px;color:#444;">eğitimini başarıyla tamamladığını belgeler.</p>

      @if($trainingHours)
      <p style="font-family:monospace;font-size:9px;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-top:2px;">Toplam Eğitim Süresi: {{ $trainingHours }} Saat</p>
      @endif

    </div>

    {{-- Alt satır: Tarihler + Seviye + İmza --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:16px;padding-top:14px;border-top:2px solid #0A0A0A;">

      {{-- Tarihler --}}
      <div>
        <p style="font-family:monospace;font-size:8px;letter-spacing:0.12em;text-transform:uppercase;color:#888;">VERİLİŞ TARİHİ</p>
        <p style="font-weight:700;font-size:13px;color:#0A0A0A;margin-top:1px;">{{ $issueDate }}</p>
        @if($expiryDate)
        <p style="font-family:monospace;font-size:8px;letter-spacing:0.12em;text-transform:uppercase;color:#888;margin-top:6px;">GEÇERLİLİK</p>
        <p style="font-weight:700;font-size:13px;color:#0A0A0A;margin-top:1px;">{{ $expiryDate }}</p>
        @endif
      </div>

      {{-- Seviye rozeti --}}
      <div style="text-align:center;">
        <div style="background:#FFE000;border:3px solid #0A0A0A;padding:8px 20px;box-shadow:3px 3px 0 #0A0A0A;display:inline-block;">
          <p style="font-family:monospace;font-size:7px;letter-spacing:0.15em;text-transform:uppercase;color:#0A0A0A;">SEVİYE</p>
          <p style="font-family:'Inter',sans-serif;font-weight:900;font-size:15px;letter-spacing:-0.02em;color:#0A0A0A;">{{ $levelLabel }}</p>
        </div>
      </div>

      {{-- İmza --}}
      <div style="text-align:right;">
        <div style="border-bottom:2px solid #0A0A0A;width:140px;margin-bottom:4px;"></div>
        <p style="font-weight:700;font-size:12px;color:#0A0A0A;">{{ $instructorName }}</p>
        <p style="font-family:monospace;font-size:8px;letter-spacing:0.1em;text-transform:uppercase;color:#888;margin-top:1px;">Eğitmen / Instructor</p>
        @if($cert->site)
        <p style="font-family:monospace;font-size:8px;color:#888;margin-top:2px;">{{ $cert->site }}</p>
        @endif
      </div>

    </div>
  </div>
</div>
