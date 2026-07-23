<?php
// create_groupe_matiere_table (pivot)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groupe_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('groupe_matiere_id')->constrained('groupes_matieres')->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->decimal('coefficient_groupe', 4, 2)->default(1); // coef spécifique au groupe (peut différer du coef de base)
            $table->timestamps();

            $table->unique(['groupe_matiere_id', 'matiere_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groupe_matiere');
    }
};