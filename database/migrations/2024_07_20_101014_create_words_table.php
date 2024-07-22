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
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->char('language_code', 2)->nullable();
            $table->string('word');
            $table->integer('display_count')->default(1);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();

            $table->foreign('language_code')->references('language_code')->on('languages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('words', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['language_code']);
        });

        Schema::dropIfExists('words');
    }
};
