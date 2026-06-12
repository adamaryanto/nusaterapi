<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add reschedule config columns to membership_tiers
        Schema::table('membership_tiers', function (Blueprint $table) {
            $table->integer('free_reschedule_limit')->default(3)->after('duration');
            $table->integer('reschedule_fee')->default(15000)->after('free_reschedule_limit');
        });

        // Add reschedule tracking columns to bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('reschedule_count')->default(0)->after('is_membership_discount_applied');
            $table->integer('reschedule_fee_charged')->default(0)->after('reschedule_count');
        });
    }

    public function down(): void
    {
        Schema::table('membership_tiers', function (Blueprint $table) {
            $table->dropColumn(['free_reschedule_limit', 'reschedule_fee']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['reschedule_count', 'reschedule_fee_charged']);
        });
    }
};
