<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Renomear a coluna name para title (MySQL 5.7)
        DB::statement("ALTER TABLE social_columns CHANGE `name` `title` VARCHAR(191) NOT NULL");

        // 2️⃣ Adicionar a coluna slug agora que title existe
        Schema::table('social_columns', function (Blueprint $table) {
            $table->string('slug')->after('title');
        });
    }

    public function down(): void
    {
        // 1️⃣ Remover slug
        Schema::table('social_columns', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        // 2️⃣ Renomear title de volta para name
        DB::statement("ALTER TABLE social_columns CHANGE `title` `name` VARCHAR(191) NOT NULL");
    }
};
