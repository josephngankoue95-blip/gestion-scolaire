<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('emploi_temps_id')->nullable()->constrained('emplois_temps')->onDelete('set null');
            $table->date('date_absence');
            $table->enum('type', ['absence', 'retard'])->default('absence');
            $table->boolean('justifiee')->default(false);
            $table->text('motif')->nullable();
            $table->foreignId('signale_par')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};