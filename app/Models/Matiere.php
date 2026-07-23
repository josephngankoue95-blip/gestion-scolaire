<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $fillable = ['nom', 'code', 'section_id']; // coefficient supprimé

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function groupes(): BelongsToMany
    {
        return $this->belongsToMany(GroupeMatiere::class, 'groupe_matiere', 'matiere_id', 'groupe_matiere_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClasseModel::class, 'classe_matiere', 'matiere_id', 'classe_id')
            ->withPivot('coefficient', 'ordre');
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

}