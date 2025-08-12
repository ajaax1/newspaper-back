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
        // Tabela principal
        Schema::create('social_columns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tabela de imagens
        Schema::create('social_column_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_column_id')
                  ->constrained('social_columns')
                  ->onDelete('cascade');
            $table->string('image_url');
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_column_images');
        Schema::dropIfExists('social_columns');
    }
};
