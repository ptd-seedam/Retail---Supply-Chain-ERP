<?php

use App\Support\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends BaseMigration
{
    protected string $table = 'permissions';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // e.g., view_users, create_users
            $table->string('slug', 100)->unique(); // e.g., view-users, create-users
            $table->text('description')->nullable();
            $table->string('module', 100)->nullable(); // e.g., users, products, orders
            $table->string('action', 50)->nullable(); // e.g., view, create, edit, delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
