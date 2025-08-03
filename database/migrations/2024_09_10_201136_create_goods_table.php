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
    // Membuat tabel categories
    Schema::create('categories', function (Blueprint $table) {
        $table->id(); // id otomatis
        $table->string('name');
        $table->string('desc')->nullable();
        $table->timestamps();
    });

    // Membuat tabel brands
    Schema::create('brands', function (Blueprint $table) {
        $table->id(); // id otomatis
        $table->string('name');
        $table->string('desc')->nullable();
        $table->timestamps();
    });

    // Membuat tabel goods
    Schema::create('goods', function (Blueprint $table) {
        $table->id(); // id otomatis
        // Foreign key untuk category_id
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        
        // Foreign key untuk brand_id 
        $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
        
        // Kolom lainnya
        $table->string('name');
        $table->integer('stock')->default(0);
        $table->string('unit');
        $table->integer('cost');
        $table->integer('price');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};
