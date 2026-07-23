<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements_scolarite', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scolarite_id')
                ->constrained('scolarites')
                ->cascadeOnDelete();

            $table->enum('type', [
                'inscription',
                'tranche1',
                'tranche2',
                'tranche3',
                'transport'
            ]);

            $table->decimal('montant', 10, 2);

            $table->date('date_paiement');

            $table->string('numero_recu')->nullable();

            $table->text('note')->nullable();

            $table->foreignId('enregistre_par')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements_scolarite');
    }
};