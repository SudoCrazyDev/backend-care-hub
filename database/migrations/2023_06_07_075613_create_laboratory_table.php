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
        Schema::create('laboratories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('appointment_id')->constrained('appointments');
            $table->foreignUuid('patient_id')->constrained('patients');
            $table->date('result_date')->nullable();
            $table->string('cbc')->nullable();
            $table->string('urinalysis')->nullable();
            $table->string('stool_exam')->nullable();
            $table->text('xray')->nullable(); //JSON
            $table->text('blood_chemistry')->nullable(); //JSON
            $table->string('others')->nullable(); //JSON
            $table->string('status')->nullable();
            $table->string('request_id')->nullable();
            $table->string('result_url')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory');
    }
};
