<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('eleves', function (Blueprint $table) {
        $table->foreignId('classe_id')
              ->nullable()
              ->after('parent_user_id')
              ->constrained('classes')
              ->nullOnDelete();
    });
}

public function down()
{
    Schema::table('eleves', function (Blueprint $table) {
        $table->dropForeign(['classe_id']);
        $table->dropColumn('classe_id');
    });
}
};
