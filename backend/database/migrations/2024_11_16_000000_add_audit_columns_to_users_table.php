<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'created_by_id')) {
                $table->unsignedBigInteger('created_by_id')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'updated_by_id')) {
                $table->unsignedBigInteger('updated_by_id')->nullable()->after('created_by_id');
            }
            if (!Schema::hasColumn('users', 'deleted_by_id')) {
                $table->unsignedBigInteger('deleted_by_id')->nullable()->after('updated_by_id');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'created_by_id')) {
                $table->dropColumn('created_by_id');
            }
            if (Schema::hasColumn('users', 'updated_by_id')) {
                $table->dropColumn('updated_by_id');
            }
            if (Schema::hasColumn('users', 'deleted_by_id')) {
                $table->dropColumn('deleted_by_id');
            }
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
