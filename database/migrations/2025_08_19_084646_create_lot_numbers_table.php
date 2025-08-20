<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('lot_numbers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                  ->constrained('product_categories')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            $table->string('lot_no')->index();                 // เลขล็อต
            $table->date('mfg_date')->nullable();              // วันที่ผลิต
            $table->time('mfg_time')->nullable();              // เวลาผลิต
            $table->unsignedInteger('qty')->default(0);        // จำนวน

            $table->string('product_no_old')->nullable();      // Product No. เดิม
            $table->string('product_no_new')->nullable();      // Product No. ล่าสุด

            $table->date('received_date')->nullable();         // วันรับเข้า
            $table->string('supplier')->nullable();            // Supplier
            $table->string('stock_no')->nullable();            // เลข Stock หมด
            $table->text('remark')->nullable();                // หมายเหตุ

            // ไฟล์แนบ
            $table->string('galvanize_cert_path')->nullable(); // ใบเชอร์ชุบกัลวาไนซ์
            $table->string('steel_cert_path')->nullable();     // ใบเซอร์เหล็ก

            $table->foreignId('created_by')->constrained('users'); // ผู้สร้าง

            $table->timestamps();

            // กันซ้ำภายในสินค้าตัวเดียวกัน
            $table->unique(['product_id','lot_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_numbers');
    }
};
