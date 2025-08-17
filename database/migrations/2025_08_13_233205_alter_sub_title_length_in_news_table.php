<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('sub_title')->change();
        });
    }

    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('sub_title', 255)->change();
        });
    }
};
