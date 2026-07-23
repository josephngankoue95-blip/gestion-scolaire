<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'titre', 'matiere_id', 'classe_id', 'sequence_id',
        'enseignant_id', 'note_sur', 'date_evaluation',
    ];

    protected $casts = ['date_evaluation' => 'date', 'note_sur' => 'decimal:2'];

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(ClasseModel::class, 'classe_id');
    }

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(Sequence::class);
    }

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}