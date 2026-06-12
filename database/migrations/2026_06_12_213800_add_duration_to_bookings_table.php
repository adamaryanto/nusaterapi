<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('duration')->default(90)->after('schedule_time'); // duration in minutes
        });

        // Populate duration for existing bookings based on their service name
        try {
            $bookings = \App\Models\Booking::all();
            foreach ($bookings as $booking) {
                $duration = 90; // default
                if (preg_match('/(\d+)\s*(?:Menit|m)/i', $booking->service_name, $matches)) {
                    $duration = (int)$matches[1];
                }
                $booking->update(['duration' => $duration]);
            }
        } catch (\Throwable $e) {
            // Log or ignore if table doesn't exist/model fails in testing environment
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
