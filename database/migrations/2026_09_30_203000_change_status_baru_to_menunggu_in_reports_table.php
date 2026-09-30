<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'menunggu' to enum and set default to 'menunggu'
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('menunggu', 'baru', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'menunggu'");
        
        // Convert any existing 'baru' status to 'menunggu'
        DB::table('reports')->where('status', 'baru')->update(['status' => 'menunggu']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('reports')->where('status', 'menunggu')->update(['status' => 'baru']);
        DB::statement("ALTER TABLE reports MODIFY COLUMN status ENUM('baru', 'diproses', 'selesai', 'ditolak') NOT NULL DEFAULT 'baru'");
    }
};
