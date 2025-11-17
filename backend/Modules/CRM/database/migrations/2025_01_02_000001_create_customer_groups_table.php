<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->auditColumns();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_groups');
    }
};
