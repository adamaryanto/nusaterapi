<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Therapist;
use App\Models\WebSetting;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access system settings.
     */
    public function test_admin_can_access_and_update_system_settings(): void
    {
        // 1. Create an admin user
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@nusaterapi.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // 2. Perform GET and assert success
        $response = $this->actingAs($admin)->get('/admin/settings/system');
        $response->assertStatus(200);

        // 3. Post to update settings
        $postResponse = $this->actingAs($admin)->post('/admin/settings/system', [
            'admin_fee' => 7500,
            'ppn_percentage' => 12,
        ]);

        // Assert redirect and settings stored
        $postResponse->assertRedirect(route('admin.settings.system'));
        $this->assertEquals('7500', WebSetting::get('admin_fee'));
        $this->assertEquals('12', WebSetting::get('ppn_percentage'));
    }

    /**
     * Test customer checkout calculates and stores updated admin fee and tax amount.
     */
    public function test_booking_applies_configured_admin_fee_and_tax(): void
    {
        // 1. Set settings
        WebSetting::set('admin_fee', 6000);
        WebSetting::set('ppn_percentage', 10);

        // 2. Create customer, service, therapist
        $customer = User::create([
            'name' => 'Customer Test',
            'email' => 'customer.test@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'gender' => 'Laki-laki',
        ]);

        $service = Service::create([
            'name' => 'Terapi Tradisional',
            'slug' => 'terapi-tradisional',
            'default_duration' => '90 Menit',
            'price_clinic' => 100000,
            'price_home' => 120000,
            'status' => 'Active',
        ]);

        $therapistUser = User::create([
            'name' => 'Therapist Test',
            'email' => 'therapist.test@nusaterapi.com',
            'password' => bcrypt('password123'),
            'role' => 'therapist',
            'gender' => 'Laki-laki',
        ]);

        $therapist = Therapist::create([
            'user_id' => $therapistUser->id,
            'name' => 'Therapist Test',
            'gender' => 'Laki-laki',
            'specialty' => 'Pijat',
            'status' => 'Active',
        ]);

        // 3. Call store booking endpoint as customer
        $bookingData = [
            'service_key' => $service->slug,
            'therapist_name' => $therapist->name,
            'schedule_date' => date('Y-m-d', strtotime('+1 day')),
            'schedule_time' => '10:00',
            'location_type' => 'clinic',
            'address' => 'Solo, Indonesia',
            'duration' => 90,
        ];

        $response = $this->actingAs($customer)->post('/booking', $bookingData);

        // Assert redirect to history page
        $response->assertRedirect(route('customer.history'));

        // Retrieve latest booking and verify calculations
        $booking = Booking::first();
        $this->assertNotNull($booking);

        // Price clinic is 100,000. Clinic duration is 90 mins (equal to default).
        // Discount is 0. Transport fee is 0 (clinic).
        // Subtotal = 100,000 + 0 - 0 = 100,000.
        // Tax (10%) = 10,000.
        // Admin fee = 6,000.
        // Total payment = 100,000 + 10,000 + 6,000 = 116,000.
        $this->assertEquals(6000, $booking->admin_fee);
        $this->assertEquals(10000, $booking->tax_amount);
        $this->assertEquals(116000, $booking->total_payment);
    }

    /**
     * Test booking slot overlap logic.
     */
    public function test_booking_detects_time_slot_overlaps_correctly(): void
    {
        // 1. Create customer, service, therapist
        $customer = User::create([
            'name' => 'Customer Test 2',
            'email' => 'customer.test2@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
            'gender' => 'Laki-laki',
        ]);

        $service = Service::create([
            'name' => 'Terapi Tradisional',
            'slug' => 'terapi-tradisional',
            'default_duration' => '90 Menit',
            'price_clinic' => 100000,
            'price_home' => 120000,
            'status' => 'Active',
        ]);

        $therapistUser = User::create([
            'name' => 'Therapist Test 2',
            'email' => 'therapist.test2@nusaterapi.com',
            'password' => bcrypt('password123'),
            'role' => 'therapist',
            'gender' => 'Laki-laki',
        ]);

        $therapist = Therapist::create([
            'user_id' => $therapistUser->id,
            'name' => 'Therapist Test 2',
            'gender' => 'Laki-laki',
            'specialty' => 'Pijat',
            'status' => 'Active',
        ]);

        $scheduleDate = date('Y-m-d', strtotime('+1 day'));

        // 2. Create an existing booking at 09:00 for 120 minutes (covers 09:00 to 11:00)
        Booking::create([
            'id' => 'TRX-TEST-001',
            'user_id' => $customer->id,
            'therapist_id' => $therapist->id,
            'service_name' => 'Service (120 Menit)',
            'schedule_date' => $scheduleDate,
            'schedule_time' => '09:00',
            'duration' => 120,
            'location_type' => 'clinic',
            'address' => 'Solo',
            'service_price' => 150000,
            'transport_price' => 0,
            'total_payment' => 150000,
            'status' => 'Akan Datang',
            'pay_status' => 'Lunas',
        ]);

        // 3. Test check availability endpoint for this therapist with 60 minutes duration
        $response = $this->actingAs($customer)->getJson("/booking/check-availability?therapist_name=" . urlencode($therapist->name) . "&schedule_date={$scheduleDate}&duration=60");
        $response->assertStatus(200);
        $bookedTimes = $response->json('booked_times');

        // Overlapping slots with [09:00, 11:00) when requesting 60m:
        // - 09:00 slot (covers [09:00, 10:00), overlaps)
        // - 10:00 slot (covers [10:00, 11:00), overlaps)
        // - 11:00 slot (covers [11:00, 12:00), does NOT overlap)
        $this->assertContains('09:00', $bookedTimes);
        $this->assertContains('10:00', $bookedTimes);
        $this->assertNotContains('11:00', $bookedTimes);

        // 4. Test check availability endpoint with 120 minutes duration
        $response2 = $this->actingAs($customer)->getJson("/booking/check-availability?therapist_name=" . urlencode($therapist->name) . "&schedule_date={$scheduleDate}&duration=120");
        $response2->assertStatus(200);
        $bookedTimes2 = $response2->json('booked_times');

        // Overlapping slots with [09:00, 11:00) when requesting 120m:
        // - 08:00 (if existed) would overlap ([08:00, 10:00))
        // - 09:00 (overlaps)
        // - 10:00 (overlaps)
        // - 11:00 (covers [11:00, 13:00), does NOT overlap)
        $this->assertContains('09:00', $bookedTimes2);
        $this->assertContains('10:00', $bookedTimes2);
        $this->assertNotContains('11:00', $bookedTimes2);

        // 5. Try to book a slot that overlaps (e.g. 10:00 AM) and assert failure
        $overlapBookingData = [
            'service_key' => $service->slug,
            'therapist_name' => $therapist->name,
            'schedule_date' => $scheduleDate,
            'schedule_time' => '10:00',
            'location_type' => 'clinic',
            'address' => 'Solo',
            'duration' => 60,
        ];

        $postResponse = $this->actingAs($customer)->postJson('/booking', $overlapBookingData);
        $postResponse->assertStatus(400);
        $this->assertFalse($postResponse->json('success'));
        $this->assertStringContainsString('bentrok', $postResponse->json('message'));

        // 6. Try to book a slot that does NOT overlap (e.g. 11:00 AM) and assert success
        $validBookingData = [
            'service_key' => $service->slug,
            'therapist_name' => $therapist->name,
            'schedule_date' => $scheduleDate,
            'schedule_time' => '11:00',
            'location_type' => 'clinic',
            'address' => 'Solo',
            'duration' => 60,
        ];

        $postResponse2 = $this->actingAs($customer)->postJson('/booking', $validBookingData);
        $postResponse2->assertStatus(200);
        $this->assertTrue($postResponse2->json('success'));
    }

    /**
     * Test registration redirects to login page with success session message.
     */
    public function test_registration_redirects_to_login_page_with_message(): void
    {
        $registerData = [
            'name' => 'New Customer',
            'email' => 'new.customer@gmail.com',
            'phone' => '0812-3456-7890',
            'address' => 'Solo Raya',
            'gender' => 'Laki-laki',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $registerData);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Akun Anda berhasil dibuat! Silakan login menggunakan email dan password Anda.');

        // Verify user was created in database but is not logged in
        $this->assertDatabaseHas('users', [
            'email' => 'new.customer@gmail.com',
            'role' => 'customer',
        ]);
        $this->assertFalse(auth()->check());
    }
}
