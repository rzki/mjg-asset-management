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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('assetId')->unique();
            $table->string('asset_name');
            $table->string('asset_code')->unique();
            $table->year('asset_year_bought');
            $table->string('asset_type');
            $table->string('asset_serial_number');
            $table->enum('asset_condition', ['New', 'Good', 'Fair', 'Poor']);
            $table->text('asset_notes');
            $table->string('asset_location');
            $table->string('asset_user');
            $table->foreignId('pic_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('barcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
