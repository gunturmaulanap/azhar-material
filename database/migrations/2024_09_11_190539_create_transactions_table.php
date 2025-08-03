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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 13)->nullable();
            $table->integer('total');
            $table->integer('discount')->nullable();
            $table->integer('grand_total')->nullable();
            $table->integer('balance')->nullable();
            $table->integer('bill');
            $table->integer('return');
            $table->string('status');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('price');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->string('delivery')->default(false);
            $table->timestamps();
        });

        Schema::create('retur_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('price');
            $table->integer('retur_qty');
            $table->integer('subcashback');
            $table->timestamps();
        });

        Schema::create('act_debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('pay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel yang bergantung pada transactions terlebih dahulu
        Schema::dropIfExists('act_debts');
        Schema::dropIfExists('retur_transactions');
        Schema::dropIfExists('goods_transaction');
        Schema::dropIfExists('transactions');
    }
    
};
