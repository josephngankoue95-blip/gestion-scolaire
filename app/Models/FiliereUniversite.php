<?php
// app/Models/FiliereUniversite.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiliereUniversite extends Model
{
    protected $table = 'filieres_universite';
    protected $fillable = [
        'cycle', 'categorie', 'nom', 'description',
        'frais_inscription', 'frais_scolarite_min', 'frais_scolarite_max',
        'duree', 'ordre',
    ];

    public function scopeCycle($query, string $cycle)
    {
        return $query->where('cycle', $cycle)->orderBy('ordre');
    }
}