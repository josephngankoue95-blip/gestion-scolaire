<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne table notes
        Schema::dropIfExists('notes');

        // Nouvelle table notes simplifiée
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('sequence_id')->constrained('sequences')->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade');
            $table->decimal('note', 5, 2)->nullable(); // null = absent
            $table->boolean('absent')->default(false);
            $table->timestamps();

            // Une seule note par élève, par matière, par classe, par séquence
            $table->unique(['eleve_id', 'matiere_id', 'classe_id', 'sequence_id'], 'note_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');

        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->decimal('note', 5, 2)->nullable();
            $table->boolean('absent')->default(false);
            $table->timestamps();
            $table->unique(['evaluation_id', 'eleve_id']);
        });
    }
};