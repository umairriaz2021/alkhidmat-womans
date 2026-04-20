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
        Schema::table('settings', function (Blueprint $table) {
           $table->unsignedBigInteger('site_logo')->nullable()->change();
            $table->unsignedBigInteger('footer_logo')->nullable()->change();
            $table->foreign('site_logo')->references('id')->on('media')->onDelete('set null');
            $table->foreign('footer_logo')->references('id')->on('media')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('site_logo')->nullable()->change();
            $table->string('footer_logo')->nullable()->change();
        });
    }
};
