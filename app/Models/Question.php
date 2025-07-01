<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'questionnaire_id', // ELIMINAR ESTA LÍNEA
        'section_id', // AÑADIR ESTA LÍNEA
        'text',
        'type',
        'order',
        'observations_enabled',
    ];

    protected $casts = [
        'observations_enabled' => 'boolean',
    ];

    /**
     * Get the section that owns the question.
     */
    public function section(): BelongsTo // CAMBIAR DE questionnaire() A section()
    {
        return $this->belongsTo(Section::class); // CAMBIAR DE Questionnaire::class A Section::class
    }

    /**
     * Get the options for the question (if multiple choice).
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->orderBy('order');
    }
}
