<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Counter antrean per poli per hari (atomic, tidak pakai MAX()+1)
        Schema::create('queue_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poli_id')->constrained('polis');
            $table->date('queue_date');
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['poli_id', 'queue_date']);
        });

        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('poli_id')->constrained('polis');
            $table->date('queue_date');
            $table->unsignedInteger('queue_number');
            $table->string('queue_code'); // contoh: A-001
            $table->string('status')->default('menunggu');
            // menunggu | dipanggil | sedang_dilayani | selesai
            $table->timestamp('called_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->unique(['poli_id', 'queue_date', 'queue_number']);
        });

        Schema::create('queue_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('changed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_status_logs');
        Schema::dropIfExists('queues');
        Schema::dropIfExists('queue_counters');
    }
};
