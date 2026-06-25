<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('check_date');
            // Parameters - all nullable (partial input allowed)
            $table->decimal('fasting_blood_sugar', 6, 2)->nullable();  // Gula Darah Puasa
            $table->decimal('random_blood_sugar', 6, 2)->nullable();   // Gula Darah Sewaktu
            $table->decimal('uric_acid', 5, 2)->nullable();            // Asam Urat
            $table->decimal('cholesterol', 6, 2)->nullable();          // Kolesterol
            $table->integer('systolic')->nullable();                    // Sistolik
            $table->integer('diastolic')->nullable();                   // Diastolik
            // Auto-calculated status per parameter (normal/high/unmeasured)
            $table->string('fasting_blood_sugar_status')->nullable();
            $table->string('random_blood_sugar_status')->nullable();
            $table->string('uric_acid_status')->nullable();
            $table->string('cholesterol_status')->nullable();
            $table->string('blood_pressure_status')->nullable();
            $table->string('overall_status')->default('normal'); // normal / attention
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Indexes for report queries
            $table->index(['user_id', 'check_date']);
            $table->index('check_date');
            $table->index('overall_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_checks');
    }
};
