<?php
// create_notes_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->decimal('note', 5, 2)->nullable(); // null = absent/non noté
            $table->boolean('absent')->default(false);
            $table->timestamps();

            $table->unique(['evaluation_id', 'eleve_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};