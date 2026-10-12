<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Counter untuk nomor rekam medis, nomor kunjungan, dll.
        Schema::create('number_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('sequence_name'); // medical_record, visit
            $table->string('period_key');    // contoh: 2026 atau 20261006
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['sequence_name', 'period_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('number_sequences');
    }
};
