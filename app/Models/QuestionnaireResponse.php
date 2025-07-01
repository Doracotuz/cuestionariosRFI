<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Asegúrate de importar HasOne

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'user_id',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the questionnaire that this response belongs to.
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the user who submitted this response.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the individual answer submissions for this response.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerSubmission::class);
    }

    /**
     * Get the assignment associated with this response.
     */
    public function assignment(): HasOne
    {
        return $this->hasOne(QuestionnaireAssignment::class, 'questionnaire_response_id');
    }
}

