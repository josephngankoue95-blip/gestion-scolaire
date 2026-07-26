<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annees_scolaires', function (Blueprint $table) {
            $table->id();
            $table->string('libelle'); // ex: 2025-2026
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('active')->default(false);
            if (!Schema::hasColumn('annees_scolaires', 'initialisee')) {
                $table->boolean('initialisee')->default(false)->after('active');
            }
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_scolaires');
    }
};