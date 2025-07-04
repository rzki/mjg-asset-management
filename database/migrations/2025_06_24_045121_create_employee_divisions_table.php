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
        Schema::create('employee_divisions', function (Blueprint $table) {
            $table->id();
            $table->uuid('divisionId')->unique();
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->onDelete('set null')->onUpdate('cascade');
            $table->string('abbreviation', 25)->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_divisions');
    }
};
