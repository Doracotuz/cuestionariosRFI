<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that created the questionnaire.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the sections for the questionnaire.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    // La relación `questions()` ya no será directamente en Questionnaire,
    // sino a través de las secciones. Si la tenías, puedes eliminarla
    // o modificarla para cargar preguntas a través de secciones (eager loading anidado).
    // public function questions(): HasMany
    // {
    //     return $this->hasMany(Question::class)->orderBy('order');
    // }
}