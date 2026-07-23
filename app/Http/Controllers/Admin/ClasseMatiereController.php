<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Matiere;
use App\Models\GroupeMatiere;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class ClasseMatiereController extends Controller
{
    public function index(ClasseModel $classe)
    {
        $classe->load('section');

        // Matières de la classe triées par groupe puis ordre
        $matieres = $classe->matieres()
            ->orderBy('classe_matiere.groupe')
            ->orderBy('classe_matiere.ordre')
            ->get();

        // Groupées pour l'affichage
        $matieresParGroupe = $matieres->groupBy('pivot.groupe');

        // Matières disponibles à ajouter
        $matieresDisponibles = Matiere::where('section_id', $classe->section_id)
            ->whereNotIn('id', $matieres->pluck('id'))
            ->orderBy('nom')
            ->get();

        // Groupes templates
        // $groupes = GroupeMatiere::where('section_id', $classe->section_id)
        //     ->with('matieres')
        //     ->get();

        return view('admin.classes.matieres', compact(
            'classe', 'matieres', 'matieresParGroupe',
            'matieresDisponibles'
        ));
    }

    public function store(Request $request, ClasseModel $classe)
    {
        $validated = $request->validate([
            'matiere_id'  => 'required|exists:matieres,id',
            'coefficient' => 'required|numeric|min:0.5|max:20',
            'groupe'      => 'required|in:1,2,3',
            'ordre'       => 'nullable|integer|min:0',
        ]);

        if ($classe->matieres()->where('matiere_id', $validated['matiere_id'])->exists()) {
            return back()->with('error', 'Cette matière est déjà configurée pour cette classe.');
        }

        $classe->matieres()->attach($validated['matiere_id'], [
            'coefficient' => $validated['coefficient'],
            'groupe'      => $validated['groupe'],
            'ordre'       => $validated['ordre'] ?? $classe->matieres()->count(),
        ]);

        return back()->with('success', 'Matière ajoutée à la classe.');
    }

    public function update(Request $request, ClasseModel $classe, Matiere $matiere)
    {
        $validated = $request->validate([
            'coefficient' => 'required|numeric|min:0.5|max:20',
            'groupe'      => 'required|in:1,2,3',
            'ordre'       => 'nullable|integer|min:0',
        ]);

        $classe->matieres()->updateExistingPivot($matiere->id, [
            'coefficient' => $validated['coefficient'],
            'groupe'      => $validated['groupe'],
            'ordre'       => $validated['ordre'] ?? 0,
        ]);

        return back()->with('success', 'Matière mise à jour.');
    }

    public function destroy(ClasseModel $classe, Matiere $matiere)
    {
        $classe->matieres()->detach($matiere->id);
        return back()->with('success', 'Matière retirée de la classe.');
    }

    public function importerGroupe(Request $request, ClasseModel $classe)
    {
        $validated = $request->validate([
            'groupe_matiere_id'  => 'required|exists:groupes_matieres,id',
            'coefficient_defaut' => 'required|numeric|min:0.5|max:20',
            'groupe'             => 'required|in:1,2,3',
        ]);

        $groupe   = GroupeMatiere::with('matieres')->findOrFail($validated['groupe_matiere_id']);
        $importes = 0;

        foreach ($groupe->matieres as $index => $matiere) {
            if (!$classe->matieres()->where('matiere_id', $matiere->id)->exists()) {
                $classe->matieres()->attach($matiere->id, [
                    'coefficient' => $validated['coefficient_defaut'],
                    'groupe'      => $validated['groupe'],
                    'ordre'       => $classe->matieres()->count() + $index,
                ]);
                $importes++;
            }
        }

        return back()->with('success', "{$importes} matière(s) importée(s) dans le Groupe {$validated['groupe']}.");
    }
}