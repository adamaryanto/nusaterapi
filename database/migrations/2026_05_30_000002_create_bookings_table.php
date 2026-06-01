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
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id')->primary(); // Format: TRX-2605-XXX
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->onDelete('set null');
            $table->string('service_name');
            $table->date('schedule_date');
            $table->string('schedule_time');
            $table->string('location_type'); // home, clinic
            $table->text('address')->nullable();
            $table->integer('service_price');
            $table->integer('transport_price')->default(0);
            $table->integer('total_payment');
            $table->enum('status', ['Pending', 'Akan Datang', 'Dalam Perjalanan', 'Sampai Tujuan', 'Selesai', 'Dibatalkan', 'Menunggu Pembayaran'])->default('Pending');
            $table->enum('pay_status', ['Lunas', 'Belum Bayar', 'Batal'])->default('Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
