<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Niveau;
use App\Models\Section;
use App\Models\AnneeScolaire;
use App\Models\Enseignant;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index(Request $request)
{
    $annee = AnneeScolaire::getActive();

    $sections = Section::orderBy('nom')->get();

    // Aucun affichage tant qu'aucune section n'est sélectionnée
    if (!$request->filled('section_id')) {

        $classes = ClasseModel::whereRaw('1=0')
            ->paginate(15);

        return view('admin.classes.index', compact(
            'classes',
            'sections',
            'annee'
        ));
    }

    $query = ClasseModel::with([
        'section',
        'anneeScolaire',
        'professeurPrincipal.user',
        'niveau'
    ])
    ->where('annee_scolaire_id', $annee?->id)
    ->where('section_id', $request->section_id);

    // Recherche
    if ($request->filled('search')) {

        $query->where('nom', 'like', '%' . $request->search . '%');

    }

    // Tri alphabétique
    $classes = $query
        ->orderBy('nom')
        ->paginate(8)
        ->withQueryString();

    return view('admin.classes.index', compact(
        'classes',
        'sections',
        'annee'
    ));
}

public function create()
{
    $sections    = Section::all();
    $enseignants = Enseignant::with('user')->where('statut', 'actif')->get();
    $niveaux = Niveau::orderBy('ordre')->get();

    return view('admin.classes.create', compact('sections', 'enseignants', 'niveaux'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'niveau_id' => 'required|exists:niveaux,id',
            'section_id' => 'required|exists:sections,id',
            'capacite_max' => 'required|integer|min:1',
            'professeur_principal_id' => 'nullable|exists:enseignants,id',
        ]);

        $validated['annee_scolaire_id'] = AnneeScolaire::getActive()?->id;

        ClasseModel::create($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function edit(ClasseModel $classe)
    {
        $sections = Section::all();
        $enseignants = Enseignant::with('user')->orderBy('id', 'desc')->get();
        $niveaux = Niveau::orderBy('ordre')->get();

        return view('admin.classes.edit', compact('classe','sections','enseignants','niveaux'));
    }

    public function update(Request $request, ClasseModel $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:50',
            'niveau_id' => 'required|exists:niveaux,id',
            'section_id' => 'required|exists:sections,id',
            'capacite_max' => 'required|integer|min:1',
            'professeur_principal_id' => 'nullable|exists:enseignants,id',
        ]);

        $classe->update($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe modifiée avec succès.');
    }

    public function destroy(ClasseModel $classe)
    {
        if ($classe->effectif() > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer : des élèves sont inscrits dans cette classe.'
            );
        }

        $classe->delete();

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Classe supprimée.');
    }

public function show(ClasseModel $classe)
{
    $classe->load([
        'section',
        'anneeScolaire',
        'professeurPrincipal.user',
        'eleves',
    ]);

    return view('admin.classes.show', compact('classe'));
}

}