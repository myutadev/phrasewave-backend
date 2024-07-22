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
        Schema::table('users', function (Blueprint $table) {
            $table->char('first_language_code', 2)->nullable();
            $table->char('country_code', 2)->nullable();

            $table->foreign('first_language_code')->references('language_code')->on('languages')->onDelete('set null');
            $table->foreign('country_code')->references('country_code')->on('countries')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['first_language_code']);
            $table->dropForeign(['country_code']);
            $table->dropColumn('first_language_code');
            $table->dropColumn('country_code');
        });
    }
};
