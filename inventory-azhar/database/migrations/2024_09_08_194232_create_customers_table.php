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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 13)->nullable();
            $table->text('address')->nullable();
            $table->integer('balance')->default(0);
            $table->integer('debt')->default(0);
            $table->string('username')->nullable()->unique(); // Username harus unik
            $table->string('password')->nullable(); // Password bersifat opsional
            $table->timestamps();
        });

        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('before')->default(0);
            $table->integer('nominal')->default(0);
            $table->integer('after')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topups');
        Schema::dropIfExists('customers');
    }
};
