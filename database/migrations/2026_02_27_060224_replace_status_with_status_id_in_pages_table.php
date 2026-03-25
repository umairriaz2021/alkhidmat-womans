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
        Schema::table('pages', function (Blueprint $table) {
        // 1. Column drop karne se pehle check karlein
        if (Schema::hasColumn('pages', 'status')) {
            $table->dropColumn('status');
        }

        // 2. Foreign Key lagane ka sabse safe tareeka
        $table->unsignedBigInteger('status_id')->nullable()->after('title');
        
        $table->foreign('status_id')
              ->references('id')
              ->on('status')
              ->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
            $table->string('status')->default('draft');
        });
    }
};
