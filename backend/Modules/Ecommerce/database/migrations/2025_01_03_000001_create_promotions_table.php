<?php

use App\Support\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends BaseMigration
{
    protected string $table = 'promotions';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->enum('type', ['fixed', 'percent']);
            $table->decimal('discount_value', 15, 2);
            $table->integer('max_usage')->nullable();
            $table->integer('current_usage')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->auditColumns();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
