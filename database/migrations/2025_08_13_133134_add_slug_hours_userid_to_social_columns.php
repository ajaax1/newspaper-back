<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_columns', function (Blueprint $table) {
            $table->integer('hours')->default(0)->after('slug');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('hours');
        });
    }

    public function down(): void
    {
        Schema::table('social_columns', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['hours', 'user_id']);
        });
    }
};
