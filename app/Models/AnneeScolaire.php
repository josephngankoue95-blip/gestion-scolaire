<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ClasseModel;

class AnneeScolaire extends Model
{
    protected $table = 'annees_scolaires';

    protected $fillable = ['libelle', 'date_debut', 'date_fin', 'active'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(ClasseModel::class, 'annee_scolaire_id');
    }

        public static function getActive(): ?self
        {
            return static::where('active', 1)
                ->orderBy('id', 'desc')
                ->first();
        }
}