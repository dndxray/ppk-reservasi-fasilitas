<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pengguna', 'petugas', 'admin'])
                  ->default('pengguna')
                  ->after('email');
            $table->string('nim_nip')->nullable()->after('role');
            $table->string('no_telepon')->nullable()->after('nim_nip');
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])
                  ->default('menunggu')
                  ->after('no_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nim_nip', 'no_telepon', 'status_verifikasi']);
        });
    }
};