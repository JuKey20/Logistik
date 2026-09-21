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
        Schema::create('operational_functions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('normalized_name', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('normalized_name', 'operational_functions_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_functions');
    }
};
