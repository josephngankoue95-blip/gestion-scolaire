<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frais_scolarite', function (Blueprint $table) {
            $table->id();

            $table->foreignId('annee_scolaire_id')
                ->constrained('annees_scolaires')
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->constrained('sections')
                ->cascadeOnDelete();

            $table->string('niveau')->nullable();

            $table->decimal('frais_inscription', 10, 2)->default(0);
            $table->decimal('tranche1', 10, 2)->default(0);
            $table->decimal('tranche2', 10, 2)->default(0);
            $table->decimal('tranche3', 10, 2)->default(0);

            $table->date('echeance_tranche1')->nullable();
            $table->date('echeance_tranche2')->nullable();
            $table->date('echeance_tranche3')->nullable();

            $table->timestamps();

            $table->unique(
                ['annee_scolaire_id', 'section_id', 'niveau'],
                'frais_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frais_scolarite');
    }
};