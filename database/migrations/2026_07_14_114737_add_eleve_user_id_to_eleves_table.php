<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// add_eleve_user_id_to_eleves_table
        public function up(): void {
            Schema::table('eleves', function (Blueprint $table) {
                $table->foreignId('eleve_user_id')->nullable()->after('parent_user_id')
                    ->constrained('users')->onDelete('set null');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            //
        });
    }
};
