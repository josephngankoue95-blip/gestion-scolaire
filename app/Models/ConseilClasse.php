<?php
// app/Models/ConseilClasse.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConseilClasse extends Model
{
    protected $table = 'conseils_classe';
    protected $fillable = [
        'classe_id', 'annee_scolaire_id', 'trimestre_id', 'date_conseil',
        'observations_generales', 'statut', 'preside_par', 'cree_par',
    ];
    protected $casts = ['date_conseil' => 'date'];

    public function classe(): BelongsTo { return $this->belongsTo(ClasseModel::class, 'classe_id'); }
    public function anneeScolaire(): BelongsTo { return $this->belongsTo(AnneeScolaire::class, 'annee_scolaire_id'); }
    public function trimestre(): BelongsTo { return $this->belongsTo(Trimestre::class, 'trimestre_id'); }
    public function president(): BelongsTo { return $this->belongsTo(User::class, 'preside_par'); }
    public function creePar(): BelongsTo { return $this->belongsTo(User::class, 'cree_par'); }
    public function decisions(): HasMany { return $this->hasMany(DecisionConseil::class, 'conseil_classe_id'); }
}