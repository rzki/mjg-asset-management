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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('employeeId')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('division_id')->constrained('employee_divisions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('position_id')->constrained('employee_positions')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('employee_number')->unique();
            $table->string('initial', 3)->unique();
            $table->enum('status', ['active', 'terminated']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
