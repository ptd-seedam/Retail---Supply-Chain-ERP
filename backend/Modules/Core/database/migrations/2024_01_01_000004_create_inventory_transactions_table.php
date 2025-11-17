<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_id');
            $table->enum('type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT']);
            $table->integer('quantity');
            $table->integer('current_stock');
            $table->text('notes')->nullable();
            $table->string('ref_type')->nullable(); // 'order', 'purchase', 'transfer'
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->auditColumns();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->index(['warehouse_id', 'product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
