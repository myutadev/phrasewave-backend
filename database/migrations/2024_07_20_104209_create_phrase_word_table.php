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
        Schema::create('phrase_word', function (Blueprint $table) {
            $table->foreignId('phrase_id')->constrained();
            $table->foreignId('word_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phrase_word', function (Blueprint $table) {

            $table->dropForeign(['phrase_id']);
            $table->dropForeign(['word_id']);
        });
        Schema::dropIfExists('phrase_word');
    }
};
