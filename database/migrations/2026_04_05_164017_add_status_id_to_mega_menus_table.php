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
        Schema::table('mega_menus', function (Blueprint $table) {
           $table->foreignId('status_id')
                  ->nullable()           // Purane data ke liye nullable rakhna behtar hai
                  ->after('group_name')  // Isay group_name ke baad position karega
                  ->constrained('status') // Table ka naam confirm karlein ('status' ya 'statuses')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mega_menus', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
