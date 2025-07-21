<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('image_url')->nullable();
            $table->string('badge')->nullable();

            // Reference to the user who created the news
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Enum for top position
            $table->enum('top_position', ['main_top', 'top_1', 'top_2', 'top_3'])->nullable();

            // Enum for status
            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->timestamps();       // created_at, updated_at
            $table->softDeletes();      // deleted_at for trash
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
