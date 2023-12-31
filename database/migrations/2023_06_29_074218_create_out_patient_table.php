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
        Schema::create('out_patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('appointment_id')->nullable();
            $table->string('patient_id')->nullable();
            $table->text('significant_findings')->nullable();
            $table->text('medicines')->nullable();
            $table->bigInteger('professional_fee')->default(0);
            $table->bigInteger('amount_tendered')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('out_patient');
    }
};
