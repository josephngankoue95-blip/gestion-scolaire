<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etablissement', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('sigle')->nullable();
            $table->string('logo')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('Cameroun');
            $table->string('telephone')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->string('bp')->nullable(); // Boîte postale
            $table->string('region')->nullable();
            $table->string('ministre_tutelle')->nullable(); // ex: MINESEC
            $table->string('ordre_enseignement')->nullable(); // Public / Privé / Confessionnel
            $table->string('type_etablissement')->nullable(); // Lycée / Collège / CES
            $table->string('code_etablissement')->nullable();
            $table->text('devise')->nullable(); // Slogan de l'école
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etablissement');
    }
};