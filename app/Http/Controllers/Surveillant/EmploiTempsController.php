<?php
namespace App\Http\Controllers\Surveillant;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\EmploiTemps;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class EmploiTempsController extends Controller
{
    protected array $jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];

    public function index()
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with('section')->orderBy('niveau_id')->get();

        return view('surveillant.emplois-temps.index', compact('classes'));
    }

    public function show(ClasseModel $classe)
{
    $creneaux = EmploiTemps::where('classe_id', $classe->id)
        ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
        ->with('matiere', 'enseignant.user')
        ->get();

    $creneauxParHoraire = $creneaux
        ->groupBy(function ($c) {
            return substr($c->heure_debut,0,5).'-'.substr($c->heure_fin,0,5);
        })
        ->sortKeys();

    return view('surveillant.emplois-temps.show',[
        'classe'=>$classe,
        'jours'=>$this->jours,
        'creneauxParHoraire'=>$creneauxParHoraire,
    ]);
}

}