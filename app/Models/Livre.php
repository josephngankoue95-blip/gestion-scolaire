<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    protected $fillable = ['titre','auteur','editeur','isbn','categorie','quantite_totale','quantite_disponible','emplacement','image'];

    public function emprunts(): HasMany { return $this->hasMany(Emprunt::class); }
}