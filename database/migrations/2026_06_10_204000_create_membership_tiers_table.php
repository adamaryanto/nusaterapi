<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->integer('discount_wd')->default(0); // Weekday discount percentage
            $table->integer('discount_we')->default(0); // Weekend discount percentage
            $table->integer('limit_wd')->nullable(); // Weekday limit (null = unlimited)
            $table->integer('limit_we')->nullable(); // Weekend limit (null = unlimited)
            $table->integer('window')->default(7); // Quota reset window in days (e.g. 7 days)
            $table->integer('duration')->default(30); // Membership duration in days (e.g. 30 days)
            $table->string('status')->default('active'); // active / inactive
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_tier_id')->nullable()->after('is_member');
            $table->foreign('membership_tier_id')->references('id')->on('membership_tiers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['membership_tier_id']);
            $table->dropColumn('membership_tier_id');
        });

        Schema::dropIfExists('membership_tiers');
    }
};
