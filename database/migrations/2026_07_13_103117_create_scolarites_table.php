<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scolarites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('eleve_id')
                ->constrained('eleves')
                ->cascadeOnDelete();

            $table->foreignId('classe_id')
                ->constrained('classes')
                ->cascadeOnDelete();

            $table->foreignId('annee_scolaire_id')
                ->constrained('annees_scolaires')
                ->cascadeOnDelete();

            $table->foreignId('zone_transport_id')
                ->nullable()
                ->constrained('zones_transport')
                ->nullOnDelete();

            $table->date('date_inscription');

            $table->enum('type_inscription', [
                'nouvelle',
                'redoublant',
                'transfert'
            ])->default('nouvelle');

            // Montants dus
            $table->decimal('frais_inscription', 10, 2)->default(0);
            $table->decimal('montant_tranche1', 10, 2)->default(0);
            $table->decimal('montant_tranche2', 10, 2)->default(0);
            $table->decimal('montant_tranche3', 10, 2)->default(0);
            $table->decimal('montant_transport', 10, 2)->default(0);

            // Montants payés
            $table->decimal('paye_inscription', 10, 2)->default(0);
            $table->decimal('paye_tranche1', 10, 2)->default(0);
            $table->decimal('paye_tranche2', 10, 2)->default(0);
            $table->decimal('paye_tranche3', 10, 2)->default(0);
            $table->decimal('paye_transport', 10, 2)->default(0);

            $table->timestamps();

            $table->unique(
                ['eleve_id', 'annee_scolaire_id'],
                'scolarite_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scolarites');
    }
};