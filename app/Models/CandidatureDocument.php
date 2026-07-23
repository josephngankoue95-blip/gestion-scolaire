<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidatureDocument extends Model
{
    protected $fillable = ['candidature_id', 'type', 'nom_fichier', 'chemin'];

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }
}