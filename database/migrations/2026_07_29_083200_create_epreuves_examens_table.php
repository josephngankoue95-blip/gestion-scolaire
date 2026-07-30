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
    // Table dédiée aux épreuves d'EXAMEN (archives multi-années, consultables élèves)
    Schema::create('epreuves_examens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
        $table->foreignId('niveau_id')->constrained('niveaux')->onDelete('cascade');
        $table->string('annee_examen'); // ex: 2022, 2023
        $table->string('titre');
        $table->string('fichier');
        $table->string('fichier_corrige')->nullable();
        // insere_par : soit un enseignant, soit un préfet — on stocke le user générique
        $table->foreignId('insere_par')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });

    // Nettoyage : epreuves_composition redevient strictement "séquence en cours"
    Schema::table('epreuves_composition', function (Blueprint $table) {
        if (Schema::hasColumn('epreuves_composition', 'archive')) {
            $table->dropColumn('archive');
        }
        if (Schema::hasColumn('epreuves_composition', 'annee_examen')) {
            $table->dropColumn('annee_examen');
        }
        if (Schema::hasColumn('epreuves_composition', 'niveau_id')) {
            $table->dropColumn('niveau_id');
        }
        // classe_id et sequence_id redeviennent obligatoires
        $table->foreignId('classe_id')->nullable(false)->change();
        $table->foreignId('sequence_id')->nullable(false)->change();
    });
}

public function down(): void
{
    Schema::dropIfExists('epreuves_examens');
}

};
