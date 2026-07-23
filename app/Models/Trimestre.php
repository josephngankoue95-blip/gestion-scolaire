<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trimestre extends Model
{
    protected $fillable = ['nom', 'numero', 'annee_scolaire_id', 'date_debut', 'date_fin'];

    protected $casts = ['date_debut' => 'date', 'date_fin' => 'date'];

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id');
    }

    public function sequences(): HasMany
    {
        return $this->hasMany(Sequence::class);
    }
}