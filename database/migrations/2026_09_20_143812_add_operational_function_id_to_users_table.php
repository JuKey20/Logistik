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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('operational_function_id')
                ->nullable()
                ->after('is_active');
            $table->index('operational_function_id', 'users_operational_function_idx');
            $table->foreign('operational_function_id')
                ->references('id')
                ->on('operational_functions')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['operational_function_id']);
            $table->dropIndex('users_operational_function_idx');
            $table->dropColumn('operational_function_id');
        });
    }
};
