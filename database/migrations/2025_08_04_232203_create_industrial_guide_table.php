<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('industrial_guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url');
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industrial_guides');
    }
};
