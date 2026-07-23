<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // ex: CAND2026-0001
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('lieu_naissance')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('classe_demandee'); // niveau souhaité, texte libre (6eme, Seconde...)
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('etablissement_origine')->nullable();
            $table->string('nom_parent');
            $table->string('telephone_parent');
            $table->string('email_parent')->nullable();
            $table->text('adresse')->nullable();
            $table->enum('statut', ['en_attente', 'en_cours_examen', 'acceptee', 'refusee'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onDelete('set null'); // rempli après conversion
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('notifie_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
    }
};