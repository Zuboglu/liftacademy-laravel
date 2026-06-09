<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kullanıcılar
        $admin = User::firstOrCreate(['email' => 'admin@liftacademy.com'], [
            'name'     => 'Admin Kullanıcı',
            'password' => Hash::make('admin123'),
            'role'     => 'ADMIN',
        ]);

        $instructor = User::firstOrCreate(['email' => 'instructor@liftacademy.com'], [
            'name'     => 'Mehmet Eğitmen',
            'password' => Hash::make('instructor123'),
            'role'     => 'INSTRUCTOR',
        ]);

        $student = User::firstOrCreate(['email' => 'student@liftacademy.com'], [
            'name'     => 'Ayşe Öğrenci',
            'password' => Hash::make('student123'),
            'role'     => 'STUDENT',
        ]);

        $supervisor = User::firstOrCreate(['email' => 'supervisor@liftacademy.com'], [
            'name'     => 'Ali Süpervizör',
            'password' => Hash::make('supervisor123'),
            'role'     => 'SUPERVISOR',
        ]);

        // Kurslar
        $courses = [
            ['İSG Temel Eğitimi', 'isg-temel', 'SAFETY', 'BEGINNER', true, 'İş Sağlığı ve Güvenliği temel bilgileri.'],
            ['Mobil Vinç Operatörü', 'mobil-vinc-operatoru', 'CRANE_TYPE', 'INTERMEDIATE', false, 'Mobil vinç kullanım teknikleri ve güvenlik.'],
            ['Devrilme Riski Analizi', 'devrilme-riski', 'RISK', 'ADVANCED', false, 'Devrilme kaza senaryoları ve önleme yöntemleri.'],
            ['Kule Vinci Sertifikasyon', 'kule-vinc-sertifika', 'CERTIFICATION', 'ALL_LEVELS', false, 'Uluslararası kule vinci operatörü sertifikasyonu.'],
            ['Elektrik Hattı Güvenliği', 'elektrik-hatti-guvenligi', 'SAFETY', 'BEGINNER', true, 'Yüksek gerilim hatlarına yakın çalışma prosedürleri.'],
            ['Portal Vinç Operasyonu', 'portal-vinc', 'OPERATION', 'INTERMEDIATE', false, 'Portal vinç operasyon teknikleri.'],
        ];

        foreach ($courses as [$title, $slug, $cat, $level, $mandatory, $desc]) {
            $course = Course::firstOrCreate(['slug' => $slug], [
                'title'        => $title,
                'description'  => $desc,
                'category'     => $cat,
                'level'        => $level,
                'is_mandatory' => $mandatory,
                'published'    => true,
                'price'        => $mandatory ? 0 : random_int(0, 1) * random_int(299, 999),
                'passing_score'=> 70,
                'instructor_id'=> $instructor->id,
            ]);

            // Bölümler ve dersler
            if ($course->sections()->count() === 0) {
                foreach (['Giriş ve Temel Kavramlar', 'Uygulama ve Prosedürler', 'Değerlendirme'] as $i => $secTitle) {
                    $section = Section::create([
                        'course_id' => $course->id,
                        'title'     => $secTitle,
                        'order'     => $i + 1,
                    ]);
                    foreach (['Konu Anlatımı', 'Video Ders', 'Pratik Uygulama', 'Quiz'] as $j => $lesTitle) {
                        Lesson::create([
                            'section_id'       => $section->id,
                            'title'            => $lesTitle,
                            'type'             => $j === 3 ? 'QUIZ' : 'VIDEO',
                            'order'            => $j + 1,
                            'duration' => $j < 3 ? random_int(300, 1200) : null,
                            'is_free'          => $j === 0,
                        ]);
                    }
                }
            }
        }

        // Öğrenci kaydı
        $firstCourse = Course::first();
        if ($firstCourse) {
            Enrollment::firstOrCreate([
                'user_id'   => $student->id,
                'course_id' => $firstCourse->id,
            ], ['status' => 'ACTIVE']);
        }

        $this->command->info('✅ LiftAcademy seed tamamlandı!');
        $this->command->table(['Rol','E-posta','Şifre'], [
            ['Admin',      'admin@liftacademy.com',      'admin123'],
            ['Eğitmen',    'instructor@liftacademy.com', 'instructor123'],
            ['Öğrenci',    'student@liftacademy.com',    'student123'],
            ['Süpervizör', 'supervisor@liftacademy.com', 'supervisor123'],
        ]);
    }
}
