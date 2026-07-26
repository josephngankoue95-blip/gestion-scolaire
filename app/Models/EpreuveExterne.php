<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpreuveExterne extends Model
{
    protected $table = 'epreuves_externes';
    protected $fillable = ['titre','niveau','matiere','annee_examen','source','lien_externe','actif','ordre'];
}