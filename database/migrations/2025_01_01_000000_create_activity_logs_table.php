<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // polymorphic ไปยัง entity ที่ถูกกระทำ (เช่น qrcodes, lot_numbers)
            $t->morphs('entity'); // entity_type, entity_id (indexed)

            $t->string('action', 50); // created, updated, deleted, viewed, downloaded ฯลฯ
            $t->json('changes')->nullable(); // diff ก่อน/หลัง เฉพาะ updated
            $t->json('meta')->nullable();    // context เสริม

            // บริบทของ request
            $t->string('ip', 45)->nullable();
            $t->text('user_agent')->nullable();
            $t->string('method', 10)->nullable();
            $t->string('uri')->nullable();

            $t->timestamps();

            $t->index(['action', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('activity_logs'); }
};
