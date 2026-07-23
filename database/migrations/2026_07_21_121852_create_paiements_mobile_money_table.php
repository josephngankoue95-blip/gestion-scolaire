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
    Schema::create('paiements_mobile_money', function (Blueprint $table) {
        $table->id();
        $table->foreignId('scolarite_id')->constrained('scolarites')->onDelete('cascade');
        $table->enum('operateur', ['mtn_momo','orange_money']);
        $table->string('numero_telephone');
        $table->string('type_paiement'); // inscription, tranche1, tranche2, tranche3, transport
        $table->decimal('montant', 10, 2);
        $table->string('reference_transaction')->nullable(); // ID renvoyé par l'API
        $table->enum('statut', ['en_attente','confirme','echoue','annule'])->default('en_attente');
        $table->foreignId('initie_par')->nullable()->constrained('users')->onDelete('set null'); // null si initié par le parent
        $table->foreignId('verifie_par')->nullable()->constrained('users')->onDelete('set null'); // admin/secrétaire qui valide
        $table->timestamp('verifie_le')->nullable();
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements_mobile_money');
    }
};
