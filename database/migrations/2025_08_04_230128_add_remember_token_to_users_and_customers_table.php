<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom remember_token ke tabel 'users' jika belum ada
        if (!Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->rememberToken();
            });
        }

        // Tambahkan kolom remember_token ke tabel 'customers' jika belum ada
        if (!Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->rememberToken();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom remember_token dari tabel 'users'
        if (Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }

        // Hapus kolom remember_token dari tabel 'customers'
        if (Schema::hasColumn('customers', 'remember_token')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
};
