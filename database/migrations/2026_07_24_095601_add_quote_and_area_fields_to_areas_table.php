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
        Schema::table('areas', function (Blueprint $table) {
            $table->string('area_heading')->nullable()->after('title');
            $table->text('area_content')->nullable()->after('area_heading');
            $table->text('area_quote')->nullable()->after('area_content');
            $table->string('quote_button_text')->nullable()->after('area_quote');
            $table->string('quote_url')->nullable()->after('quote_button_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn([
                'area_heading',
                'area_content',
                'area_quote',
                'quote_button_text',
                'quote_url'
            ]);
        });
    }
};
