<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use App\Models\Section;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function index()
    {
        $niveaux  = Niveau::with('section')->orderBy('section_id')->orderBy('ordre')->get();
        $sections = Section::all();
        return view('admin.niveaux.index', compact('niveaux', 'sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:50',
            'nom_en'        => 'nullable|string|max:50',
            'code'          => 'required|string|max:20',
            'section_id'    => 'required|exists:sections,id',
            'ordre'         => 'nullable|integer|min:0',
            'est_terminale' => 'nullable|boolean',
        ]);

        $validated['est_terminale'] = $request->boolean('est_terminale');

        Niveau::create($validated);

        return back()->with('success', 'Niveau créé avec succès.');
    }

    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'nom'           => 'required|string|max:50',
            'nom_en'        => 'nullable|string|max:50',
            'code'          => 'required|string|max:20',
            'section_id'    => 'required|exists:sections,id',
            'ordre'         => 'nullable|integer|min:0',
            'est_terminale' => 'nullable|boolean',
        ]);

        $validated['est_terminale'] = $request->boolean('est_terminale');

        $niveau->update($validated);

        return back()->with('success', 'Niveau modifié.');
    }

    public function destroy(Niveau $niveau)
    {
        $niveau->delete();
        return back()->with('success', 'Niveau supprimé.');
    }

    /** AJAX : niveaux filtrés par section (pour le formulaire classe) */
    public function parSection(Request $request)
    {
        $niveaux = Niveau::where('section_id', $request->section_id)
            ->orderBy('ordre')
            ->get(['id', 'nom', 'nom_en', 'code', 'est_terminale']);
        return response()->json($niveaux);
    }
}