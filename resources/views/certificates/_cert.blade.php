@php
  $recipientName  = $cert->recipient_name  ?: ($cert->user->name  ?? '—');
  $instructorName = $cert->instructor_name ?: '—';
  $courseTitle    = $cert->course->title   ?? '—';
  $issueDate      = $cert->completed_at    ? $cert->completed_at->format('d.m.Y') : $cert->created_at->format('d.m.Y');
  $expiryDate     = $cert->expires_at      ? $cert->expires_at->format('d.m.Y')   : null;
  $trainingHours  = $cert->training_hours;
  $level          = $cert->level;
  $levelLabels    = ['JUNIOR'=>'Junior','OPERATOR'=>'Operatör','SENIOR'=>'Senior','SUPERVISOR'=>'Süpervizör','TRAINER'=>'Trainer'];
  $levelLabel     = $levelLabels[$level] ?? $level;
  $certId         = $printId ?? 'cert-preview';
@endphp

<div id="{{ $certId }}" style="
  width: 100%;
  aspect-ratio: 297 / 210;
  font-family: 'Inter', 'Space Grotesk', system-ui, sans-serif;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #1a0000 0%, #6b0000 40%, #1a0000 100%);
  border: 6px solid #222;
  box-shadow: 0 20px 60px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.08);
">

  {{-- Arka plan desen --}}
  <div style="position:absolute;inset:0;opacity:0.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>

  {{-- Üst altın şerit --}}
  <div style="position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#FFE000,#FFA500,#FFD700,#FFA500,#FFE000);"></div>
  {{-- Alt altın şerit --}}
  <div style="position:absolute;bottom:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#FFE000,#FFA500,#FFD700,#FFA500,#FFE000);"></div>

  {{-- Köşe süslemeleri --}}
  <div style="position:absolute;top:14px;left:14px;width:40px;height:40px;border-left:3px solid #FFE000;border-top:3px solid #FFE000;opacity:0.8;"></div>
  <div style="position:absolute;top:14px;right:14px;width:40px;height:40px;border-right:3px solid #FFE000;border-top:3px solid #FFE000;opacity:0.8;"></div>
  <div style="position:absolute;bottom:14px;left:14px;width:40px;height:40px;border-left:3px solid #FFE000;border-bottom:3px solid #FFE000;opacity:0.8;"></div>
  <div style="position:absolute;bottom:14px;right:14px;width:40px;height:40px;border-right:3px solid #FFE000;border-bottom:3px solid #FFE000;opacity:0.8;"></div>

  {{-- İçerik --}}
  <div style="position:relative;z-index:10;display:flex;flex-direction:column;height:100%;padding:28px 52px 24px;color:#fff;">

    {{-- Üst: Logo + Sertifika No --}}
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
      <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:40px;height:40px;background:#FFE000;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">🏗</div>
        <div>
          <p style="font-weight:900;font-size:16px;letter-spacing:-0.02em;line-height:1;color:#FFE000;">LIFT<span style="background:#FFE000;color:#1a0000;padding:0 3px;">ACADEMY</span></p>
          <p style="font-family:monospace;font-size:7px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(255,200,100,0.6);margin-top:2px;">VİNÇ EĞİTİM PLATFORMU</p>
        </div>
      </div>
      <div style="text-align:right;">
        <p style="font-family:monospace;font-size:7px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,200,100,0.5);">SERTİFİKA NO</p>
        <p style="font-family:monospace;font-size:10px;font-weight:700;color:#FFE000;margin-top:1px;">{{ $cert->cert_number }}</p>
      </div>
    </div>

    {{-- Orta: Ana içerik --}}
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:6px;">

      <p style="font-family:monospace;font-size:8px;letter-spacing:0.25em;text-transform:uppercase;color:rgba(255,200,100,0.6);border:1px solid rgba(255,224,0,0.3);padding:2px 14px;display:inline-block;">
        BAŞARI SERTİFİKASI
      </p>

      <p style="font-size:12px;color:rgba(255,255,255,0.55);margin-top:4px;">Bu belge,</p>

      <div style="background:rgba(255,224,0,0.1);border:1px solid rgba(255,224,0,0.3);padding:8px 32px;margin:2px 0;">
        <p style="font-weight:900;font-size:clamp(20px,3.5vw,30px);letter-spacing:-0.03em;color:#FFE000;text-shadow:0 0 30px rgba(255,200,0,0.5);line-height:1.1;">{{ $recipientName }}</p>
      </div>

      @if($cert->employee_id || $cert->department)
      <p style="font-family:monospace;font-size:8px;letter-spacing:0.1em;color:rgba(255,200,100,0.5);">
        {{ collect([$cert->employee_id, $cert->department])->filter()->implode(' · ') }}
      </p>
      @endif

      <p style="font-size:12px;color:rgba(255,255,255,0.55);margin-top:2px;">kişinin</p>

      <div style="background:rgba(255,255,255,0.06);border-top:2px solid rgba(255,100,100,0.5);border-bottom:2px solid rgba(255,100,100,0.5);padding:6px 28px;margin:3px 0;">
        <p style="font-weight:900;font-size:clamp(12px,2vw,18px);letter-spacing:-0.01em;text-transform:uppercase;color:#fff;">{{ $courseTitle }}</p>
      </div>

      <p style="font-size:12px;color:rgba(255,255,255,0.55);">eğitimini başarıyla tamamladığını belgeler.</p>

      @if($trainingHours)
      <p style="font-family:monospace;font-size:8px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,200,100,0.5);margin-top:2px;">Toplam Eğitim Süresi: {{ $trainingHours }} Saat</p>
      @endif

    </div>

    {{-- Alt: Tarih + Seviye + İmza --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-end;padding-top:12px;border-top:1px solid rgba(255,200,100,0.2);">

      <div>
        <p style="font-family:monospace;font-size:7px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,200,100,0.5);">VERİLİŞ TARİHİ</p>
        <p style="font-weight:700;font-size:13px;color:#fff;margin-top:1px;">{{ $issueDate }}</p>
        @if($expiryDate)
        <p style="font-family:monospace;font-size:7px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,200,100,0.5);margin-top:5px;">GEÇERLİLİK</p>
        <p style="font-weight:700;font-size:12px;color:rgba(255,255,255,0.7);margin-top:1px;">{{ $expiryDate }}</p>
        @endif
      </div>

      <div style="text-align:center;">
        <div style="background:rgba(255,224,0,0.12);border:2px solid #FFE000;padding:6px 20px;display:inline-block;">
          <p style="font-family:monospace;font-size:7px;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,200,100,0.6);">SEVİYE</p>
          <p style="font-weight:900;font-size:15px;letter-spacing:-0.02em;color:#FFE000;">{{ $levelLabel }}</p>
        </div>
      </div>

      <div style="text-align:right;">
        <div style="border-bottom:1px solid rgba(255,255,255,0.3);width:130px;margin-bottom:4px;margin-left:auto;"></div>
        <p style="font-weight:700;font-size:12px;color:#fff;">{{ $instructorName }}</p>
        <p style="font-family:monospace;font-size:7px;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,200,100,0.5);margin-top:1px;">Eğitmen / Instructor</p>
        @if($cert->site)
        <p style="font-family:monospace;font-size:7px;color:rgba(255,200,100,0.5);margin-top:2px;">{{ $cert->site }}</p>
        @endif
      </div>

    </div>
  </div>
</div>
