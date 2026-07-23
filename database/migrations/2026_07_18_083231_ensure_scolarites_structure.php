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
    // rien à ajouter si déjà présent, sinon :
    if (!Schema::hasColumn('scolarites', 'classe_id')) {
        Schema::table('scolarites', function (Blueprint $table) {
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
        });
    }
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
