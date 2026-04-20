<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('mega_menus_id')
                  ->nullable()
                  ->after('id') // Isay 'id' ke baad position karega
                  ->constrained('mega_menus') // Ye 'megamenus' table se link karega
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['mega_menus_id']);
            $table->dropColumn('mega_menus_id');
        });
    }
};
