<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Samakan format nomor lama dulu (+62812..., 62812... -> 0812...)
        DB::table('users')
            ->whereNotNull('no_telepon')
            ->orderBy('id')
            ->each(function ($user) {
                $nomor = preg_replace('/[^0-9+]/', '', trim($user->no_telepon));

                if (str_starts_with($nomor, '+62')) {
                    $nomor = '0' . substr($nomor, 3);
                } elseif (str_starts_with($nomor, '62')) {
                    $nomor = '0' . substr($nomor, 2);
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['no_telepon' => $nomor !== '' ? $nomor : null]);
            });

        // Gagal di sini = masih ada nomor kembar di database (lihat query pengecekan)
        Schema::table('users', function (Blueprint $table) {
            $table->unique('no_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['no_telepon']);
        });
    }
};