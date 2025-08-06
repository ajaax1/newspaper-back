<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustrialGuideSectorTable extends Migration
{
    public function up()
    {
        Schema::create('sector_industrial_guide', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sector_id');
            $table->unsignedBigInteger('industrial_guide_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('sector_id')->references('id')->on('sectors')->onDelete('cascade');
            $table->foreign('industrial_guide_id')->references('id')->on('industrial_guides')->onDelete('cascade');

            // Para evitar duplicidade
            $table->unique(['sector_id', 'industrial_guide_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sector_industrial_guide');
    }
}
