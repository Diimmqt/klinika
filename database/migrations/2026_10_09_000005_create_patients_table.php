<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('medical_record_number')->unique();
            $table->string('nik')->nullable()->unique();
            $table->string('name');
            $table->string('gender'); // L / P
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('satusehat_ihs_number')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
