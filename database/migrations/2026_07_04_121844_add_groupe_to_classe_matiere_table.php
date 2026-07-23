<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classe_matiere', function (Blueprint $table) {
            $table->tinyInteger('groupe')->default(1)->after('coefficient');
            // 1 = Groupe 1, 2 = Groupe 2, 3 = Groupe 3
        });
    }

    public function down(): void
    {
        Schema::table('classe_matiere', function (Blueprint $table) {
            $table->dropColumn('groupe');
        });
    }
};