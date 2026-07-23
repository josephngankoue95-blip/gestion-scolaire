<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraisScolarite;
use App\Models\Section;
use App\Models\Niveau;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class FraisScolariteController extends Controller
{
    public function index()
    {
        $annee    = AnneeScolaire::getActive();
        $grilles  = FraisScolarite::with('section')
            ->where('annee_scolaire_id', $annee?->id)
            ->orderBy('section_id')->orderBy('niveau')
            ->get();
        $sections = Section::all();
        $niveaux  = Niveau::orderBy('section_id')->orderBy('ordre')->get()->groupBy('section_id');

        return view('admin.scolarite.frais.index', compact('grilles', 'sections', 'niveaux', 'annee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id'        => 'required|exists:sections,id',
            'niveau'            => 'nullable|string|max:30',
            'frais_inscription' => 'required|numeric|min:0',
            'tranche1'          => 'required|numeric|min:0',
            'tranche2'          => 'required|numeric|min:0',
            'tranche3'          => 'required|numeric|min:0',
            'echeance_tranche1' => 'nullable|date',
            'echeance_tranche2' => 'nullable|date',
            'echeance_tranche3' => 'nullable|date',
        ]);

        $validated['niveau']            = $request->niveau ?: null;
        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;

        FraisScolarite::updateOrCreate(
            [
                'annee_scolaire_id' => $validated['annee_scolaire_id'],
                'section_id'        => $validated['section_id'],
                'niveau'            => $validated['niveau'],
            ],
            $validated
        );

        return back()->with('success', 'Grille de frais enregistrée.');
    }

    public function update(Request $request, FraisScolarite $fraisScolarite)
    {
        $validated = $request->validate([
            'frais_inscription' => 'required|numeric|min:0',
            'tranche1'          => 'required|numeric|min:0',
            'tranche2'          => 'required|numeric|min:0',
            'tranche3'          => 'required|numeric|min:0',
            'echeance_tranche1' => 'nullable|date',
            'echeance_tranche2' => 'nullable|date',
            'echeance_tranche3' => 'nullable|date',
        ]);

        $fraisScolarite->update($validated);
        return back()->with('success', 'Grille mise à jour.');
    }

    public function destroy(FraisScolarite $fraisScolarite)
    {
        $fraisScolarite->delete();
        return back()->with('success', 'Grille supprimée.');
    }

    /** AJAX — retourne les frais pour une classe donnée */
 public function pourClasse(Request $request)
{
    $validated = $request->validate([
        'classe_id' => ['required', 'exists:classes,id'],
    ]);

    $classe = \App\Models\ClasseModel::with('niveau')->findOrFail($validated['classe_id']);
    $frais = FraisScolarite::pourClasse($classe);

    return response()->json([
        'frais_inscription' => $frais->frais_inscription ?? 0,
        'tranche1' => $frais->tranche1 ?? 0,
        'tranche2' => $frais->tranche2 ?? 0,
        'tranche3' => $frais->tranche3 ?? 0,
    ]);
}

}