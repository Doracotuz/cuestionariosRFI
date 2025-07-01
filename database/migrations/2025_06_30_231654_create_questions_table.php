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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade'); // ELIMINAR ESTA LÍNEA
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade'); // AÑADIR ESTA LÍNEA
            $table->text('text');
            $table->enum('type', ['multiple_choice', 'text']);
            $table->integer('order')->default(0); // Para mantener el orden de las preguntas dentro de la sección
            $table->boolean('observations_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};