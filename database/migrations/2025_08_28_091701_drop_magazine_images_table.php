<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('magazine_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('magazine_images', function ($table) {
            $table->id();
            $table->string('image_url')->nullable();
            $table->foreignId('magazine_id')
                ->constrained('magazines')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }
};
