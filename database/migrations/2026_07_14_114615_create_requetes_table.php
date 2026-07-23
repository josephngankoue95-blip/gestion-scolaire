<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// create_requetes_table
public function up(): void {
    Schema::create('requetes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
        $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
        $table->string('objet');
        $table->text('message');
        $table->enum('type', ['attestation','certificat_scolarite','bulletin','transfert','autre'])->default('autre');
        $table->enum('statut', ['en_attente','en_cours','traitee','rejetee'])->default('en_attente');
        $table->text('reponse')->nullable();
        $table->foreignId('traitee_par')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamp('traitee_le')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requetes');
    }
};
