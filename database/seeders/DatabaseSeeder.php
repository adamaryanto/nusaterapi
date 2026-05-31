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
        $customerBudi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '0812-3456-7890',
            'address' => "Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123",
            'role' => 'customer',
        ]);

        // 2b. Seed Therapist User Accounts
        $therapistUser = User::create([
            'name' => 'Dika',
            'email' => 'dika@nusaterapi.com',
            'password' => Hash::make('12345678'),
            'phone' => '0813-1111-0001',
            'address' => 'Solo, Jawa Tengah',
            'role' => 'therapist',
        ]);

        // 3. Seed Default Therapists (linked to user accounts where applicable)
        $dika = Therapist::create([
            'user_id'   => $therapistUser->id,
            'name'      => 'Dika',
            'specialty' => 'Pijat Tradisional, Refleksi, Bekam, Lulur & Scrub',
            'rating'    => 4.9,
            'status'    => 'Active',
        ]);

        // 4. Seed Default Bookings matching layout
        Booking::create([
            'id' => 'TRX-2605-001',
            'user_id' => $customerBudi->id,
            'therapist_id' => $dika->id,
            'service_name' => 'Pijat Tradisional (90m)',
            'schedule_date' => '2026-05-13',
            'schedule_time' => '14:00',
            'location_type' => 'home',
            'address' => "Jl. Mawar No. 2",
            'service_price' => 170000,
            'transport_price' => 20000,
            'total_payment' => 190000,
            'status' => 'Pending',
            'pay_status' => 'Lunas',
        ]);

        Booking::create([
            'id' => 'TRX-2605-002',
            'user_id' => $customerBudi->id,
            'therapist_id' => $dika->id,
            'service_name' => 'Refleksi (60m)',
            'schedule_date' => '2026-05-14',
            'schedule_time' => '10:00',
            'location_type' => 'home',
            'address' => "Jl. Melati No. 5",
            'service_price' => 120000,
            'transport_price' => 20000,
            'total_payment' => 140000,
            'status' => 'Akan Datang', // Diterima
            'pay_status' => 'Lunas',
        ]);

        Booking::create([
            'id' => 'TRX-2605-003',
            'user_id' => $customerBudi->id,
            'therapist_id' => $dika->id,
            'service_name' => 'Pijat + Bekam',
            'schedule_date' => '2026-05-14',
            'schedule_time' => '15:00',
            'location_type' => 'home',
            'address' => "Jl. Anggrek No. 12",
            'service_price' => 140000,
            'transport_price' => 20000,
            'total_payment' => 160000,
            'status' => 'Dibatalkan', // Ditolak
            'pay_status' => 'Batal',
        ]);

        Booking::create([
            'id' => 'TRX-2605-004',
            'user_id' => $customerBudi->id,
            'therapist_id' => $dika->id,
            'service_name' => 'Lulur Tradisional',
            'schedule_date' => '2026-05-15',
            'schedule_time' => '09:00',
            'location_type' => 'home',
            'address' => "Jl. Kenanga No. 8",
            'service_price' => 230000,
            'transport_price' => 20000,
            'total_payment' => 250000,
            'status' => 'Pending',
            'pay_status' => 'Lunas',
        ]);

        // Additional clinic booking to verify clinic view
        Booking::create([
            'id' => 'TRX-2605-005',
            'user_id' => $customerBudi->id,
            'therapist_id' => $dika->id,
            'service_name' => 'Terapi Bekam (60m)',
            'schedule_date' => '2026-05-16',
            'schedule_time' => '11:00',
            'location_type' => 'clinic',
            'address' => 'Klinik Utama Nusa Terapi, Solo',
            'service_price' => 120000,
            'transport_price' => 0,
            'total_payment' => 120000,
            'status' => 'Pending',
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
