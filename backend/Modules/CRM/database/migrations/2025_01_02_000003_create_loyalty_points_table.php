<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->integer('points');
            $table->decimal('value', 15, 2)->default(0);
            $table->enum('type', ['earn', 'redeem']);
            $table->string('ref_type')->nullable(); // 'order', 'purchase'
            $table->unsignedBigInteger('ref_id')->nullable();
            $table->text('notes')->nullable();
            $table->auditColumns();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
