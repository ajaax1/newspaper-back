<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesNewsTable extends Migration
{
    public function up()
    {
        Schema::create('categories_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('news_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');

            // Para evitar duplicidade
            $table->unique(['category_id', 'news_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories_news');
    }
}
