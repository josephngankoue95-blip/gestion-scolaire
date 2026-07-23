<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groupe_matiere', function (Blueprint $table) {
            $table->dropColumn('coefficient_groupe');
        });
    }

    public function down(): void
    {
        Schema::table('groupe_matiere', function (Blueprint $table) {
            $table->decimal('coefficient_groupe', 4, 2)->default(1);
        });
    }
};