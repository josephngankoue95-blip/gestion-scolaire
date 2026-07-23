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
    Schema::create('conseils_classe', function (Blueprint $table) {
        $table->id();
        $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
        $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
        $table->foreignId('trimestre_id')->nullable()->constrained('trimestres')->onDelete('set null');
        $table->date('date_conseil');
        $table->text('observations_generales')->nullable();
        $table->string('statut')->default('planifie'); // planifie, tenu, cloture
        $table->foreignId('preside_par')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('cree_par')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });

    Schema::create('decisions_conseil', function (Blueprint $table) {
        $table->id();
        $table->foreignId('conseil_classe_id')->constrained('conseils_classe')->onDelete('cascade');
        $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
        $table->enum('type_decision', [
            'passage', 'redoublement', 'exclusion_temporaire', 'exclusion_definitive',
            'avertissement', 'felicitations', 'encouragements', 'blame', 'tableau_honneur', 'autre'
        ]);
        $table->text('motif')->nullable();
        $table->text('observation')->nullable();
        $table->date('date_application')->nullable();
        $table->foreignId('decidee_par')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('decisions_conseil');
    Schema::dropIfExists('conseils_classe');
}

};
