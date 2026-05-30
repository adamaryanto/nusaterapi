<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Therapist;
use App\Models\Booking;
use App\Models\WebSetting;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@nusaterapi.com',
            'password' => Hash::make('12345678'),
            'phone' => '0812-9876-5432',
            'address' => 'Solo, Jawa Tengah',
            'role' => 'admin',
        ]);

        // 2. Seed Customer User
        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '0812-3456-7890',
            'address' => "Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123",
            'role' => 'customer',
        ]);

        // 3. Seed Default Therapists
        $adam = Therapist::create([
            'name' => 'Adam Aryanto',
            'specialty' => 'Pijat Tradisional, Refleksi, Bekam',
            'rating' => 4.9,
            'status' => 'Active',
        ]);

        $siti = Therapist::create([
            'name' => 'Siti Aminah',
            'specialty' => 'Refleksi Kaki, Pijat Relaksasi',
            'rating' => 4.8,
            'status' => 'Active',
        ]);

        $rizky = Therapist::create([
            'name' => 'Rizky Firmansyah',
            'specialty' => 'Pijat Cedera Olahraga, Bekam',
            'rating' => 4.7,
            'status' => 'Active',
        ]);

        $diana = Therapist::create([
            'name' => 'Diana Putri',
            'specialty' => 'Lulur, Pijat Relaksasi',
            'rating' => 4.8,
            'status' => 'Active',
        ]);

        $rani = Therapist::create([
            'name' => 'Rani Suryani',
            'specialty' => 'Pijat Tradisional Jawa, Kerokan',
            'rating' => 4.9,
            'status' => 'Active',
        ]);

        // 4. Seed Default Bookings for Budi Santoso (Customer)
        Booking::create([
            'id' => 'TRX-2605-001',
            'user_id' => $customer->id,
            'therapist_id' => $adam->id,
            'service_name' => 'Pijat Tradisional (90 Menit)',
            'schedule_date' => '2026-05-16',
            'schedule_time' => '13:00',
            'location_type' => 'home',
            'address' => "Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123",
            'service_price' => 150000,
            'transport_price' => 20000,
            'total_payment' => 170000,
            'status' => 'Akan Datang',
            'pay_status' => 'Lunas',
        ]);

        Booking::create([
            'id' => 'TRX-2605-002',
            'user_id' => $customer->id,
            'therapist_id' => $siti->id,
            'service_name' => 'Refleksi Kaki (60 Menit)',
            'schedule_date' => '2026-05-12',
            'schedule_time' => '19:00',
            'location_type' => 'home',
            'address' => "Jl. Adi Sucipto No. 8, Solo\nKota Solo, Jawa Tengah, 57139",
            'service_price' => 130000,
            'transport_price' => 20000,
            'total_payment' => 150000,
            'status' => 'Selesai',
            'pay_status' => 'Lunas',
        ]);

        Booking::create([
            'id' => 'TRX-2605-003',
            'user_id' => $customer->id,
            'therapist_id' => $rizky->id,
            'service_name' => 'Terapi Bekam (60 Menit)',
            'schedule_date' => '2026-05-01',
            'schedule_time' => '15:00',
            'location_type' => 'home',
            'address' => "Jl. Slamet Riyadi No. 45, Solo\nKota Solo, Jawa Tengah, 57123",
            'service_price' => 100000,
            'transport_price' => 20000,
            'total_payment' => 120000,
            'status' => 'Selesai',
            'pay_status' => 'Lunas',
        ]);

        // 5. Seed Default Web Settings
        $webSettings = [
            ['key' => 'banner_headline',    'value' => 'Kembalikan Kebugaran Tubuh Tanpa Perlu Keluar Rumah'],
            ['key' => 'banner_subheadline', 'value' => 'Layanan pijat & terapi profesional langsung ke rumah Anda di Solo Raya'],
            ['key' => 'banner_image',       'value' => ''],
            ['key' => 'about_title',        'value' => 'Kesehatan & Relaksasi Anda Adalah Prioritas Kami'],
            ['key' => 'about_description',  'value' => 'Nusa Terapi Center hadir di Solo Raya untuk memberikan layanan pijat dan terapi profesional. Dengan terapis bersertifikat dan berpengalaman, kami siap membantu Anda mendapatkan kesehatan dan ketenangan jiwa raga. Layanan tersedia di klinik maupun home service.'],
            ['key' => 'about_image',        'value' => ''],
        ];
        foreach ($webSettings as $setting) {
            WebSetting::create($setting);
        }

        // 6. Seed Default Services
        $services = [
            [
                'name'             => 'Pijat Tradisional',
                'slug'             => 'pijat-tradisional',
                'default_duration' => '90 Menit',
                'price_clinic'     => 150000,
                'price_home'       => 170000,
                'description'      => 'Pijat tradisional Jawa untuk relaksasi tubuh menyeluruh',
                'status'           => 'Active',
                'sort_order'       => 1,
            ],
            [
                'name'             => 'Refleksi Kaki',
                'slug'             => 'refleksi-kaki',
                'default_duration' => '60 Menit',
                'price_clinic'     => 100000,
                'price_home'       => 120000,
                'description'      => 'Terapi refleksi titik saraf kaki untuk melancarkan peredaran darah',
                'status'           => 'Active',
                'sort_order'       => 2,
            ],
            [
                'name'             => 'Terapi Bekam',
                'slug'             => 'terapi-bekam',
                'default_duration' => '60 Menit',
                'price_clinic'     => 120000,
                'price_home'       => 140000,
                'description'      => 'Bekam basah/kering untuk detoksifikasi dan meningkatkan imunitas',
                'status'           => 'Active',
                'sort_order'       => 3,
            ],
            [
                'name'             => 'Lulur & Scrub',
                'slug'             => 'lulur-scrub',
                'default_duration' => '120 Menit',
                'price_clinic'     => 200000,
                'price_home'       => 230000,
                'description'      => 'Perawatan kulit tubuh dengan lulur tradisional dan scrub alami',
                'status'           => 'Active',
                'sort_order'       => 4,
            ],
        ];
        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
