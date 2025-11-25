<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Ajout de la colonne
            $table->string('token', 64)
                  ->unique()
                  ->nullable() // À retirer si ta table est vide
                  ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Suppression de la colonne en cas de rollback
            $table->dropColumn('token');
        });
    }
};
