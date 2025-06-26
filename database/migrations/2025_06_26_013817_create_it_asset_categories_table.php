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
        Schema::create('it_asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name')->unique();
            $table->timestamps();
        });
        Schema::table('it_assets', function (Blueprint $table) {
            $table->foreignId('asset_category_id')
                ->after('asset_model')
                ->constrained('it_asset_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_t_asset_categories');
    }
};
