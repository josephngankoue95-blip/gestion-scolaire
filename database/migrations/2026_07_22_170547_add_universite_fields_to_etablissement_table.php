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
    Schema::table('etablissement', function (Blueprint $table) {
        // Infos université (ISSPED)
        $table->string('nom_universite')->nullable();
        $table->string('sigle_universite')->nullable();
        $table->string('logo_universite')->nullable();
        $table->string('autorisation_minesup')->nullable(); // AUT.N0296765/MINESUP/...
        $table->string('universite_partenaire')->nullable(); // Université de Dschang
        $table->string('logo_universite_partenaire')->nullable();
        $table->string('annee_academique')->nullable(); // 2026-2027
        $table->text('campus')->nullable(); // JSON ou texte multi-lignes
        $table->text('telephones_universite')->nullable();
        $table->string('email_universite')->nullable();
        $table->string('facebook_universite')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etablissement', function (Blueprint $table) {
            //
        });
    }
};
