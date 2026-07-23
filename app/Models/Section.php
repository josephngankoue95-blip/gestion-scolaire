<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Matiere;
use App\Models\ClasseModel;

class Section extends Model
{
    protected $fillable = ['nom', 'code'];

    public function classes(): HasMany
    {
        return $this->hasMany(ClasseModel::class);
    }

    public function matieres(): HasMany
    {
        return $this->hasMany(Matiere::class);
    }

    public function niveaux(): HasMany
    {
        return $this->hasMany(Niveau::class);
    }
}
