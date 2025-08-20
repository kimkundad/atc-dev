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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code', 100)->unique();          // รหัส QR
            $table->string('link_url')->nullable();            // URL ปลายทาง
            $table->boolean('is_active')->default(true);       // เปิด/ปิดใช้งาน
            $table->foreignId('lot_id')->nullable()            // ผูกกับล็อตนัมเบอร์ (ถ้ามี)
                  ->constrained('lot_numbers')->nullOnDelete();
            $table->foreignId('created_by')->nullable()        // ผู้สร้าง
                  ->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['lot_id']);
            $table->index(['created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('q_r_codes');
    }
};
