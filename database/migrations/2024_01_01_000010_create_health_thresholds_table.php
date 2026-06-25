<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_thresholds', function (Blueprint $table) {
            $table->id();
            $table->string('parameter_name'); // fasting_blood_sugar, random_blood_sugar, uric_acid, cholesterol, blood_pressure
            $table->enum('gender', ['male', 'female', 'all'])->default('all');
            $table->decimal('min_value', 8, 2)->nullable();
            $table->decimal('max_value', 8, 2)->nullable();
            $table->string('abnormal_operator')->nullable(); // '>', '<', '>=', '<='
            $table->decimal('abnormal_value', 8, 2)->nullable();
            $table->string('label'); // display label
            $table->string('unit')->nullable(); // mg/dL, mmHg, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_thresholds');
    }
};
