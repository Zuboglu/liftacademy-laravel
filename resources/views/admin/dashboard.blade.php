@extends('layouts.app')
@section('title', 'Yönetim Paneli – LiftAcademy')
@section('content')

<div class="bg-[#F5F0E8] min-h-screen">
  <div class="max-w-[1400px] mx-auto px-6 py-10">
    <div class="mb-10">
      <span class="tag-black mb-4 inline-block">YÖNETİM PANELİ</span>
      <h1 class="text-4xl font-black uppercase leading-none tracking-tight">ADMIN<br><span class="text-[#FF2D2D]">DASHBOARD</span></h1>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 border-[3px] border-[#0A0A0A] mb-10" style="box-shadow:6px 6px 0 #0A0A0A">
      @php
        $stats = [
          [\App\Models\User::count(), 'KULLANICI', '👤', 'bg-[#F5F0E8]'],
          [\App\Models\Course::count(), 'KURS', '📚', 'bg-[#FFE000]'],
          [\App\Models\Enrollment::count(), 'KAYIT', '✅', 'bg-[#CCFF00]'],
          [\App\Models\Certificate::count(), 'SERTİFİKA', '🏆', 'bg-[#0A0A0A]'],
        ];
      @endphp
      @foreach($stats as $i => $s)
      <div class="{{ $s[3] }} {{ $s[3]==='bg-[#0A0A0A]' ? 'text-[#FFE000]' : 'text-[#0A0A0A]' }} p-6 {{ $i < 3 ? 'border-r-[3px] border-[#0A0A0A]' : '' }}">
        <span class="text-2xl mb-2 block">{{ $s[2] }}</span>
        <p class="text-3xl font-black leading-none mb-1">{{ $s[0] }}</p>
        <p class="text-mono-sm opacity-60">{{ $s[1] }}</p>
      </div>
      @endforeach
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      @foreach([
        ['Kurs Yönetimi','Kurs ekle, düzenle, yayınla','📚','bg-[#FFE000]','/admin/courses'],
        ['Kullanıcı Yönetimi','Kullanıcı ekle, rolleri düzenle','👤','bg-[#CCFF00]','/admin/users'],
        ['Sertifikalar','Sertifika görüntüle ve yönet','🏆','bg-[#0A0A0A]','/admin/certificates'],
      ] as $card)
      <a href="{{ $card[4] }}" class="border-[3px] border-[#0A0A0A] p-8 {{ $card[2]==='🏆' ? 'bg-[#0A0A0A] text-[#FFE000]' : $card[3].' text-[#0A0A0A]' }} hover-lift group">
        <span class="text-4xl block mb-4">{{ $card[2] }}</span>
        <p class="font-black text-xl uppercase tracking-tight mb-2">{{ $card[0] }}</p>
        <p class="text-sm font-medium opacity-70">{{ $card[1] }}</p>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endsection
