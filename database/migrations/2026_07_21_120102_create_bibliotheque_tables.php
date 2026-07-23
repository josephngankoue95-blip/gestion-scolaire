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
    Schema::create('livres', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->string('auteur')->nullable();
        $table->string('editeur')->nullable();
        $table->string('isbn')->nullable();
        $table->string('categorie')->nullable();
        $table->integer('quantite_totale')->default(1);
        $table->integer('quantite_disponible')->default(1);
        $table->string('emplacement')->nullable();
        $table->string('image')->nullable();
        $table->timestamps();
    });

    Schema::create('emprunts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('livre_id')->constrained('livres')->onDelete('cascade');
        $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onDelete('cascade');
        $table->foreignId('enseignant_id')->nullable()->constrained('enseignants')->onDelete('cascade');
        $table->date('date_emprunt');
        $table->date('date_retour_prevue');
        $table->date('date_retour_effective')->nullable();
        $table->enum('statut', ['en_cours', 'retourne', 'perdu', 'en_retard'])->default('en_cours');
        $table->foreignId('enregistre_par')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotheque_tables');
    }
};
