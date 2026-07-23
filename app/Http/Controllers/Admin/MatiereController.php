<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use App\Models\Section;
use App\Models\GroupeMatiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{

public function index(Request $request)
{
    $sections = Section::orderBy('nom')->get();

    // Aucun affichage tant qu'aucune section n'est choisie
    if (!$request->filled('section_id')) {

        $matieres = Matiere::whereRaw('1=0')
            ->paginate(5);

        return view('admin.matieres.index', compact(
            'matieres',
            'sections'
        ));
    }

    $query = Matiere::with('section')
        ->where('section_id', $request->section_id);

    // Recherche
    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");

        });

    }

    // Tri alphabétique
    $matieres = $query
        ->orderBy('nom')
        ->paginate(5)
        ->withQueryString();

    return view('admin.matieres.index', compact(
        'matieres',
        'sections'
    ));
}

    public function create()
    {
        $sections = Section::all();
        return view('admin.matieres.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:matieres,code',
            'section_id' => 'required|exists:sections,id',
        ]);

        Matiere::create($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière créée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        $sections = Section::all();
        return view('admin.matieres.edit', compact('matiere', 'sections'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:matieres,code,' . $matiere->id,
            'section_id' => 'required|exists:sections,id',
        ]);

        $matiere->update($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière modifiée avec succès.');
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return back()->with('success', 'Matière supprimée.');
    }
}