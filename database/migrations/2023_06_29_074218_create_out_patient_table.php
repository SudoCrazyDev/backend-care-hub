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
            $table->string('appointment_id');
            $table->string('significant_findings')->nullable();
            $table->string('medicines')->nullable();
            $table->bigInteger('professional_fee')->default(0);
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
