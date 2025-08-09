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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('weight')->nullable()->after('location');
            $table->text('external_image_url')->nullable()->after('image');
            $table->string('date')->nullable()->after('year'); // More flexible date field
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['weight', 'external_image_url', 'date']);
        });
    }
};
