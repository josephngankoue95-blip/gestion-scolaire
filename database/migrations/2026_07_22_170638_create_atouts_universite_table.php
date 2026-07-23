<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// create_atouts_universite_table
public function up(): void
{
    Schema::create('atouts_universite', function (Blueprint $table) {
        $table->id();
        $table->string('libelle'); // ex: "Bibliothèque", "Transport étudiants gratuit"
        $table->string('icone')->nullable(); // nom icône lucide
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });

    Schema::create('partenaires_universite', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('logo')->nullable();
        $table->string('pays')->nullable();
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atouts_universite');
    }
};
