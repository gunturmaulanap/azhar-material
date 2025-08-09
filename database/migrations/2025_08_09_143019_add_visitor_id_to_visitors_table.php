<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('visitor_id', 100)->nullable()->index()->after('id');
            $table->unique(['visitor_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropUnique(['visitor_id', 'visit_date']);
            $table->dropColumn('visitor_id');
        });
    }
};
