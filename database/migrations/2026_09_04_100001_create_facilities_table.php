<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas');
            $table->string('tipe');
            $table->string('lokasi');
            $table->unsignedInteger('kapasitas')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'dalam_perbaikan', 'nonaktif'])
                  ->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};