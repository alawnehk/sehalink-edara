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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('medical_record_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender'); // Male, Female, Other
            $table->date('date_of_birth');
            $table->string('phone');
            $table->string('national_id')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes(); // Standard for medical records
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};