<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom');           // ex: 6ème, Seconde
            $table->string('nom_en')->nullable(); // ex: Form 1, Lower Sixth
            $table->string('code', 20);      // ex: 6EME, SEC
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveaux');
    }
};