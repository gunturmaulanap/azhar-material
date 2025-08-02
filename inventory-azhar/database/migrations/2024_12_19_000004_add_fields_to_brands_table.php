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
        Schema::table('brands', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('logo')->nullable()->after('description');
            $table->string('website_url')->nullable()->after('logo');
            $table->boolean('is_active')->default(true)->after('website_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(['description', 'logo', 'website_url', 'is_active']);
        });
    }
};
