@extends('layouts.app')
@section('title', 'LiftAcademy – ' . __('ui.home_tagline'))
@section('content')

{{-- HOOK DECORATIONS (position:fixed, parallax scroll) --}}
<div id="hook-left" aria-hidden="true" style="position:fixed;top:-400px;left:75px;width:80px;z-index:0;pointer-events:none;opacity:1;">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>
<div id="hook-right" aria-hidden="true" style="position:fixed;top:-400px;right:75px;width:80px;z-index:0;pointer-events:none;opacity:1;transform:scaleX(-1);">
  <img src="/images/hook.svg" width="80" height="900" alt="">
</div>

<div class="text-[#0A0A0A] min-h-screen relative z-10">

  {{-- HERO --}}
  <section class="max-w-[1400px] mx-auto px-6 pt-16 pb-0 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-8 items-start mb-10">
      <div>
        <div class="flex flex-wrap items-center gap-3 mb-6">
          <span class="tag-black">#001 Platform</span>
          <span class="tag-lime">Türkiye'de</span>
          <span class="text-mono-sm text-[#888] mt-1">– {{ __('ui.home_mq_lms') }}</span>
        </div>
        <h1 class="text-display text-[#0A0A0A] mb-6" style="font-family:Inter,sans-serif">
          {{ __('ui.home_hero_h1_1') }}<br>
          <span class="relative inline-block">
            <span class="relative z-10">{{ __('ui.home_hero_h1_2') }}</span>
            <span class="absolute bottom-2 left-0 w-full h-5 bg-[#FFE000] -z-0"></span>
          </span><br>{{ __('ui.home_hero_h1_3') }}
        </h1>
        <p class="text-lg text-[#555] max-w-xl font-medium leading-relaxed mb-8">
          {{ __('ui.home_hero_sub') }}<br>
          {{ __('ui.home_hero_sub2') }}
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('register') }}" class="btn-brut text-base px-8 py-4">{{ __('ui.home_start_btn') }} ↗</a>
          <a href="{{ route('courses.index') }}" class="btn-brut-dark text-base px-8 py-4">{{ __('ui.home_browse_btn') }} →</a>
        </div>
      </div>
      <div class="hidden lg:flex flex-col gap-5 pt-4">
        <div class="card-brut-yellow w-52 animate-float">
          <p class="text-mono-sm mb-1">{{ __('ui.home_platform_stat') }}</p>
          <p class="text-5xl font-black leading-none">97%</p>
          <p class="text-sm font-bold mt-1 uppercase tracking-wide">{{ __('ui.home_pass_rate_label') }}</p>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 border-[3px] border-[#0A0A0A]" style="box-shadow:6px 6px 0 #0A0A0A">
      @foreach([['1.2K+',__('ui.home_stat_operators'),false],['135+',__('ui.home_stat_courses'),true],['%97',__('ui.home_stat_pass_rate'),false],['3.4K+',__('ui.home_stat_certs'),false]] as $i => $s)
      <div class="p-6 flex flex-col gap-1 {{ $i < 3 ? 'border-r-[3px] border-[#0A0A0A]' : '' }} {{ $i===1 ? 'bg-[#0A0A0A] text-[#F5F0E8]' : '' }}">
        <span class="text-4xl font-black">{{ $s[0] }}</span>
        <span class="text-mono-sm text-[#888]">{{ $s[1] }}</span>
      </div>
      @endforeach
    </div>
  </section>

  {{-- MARQUEE --}}
  <div class="mt-16">
    <div class="overflow-hidden border-y-[3px] border-[#0A0A0A] bg-[#FFE000] py-3 select-none">
      <div class="marquee-track">
        @foreach(array_fill(0, 4, [__('ui.home_mq_crane'),'★',__('ui.home_mq_cert'),'★',__('ui.home_mq_train'),'★',__('ui.home_mq_safe'),'★',__('ui.home_mq_lms'),'★']) as $row)
          @foreach($row as $w)<span class="font-black text-sm uppercase tracking-widest text-[#0A0A0A] px-8 shrink-0">{{ $w }}</span>@endforeach
        @endforeach
      </div>
    </div>
    <div class="overflow-hidden border-b-[3px] border-[#0A0A0A] bg-[#FFE000] py-3 select-none">
      <div class="marquee-track-reverse">
        @foreach(array_fill(0, 4, [__('ui.home_mq_crane'),'★',__('ui.home_mq_cert'),'★',__('ui.home_mq_train'),'★',__('ui.home_mq_safe'),'★',__('ui.home_mq_lms'),'★']) as $row)
          @foreach($row as $w)<span class="font-black text-sm uppercase tracking-widest text-[#0A0A0A] px-8 shrink-0">{{ $w }}</span>@endforeach
        @endforeach
      </div>
    </div>
  </div>

  {{-- KATEGORİLER --}}
  <section class="max-w-[1400px] mx-auto px-6 py-20">
    <div class="flex items-end justify-between mb-10">
      <h2 class="text-section">{{ __('ui.home_categories') }}</h2>
      <a href="{{ route('courses.index') }}" class="btn-brut-dark text-xs py-2.5 hidden md:inline-flex">{{ __('ui.home_all_courses_btn') }} ↗</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      @foreach([
        ['🦺',__('ui.cat_isg'),'SAFETY','bg-[#FF2D2D]','text-white'],
        ['🏗️',__('ui.cat_crane'),'CRANE_TYPE','bg-[#0047FF]','text-white'],
        ['⚙️',__('ui.cat_operation'),'OPERATION','bg-[#FFE000]','text-[#0A0A0A]'],
        ['🔧',__('ui.cat_technical'),'TECHNICAL','bg-[#CCFF00]','text-[#0A0A0A]'],
        ['⚠️',__('ui.cat_risk'),'RISK','bg-[#FF3CAC]','text-white'],
        ['🪪',__('ui.cat_certification'),'CERTIFICATION','bg-[#0A0A0A]','text-[#FFE000]'],
      ] as $cat)
      <a href="{{ route('courses.index', ['category' => $cat[2]]) }}" class="group flex flex-col justify-between p-6 {{ $cat[3] }} {{ $cat[4] }} border-[3px] border-[#0A0A0A] min-h-[140px] hover-lift">
        <span class="text-3xl">{{ $cat[0] }}</span>
        <div>
          <p class="font-black text-base uppercase tracking-tight leading-snug">{{ $cat[1] }}</p>
          <p class="text-xs font-bold uppercase tracking-wider opacity-60 mt-1">{{ __('ui.home_explore') }} ↗</p>
        </div>
      </a>
      @endforeach
    </div>
  </section>

  {{-- CASE STUDIES --}}
  <section class="border-t-[3px] border-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
      <div class="flex items-center gap-6 mb-12">
        <h2 class="text-section">{{ __('ui.home_cases_title') }}</h2>
        <div class="flex-1 border-t-[3px] border-dashed border-[#0A0A0A] hidden md:block"></div>
        <span class="tag-black hidden md:inline-flex">04 CASE STUDY</span>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach([
          ['01',__('ui.case1_title'),__('ui.case1_tag'),__('ui.case1_desc'),__('ui.case1_stat'),'bg-[#FFE000]','text-[#0A0A0A]','tag-black','🦺'],
          ['02',__('ui.case2_title'),__('ui.case2_tag'),__('ui.case2_desc'),__('ui.case2_stat'),'bg-[#0047FF]','text-white','tag-yellow','🏗️'],
          ['03',__('ui.case3_title'),__('ui.case3_tag'),__('ui.case3_desc'),__('ui.case3_stat'),'bg-[#CCFF00]','text-[#0A0A0A]','tag-black','🪪'],
          ['04',__('ui.case4_title'),__('ui.case4_tag'),__('ui.case4_desc'),__('ui.case4_stat'),'bg-[#FF2D2D]','text-white','tag-yellow','⚠️'],
        ] as $cs)
        <div class="{{ $cs[5] }} {{ $cs[6] }} border-[3px] border-[#0A0A0A] p-8 group hover-lift-cs">
          <div class="flex items-start justify-between mb-6">
            <span class="text-mono-sm opacity-60">{{ $cs[0] }}</span>
            <span class="{{ $cs[7] }} text-[10px]">{{ $cs[2] }}</span>
          </div>
          <div class="text-4xl mb-4">{{ $cs[8] }}</div>
          <h3 class="text-xl font-black uppercase tracking-tight mb-3 leading-snug">{{ $cs[1] }}</h3>
          <p class="text-sm font-medium opacity-75 leading-relaxed mb-6">{{ $cs[3] }}</p>
          <div class="border-t-[2px] border-current opacity-20 mb-4"></div>
          <div class="flex items-center justify-between">
            <span class="font-black text-sm">{{ $cs[4] }}</span>
            <span class="opacity-60 group-hover:opacity-100">↗</span>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- VİNÇ KAPASİTE VİZÜALİZASYON SİSTEMİ --}}
  <section class="border-t-[3px] border-[#0A0A0A] bg-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-16">

      {{-- Başlık --}}
      <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 mb-8">
        <div>
          <div class="flex items-center gap-2 mb-3">
            <span class="tag-yellow text-[10px]">{{ __('ui.cv_section_tag') }}</span>
            <span class="tag-pink text-[10px]">{{ __('ui.cv_live') }}</span>
          </div>
          <h2 class="text-section text-[#F5F0E8]">{{ __('ui.cv_title_1') }}<br><span class="text-[#FFE000]">{{ __('ui.cv_title_2') }}</span></h2>
          <p class="text-[#444] font-medium mt-3 max-w-xl leading-relaxed text-sm">{{ __('ui.cv_desc') }}</p>
        </div>
        <div class="flex items-center gap-3">
          <span class="text-mono-sm text-[#333]">{{ __('ui.cv_safety_thresh') }}</span>
          <input id="cv-thresh" type="range" min="50" max="100" value="80"
            class="w-28 accent-[#FFE000] cursor-pointer">
          <span id="cv-thresh-val" class="text-mono-sm text-[#FFE000] w-10">%80</span>
          <button id="cv-reset" class="border-[2px] border-[#FF2D2D] text-[#FF2D2D] font-black text-[10px] uppercase tracking-wider px-3 py-1.5 hover:bg-[#FF2D2D] hover:text-white transition-colors cursor-pointer">{{ __('ui.cv_clear') }}</button>
        </div>
      </div>

      {{-- Ana panel --}}
      <div class="flex flex-col lg:flex-row border-[3px] border-[#FFE000]" style="box-shadow:6px 6px 0 #FFE000">

        {{-- SOL: Grafik --}}
        <div class="flex-1 bg-[#0a0a0a] relative" style="min-height:460px">

          {{-- Toolbar --}}
          <div class="flex flex-wrap items-center gap-2 px-4 py-2 border-b border-[#222]">
            {{-- Model --}}
            <div class="flex items-center gap-1.5">
              <span class="text-[#666] font-mono text-[9px] uppercase tracking-widest">{{ __('ui.cv_model') }}</span>
              <select id="cv-model" class="bg-[#0f0f0f] text-[#FFE000] border border-[#333] font-black text-[10px] uppercase tracking-wide px-2 py-1.5 outline-none focus:border-[#FFE000] cursor-pointer">
                <option value="ltm1120-41">LTM 1120-4.1</option>
                <option value="ltm1250">LTM 1250</option>
                <option value="gmk4100">Grove GMK4100</option>
                <option value="ac100">Demag AC100</option>
              </select>
            </div>
            <div class="w-px h-5 bg-[#222]"></div>
            {{-- Bum --}}
            <div class="flex items-center gap-1.5">
              <span class="text-[#666] font-mono text-[9px] uppercase tracking-widest">{{ __('ui.cv_boom') }}</span>
              <select id="cv-boom" class="bg-[#0f0f0f] text-[#ccc] border border-[#333] font-bold text-[10px] uppercase px-2 py-1.5 outline-none focus:border-[#FFE000] cursor-pointer">
                <option value="30">30 m</option>
                <option value="42" selected>42 m</option>
                <option value="52">52 m</option>
                <option value="60">60 m</option>
              </select>
            </div>
            <div class="w-px h-5 bg-[#222]"></div>
            {{-- Karşı ağırlık --}}
            <div class="flex items-center gap-1.5">
              <span class="text-[#666] font-mono text-[9px] uppercase tracking-widest">{{ __('ui.cv_counterweight') }}</span>
              <select id="cv-cw" class="bg-[#0f0f0f] text-[#ccc] border border-[#333] font-bold text-[10px] uppercase px-2 py-1.5 outline-none focus:border-[#FFE000] cursor-pointer">
                <option value="8">8 ton</option>
                <option value="14" selected>14 ton</option>
                <option value="20">20 ton</option>
              </select>
            </div>
            <div class="w-px h-5 bg-[#222]"></div>
            {{-- Taşınacak yük --}}
            <div class="flex items-center gap-1.5">
              <span class="text-[#666] font-mono text-[9px] uppercase tracking-widest">{{ __('ui.cv_load') }}</span>
              <input id="cv-load" type="number" min="0" max="999" value="" placeholder="ton"
                class="bg-[#0f0f0f] text-[#fff] border border-[#333] font-bold text-[10px] px-2 py-1.5 w-16 outline-none focus:border-[#FFE000] placeholder-[#333]">
            </div>
            <div class="ml-auto">
              <button id="cv-multi" class="text-[#CCFF00] border border-[#CCFF00] font-bold text-[9px] uppercase tracking-wider px-2.5 py-1 transition-colors cursor-pointer" style="opacity:0.9" data-multi="{{ __('ui.cv_multi_mode') }}" data-single="{{ __('ui.cv_single_mode') }}">
                {{ __('ui.cv_multi_mode') }}
              </button>
            </div>
          </div>

          {{-- SVG --}}
          <div class="relative" id="cv-svg-wrap">
            <svg id="cv-svg" style="display:block;width:100%;height:460px;cursor:crosshair"></svg>
            {{-- Tooltip --}}
            <div id="cv-tip" class="absolute pointer-events-none opacity-0 z-30 bg-[#0f0f0f] border border-[#FFE000] px-3 py-2.5 text-[10px] font-mono min-w-[150px]" style="box-shadow:3px 3px 0 #FFE000;transition:opacity .12s">
              <div id="cv-tip-r" class="text-[#888] mb-0.5"></div>
              <div id="cv-tip-h" class="text-[#888] mb-1.5"></div>
              <div id="cv-tip-cap" class="text-[#FFE000] font-bold text-sm leading-none mb-1"></div>
              <div id="cv-tip-mg" class="text-[#666]"></div>
              <div id="cv-tip-st" class="font-black text-[10px] uppercase tracking-wider mt-1.5"></div>
            </div>
          </div>

          {{-- Lejant alt --}}
          <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 px-4 py-3 border-t border-[#1e1e1e]">
            {{-- Kapasite eğrisi --}}
            <div class="flex items-center gap-2">
              <svg width="28" height="10"><path d="M0 9 L28 1" stroke="#CCFF00" stroke-width="2" fill="none"/></svg>
              <div>
                <p class="font-mono text-[10px] text-[#ddd] font-bold uppercase tracking-wide leading-none">{{ __('ui.cv_cap_curve') }}</p>
                <p class="font-mono text-[9px] text-[#555] leading-none mt-0.5">{{ __('ui.cv_cap_curve_sub') }}</p>
              </div>
            </div>
            {{-- Güvenlik eşiği --}}
            <div class="flex items-center gap-2">
              <svg width="28" height="10"><path d="M0 9 L28 1" stroke="#FF2D2D" stroke-width="1.5" stroke-dasharray="5,3" fill="none"/></svg>
              <div>
                <p class="font-mono text-[10px] text-[#ddd] font-bold uppercase tracking-wide leading-none">{{ __('ui.cv_thresh_line') }}</p>
                <p class="font-mono text-[9px] text-[#555] leading-none mt-0.5">{{ __('ui.cv_thresh_line_sub') }} (<span id="leg-thresh">80</span>%)</p>
              </div>
            </div>
            {{-- Marker durumları --}}
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-[#CCFF00]"></div><span class="font-mono text-[9px] text-[#bbb] uppercase">{{ __('ui.cv_safe') }}</span></div>
              <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-[#FFE000]"></div><span class="font-mono text-[9px] text-[#bbb] uppercase">{{ __('ui.cv_limit') }}</span></div>
              <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-[#FF2D2D]"></div><span class="font-mono text-[9px] text-[#bbb] uppercase">{{ __('ui.cv_exceed') }}</span></div>
            </div>
            <div class="ml-auto font-mono text-[9px] text-[#444] uppercase">{{ __('ui.cv_click_hint') }}</div>
          </div>
        </div>

        {{-- SAĞ: Panel --}}
        <div class="w-full lg:w-72 bg-[#0d0d0d] border-t-[3px] lg:border-t-0 lg:border-l-[3px] border-[#FFE000] flex flex-col">

          {{-- Başlık --}}
          <div class="px-4 py-3 border-b border-[#1e1e1e] bg-[#111]">
            <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest">{{ __('ui.cv_live_calc') }}</p>
            <p id="cv-model-label" class="text-[#FFE000] font-black text-xs uppercase tracking-wide mt-0.5">Liebherr LTM 1120-4.1</p>
          </div>

          {{-- Durum --}}
          <div id="cv-status-box" class="mx-3 my-2.5 py-2 text-center font-black text-xs uppercase tracking-widest border-[2px] border-[#2a2a2a] text-[#3a3a3a]">
            {{ __('ui.cv_select_point') }}
          </div>

          {{-- Değerler 2×2 --}}
          <div class="grid grid-cols-2 border-b border-[#1e1e1e]">
            <div class="px-3 py-2.5 border-r border-[#1e1e1e]">
              <p class="font-mono text-[9px] text-[#666] uppercase tracking-widest mb-1">{{ __('ui.cv_hook_height') }}</p>
              <p id="cv-ph" class="font-black text-xl text-white leading-none">—</p>
              <p class="font-mono text-[9px] text-[#444] mt-1">{{ __('ui.cv_meter') }}</p>
            </div>
            <div class="px-3 py-2.5">
              <p class="font-mono text-[9px] text-[#666] uppercase tracking-widest mb-1">{{ __('ui.cv_radius') }}</p>
              <p id="cv-pr" class="font-black text-xl text-white leading-none">—</p>
              <p class="font-mono text-[9px] text-[#444] mt-1">{{ __('ui.cv_meter') }}</p>
            </div>
          </div>
          <div class="grid grid-cols-2 border-b border-[#1e1e1e]">
            <div class="px-3 py-2.5 border-r border-[#1e1e1e]">
              <p class="font-mono text-[9px] text-[#666] uppercase tracking-widest mb-1">{{ __('ui.cv_max_cap') }}</p>
              <p id="cv-pcap" class="font-black text-xl text-[#FFE000] leading-none">—</p>
              <p class="font-mono text-[9px] text-[#444] mt-1">{{ __('ui.cv_ton_radius') }}</p>
            </div>
            <div class="px-3 py-2.5">
              <p class="font-mono text-[9px] text-[#666] uppercase tracking-widest mb-1">{{ __('ui.cv_margin') }}</p>
              <p id="cv-pmg" class="font-black text-xl text-white leading-none">—</p>
              <p class="font-mono text-[9px] text-[#444] mt-1">{{ __('ui.cv_load_fill') }}</p>
            </div>
          </div>

          {{-- Kapasite bar --}}
          <div class="px-3 py-2.5 border-b border-[#1e1e1e]">
            <div class="flex justify-between items-center mb-1.5">
              <span class="font-mono text-[9px] text-[#666] uppercase tracking-widest">{{ __('ui.cv_cap_usage') }}</span>
              <span id="cv-pct-val" class="font-mono text-[9px] text-[#aaa] font-bold">—</span>
            </div>
            <div class="h-1.5 bg-[#1a1a1a] rounded-none">
              <div id="cv-pct-bar" class="h-full bg-[#CCFF00] transition-all duration-300" style="width:0%"></div>
            </div>
          </div>

          {{-- Vinç özellikleri --}}
          <div class="px-3 py-2.5 border-b border-[#1e1e1e]">
            <p class="font-mono text-[9px] text-[#555] uppercase tracking-widest mb-2">{{ __('ui.cv_crane_specs') }}</p>
            <div class="space-y-1.5">
              <div class="flex justify-between">
                <span class="font-mono text-[10px] text-[#666]">{{ __('ui.cv_boom_len') }}</span>
                <span id="cv-spec-boom" class="font-mono text-[10px] text-[#ccc] font-bold">42 m</span>
              </div>
              <div class="flex justify-between">
                <span class="font-mono text-[10px] text-[#666]">{{ __('ui.cv_cw_label') }}</span>
                <span id="cv-spec-cw" class="font-mono text-[10px] text-[#ccc] font-bold">14 ton</span>
              </div>
              <div class="flex justify-between">
                <span class="font-mono text-[10px] text-[#666]">{{ __('ui.cv_max_height') }}</span>
                <span id="cv-spec-mh" class="font-mono text-[10px] text-[#ccc] font-bold">60 m</span>
              </div>
              <div class="flex justify-between">
                <span class="font-mono text-[10px] text-[#666]">{{ __('ui.cv_max_radius') }}</span>
                <span id="cv-spec-mr" class="font-mono text-[10px] text-[#ccc] font-bold">52 m</span>
              </div>
              <div class="flex justify-between">
                <span class="font-mono text-[10px] text-[#666]">{{ __('ui.cv_safety_th_label') }}</span>
                <span id="cv-spec-th" class="font-mono text-[10px] text-[#FFE000] font-bold">%80</span>
              </div>
            </div>
          </div>

          {{-- Seçili noktalar --}}
          <div class="flex-1 flex flex-col overflow-hidden" style="min-height:0">
            <div class="flex items-center justify-between px-3 py-2 border-b border-[#1e1e1e]">
              <span class="font-mono text-[9px] text-[#555] uppercase tracking-widest">{{ __('ui.cv_selected_points') }}</span>
              <span id="cv-cnt" class="font-mono text-[10px] text-[#FFE000] font-bold">0</span>
            </div>
            <div id="cv-list" class="flex-1 overflow-y-auto divide-y divide-[#1a1a1a]">
              <p class="font-mono text-[10px] text-[#333] text-center py-5">{{ __('ui.cv_add_hint') }}</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  {{-- ÖNE ÇIKAN KURSLAR --}}
  <section class="border-t-[3px] border-[#0A0A0A] bg-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
      <div class="flex items-end justify-between mb-12">
        <h2 class="text-section text-[#F5F0E8]">{{ __('ui.home_featured_title') }}</h2>
        <a href="{{ route('courses.index') }}" class="btn-brut text-xs py-2.5 hidden md:inline-flex">{{ __('ui.home_see_all') }} ↗</a>
      </div>
      @if($featuredCourses->isEmpty())
      <div class="text-center py-16 border-[3px] border-[#F5F0E8]/20">
        <p class="font-black text-[#F5F0E8] uppercase tracking-tight mb-2">{{ __('ui.home_no_courses') }}</p>
      </div>
      @else
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @php $palettes=[['bg-[#FF2D2D]','🦺'],['bg-[#0047FF]','🏗️'],['bg-[#FFE000]','⚙️'],['bg-[#CCFF00]','⚠️'],['bg-[#FF3CAC]','🗼'],['bg-[#0A0A0A]','🪪']]; @endphp
        @foreach($featuredCourses as $i => $c)
        @php $p = $palettes[$i % count($palettes)]; @endphp
        <a href="{{ route('courses.show', $c->slug) }}" class="group border-[3px] border-[#F5F0E8]/20 hover:border-[#FFE000] bg-[#111] p-6 flex flex-col gap-4 hover-lift-yellow">
          <div class="flex items-center justify-between">
            <span class="text-mono-sm text-[#444]">0{{ $i+1 }}</span>
            <span class="text-2xl">{{ $p[1] }}</span>
          </div>
          <div class="h-1.5 w-12 {{ $p[0] }}"></div>
          <h3 class="font-black text-[#F5F0E8] text-sm uppercase tracking-tight leading-snug group-hover:text-[#FFE000] transition-colors">{{ $c->title }}</h3>
          <div class="flex flex-wrap items-center gap-2 mt-auto">
            @if($c->is_mandatory)<span class="tag-red text-[10px]">{{ __('ui.mandatory') }}</span>@endif
            <span class="text-[10px] font-bold uppercase tracking-widest text-[#555]">{{ $c->level }}</span>
            <span class="ml-auto text-[10px] font-bold text-[#555]">{{ $c->enrollments_count }} {{ __('ui.student') }}</span>
          </div>
        </a>
        @endforeach
      </div>
      @endif
    </div>
  </section>

  {{-- NASIL ÇALIŞIR --}}
  <section class="border-t-[3px] border-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
      <h2 class="text-section mb-12 text-center">{{ __('ui.home_how_title') }}</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-[3px] border-[#0A0A0A]" style="box-shadow:8px 8px 0 #0A0A0A">
        @foreach([
          ['01','▶',__('ui.step1_title'),__('ui.step1_desc'),'bg-[#F5F0E8]','text-[#0A0A0A]',true],
          ['02','⚡',__('ui.step2_title'),__('ui.step2_desc'),'bg-[#FFE000]','text-[#0A0A0A]',true],
          ['03','🏆',__('ui.step3_title'),__('ui.step3_desc'),'bg-[#0A0A0A]','text-[#F5F0E8]',false],
        ] as $i => $step)
        <div class="{{ $step[4] }} {{ $step[5] }} p-10 flex flex-col gap-6 {{ $step[6] ? 'border-r-[3px] border-[#0A0A0A]' : '' }}">
          <span class="text-mono-sm opacity-40">{{ $step[0] }}</span>
          <span class="text-3xl">{{ $step[1] }}</span>
          <div>
            <h3 class="font-black text-xl uppercase tracking-tight mb-2">{{ $step[2] }}</h3>
            <p class="text-sm font-medium leading-relaxed opacity-70">{{ $step[3] }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- SERTİFİKASYON YOLU --}}
  <section class="border-t-[3px] border-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <span class="tag-black mb-6 inline-block">{{ __('ui.home_cert_levels') }}</span>
          <h2 class="text-section mb-6">{{ __('ui.home_cert_path') }}</h2>
          <p class="text-[#555] font-medium leading-relaxed mb-8">{{ __('ui.home_cert_desc') }}</p>
          <a href="{{ route('courses.index', ['category'=>'CERTIFICATION']) }}" class="btn-brut text-base px-8 py-4">{{ __('ui.home_cert_start') }} ↗</a>
        </div>
        <div class="space-y-0 border-[3px] border-[#0A0A0A]" style="box-shadow:8px 8px 0 #0A0A0A">
          @foreach([
            [__('ui.cert_level_junior'),4,'bg-[#CCFF00]','text-[#0A0A0A]'],
            [__('ui.cert_level_operator'),7,'bg-[#FFE000]','text-[#0A0A0A]'],
            [__('ui.cert_level_senior'),10,'bg-[#FF3CAC]','text-white'],
            [__('ui.cert_level_supervisor'),14,'bg-[#FF2D2D]','text-white'],
            [__('ui.cert_level_trainer'),18,'bg-[#0A0A0A]','text-[#FFE000]'],
          ] as $i => $lvl)
          <div class="{{ $lvl[2] }} {{ $lvl[3] }} p-5 flex items-center justify-between {{ $i < 4 ? 'border-b-[3px] border-[#0A0A0A]' : '' }}">
            <div class="flex items-center gap-4">
              <span class="text-mono-sm opacity-50">0{{ $i+1 }}</span>
              <span class="font-black uppercase tracking-tight text-sm">{{ $lvl[0] }}</span>
            </div>
            <span class="text-mono-sm opacity-60">{{ $lvl[1] }} {{ __('ui.home_courses_label') }}</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- CONTACT --}}
  <section class="border-t-[3px] border-[#0A0A0A] bg-[#FFE000]">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div>
          <span class="tag-black mb-6 inline-block">{{ __('ui.home_demo_tag') }}</span>
          <h2 class="text-section text-[#0A0A0A] mb-6">{{ __('ui.home_demo_title') }}</h2>
          <p class="text-[#0A0A0A] font-medium leading-relaxed mb-8 opacity-70">{{ __('ui.home_demo_desc') }}</p>
          <div class="space-y-4">
            @foreach([__('ui.home_demo_f1'),__('ui.home_demo_f2'),__('ui.home_demo_f3')] as $item)
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-[#0A0A0A] flex items-center justify-center shrink-0 border-[2px] border-[#0A0A0A]">
                <span class="text-[#FFE000] text-xs">✓</span>
              </div>
              <span class="font-bold text-sm text-[#0A0A0A]">{{ $item }}</span>
            </div>
            @endforeach
          </div>
        </div>
        <form class="bg-[#F5F0E8] border-[3px] border-[#0A0A0A] p-8 space-y-4" style="box-shadow:8px 8px 0 #0A0A0A" method="POST" action="#">
          @csrf
          <div class="grid grid-cols-2 gap-4">
            <div><label class="text-mono-sm mb-1.5 block">{{ __('ui.home_form_name') }}</label><input type="text" placeholder="{{ __('ui.home_name_ph') }}" class="input-brut"></div>
            <div><label class="text-mono-sm mb-1.5 block">{{ __('ui.home_form_company') }}</label><input type="text" placeholder="{{ __('ui.home_company_ph') }}" class="input-brut"></div>
          </div>
          <div><label class="text-mono-sm mb-1.5 block">{{ __('ui.home_form_email') }}</label><input type="email" placeholder="{{ __('ui.home_email_ph') }}" class="input-brut"></div>
          <div>
            <label class="text-mono-sm mb-1.5 block">{{ __('ui.home_form_operators') }}</label>
            <select class="input-brut"><option>1–10</option><option>10–50</option><option>50–200</option><option>200+</option></select>
          </div>
          <div><label class="text-mono-sm mb-1.5 block">{{ __('ui.home_form_message') }}</label><textarea rows="4" placeholder="{{ __('ui.home_form_msg_ph') }}" class="input-brut resize-none"></textarea></div>
          <button type="submit" class="btn-brut-dark w-full justify-center py-4 text-sm">{{ __('ui.home_form_submit') }} ↗</button>
        </form>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="border-t-[3px] border-[#0A0A0A] bg-[#0A0A0A]">
    <div class="max-w-[1400px] mx-auto px-6 py-12">
      <div class="flex flex-col md:flex-row items-start justify-between gap-8 mb-10">
        <div>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-[#FFE000] border-[3px] border-[#FFE000] flex items-center justify-center font-black text-[#0A0A0A] text-lg">C</div>
            <span class="font-black text-xl text-[#F5F0E8] uppercase">LiftAcademy</span>
          </div>
          <p class="text-sm text-[#444] max-w-xs leading-relaxed">{{ __('ui.footer_tagline') }}</p>
        </div>
        <div class="grid grid-cols-3 gap-8 text-sm">
          @foreach([[__('ui.footer_platform'),[__('ui.footer_courses'),__('ui.footer_certification'),__('ui.footer_simulations'),__('ui.footer_admin')]],[__('ui.footer_roles'),[__('ui.footer_operator'),__('ui.footer_supervisor'),__('ui.footer_instructor'),__('ui.footer_manager')]],[__('ui.footer_support'),[__('ui.footer_faq'),__('ui.footer_contact'),__('ui.footer_privacy'),__('ui.footer_terms')]]] as $col)
          <div>
            <p class="text-mono-sm text-[#444] mb-3">{{ $col[0] }}</p>
            @foreach($col[1] as $l)<p><a href="#" class="text-[#888] hover:text-[#FFE000] transition-colors font-medium block mb-1.5">{{ $l }}</a></p>@endforeach
          </div>
          @endforeach
        </div>
      </div>
      <div class="border-t-[2px] border-[#1a1a1a] pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
        <span class="text-mono-sm text-[#444]">{{ __('ui.footer_copyright') }}</span>
        <span class="tag-yellow text-[10px]">v2.0 BRUTALIST EDITION</span>
      </div>
    </div>
  </footer>
</div>
@endsection

@push('scripts')
<script>
window.CV_UI = {
  safe:       "{{ __('ui.cv_status_safe') }}",
  limit:      "{{ __('ui.cv_status_limit') }}",
  exceed:     "{{ __('ui.cv_status_exceed') }}",
  out:        "{{ __('ui.cv_status_out') }}",
  xAxis:      "{{ __('ui.cv_x_axis') }}",
  yAxis:      "{{ __('ui.cv_y_axis') }}",
  maxShort:   "{{ __('ui.cv_max_short') }}",
  clickPoint: "{{ __('ui.cv_click_point') }}",
  listHint:   "{{ __('ui.cv_add_list_hint') }}",
  multiMode:  "{{ __('ui.cv_multi_mode') }}",
  singleMode: "{{ __('ui.cv_single_mode') }}",
  thresh:     "{{ __('ui.cv_safety_thresh') }}",
};
</script>
<script>
/* Hook parallax — smooth scroll follow */
(function(){
  var L=document.getElementById('hook-left'),R=document.getElementById('hook-right');
  if(!L||!R) return;
  var cur=0,tgt=0,raf=null;
  window.addEventListener('scroll',function(){
    tgt=Math.min(window.scrollY*0.25,400);
    if(!raf) raf=requestAnimationFrame(upd);
  },{passive:true});
  function upd(){
    cur+=(tgt-cur)*0.12;
    var y=cur.toFixed(1);
    L.style.transform='translateY('+y+'px)';
    R.style.transform='scaleX(-1) translateY('+y+'px)';
    if(Math.abs(tgt-cur)>0.1){raf=requestAnimationFrame(upd);}
    else{cur=tgt;raf=null;}
  }
})();
</script>
<script>
(function(){
'use strict';
/* X = ÇALIŞMA YARÇAPI (m), Y = KAPASİTE (ton)
   Grafik içinde HER NOKTAYA tıklanabilir. Seçilen (r, yük) çifti
   eğriye göre değerlendirilir: üstü = AŞIM, altı = güvenli/sınır. */
const CRANES = {
  'ltm1120-41': {
    label: 'Liebherr LTM 1120-4.1',
    maxCapTon: 120, maxRadM: 52, maxHookM: 60,
    counterweights: { 8: 0.88, 14: 1.0, 20: 1.07 },
    boomFactor: { 30: 1.12, 42: 1.0, 52: 0.87, 60: 0.76 },
    curve: {
      3:120, 4:97, 5:78, 6:64, 7:53, 8:44.5, 9:37.5, 10:32,
      12:24.2, 14:18.8, 16:14.8, 18:11.8, 20:9.5, 22:7.7,
      24:6.3, 26:5.2, 28:4.3, 30:3.6, 32:3.0, 35:2.3,
      38:1.8, 42:1.4, 46:1.1, 50:0.85, 52:0.7
    },
    heightAt: { 3:57, 5:56, 8:54, 10:52, 14:49, 18:45, 22:41, 26:36, 30:31, 35:25, 40:18, 46:11, 52:4 }
  },
  'ltm1250': {
    label: 'Liebherr LTM 1250',
    maxCapTon: 250, maxRadM: 66, maxHookM: 72,
    counterweights: { 8: 0.90, 14: 1.0, 20: 1.06 },
    boomFactor: { 30: 1.10, 42: 1.0, 52: 0.90, 60: 0.80 },
    curve: {
      3:250, 4:195, 5:158, 6:130, 7:108, 8:92, 9:79, 10:68,
      12:52, 14:41, 16:33, 18:27, 20:22.5, 22:18.8, 24:15.8,
      26:13.3, 28:11.3, 30:9.7, 35:7.2, 40:5.5, 46:4.0,
      52:3.0, 58:2.2, 64:1.6, 66:1.3
    },
    heightAt: { 3:70, 5:69, 8:67, 10:65, 14:62, 18:58, 22:53, 26:48, 30:43, 36:36, 42:28, 50:18, 58:9, 66:2 }
  },
  'gmk4100': {
    label: 'Grove GMK4100',
    maxCapTon: 100, maxRadM: 48, maxHookM: 55,
    counterweights: { 8: 0.90, 14: 1.0, 20: 1.08 },
    boomFactor: { 30: 1.08, 42: 1.0, 52: 0.88, 60: 0.77 },
    curve: {
      3:100, 4:80, 5:66, 6:56, 7:48, 8:41, 9:35.5, 10:31,
      12:24, 14:19.2, 16:15.4, 18:12.6, 20:10.4, 22:8.7,
      24:7.3, 26:6.2, 28:5.3, 30:4.5, 34:3.5, 38:2.7, 42:2.1, 46:1.6, 48:1.3
    },
    heightAt: { 3:53, 5:52, 8:50, 10:48, 14:45, 18:41, 22:37, 26:32, 30:27, 36:20, 42:12, 48:4 }
  },
  'ac100': {
    label: 'Demag AC100',
    maxCapTon: 100, maxRadM: 50, maxHookM: 58,
    counterweights: { 8: 0.91, 14: 1.0, 20: 1.06 },
    boomFactor: { 30: 1.10, 42: 1.0, 52: 0.89, 60: 0.79 },
    curve: {
      3:100, 4:82, 5:67, 6:56, 7:47.5, 8:40, 9:34.5, 10:29.5,
      12:22.5, 14:17.8, 16:14.2, 18:11.5, 20:9.5, 22:7.9,
      24:6.6, 26:5.6, 28:4.8, 30:4.1, 34:3.1, 38:2.4, 42:1.9, 46:1.4, 50:1.0
    },
    heightAt: { 3:56, 5:55, 8:53, 10:51, 14:48, 18:44, 22:40, 26:35, 30:29, 36:22, 42:14, 48:5, 50:1 }
  }
};

function getCurvePoints(craneId, boomLen, cwTon) {
  const c = CRANES[craneId]; if (!c) return [];
  const bf = c.boomFactor[boomLen] || 1.0;
  const cwf = c.counterweights[cwTon] || 1.0;
  const radii = Object.keys(c.curve).map(Number).sort((a,b)=>a-b);
  return radii.map(r => ({ r, cap: Math.max(0, c.curve[r] * bf * cwf) }));
}

function interpCap(craneId, boomLen, cwTon, radius) {
  const pts = getCurvePoints(craneId, boomLen, cwTon);
  if (!pts.length) return 0;
  if (radius <= pts[0].r) return pts[0].cap;
  if (radius >= pts[pts.length-1].r) return 0;
  for (let i=0; i<pts.length-1; i++) {
    if (radius >= pts[i].r && radius <= pts[i+1].r) {
      const t = (radius - pts[i].r) / (pts[i+1].r - pts[i].r);
      return Math.max(0, pts[i].cap + t*(pts[i+1].cap - pts[i].cap));
    }
  }
  return 0;
}

function interpHeight(craneId, radius) {
  const c = CRANES[craneId]; if (!c) return 0;
  const env = c.heightAt;
  const rr = Object.keys(env).map(Number).sort((a,b)=>a-b);
  if (radius <= rr[0]) return env[rr[0]];
  if (radius >= rr[rr.length-1]) return env[rr[rr.length-1]];
  for (let i=0; i<rr.length-1; i++) {
    if (radius >= rr[i] && radius <= rr[i+1]) {
      const t = (radius-rr[i])/(rr[i+1]-rr[i]);
      return env[rr[i]] + t*(env[rr[i+1]]-env[rr[i]]);
    }
  }
  return 0;
}

/* ── DURUM ────────────────────────────────────────────────────────── */
let craneId='ltm1120-41', boomLen=42, cwTon=14, safetyPct=80;
let markers=[], activeId=null, multiMode=true;

/* ── SVG koordinatları ────────────────────────────────────────────── */
const PAD={t:28,r:20,b:48,l:64};
let svgEl, wrapEl, tipEl;

function W()  { return svgEl ? svgEl.clientWidth  : 700; }
function H()  { return svgEl ? svgEl.clientHeight : 460; }
function cW() { return W()-PAD.l-PAD.r; }
function cH() { return H()-PAD.t-PAD.b; }

/* Grafik X: 0..30m, Y: 0..maxCap */
const DISP_MAX_R = 30;
function maxCap() {
  const pts=getCurvePoints(craneId,boomLen,cwTon);
  return pts.length ? Math.ceil(pts[0].cap/20)*20 : 140;
}

function px(r)    { return PAD.l + (r/DISP_MAX_R)*cW(); }
function py(cap)  { return PAD.t + (1-(cap/maxCap()))*cH(); }

/* Piksel → (yarıçap, yük) — HER NOKTAYA tıklanabilir */
function coordFromEvent(e) {
  const rect = svgEl.getBoundingClientRect();
  const mx = e.clientX - rect.left;
  const my = e.clientY - rect.top;
  const r   = Math.max(0, Math.min(DISP_MAX_R, (mx-PAD.l)/cW()*DISP_MAX_R));
  const cap = Math.max(0, Math.min(maxCap(),   (1-(my-PAD.t)/cH())*maxCap()));
  const snapR   = Math.round(r*2)/2;           // 0.5m hassasiyet
  const snapCap = Math.round(cap*10)/10;       // 0.1t hassasiyet
  const maxC    = interpCap(craneId,boomLen,cwTon,snapR);
  return { r:snapR, load:snapCap, maxC };
}

/* Seçilen (r, load) noktasının durumu */
function statusOf(r, load) {
  const maxC = interpCap(craneId,boomLen,cwTon,r);
  if (maxC<=0) return { st:window.CV_UI.out, col:'#555' };
  const ratio = load/maxC*100;
  if (ratio > 100)       return { st:window.CV_UI.exceed, col:'#FF2D2D' };
  if (ratio > safetyPct) return { st:window.CV_UI.limit, col:'#FFE000' };
  return { st:window.CV_UI.safe, col:'#CCFF00' };
}

/* ── SVG ÇİZİM ───────────────────────────────────────────────────── */
function buildSVG() {
  if (!svgEl) return;
  const w=cW(), h=cH(), MC=maxCap();
  const pts=getCurvePoints(craneId,boomLen,cwTon);
  /* sadece DISP_MAX_R içinde kalan eğri noktaları */
  const vispts=pts.filter(p=>p.r<=DISP_MAX_R);
  const cr=CRANES[craneId];
  let s='';

  /* BG */
  s+=`<rect width="${W()}" height="${H()}" fill="#0a0a0a"/>`;
  s+=`<rect x="${PAD.l}" y="${PAD.t}" width="${w}" height="${h}" fill="#0d0d0d"/>`;

  /* Grid */
  const capStep = MC<=60?10:MC<=140?20:50;
  for(let cv=0;cv<=MC;cv+=capStep){
    const y=py(cv), maj=cv%(capStep*2)===0;
    s+=`<line x1="${PAD.l}" y1="${y}" x2="${PAD.l+w}" y2="${y}" stroke="${maj?'#222':'#191919'}" stroke-width="${maj?0.8:0.4}"/>`;
  }
  for(let rv=0;rv<=DISP_MAX_R;rv+=2){
    const x=px(rv), maj=rv%10===0;
    s+=`<line x1="${x}" y1="${PAD.t}" x2="${x}" y2="${PAD.t+h}" stroke="${maj?'#222':'#191919'}" stroke-width="${maj?0.8:0.4}"/>`;
  }

  /* Kapasite eğrisi fill */
  if(vispts.length>1){
    let fill=`M${px(vispts[0].r)} ${py(0)}`;
    for(const p of vispts) fill+=` L${px(p.r)} ${py(p.cap)}`;
    fill+=` L${px(vispts[vispts.length-1].r)} ${py(0)} Z`;
    s+=`<path d="${fill}" fill="rgba(204,255,0,0.06)" stroke="none"/>`;

    let curve=`M${px(vispts[0].r)} ${py(vispts[0].cap)}`;
    for(let i=1;i<vispts.length;i++) curve+=` L${px(vispts[i].r)} ${py(vispts[i].cap)}`;
    s+=`<path d="${curve}" fill="none" stroke="#CCFF00" stroke-width="2.5" stroke-linejoin="round"/>`;
    s+=`<path d="${curve}" fill="none" stroke="#CCFF00" stroke-width="10" opacity="0.07" stroke-linejoin="round"/>`;
  }

  /* Güvenlik eşiği çizgisi — eğrinin safetyPct/100 katı */
  if(vispts.length>1){
    let thr=`M${px(vispts[0].r)} ${py(vispts[0].cap*safetyPct/100)}`;
    for(let i=1;i<vispts.length;i++) thr+=` L${px(vispts[i].r)} ${py(vispts[i].cap*safetyPct/100)}`;
    s+=`<path d="${thr}" fill="none" stroke="#FF6B00" stroke-width="1.2" stroke-dasharray="8,4" opacity="0.55"/>`;
    /* güvenli bölge label */
    const midIdx=Math.floor(vispts.length/3);
    const lx=px(vispts[midIdx].r)+4, ly=py(vispts[midIdx].cap*safetyPct/100)-6;
    s+=`<text x="${lx}" y="${ly}" fill="#FF6B00" font-size="8" font-family="monospace" opacity="0.7" font-weight="700">%${safetyPct} ${window.CV_UI.thresh}</text>`;
  }

  /* Aktif marker crosshair */
  const act=markers.find(m=>m.id===activeId);
  if(act){
    const ax=px(act.r), ay=py(act.load);
    s+=`<line x1="${ax}" y1="${PAD.t}" x2="${ax}" y2="${PAD.t+h}" stroke="#FFE000" stroke-width="1" stroke-dasharray="5,4" opacity="0.6"/>`;
    s+=`<line x1="${PAD.l}" y1="${ay}" x2="${PAD.l+w}" y2="${ay}" stroke="#FFE000" stroke-width="1" stroke-dasharray="5,4" opacity="0.6"/>`;
    /* eğri üzerindeki karşılık noktasını da göster */
    const ay2=py(act.maxC);
    s+=`<line x1="${ax}" y1="${ay2}" x2="${ax}" y2="${ay}" stroke="${act.load>act.maxC?'#FF2D2D':'#CCFF00'}" stroke-width="1.5" opacity="0.4"/>`;
  }

  /* Hover indikatörü */
  s+=`<g id="cv-snap-g" opacity="0" style="pointer-events:none">
    <line id="cv-snap-hl" x1="${PAD.l}" y1="0" x2="${PAD.l+w}" y2="0" stroke="#444" stroke-width="0.7" stroke-dasharray="4,3"/>
    <line id="cv-snap-vl" x1="0" y1="${PAD.t}" x2="0" y2="${PAD.t+h}" stroke="#444" stroke-width="0.7" stroke-dasharray="4,3"/>
    <circle id="cv-snap-dot" cx="0" cy="0" r="4.5" fill="none" stroke="#fff" stroke-width="1.2"/>
    <circle id="cv-snap-edot" cx="0" cy="0" r="3" fill="#CCFF00" opacity="0.8"/>
  </g>`;

  /* Marker'lar */
  for(const m of markers) s+=buildMarker(m);

  /* X ekseni */
  s+=`<line x1="${PAD.l}" y1="${PAD.t+h}" x2="${PAD.l+w}" y2="${PAD.t+h}" stroke="#3a3a3a" stroke-width="1.5"/>`;
  for(let rv=0;rv<=DISP_MAX_R;rv+=2){
    const x=px(rv), maj=rv%10===0, mid=rv%5===0;
    if(!mid&&!maj) continue;
    s+=`<line x1="${x}" y1="${PAD.t+h}" x2="${x}" y2="${PAD.t+h+(maj?6:4)}" stroke="#444" stroke-width="1"/>`;
    if(maj||rv%5===0) s+=`<text x="${x}" y="${PAD.t+h+17}" fill="${maj?'#ccc':'#888'}" font-size="${maj?11:9}" font-family="monospace" text-anchor="middle" font-weight="${maj?'700':'400'}">${rv}</text>`;
  }
  s+=`<text x="${PAD.l+w/2}" y="${H()-4}" fill="#777" font-size="10" font-family="monospace" text-anchor="middle" letter-spacing="3" font-weight="700">${window.CV_UI.xAxis}</text>`;

  /* Y ekseni */
  s+=`<line x1="${PAD.l}" y1="${PAD.t}" x2="${PAD.l}" y2="${PAD.t+h}" stroke="#3a3a3a" stroke-width="1.5"/>`;
  for(let cv=0;cv<=MC;cv+=capStep){
    const y=py(cv);
    s+=`<line x1="${PAD.l-5}" y1="${y}" x2="${PAD.l}" y2="${y}" stroke="#444" stroke-width="1"/>`;
    s+=`<text x="${PAD.l-9}" y="${y+4}" fill="#ccc" font-size="11" font-family="monospace" text-anchor="end" font-weight="600">${cv}</text>`;
  }
  s+=`<text x="13" y="${PAD.t+h/2}" fill="#777" font-size="10" font-family="monospace" text-anchor="middle" font-weight="700" transform="rotate(-90 13 ${PAD.t+h/2})" letter-spacing="2">${window.CV_UI.yAxis}</text>`;

  /* Model etiketi */
  s+=`<text x="${PAD.l+w-4}" y="${PAD.t+13}" fill="#2e2e2e" font-size="9" font-family="monospace" text-anchor="end">${cr.label.toUpperCase()} · BUM ${boomLen}m · KA ${cwTon}t</text>`;

  /* Overlay — en üstte, tüm chart alanı */
  s+=`<rect id="cv-overlay" x="${PAD.l}" y="${PAD.t}" width="${w}" height="${h}" fill="transparent" style="cursor:crosshair"/>`;

  svgEl.innerHTML=s;

  const ov=svgEl.querySelector('#cv-overlay');
  ov.addEventListener('click',     onOverlayClick);
  ov.addEventListener('mousemove', onOverlayMove);
  ov.addEventListener('mouseleave',onOverlayLeave);
}

function buildMarker(m) {
  const x=px(m.r), y=py(m.load);
  const { st, col } = statusOf(m.r, m.load);
  const isAct = m.id===activeId;
  /* eğri üzerindeki nokta */
  const ey=py(m.maxC);
  let g=`<g style="pointer-events:none">`;
  /* eğriye dikey mesafe çizgisi */
  g+=`<line x1="${x}" y1="${Math.min(y,ey)}" x2="${x}" y2="${Math.max(y,ey)}" stroke="${col}" stroke-width="1" opacity="0.3" stroke-dasharray="3,2"/>`;
  /* eğri üzerindeki nokta (küçük) */
  g+=`<circle cx="${x}" cy="${ey}" r="3" fill="#CCFF00" opacity="0.5" stroke="none"/>`;
  if(isAct){
    g+=`<circle cx="${x}" cy="${y}" r="18" fill="${col}" opacity="0.08"/>`;
    g+=`<circle cx="${x}" cy="${y}" r="10" fill="${col}" stroke="#0a0a0a" stroke-width="2"/>`;
    g+=`<circle cx="${x}" cy="${y}" r="3.5" fill="#0a0a0a"/>`;
    const lx=x+14, ly=y-12;
    g+=`<text x="${lx}" y="${ly}" fill="${col}" font-size="10" font-family="monospace" font-weight="700">R${m.r}m / ${m.load.toFixed(1)}t</text>`;
    g+=`<text x="${lx}" y="${ly+13}" fill="${col}" font-size="8" font-family="monospace" opacity="0.8">${window.CV_UI.maxShort}: ${m.maxC.toFixed(1)}t — ${st}</text>`;
  } else {
    g+=`<circle cx="${x}" cy="${y}" r="7" fill="${col}" stroke="#0a0a0a" stroke-width="1.5"/>`;
    g+=`<circle cx="${x}" cy="${y}" r="2.5" fill="#0a0a0a"/>`;
  }
  g+=`</g>`;
  return g;
}

/* ── PANEL GÜNCELLEMESİ ──────────────────────────────────────────── */
function updatePanel() {
  const cr=CRANES[craneId];
  const el=id=>document.getElementById(id);
  el('cv-model-label') && (el('cv-model-label').textContent=cr.label);
  el('cv-spec-boom') && (el('cv-spec-boom').textContent=boomLen+' m');
  el('cv-spec-cw')   && (el('cv-spec-cw').textContent=cwTon+' ton');
  el('cv-spec-mh')   && (el('cv-spec-mh').textContent=cr.maxHookM+' m');
  el('cv-spec-mr')   && (el('cv-spec-mr').textContent=cr.maxRadM+' m');
  el('cv-spec-th')   && (el('cv-spec-th').textContent='%'+safetyPct);

  const act=markers.find(m=>m.id===activeId);
  if(!act){
    ['cv-ph','cv-pr','cv-pcap','cv-pmg'].forEach(i=>el(i)&&(el(i).textContent='—'));
    setStatus(null,null);
    el('cv-pct-val') && (el('cv-pct-val').textContent='—');
    el('cv-pct-bar') && (el('cv-pct-bar').style.width='0%');
  } else {
    const { r, load, maxC } = act;
    const hookH = Math.round(interpHeight(craneId,r)*10)/10;
    const { st, col } = statusOf(r, load);
    const usePct = maxC>0 ? Math.min(200,Math.round(load/maxC*100)) : 0;
    const margin = maxC>0 ? ((1-load/maxC)*100).toFixed(0)+'%' : '—';

    el('cv-ph')  && (el('cv-ph').textContent=hookH);
    el('cv-pr')  && (el('cv-pr').textContent=r);
    el('cv-pcap')&& (el('cv-pcap').textContent=maxC.toFixed(1));
    el('cv-pmg') && (el('cv-pmg').textContent=load<=maxC?margin:window.CV_UI.exceed);
    el('cv-pct-val') && (el('cv-pct-val').textContent='%'+usePct);
    if(el('cv-pct-bar')){
      el('cv-pct-bar').style.width=Math.min(100,usePct)+'%';
      el('cv-pct-bar').style.background=col;
    }
    setStatus(st,col);
  }
  updateList();
}

function setStatus(st,col){
  const box=document.getElementById('cv-status-box'); if(!box) return;
  if(!st){ box.textContent=window.CV_UI.clickPoint; box.style.borderColor='#2a2a2a'; box.style.color='#3a3a3a'; return; }
  box.textContent='● '+st; box.style.color=col; box.style.borderColor=col;
}

function updateList(){
  const el=id=>document.getElementById(id);
  if(!el('cv-list')) return;
  el('cv-cnt') && (el('cv-cnt').textContent=markers.length);
  if(!markers.length){
    el('cv-list').innerHTML=`<p class="font-mono text-[10px] text-[#2a2a2a] text-center py-5">${window.CV_UI.listHint}</p>`;
    return;
  }
  el('cv-list').innerHTML=markers.map(m=>{
    const {st,col}=statusOf(m.r,m.load);
    const isAct=m.id===activeId;
    const pct=m.maxC>0?Math.round(m.load/m.maxC*100):0;
    return `<div class="flex items-center justify-between px-3 py-2.5 cursor-pointer hover:bg-[#131313] border-l-2 transition-all ${isAct?'bg-[#141414]':''}" style="border-color:${isAct?col:'transparent'}" onclick="window.__cvSel(${m.id})">
      <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-0.5">
          <span class="font-mono text-[10px] font-bold text-white">R ${m.r}m</span>
          <span class="font-mono text-[10px] text-[#555]">→</span>
          <span class="font-mono text-[10px] font-bold" style="color:${col}">${m.load.toFixed(1)} ton</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="font-mono text-[9px] text-[#555]">${window.CV_UI.maxShort}: ${m.maxC.toFixed(1)}t</span>
          <span class="font-mono text-[9px]" style="color:${col}">%${pct} — ${st}</span>
        </div>
      </div>
      <button class="text-[#2a2a2a] hover:text-[#FF2D2D] text-sm font-black px-2 shrink-0 transition-colors" onclick="event.stopPropagation();window.__cvDel(${m.id})">✕</button>
    </div>`;
  }).join('');
}

/* ── TOOLTIP ─────────────────────────────────────────────────────── */
function showTip(e, r, load, maxC){
  if(!tipEl) return;
  const hookH=Math.round(interpHeight(craneId,r)*10)/10;
  const {st,col}=statusOf(r,load);
  const pct=maxC>0?Math.round(load/maxC*100):0;
  tipEl.querySelector('#cv-tip-r').textContent=`R: ${r} m  |  H: ${hookH} m`;
  tipEl.querySelector('#cv-tip-h').textContent=`${load.toFixed(1)} t`;
  tipEl.querySelector('#cv-tip-cap').textContent=`${window.CV_UI.maxShort}: ${maxC.toFixed(1)} t`;
  tipEl.querySelector('#cv-tip-mg').textContent=`%${pct} ${load>maxC?'⚠ '+window.CV_UI.exceed:''}`;
  const ts=tipEl.querySelector('#cv-tip-st'); ts.textContent='● '+st; ts.style.color=col;
  const par=wrapEl.getBoundingClientRect();
  let tx=e.clientX-par.left+16, ty=e.clientY-par.top-14;
  if(tx+170>par.width) tx=e.clientX-par.left-180;
  if(ty<0) ty=4;
  tipEl.style.left=tx+'px'; tipEl.style.top=ty+'px'; tipEl.style.opacity='1';
}
function hideTip(){ if(tipEl) tipEl.style.opacity='0'; }

/* ── OVERLAY EVENT'LERİ ──────────────────────────────────────────── */
function onOverlayClick(e){
  const pt=coordFromEvent(e); if(!pt) return;
  /* 1t hassasiyetle aynı noktayı bul */
  const existing=markers.find(m=>m.r===pt.r && Math.abs(m.load-pt.load)<1);
  if(existing){
    if(existing.id===activeId){
      markers=markers.filter(m=>m.id!==existing.id);
      activeId=markers.length?markers[markers.length-1].id:null;
    } else { activeId=existing.id; }
  } else {
    if(!multiMode) markers=[];
    const id=Date.now()+Math.random();
    markers.push({id, r:pt.r, load:pt.load, maxC:pt.maxC});
    activeId=id;
  }
  buildSVG(); updatePanel();
}

function onOverlayMove(e){
  const pt=coordFromEvent(e); if(!pt) return;
  const sg=svgEl.querySelector('#cv-snap-g');
  if(!sg) return;
  const vl=svgEl.querySelector('#cv-snap-vl');
  const hl=svgEl.querySelector('#cv-snap-hl');
  const dot=svgEl.querySelector('#cv-snap-dot');
  const edot=svgEl.querySelector('#cv-snap-edot');
  const sx=px(pt.r), sy=py(pt.load), ey=py(pt.maxC);
  vl&&(vl.setAttribute('x1',sx),vl.setAttribute('x2',sx));
  hl&&(hl.setAttribute('y1',sy),hl.setAttribute('y2',sy));
  dot&&(dot.setAttribute('cx',sx),dot.setAttribute('cy',sy));
  edot&&(edot.setAttribute('cx',sx),edot.setAttribute('cy',ey));
  const {col}=statusOf(pt.r,pt.load);
  dot&&dot.setAttribute('stroke',col);
  sg.setAttribute('opacity','1');
  showTip(e, pt.r, pt.load, pt.maxC);
}

function onOverlayLeave(){
  const sg=svgEl&&svgEl.querySelector('#cv-snap-g');
  if(sg) sg.setAttribute('opacity','0');
  hideTip();
}

window.__cvSel=function(id){ activeId=id; buildSVG(); updatePanel(); };
window.__cvDel=function(id){
  markers=markers.filter(m=>m.id!==id);
  if(activeId===id) activeId=markers.length?markers[markers.length-1].id:null;
  buildSVG(); updatePanel();
};

/* ── BAŞLATMA ────────────────────────────────────────────────────── */
function init(){
  svgEl=document.getElementById('cv-svg'); if(!svgEl) return;
  wrapEl=document.getElementById('cv-svg-wrap');
  tipEl =document.getElementById('cv-tip');

  // Model
  document.getElementById('cv-model')?.addEventListener('change',function(){
    craneId=this.value; markers=[]; activeId=null;
    buildSVG(); updatePanel();
  });

  // Boom
  document.getElementById('cv-boom')?.addEventListener('change',function(){
    boomLen=+this.value;
    markers=markers.map(m=>({...m, maxC:interpCap(craneId,boomLen,cwTon,m.r)}));
    buildSVG(); updatePanel();
  });

  // Counterweight
  document.getElementById('cv-cw')?.addEventListener('change',function(){
    cwTon=+this.value;
    markers=markers.map(m=>({...m, maxC:interpCap(craneId,boomLen,cwTon,m.r)}));
    buildSVG(); updatePanel();
  });

  // Safety threshold
  document.getElementById('cv-thresh')?.addEventListener('input',function(){
    safetyPct=+this.value;
    const vEl=document.getElementById('cv-thresh-val'); if(vEl) vEl.textContent='%'+safetyPct;
    const sp=document.getElementById('cv-spec-th'); if(sp) sp.textContent='%'+safetyPct;
    const lt=document.getElementById('leg-thresh'); if(lt) lt.textContent=safetyPct;
    buildSVG(); updatePanel();
  });

  // Multi mode (varsayılan açık)
  document.getElementById('cv-multi')?.addEventListener('click',function(){
    multiMode=!multiMode;
    this.textContent=multiMode?window.CV_UI.multiMode:window.CV_UI.singleMode;
    this.style.color=multiMode?'#CCFF00':'#666';
    this.style.borderColor=multiMode?'#CCFF00':'#444';
    this.style.opacity=multiMode?'0.9':'0.6';
  });

  // Reset
  document.getElementById('cv-reset')?.addEventListener('click',function(){
    markers=[]; activeId=null; buildSVG(); updatePanel();
  });

  new ResizeObserver(()=>{ buildSVG(); updatePanel(); }).observe(svgEl.parentElement);

  buildSVG(); updatePanel();
}

if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',init);
else init();

})();
</script>
@endpush
