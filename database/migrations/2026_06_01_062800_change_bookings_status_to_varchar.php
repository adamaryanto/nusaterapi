<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status VARCHAR(50) DEFAULT 'Pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not strictly required to downgrade in shared hosting migrations, but keeping it clean.
    }
};
