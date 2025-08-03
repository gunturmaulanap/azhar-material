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
        // Tabel orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade'); // Relasi ke suppliers
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 13)->nullable();
            $table->integer('total');
            $table->string('status')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Tabel goods_order
        Schema::create('goods_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke orders
            $table->foreignId('goods_id')->constrained('goods')->onDelete('cascade'); // Relasi ke goods
            $table->integer('cost');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->timestamps();
        });

        // Tabel retur_orders
        Schema::create('retur_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // Relasi ke orders
            $table->foreignId('goods_id')->constrained('goods')->onDelete('cascade'); // Relasi ke goods
            $table->integer('cost');
            $table->integer('retur_qty');
            $table->integer('subcashback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    // Hapus foreign key dan tabel retur_orders
    if (Schema::hasTable('retur_orders')) {
        Schema::table('retur_orders', function (Blueprint $table) {
            if (Schema::hasColumn('retur_orders', 'order_id')) {
                $table->dropForeign(['order_id']);
            }
            if (Schema::hasColumn('retur_orders', 'goods_id')) {
                $table->dropForeign(['goods_id']);
            }
        });
        Schema::dropIfExists('retur_orders');
    }

    // Hapus foreign key dan tabel goods_order
    if (Schema::hasTable('goods_order')) {
        Schema::table('goods_order', function (Blueprint $table) {
            if (Schema::hasColumn('goods_order', 'order_id')) {
                $table->dropForeign(['order_id']);
            }
            if (Schema::hasColumn('goods_order', 'goods_id')) {
                $table->dropForeign(['goods_id']);
            }
        });
        Schema::dropIfExists('goods_order');
    }

    // Hapus tabel orders
    if (Schema::hasTable('orders')) {
        Schema::dropIfExists('orders');
    }
}

};
