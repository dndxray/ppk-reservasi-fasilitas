<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY status_verifikasi ENUM('menunggu','terverifikasi','ditolak','nonaktif') NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::table('users')
            ->where('status_verifikasi', 'nonaktif')
            ->update(['status_verifikasi' => 'terverifikasi']);

        DB::statement("ALTER TABLE users MODIFY status_verifikasi ENUM('menunggu','terverifikasi','ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};