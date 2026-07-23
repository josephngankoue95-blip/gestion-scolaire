<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// create_filieres_universite_table
public function up(): void
{
    Schema::create('filieres_universite', function (Blueprint $table) {
        $table->id();
        $table->enum('cycle', ['bts_hnd', 'licence', 'master']);
        $table->string('categorie')->nullable(); // ex: Gestion & Management, Santé & Paramédical
        $table->string('nom');
        $table->text('description')->nullable(); // détail des options/spécialités
        $table->decimal('frais_inscription', 10, 2)->nullable();
        $table->decimal('frais_scolarite_min', 10, 2)->nullable();
        $table->decimal('frais_scolarite_max', 10, 2)->nullable();
        $table->string('duree')->nullable(); // ex: "3 ans", "1 an"
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres_universite');
    }
};
