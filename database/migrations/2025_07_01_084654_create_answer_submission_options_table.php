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
        Schema::create('answer_submission_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_submission_id')->constrained('answer_submissions')->onDelete('cascade');
            $table->foreignId('question_option_id')->constrained('question_options')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['answer_submission_id', 'question_option_id'], 'unique_answer_option');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_submission_options');
    }
};
