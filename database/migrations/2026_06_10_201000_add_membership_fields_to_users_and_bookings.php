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
        if (!Schema::hasColumn('users', 'is_member')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_member')->default(false)->after('role');
            });
        }

        if (!Schema::hasColumn('bookings', 'discount_amount')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->integer('discount_amount')->default(0)->after('transport_price');
                $table->boolean('is_membership_discount_applied')->default(false)->after('discount_amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'is_member')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_member');
            });
        }

        if (Schema::hasColumn('bookings', 'discount_amount')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn(['discount_amount', 'is_membership_discount_applied']);
            });
        }
    }
};
