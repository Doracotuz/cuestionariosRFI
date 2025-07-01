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
        Schema::create('answer_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_response_id')->constrained('questionnaire_responses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('answer_text')->nullable(); // Para respuestas de texto
            // $table->foreignId('selected_option_id')->nullable()->constrained('question_options')->onDelete('set null'); // ELIMINAR ESTA LÍNEA
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_submissions');
    }
};
