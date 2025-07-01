<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Asegúrate de importar BelongsToMany

class AnswerSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_response_id',
        'question_id',
        'answer_text',
        'observations',
    ];

    /**
     * Get the questionnaire response that owns this answer.
     */
    public function response(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireResponse::class);
    }

    /**
     * Get the question that this answer is for.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the selected options for multiple choice questions.
     */
    public function selectedOptions(): BelongsToMany
    {
        // CORRECCIÓN CLAVE AQUÍ: Especificar los nombres de las columnas en la tabla pivot
        return $this->belongsToMany(
            QuestionOption::class,
            'answer_submission_options', // Nombre de la tabla pivot
            'answer_submission_id',      // Foreign key en la tabla pivot para AnswerSubmission
            'question_option_id'         // Foreign key en la tabla pivot para QuestionOption
        );
    }
}