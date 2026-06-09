# LiftAcademy — Sistem Dokümantasyonu

> Laravel 11 tabanlı vinç operatörü eğitim platformu. Oluşturulma: 2025/2026.

---

## İçindekiler

1. [Proje Genel Bakış](#1-proje-genel-bakış)
2. [Teknoloji Yığını](#2-teknoloji-yığını)
3. [Dizin Yapısı](#3-dizin-yapısı)
4. [Veritabanı Şeması](#4-veritabanı-şeması)
5. [Model Katmanı](#5-model-katmanı)
6. [Controller Katmanı](#6-controller-katmanı)
7. [Route Yapısı](#7-route-yapısı)
8. [View Katmanı](#8-view-katmanı)
9. [Tasarım Sistemi](#9-tasarım-sistemi)
10. [Vinç Kapasite Vizüalizasyon Motoru](#10-vinç-kapasite-vizüalizasyon-motoru)
11. [Konfigürasyon Dosyaları](#11-konfigürasyon-dosyaları)
12. [Önemli Notlar](#12-önemli-notlar)

---

## 1. Proje Genel Bakış

LiftAcademy, vinç operatörlerini yetiştirmek için tasarlanmış kurumsal bir LMS (Learning Management System) platformudur. Temel özellikler:

- Video eğitim + Quiz + Senaryo simülasyonu
- 5 kademeli uluslararası sertifikasyon (Junior → Trainer)
- Zorunlu İSG müfredatı
- İnteraktif vinç kapasite simülatörü (ana sayfada)
- Brutalist siyah/sarı tasarım dili

**Dağıtım Ortamı:** Standart PHP hosting (Python yok, Node sadece build aşamasında).

---

## 2. Teknoloji Yığını

| Katman | Teknoloji | Versiyon |
|--------|-----------|----------|
| Backend | Laravel | 11.x |
| PHP | PHP | ^8.2 |
| Frontend CSS | TailwindCSS | 4.x (`@tailwindcss/vite`) |
| Frontend JS | Vanilla JS | inline `<script>` |
| SVG Grafik | Inline SVG | (kütüphane yok) |
| Build | Vite | 6.x |
| DB | SQLite (geliştirme) | — |
| Auth | Laravel Session Auth | built-in |

### Bağımlılıklar (composer.json)

```
laravel/framework ^11.31
laravel/tinker ^2.9
```

### Bağımlılıklar (package.json devDependencies)

```
@tailwindcss/vite ^4.3.0
tailwindcss ^4.3.0
laravel-vite-plugin ^1.2.0
vite ^6.0.11
axios ^1.7.4
```

---

## 3. Dizin Yapısı

```
liftacademy-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php       — Giriş/Kayıt/Çıkış
│   │   ├── CourseController.php     — Kurs listeleme, detay, kayıt, öğrenme
│   │   ├── DashboardController.php  — Kullanıcı panosu
│   │   └── HomeController.php       — Ana sayfa (featured courses)
│   └── Models/
│       ├── User.php
│       ├── Course.php
│       ├── Section.php
│       ├── Lesson.php
│       ├── Enrollment.php
│       ├── Progress.php
│       ├── Certificate.php
│       ├── Quiz.php
│       ├── QuizQuestion.php
│       └── Simulation.php
├── database/migrations/
│   ├── 0001_create_users_table.php
│   ├── 0002_create_courses_table.php
│   ├── 0003_create_sections_lessons_table.php
│   ├── 0004_create_enrollments_progress_table.php
│   ├── 0005_create_quizzes_table.php
│   ├── 0006_create_simulations_table.php
│   ├── 0007_create_certificates_table.php
│   └── 0008_create_payments_reviews_table.php
├── resources/
│   ├── css/app.css                  — Tailwind import + design tokens + utility classes
│   ├── js/app.js                    — Sadece bootstrap import
│   └── views/
│       ├── layouts/app.blade.php    — Ana layout
│       ├── partials/navbar.blade.php
│       ├── home.blade.php           — Ana sayfa (vinç viz dahil)
│       ├── courses/
│       │   ├── index.blade.php
│       │   ├── show.blade.php
│       │   └── learn.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard.blade.php
│       ├── admin/dashboard.blade.php
│       └── certificates/
│           ├── index.blade.php
│           └── show.blade.php
├── routes/web.php
├── vite.config.js
├── composer.json
└── package.json
```

---

## 4. Veritabanı Şeması

### users

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| name | string | |
| email | string unique | |
| password | string hashed | |
| role | enum | STUDENT / INSTRUCTOR / ADMIN |
| status | string | ACTIVE / SUSPENDED |
| avatar | string nullable | |
| company | string nullable | |
| phone | string nullable | |
| bio | text nullable | |
| email_verified_at | timestamp nullable | |
| remember_token | string | |
| timestamps | | |

### courses

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| title | string | |
| slug | string unique | URL slug |
| description | text nullable | |
| category | enum | SAFETY / CRANE_TYPE / OPERATION / TECHNICAL / RISK / CERTIFICATION / COMPANY |
| crane_type | enum nullable | MOBILE / TOWER / PORTAL / HIAB / AERIAL / TELESCOPIC |
| level | enum | BEGINNER / INTERMEDIATE / ADVANCED / ALL_LEVELS |
| price | decimal(10,2) | default 0 |
| thumbnail | string nullable | |
| published | boolean | default false |
| is_mandatory | boolean | default false |
| passing_score | integer | default 70 |
| instructor_id | FK users | cascade delete |
| timestamps | | |

### sections

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| title | string | |
| order | integer | default 0 |
| course_id | FK courses | cascade delete |
| timestamps | | |

### lessons

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| title | string | |
| type | enum | VIDEO / DOCUMENT / QUIZ / SIMULATION |
| video_url | string nullable | |
| document_url | string nullable | |
| duration | integer nullable | saniye cinsinden |
| order | integer | default 0 |
| is_free | boolean | default false |
| section_id | FK sections | cascade delete |
| timestamps | | |

### enrollments

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | cascade delete |
| course_id | FK courses | cascade delete |
| status | enum | ACTIVE / COMPLETED / SUSPENDED / EXPIRED |
| completed_at | timestamp nullable | |
| timestamps | | |
| UNIQUE | (user_id, course_id) | |

### progress

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | cascade delete |
| lesson_id | FK lessons | cascade delete |
| completed | boolean | default false |
| watched_sec | integer | default 0 |
| timestamps | | |
| UNIQUE | (user_id, lesson_id) | |

### quizzes

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| title | string | |
| course_id | FK courses | cascade delete |
| passing_score | integer | default 70 |
| time_limit | integer nullable | dakika |
| attempts | integer | default 3 |
| timestamps | | |

### quiz_questions

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| quiz_id | FK quizzes | cascade delete |
| question | text | |
| options | json | ["A", "B", "C", "D"] |
| correct_answer | integer | options[] index |
| explanation | text nullable | |
| order | integer | default 0 |
| (timestamps yok) | | |

### quiz_attempts

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | |
| quiz_id | FK quizzes | |
| score | integer | % |
| passed | boolean | |
| answers | json | {questionId: selectedIndex} |
| started_at | timestamp | useCurrent |
| finished_at | timestamp nullable | |

### simulations

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| title | string | |
| course_id | FK courses | cascade delete |
| scenario | text | |
| difficulty | integer | 1–5 |
| timestamps | | |

### simulation_attempts

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | |
| simulation_id | FK simulations | |
| score | integer | |
| passed | boolean | |
| decisions | json | |
| completed_at | timestamp | useCurrent |

### certificates

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | cascade delete |
| course_id | FK courses | cascade delete |
| level | enum | JUNIOR / OPERATOR / SENIOR / SUPERVISOR / TRAINER |
| cert_number | string unique | |
| status | enum | ACTIVE / EXPIRED / REVOKED |
| recipient_name | string nullable | |
| employee_id | string nullable | |
| department | string nullable | |
| site | string nullable | |
| instructor_name | string nullable | |
| training_hours | integer nullable | |
| completed_at | timestamp nullable | |
| expires_at | timestamp nullable | |
| notes | text nullable | |
| timestamps | | |
| UNIQUE | (user_id, course_id) | |

### certificate_configs

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| course_id | FK courses unique | |
| cert_level | enum | JUNIOR / OPERATOR / SENIOR / SUPERVISOR / TRAINER |
| completion_days | integer | default 30 |
| validity_days | integer | default 365 |
| requires_quiz | boolean | default true |
| min_watch_pct | integer | default 80 |
| notes | text nullable | |
| timestamps | | |

### cert_prerequisites

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| config_id | FK certificate_configs | |
| course_id | FK courses | |
| UNIQUE | (config_id, course_id) | |

### payments

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | |
| course_id | FK courses | |
| stripe_id | string unique nullable | |
| amount | decimal(10,2) | |
| status | enum | PENDING / COMPLETED / REFUNDED |
| timestamps | | |

### reviews

| Kolon | Tip | Not |
|-------|-----|-----|
| id | bigint PK | |
| user_id | FK users | |
| course_id | FK courses | |
| rating | integer | 1–5 |
| comment | text nullable | |
| timestamps | | |
| UNIQUE | (user_id, course_id) | |

---

## 5. Model Katmanı

### User (`app/Models/User.php`)

```php
fillable: name, email, password, role, status, avatar, company, phone, bio
hidden: password, remember_token
casts: email_verified_at → datetime, password → hashed

relations:
  enrollments() → hasMany(Enrollment)
  certificates() → hasMany(Certificate)

helpers:
  isAdmin(): bool   — role === 'ADMIN'
  isInstructor(): bool — role in ['ADMIN', 'INSTRUCTOR']
```

### Course (`app/Models/Course.php`)

```php
fillable: title, slug, description, thumbnail, level, category, crane_type,
          published, price, is_mandatory, passing_score, instructor_id

relations:
  instructor()    → belongsTo(User)
  sections()      → hasMany(Section)
  enrollments()   → hasMany(Enrollment)
  certificates()  → hasMany(Certificate)
  quizzes()       → hasMany(Quiz)
  simulations()   → hasMany(Simulation)
```

### Section (`app/Models/Section.php`)

```php
fillable: course_id, title, order

relations:
  course()  → belongsTo(Course)
  lessons() → hasMany(Lesson)->orderBy('order')
```

### Lesson (`app/Models/Lesson.php`)

```php
fillable: section_id, title, type, content, video_url, duration, order, is_free
casts: is_free → boolean

relations:
  section() → belongsTo(Section)
```

### Enrollment (`app/Models/Enrollment.php`)

```php
fillable: user_id, course_id, status, completed_at
casts: completed_at → datetime

accessor:
  getProgressPercentAttribute(): int
    — ilişkili course+sections+lessons yüklenmiş olmalı
    — tamamlanan lesson sayısı / toplam ders * 100

relations:
  user()   → belongsTo(User)
  course() → belongsTo(Course)
```

### Progress (`app/Models/Progress.php`)

```php
table: 'progress'
fillable: user_id, lesson_id, completed, watched_sec
casts: completed → boolean
```

### Certificate (`app/Models/Certificate.php`)

```php
fillable: user_id, course_id, cert_number, level, status,
          recipient_name, instructor_name, training_hours,
          completed_at, expires_at
casts: completed_at, expires_at, created_at → datetime

accessors:
  getIssuedAtAttribute()          → returns created_at
  getCertificateNumberAttribute() → returns cert_number

relations:
  user()   → belongsTo(User)
  course() → belongsTo(Course)
```

### Quiz (`app/Models/Quiz.php`)

```php
fillable: title, course_id, passing_score, time_limit, attempts

relations:
  course()    → belongsTo(Course)
  questions() → hasMany(QuizQuestion)
```

### QuizQuestion (`app/Models/QuizQuestion.php`)

```php
$timestamps = false
fillable: quiz_id, question, options, correct_answer, explanation, order
casts: options → array

relations:
  quiz() → belongsTo(Quiz)
```

### Simulation (`app/Models/Simulation.php`)

```php
fillable: title, course_id, scenario, difficulty

relations:
  course() → belongsTo(Course)
```

---

## 6. Controller Katmanı

### HomeController

```
GET /  →  index()
  — Course::where('published',true)->withCount('enrollments')->orderByDesc()->take(6)->get()
  — view('home', compact('featuredCourses'))
```

### AuthController

```
GET  /login    → showLogin()    → view('auth.login')
POST /login    → login()
  — validate: email(required|email), password(required|min:6)
  — Auth::attempt() + session regenerate
  — redirect()->intended(route('dashboard'))
  — hata: 'E-posta veya şifre hatalı.'

GET  /register → showRegister() → view('auth.register')
POST /register → register()
  — validate: name, email(unique:users), password(min:8|confirmed), role(in:STUDENT,INSTRUCTOR)
  — User::create() + Auth::login()
  — redirect → dashboard

POST /logout   → logout()
  — Auth::logout() + session invalidate + token regenerate
  — redirect → home
```

### CourseController

```
GET  /courses          → index()
  — paginate(12), query params: ?category=, ?search=
  — withCount(['enrollments','sections'])
  — view('courses.index')

GET  /courses/{slug}   → show()
  — with(['sections.lessons','instructor','quizzes.questions','simulations'])
  — $mandatoryCourses = Course::where('is_mandatory',true)->take(4)->get()
  — view('courses.show')

POST /courses/{slug}/enroll → enroll()  [auth]
  — Enrollment::firstOrCreate(['user_id'=>..., 'course_id'=>...])
  — redirect → learn

GET  /courses/{slug}/learn  → learn()   [auth]
  — auth + enrollment kontrolü
  — view('courses.learn')
```

### DashboardController

```
GET /dashboard → index()  [auth]
  — Enrollment::where(user_id)->with(['course.sections.lessons'])->latest()->get()
  — Certificate::where(user_id)->with('course')->latest('issued_at')->take(5)->get()
  — stats: enrollments, completed, certificates, progress(avg progress_percent)
  — view('dashboard')
```

---

## 7. Route Yapısı

```php
// Public
GET  /                          → HomeController@index           name: home
GET  /login                     → AuthController@showLogin       name: login    [guest]
POST /login                     → AuthController@login
GET  /register                  → AuthController@showRegister    name: register [guest]
POST /register                  → AuthController@register
POST /logout                    → AuthController@logout          name: logout
GET  /courses                   → CourseController@index         name: courses.index
GET  /courses/{slug}            → CourseController@show          name: courses.show

// Auth required
GET  /dashboard                 → DashboardController@index      name: dashboard
POST /courses/{slug}/enroll     → CourseController@enroll        name: courses.enroll
GET  /courses/{slug}/learn      → CourseController@learn         name: courses.learn
GET  /certificates              → view('certificates.index')     name: certificates.index
GET  /certificates/{id}         → view('certificates.show')      name: certificates.show

// Admin
GET  /admin/                    → redirect to admin.dashboard    name: admin.index
GET  /admin/dashboard           → view('admin.dashboard')        name: admin.dashboard
```

---

## 8. View Katmanı

### layouts/app.blade.php

```html
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'LiftAcademy')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F0E8]">
  @include('partials.navbar')
  <main>@yield('content')</main>
  @stack('scripts')   ← sayfa-spesifik JS buraya eklenir
</body>
</html>
```

### partials/navbar.blade.php

- Sticky brutalist navbar (bg-[#F5F0E8], border-b-[3px] border-[#0A0A0A])
- Logo sol, nav linkleri orta (KURSLAR, SERTİFİKA, SİMÜLATÖR, HAKKINDA)
- Giriş yapmış kullanıcı: dropdown (avatar/isim + rol badge + PANEL + ÇIKIŞ)
- Giriş yapmamış: GİRİŞ + KAYIT butonları
- Rol badge renkleri: ADMIN=yellow, INSTRUCTOR=lime, STUDENT=blue
- Alpine.js x-data ile dropdown, JS fallback mevcut
- Mobile: hamburger menü

### home.blade.php (969 satır)

Bölümler (sırası ile):

1. **HERO** — Başlık "GÜVENLİ OPERATÖR YETİŞTİR", CTA butonlar, 97% istatistik kartı
2. **Stats bar** — 1.2K+ operatör, 135+ kurs, %97 geçme, 3.4K+ sertifika
3. **MARQUEE** — Çift yönlü kayan banner (sarı zemin)
4. **KATEGORİLER** — 6 kategori kartı (güvenlik/vinç/operasyon/teknik/risk/sertifika)
5. **ÇALIŞMA ÖRNEKLERİ (Case Studies)** — 4 örnek kart
6. **VİNÇ KAPASİTE VİZÜALİZASYONU** — İnteraktif simülatör (bkz. Bölüm 10)
7. **ÖNE ÇIKAN KURSLAR** — `$featuredCourses` loop
8. **NASIL ÇALIŞIR?** — 3 adım (Video → Quiz → Sertifika)
9. **SERTİFİKASYON PROGRAMI** — 5 kademe listesi
10. **CONTACT/DEMO FORMU** — Kurumsal demo talep formu
11. **FOOTER** — Logo, 3 sütun linkler, copyright

### courses/index.blade.php

- Filtreler: kategori dropdown + arama input
- Kurs grid (3 sütun desktop)
- Pagination
- Zorunlu kurslar ayrıca gösteriliyor

### courses/show.blade.php

- 3 sütun layout: içerik (7/12) + sidebar (5/12)
- Sol: başlık, açıklama, bölümler + dersler listesi, quizler, simülasyonlar
- Sağ: thumbnail, fiyat/kayıt CTA, instructor info, kurs metadata
- Zorunlu kurs badge

### courses/learn.blade.php

- Video player alanı (sol)
- Ders listesi sidebar (sağ)
- İlerleme takibi

### auth/login.blade.php & register.blade.php

- Brutalist form tasarımı
- Login: email + password + remember me
- Register: name + email + password + password_confirmation + role (STUDENT/INSTRUCTOR)

### dashboard.blade.php

- Stats bar (kayıt/tamamlama/sertifika/ilerleme)
- Kayıtlı kurslar listesi (ilerleme çubuğu ile)
- Son sertifikalar

### admin/dashboard.blade.php

- Platform istatistikleri
- Kullanıcı/kurs/sertifika özet tabloları

### certificates/index.blade.php & show.blade.php

- Sertifika listesi (kart grid)
- Sertifika detay: numara, seviye, geçerlilik tarihi, doğrulama

---

## 9. Tasarım Sistemi

### Renk Paleti (`resources/css/app.css`)

```css
:root {
  --black:  #0A0A0A;
  --white:  #F5F0E8;  /* kırık beyaz */
  --yellow: #FFE000;
  --lime:   #CCFF00;
  --red:    #FF2D2D;
  --blue:   #0047FF;
  --pink:   #FF3CAC;
}
```

### Tipografi

```
font-family: Space Grotesk (başlıklar), Inter (gövde)
text-display:  font-black, 80–96px, uppercase
text-section:  font-black, 48–64px, uppercase
text-mono-sm:  font-mono, 10–11px, uppercase, tracking-widest
```

### Utility Sınıfları

```
btn-brut         — sarı bg, siyah border 3px, offset shadow, hover-lift
btn-brut-dark    — siyah bg, beyaz text
btn-brut-red     — kırmızı
input-brut       — siyah border, tam genişlik

tag-black/yellow/lime/red/blue/pink
  — küçük etiket badge'leri, uppercase, font-mono

hover-lift        — translateY(-2px) box-shadow büyür
hover-lift-sm     — daha küçük
hover-lift-cs     — case study kartları
hover-lift-yellow — sarı gölge
card-brut-yellow  — sarı bg, border 3px, offset shadow
```

### Brutalist Tasarım Kuralları

- Tüm border: `border-[3px] border-[#0A0A0A]`
- Box shadow offset: `6px 6px 0 #0A0A0A` (veya renk varyantları)
- Grid/card gap: sıfır veya 4–5
- Font weight: 900 (font-black) baskın
- Letter spacing: tracking-tight veya tracking-widest
- Hover: translateY + shadow büyüme animasyonu

---

## 10. Vinç Kapasite Vizüalizasyon Motoru

`resources/views/home.blade.php` içinde `@push('scripts')` bloğunda, ~494–969. satırlar.

### Mimari Özet

Tamamen vanilla JS + inline SVG. Kütüphane yok. `@stack('scripts')` ile layout'a eklenir.

**Temel fikir:** X ekseni = çalışma yarıçapı (0–30m), Y ekseni = kapasite (ton).
Grafik içindeki herhangi bir noktaya tıklanınca seçilen `(r, load)` çifti, vincin kapasite eğrisine göre değerlendirilir.

### CRANES Veri Yapısı

```js
const CRANES = {
  'ltm1120-41': {
    label: 'Liebherr LTM 1120-4.1',
    maxCapTon: 120,      // maksimum kaldırma kapasitesi (ton)
    maxRadM: 52,         // maksimum çalışma yarıçapı (m)
    maxHookM: 60,        // maksimum kanca yüksekliği (m)
    counterweights: {    // karşı ağırlık faktörleri
      8: 0.88, 14: 1.0, 20: 1.07
    },
    boomFactor: {        // bum uzunluğu faktörleri
      30: 1.12, 42: 1.0, 52: 0.87, 60: 0.76
    },
    curve: {             // yarıçap(m) → kapasite(ton) ham tablo
      3:120, 4:97, 5:78, ..., 52:0.7
    },
    heightAt: {          // yarıçap(m) → kanca yüksekliği(m) tablosu
      3:57, 5:56, ..., 52:4
    }
  },
  'ltm1250': { ... },   // Liebherr LTM 1250 (250t)
  'gmk4100':  { ... },  // Grove GMK4100 (100t)
  'ac100':    { ... }   // Demag AC100 (100t)
};
```

### State Değişkenleri

```js
let craneId  = 'ltm1120-41';  // aktif vinç ID
let boomLen  = 42;             // bum uzunluğu (m)
let cwTon    = 14;             // karşı ağırlık (ton)
let safetyPct = 80;            // güvenlik eşiği (%)
let markers  = [];             // seçili noktalar: [{id, r, load, maxC}]
let activeId = null;           // aktif marker ID
let multiMode = true;          // çoklu seçim modu (varsayılan AÇIK)
```

### SVG Koordinat Sistemi

```js
const PAD = { t:28, r:20, b:48, l:64 };  // kenar boşlukları (px)
const DISP_MAX_R = 30;                    // gösterilen max yarıçap (m)

function W()  { return svgEl.clientWidth; }    // SVG genişliği
function H()  { return svgEl.clientHeight; }   // SVG yüksekliği (460px)
function cW() { return W()-PAD.l-PAD.r; }      // grafik alanı genişliği
function cH() { return H()-PAD.t-PAD.b; }      // grafik alanı yüksekliği

// Veri → Piksel
function px(r)   { return PAD.l + (r/DISP_MAX_R)*cW(); }
function py(cap) { return PAD.t + (1-(cap/maxCap()))*cH(); }

// Piksel → Veri (fare konumundan bağımsız X ve Y okur)
function coordFromEvent(e) {
  const rect = svgEl.getBoundingClientRect();
  const mx = e.clientX - rect.left;
  const my = e.clientY - rect.top;
  const r   = Math.max(0, Math.min(DISP_MAX_R, (mx-PAD.l)/cW()*DISP_MAX_R));
  const cap = Math.max(0, Math.min(maxCap(),   (1-(my-PAD.t)/cH())*maxCap()));
  const snapR   = Math.round(r*2)/2;    // 0.5m hassasiyet
  const snapCap = Math.round(cap*10)/10; // 0.1t hassasiyet
  return { r:snapR, load:snapCap, maxC: interpCap(craneId,boomLen,cwTon,snapR) };
}
```

### Kapasite Hesaplama Fonksiyonları

```js
// Ham tablo değerlerini bum ve karşı ağırlık faktörleriyle çarpar
function getCurvePoints(craneId, boomLen, cwTon) → [{r, cap}]

// İki nokta arasında doğrusal interpolasyon ile kapasiteyi hesaplar
function interpCap(craneId, boomLen, cwTon, radius) → ton (float)

// Kanca yüksekliğini interpolasyonla hesaplar
function interpHeight(craneId, radius) → metre (float)
```

### Durum Hesaplama

```js
function statusOf(r, load) {
  const maxC = interpCap(craneId, boomLen, cwTon, r);
  const ratio = load / maxC * 100;

  if (maxC <= 0)         → { st:'ERİŞİM DIŞI', col:'#555' }
  if (ratio > 100)       → { st:'AŞIM',        col:'#FF2D2D' }
  if (ratio > safetyPct) → { st:'SINIRA YAKIN', col:'#FFE000' }
  else                   → { st:'GÜVENLİ',      col:'#CCFF00' }
}
```

### SVG Render Pipeline (`buildSVG()`)

Render sırası (z-order düşükten yükseğe):

1. Siyah arka plan rect'leri
2. Grid çizgileri (horizontal kapasite, vertical yarıçap)
3. Kapasite eğrisi fill (yeşil transparan alan)
4. Kapasite eğrisi çizgisi (lime `#CCFF00`, stroke-width 2.5)
5. Kapasite eğrisi glow (lime, opacity 0.07, stroke-width 10)
6. Güvenlik eşiği çizgisi (turuncu kesikli, eğrinin %safetyPct katı)
7. Aktif marker crosshair (sarı kesikli çizgiler + eğriye mesafe segment)
8. Hover indikatörü grubu `#cv-snap-g` (gizli, hover'da görünür)
9. Tüm markerlar (`buildMarker()` ile, pointer-events:none)
10. X ve Y ekseni çizgileri + etiketler
11. Model etiketi (sağ üst köşe)
12. **Overlay rect** `#cv-overlay` — TAMAMEN TRANSPARAN, EN ÜSTTE, tüm event'leri alır

### Overlay Sistemi (Kritik)

Neden overlay? Marker'lar ve eğri SVG elementleri tıklamaları engelliyordu.
Çözüm: Tüm görsel elementlere `pointer-events:none` eklendi, son olarak transparan bir `<rect>` render edildi — bu rect tüm mouse event'lerini yakalar.

```js
// Her buildSVG() sonrası yeniden bağlanır
const ov = svgEl.querySelector('#cv-overlay');
ov.addEventListener('click',     onOverlayClick);
ov.addEventListener('mousemove', onOverlayMove);
ov.addEventListener('mouseleave',onOverlayLeave);
```

### Marker Veri Yapısı

```js
{
  id:    number,  // Date.now() + Math.random() — benzersiz
  r:     number,  // yarıçap (m), 0.5m hassasiyet
  load:  number,  // kullanıcının seçtiği yük (ton), 0.1t hassasiyet
  maxC:  number   // o yarıçapta vincin max kapasitesi (ton)
}
```

### Marker Etkileşim Mantığı

```
Tıklama → onOverlayClick(e)
  coordFromEvent(e) → {r, load, maxC}
  
  Eğer aynı noktada marker var (r eşit, load ~1t fark):
    Eğer bu aktif marker → sil + önceki aktif yap
    Eğer değilse → sadece aktif yap
    
  Eğer yeni nokta:
    multiMode=false → markers = [] (öncekini temizle)
    markers.push({id, r, load, maxC})
    activeId = id
  
  buildSVG(); updatePanel()
```

### Sağ Panel Güncellemesi (`updatePanel()`)

| Element ID | Gösterilen Değer |
|------------|-----------------|
| `cv-model-label` | Vinç modeli adı |
| `cv-status-box` | GÜVENLİ / SINIRA YAKIN / AŞIM |
| `cv-ph` | Kanca yüksekliği (m) |
| `cv-pr` | Çalışma yarıçapı (m) |
| `cv-pcap` | Max kapasite o yarıçapta (ton) |
| `cv-pmg` | Kalan marj (% veya "AŞIM") |
| `cv-pct-val` | Kullanım yüzdesi |
| `cv-pct-bar` | İlerleme bar (genişlik + renk) |
| `cv-spec-boom` | Bum uzunluğu |
| `cv-spec-cw` | Karşı ağırlık |
| `cv-spec-mh` | Max kanca yüksekliği |
| `cv-spec-mr` | Max yarıçap |
| `cv-spec-th` | Güvenlik eşiği % |
| `cv-cnt` | Seçili nokta sayısı |
| `cv-list` | Nokta listesi (tıklanabilir satırlar) |
| `leg-thresh` | Legend'daki eşik % değeri |

### Global Fonksiyonlar (onclick için)

```js
window.__cvSel = function(id) { activeId=id; buildSVG(); updatePanel(); }
window.__cvDel = function(id) { markers=markers.filter(m=>m.id!==id); ... }
```

### ResizeObserver

```js
new ResizeObserver(() => { buildSVG(); updatePanel(); }).observe(svgEl.parentElement);
```

SVG parent element genişlik değişince tüm grafik yeniden çizilir. Responsive tam destek.

### Toolbar Kontrolleri

| Element ID | Fonksiyon |
|------------|-----------|
| `cv-model` | select — vinç modeli değiştir, tüm markerları sil |
| `cv-boom` | select — bum uzunluğu, mevcut markerların maxC'sini güncelle |
| `cv-cw` | select — karşı ağırlık, mevcut markerların maxC'sini güncelle |
| `cv-load` | input[number] — yük referansı (sadece görsel, henüz tam entegre değil) |
| `cv-multi` | button toggle — çoklu/tek mod |
| `cv-thresh` | range input — güvenlik eşiği %, eğriyi yeniden çiz |
| `cv-reset` | button — tüm markerları temizle |

---

## 11. Konfigürasyon Dosyaları

### vite.config.js

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
});
```

> `@tailwindcss/vite` v4 kullanılıyor — `tailwind.config.js` dosyası **yok**, konfigürasyon CSS içinde.

### resources/css/app.css

```css
@import url('https://fonts.googleapis.com/css2?...');
@import "tailwindcss";

/* CSS custom properties */
/* Utility sınıflar: btn-brut, tag-*, card-brut-yellow, vb. */
/* Animasyonlar: marquee-track, animate-float */
/* Custom scrollbar */
```

### resources/js/app.js

```js
import './bootstrap';
// Başka bir şey yok — tüm JS inline script bloklarında
```

---

## 12. Önemli Notlar

### Hosting Uyumluluğu
- Python **kesinlikle kullanılmaz** — sistem standart PHP hostingde çalışacak.
- Node.js sadece `npm run build` için, üretimde çalışmaz.
- Compiled assets: `public/build/` — hosting'e bu dizin dahil edilmeli.

### Karakter Seti
- Tüm blade dosyaları UTF-8 olarak kaydedilmiştir.
- Türkçe karakterler (ğ, ü, ş, ı, ö, ç) düzgün çalışmaktadır.
- `<meta charset="UTF-8">` layout'ta mevcut.

### Git Durumu
- Bu proje henüz git repository'si içinde değil.
- Yedekleme için manuel kopyalama yapılmıştır.

### Güvenlik
- CSRF: tüm formlarda `@csrf` kullanılıyor
- Auth: Laravel built-in session auth
- Password: `Hash::make()` ile bcrypt
- SQL Injection: Eloquent ORM koruması

### Gelecek Geliştirmeler (Mevcut Değil)
- Quiz attempt kaydetme/sorgulama API
- Simülasyon attempt sistemi
- Video ilerleme gerçek zamanlı kayıt
- Ödeme sistemi (payments tablosu hazır, Stripe entegrasyonu yok)
- Admin CRUD arayüzleri
- Certificate PDF export
- Email bildirimleri

---

*Belge oluşturma tarihi: 2026-06-09*
