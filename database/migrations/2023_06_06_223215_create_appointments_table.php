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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained('patients');
            $table->date('consultation_date')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('weight')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('temperature')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->boolean('has_lab_request')->default(false);
            $table->string('lab_request_id')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
