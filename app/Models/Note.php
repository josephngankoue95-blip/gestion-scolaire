<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'eleve_id', 'matiere_id', 'classe_id',
        'sequence_id', 'enseignant_id', 'note', 'absent',
    ];

    protected $casts = [
        'note'   => 'decimal:2',
        'absent' => 'boolean',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

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

    /** Note sur 20 */
    public function noteSur20(): ?float
    {
        if ($this->absent || $this->note === null) return null;
        return round((float) $this->note, 2); // note déjà sur 20
    }
}