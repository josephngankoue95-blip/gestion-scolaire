<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
public function index(Enseignant $enseignant)
{
    $enseignant->load('user');

    $affectations = $enseignant
    ->affectationsAnneeActive()
    ->with([
        'matiere',
        'classe',
        'anneeScolaire'
    ])
    ->get();

    $matieres = Matiere::all();

    $classes = ClasseModel::where(
        'annee_scolaire_id',
        AnneeScolaire::getActive()?->id
    )->with('section')->get();

        return view(
            'admin.affectations.index',
            compact(
                'enseignant',
                'affectations',
                'matieres',
                'classes'
            )
        );
}

    public function store(Request $request, Enseignant $enseignant)
    {
        $validated = $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'classe_id' => 'required|exists:classes,id',
        ]);

        Affectation::firstOrCreate([
            'enseignant_id' => $enseignant->id,
            'matiere_id' => $validated['matiere_id'],
            'classe_id' => $validated['classe_id'],
            'annee_scolaire_id' => AnneeScolaire::getActive()?->id,
        ]);

        return redirect()->route('admin.enseignants.affectations', $enseignant)
            ->with('success', 'Affectation ajoutée avec succès.');
    }

    public function destroy(Enseignant $enseignant, Affectation $affectation)
    {
        $affectation->delete();
        return redirect()->route('admin.enseignants.affectations', $enseignant)
            ->with('success', 'Affectation retirée.');
    }
}