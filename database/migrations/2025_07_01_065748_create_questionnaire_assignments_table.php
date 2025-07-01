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
        Schema::create('questionnaire_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_id')->constrained('questionnaires')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'completed'])->default('assigned'); // 'assigned' o 'completed'
            $table->foreignId('questionnaire_response_id')->nullable()->constrained('questionnaire_responses')->onDelete('set null'); // Enlaza a la respuesta si ya fue completada
            $table->unique(['questionnaire_id', 'user_id'], 'unique_assignment'); // Un usuario solo puede ser asignado a un cuestionario una vez
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_assignments');
    }
};
