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
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'status')) {
                $table->dropColumn('status');
            }
            $table->foreignId('status_id')
                  ->nullable() // Shuru mein nullable rakhein taaki purana data error na de
                  ->after('id') // Isay ID ke baad rakhne ke liye
                  ->constrained('status') // Yeh statuses table ki ID ko automatic utha lega
                  ->onDelete('set null'); // Agar status delete ho toh slider null ho jaye
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
            $table->string('status')->default('active');
        });
    }
};
