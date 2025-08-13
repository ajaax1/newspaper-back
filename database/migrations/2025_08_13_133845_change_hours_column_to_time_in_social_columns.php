<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_columns', function (Blueprint $table) {
            $table->time('hours')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('social_columns', function (Blueprint $table) {
            $table->integer('hours')->default(0)->change();
        });
    }
};
