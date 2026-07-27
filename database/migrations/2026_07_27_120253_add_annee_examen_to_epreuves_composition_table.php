<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('epreuves_composition', function (Blueprint $table) {
        $table->string('annee_examen')->nullable()->after('titre'); // ex: 2022, 2023, 2024
        $table->foreignId('niveau_id')->nullable()->after('classe_id')->constrained('niveaux')->onDelete('set null');
        $table->boolean('archive')->default(false)->after('fichier_corrige'); // true = épreuve d'année passée, visible par tous niveaux similaires
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('epreuves_composition', function (Blueprint $table) {
            //
        });
    }
};
