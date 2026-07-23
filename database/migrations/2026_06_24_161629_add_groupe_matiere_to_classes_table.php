<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('groupe_matiere_id')->nullable()->after('section_id')
                ->constrained('groupes_matieres')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['groupe_matiere_id']);
            $table->dropColumn('groupe_matiere_id');
        });
    }
};