@extends('layouts.app')
@section('title', 'Kullanıcı Düzenle – Admin')
@section('content')
<div class="bg-[#F5F0E8] min-h-screen">
<div class="max-w-[800px] mx-auto px-6 py-10">

  <div class="mb-8">
    <a href="{{ route('admin.users.show', $user->id) }}" class="font-mono text-[10px] text-[#888] hover:text-[#0A0A0A] uppercase tracking-widest">← Kullanıcı Detay</a>
    <h1 class="font-black text-3xl uppercase tracking-tight mt-1">KULLANICI DÜZENLE</h1>
    <p class="font-mono text-[10px] text-[#888] mt-1">{{ $user->email }}</p>
  </div>

  @if($errors->any())
  <div class="border-[3px] border-[#FF2D2D] bg-[#FF2D2D] text-white p-4 mb-6 font-bold text-sm">
    @foreach($errors->all() as $e)<p>✕ {{ $e }}</p>@endforeach
  </div>
  @endif

  @if(session('success'))
  <div class="border-[3px] border-[#CCFF00] bg-[#CCFF00] p-3 mb-6 font-black text-sm uppercase">✓ {{ session('success') }}</div>
  @endif

  <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf @method('PUT')

    <div class="border-[3px] border-[#0A0A0A] bg-white mb-5" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] px-5 py-3">
        <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">TEMEL BİLGİLER</p>
      </div>
      <div class="p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">AD SOYAD *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          </div>
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">E-POSTA *</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">ROL *</label>
            <select name="role" required
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
              @foreach(['STUDENT'=>'Öğrenci','INSTRUCTOR'=>'Eğitmen','SUPERVISOR'=>'Süpervizör','ADMIN'=>'Admin'] as $val => $lbl)
              <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">TELEFON</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
              placeholder="+90 555 123 45 67">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">DEPARTMAN</label>
            <input type="text" name="department" value="{{ old('department', $user->department) }}"
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          </div>
          <div>
            <label class="font-mono text-[10px] text-[#888] uppercase tracking-widest mb-1.5 block">PERSONEL NO</label>
            <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
              class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors">
          </div>
        </div>
      </div>
    </div>

    <div class="border-[3px] border-[#0A0A0A] bg-white mb-5" style="box-shadow:4px 4px 0 #0A0A0A">
      <div class="bg-[#0A0A0A] px-5 py-3">
        <p class="font-black text-[#F5F0E8] text-xs uppercase tracking-widest">ŞİFRE DEĞİŞTİR (boş bırakılırsa değişmez)</p>
      </div>
      <div class="p-6">
        <input type="password" name="password"
          class="w-full border-[3px] border-[#0A0A0A] px-4 py-3 font-bold text-sm bg-[#F5F0E8] focus:outline-none focus:bg-[#FFE000] transition-colors"
          placeholder="Yeni şifre (min. 8 karakter)">
      </div>
    </div>

    <div class="flex gap-3">
      <button type="submit"
        class="bg-[#FFE000] text-[#0A0A0A] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-[#FFE000] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">Kaydet ↗</button>
      <a href="{{ route('admin.users.show', $user->id) }}"
        class="bg-[#0A0A0A] text-[#F5F0E8] font-black text-sm uppercase tracking-widest px-8 py-4 border-[3px] border-[#0A0A0A] hover:bg-[#F5F0E8] hover:text-[#0A0A0A] transition-colors"
        style="box-shadow:4px 4px 0 #0A0A0A">İptal</a>
    </div>
  </form>

</div>
</div>
@endsection
