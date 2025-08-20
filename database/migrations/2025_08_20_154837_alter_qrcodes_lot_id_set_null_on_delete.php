<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('qrcodes', function (Blueprint $table) {
            // 1) ตัด FK เดิมออกก่อน
            $table->dropForeign(['lot_id']);

            // 2) ทำให้คอลัมน์ lot_id เป็น nullable (ต้องใช้ doctrine/dbal หากเปลี่ยน nullability)
            $table->unsignedBigInteger('lot_id')->nullable()->change();

            // 3) ใส่ FK ใหม่แบบ ON DELETE SET NULL
            $table->foreign('lot_id')
                  ->references('id')->on('lot_numbers')
                  ->onDelete('set null'); // หรือ ->nullOnDelete() ถ้า Laravel เวอร์ชันรองรับ
        });
    }

    public function down(): void
    {
        Schema::table('qrcodes', function (Blueprint $table) {
            // rollback: ตัด FK ที่ set null ออกก่อน
            $table->dropForeign(['lot_id']);

            // ถ้าต้องการย้อนกลับให้ not null (เลือกได้)
            // $table->unsignedBigInteger('lot_id')->nullable(false)->change();

            // แล้วค่อยผูก FK แบบเดิม (เช่น restrict)
            $table->foreign('lot_id')
                  ->references('id')->on('lot_numbers')
                  ->onDelete('restrict'); // แล้วแต่ของเดิม
        });
    }
};
