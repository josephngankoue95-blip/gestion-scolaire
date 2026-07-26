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
    Schema::create('epreuves_externes', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->string('niveau')->nullable(); // ex: 6ème, Terminale — filtre optionnel
        $table->string('matiere')->nullable();
        $table->string('annee_examen')->nullable(); // ex: 2023, 2024
        $table->string('source')->nullable(); // ex: "Examens Cameroun"
        $table->string('lien_externe'); // URL du site externe
        $table->boolean('actif')->default(true);
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epreuves_externes');
    }
};
