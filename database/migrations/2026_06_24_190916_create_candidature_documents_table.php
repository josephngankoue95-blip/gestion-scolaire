<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidature_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidature_id')->constrained('candidatures')->onDelete('cascade');
            $table->enum('type', ['acte_naissance', 'bulletin_precedent', 'certificat_scolarite', 'photo', 'autre']);
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidature_documents');
    }
};