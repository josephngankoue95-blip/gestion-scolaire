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
    Schema::create('epreuves_composition', function (Blueprint $table) {
        $table->id();
        $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade');
        $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
        $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
        $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
        $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
        $table->string('titre');
        $table->string('fichier'); // épreuve uploadée (PDF/Word)
        $table->string('fichier_corrige')->nullable(); // corrigé, optionnel
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epreuves_composition');
    }
};
