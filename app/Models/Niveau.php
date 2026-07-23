<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    protected $fillable = ['nom', 'nom_en', 'code', 'section_id', 'ordre'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClasseModel::class, 'niveau_id', 'id');
    }

    public function libelle(string $lang = 'fr'): string
    {
        return $lang === 'en' && $this->nom_en ? $this->nom_en : $this->nom;
    }
}