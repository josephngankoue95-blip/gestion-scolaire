<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void {
    Schema::create('comptes_generes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('nom');
        $table->string('email');
        $table->string('mot_de_passe'); // en clair, uniquement pour export — à purger après distribution
        $table->string('role');
        $table->string('eleve_lie')->nullable();
        $table->boolean('exporte')->default(false);
        $table->timestamp('exporte_le')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes_generes');
    }
};
