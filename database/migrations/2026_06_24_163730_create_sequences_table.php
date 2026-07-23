<?php
// create_sequences_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Séquence 1, 2... (2 séquences par trimestre généralement)
            $table->integer('numero'); // 1 à 6 sur l'année
            $table->foreignId('trimestre_id')->constrained('trimestres')->onDelete('cascade');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->timestamps();

            $table->unique(['numero', 'trimestre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};