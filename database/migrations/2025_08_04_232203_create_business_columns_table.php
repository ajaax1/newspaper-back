<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_columns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_url');
            $table->string('address')->nullable();; // translated from 'endereco'
            $table->string('number')->nullable();;  // can be street/building number
            $table->text('description')->nullable(); // translated from 'descricao'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_columns');
    }
};
