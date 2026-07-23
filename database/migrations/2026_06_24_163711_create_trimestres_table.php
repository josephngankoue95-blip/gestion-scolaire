<?php
// create_trimestres_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Trimestre 1, 2, 3
            $table->integer('numero'); // 1, 2, 3
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();

            $table->unique(['numero', 'annee_scolaire_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trimestres');
    }
};