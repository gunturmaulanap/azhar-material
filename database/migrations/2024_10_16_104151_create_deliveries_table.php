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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('delivery_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->integer('delivered')->default(0);
            $table->timestamps();
        });

        Schema::create('act_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });

        Schema::create('act_delivery_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_id')->constrained()->onDelete('cascade');
            $table->json('image')->nullable(); 
            $table->timestamps();
    
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_deliveries');
        Schema::dropIfExists('delivery_goods');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('act_delivery_details');

    }
};
