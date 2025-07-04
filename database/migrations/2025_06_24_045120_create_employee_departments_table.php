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
        Schema::create('employee_departments', function (Blueprint $table) {
            $table->id();
            $table->uuid('departmentId')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::table('employee_divisions', function (Blueprint $table) {
            $table->foreignId('department_id')->after('divisionId')->nullable()->constrained('employee_departments')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_departments');
    }
};
