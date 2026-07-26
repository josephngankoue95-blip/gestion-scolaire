<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpreuveExterne;
use Illuminate\Http\Request;

class EpreuveExterneController extends Controller
{
    public function index()
    {
        $epreuves = EpreuveExterne::orderBy('ordre')->paginate(20);
        return view('admin.epreuves-externes.index', compact('epreuves'));
    }

    public function create() { return view('admin.epreuves-externes.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'niveau' => 'nullable|string|max:50',
            'matiere' => 'nullable|string|max:100',
            'annee_examen' => 'nullable|string|max:10',
            'source' => 'nullable|string|max:100',
            'lien_externe' => 'required|url|max:500',
            'actif' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);
        $validated['actif'] = $request->boolean('actif', true);
        EpreuveExterne::create($validated);
        return redirect()->route('admin.epreuves-externes.index')->with('success','Épreuve ajoutée.');
    }

    public function edit(EpreuveExterne $epreuveExterne) { return view('admin.epreuves-externes.edit', compact('epreuveExterne')); }

    public function update(Request $request, EpreuveExterne $epreuveExterne)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'niveau' => 'nullable|string|max:50',
            'matiere' => 'nullable|string|max:100',
            'annee_examen' => 'nullable|string|max:10',
            'source' => 'nullable|string|max:100',
            'lien_externe' => 'required|url|max:500',
            'actif' => 'nullable|boolean',
            'ordre' => 'nullable|integer',
        ]);
        $validated['actif'] = $request->boolean('actif', true);
        $epreuveExterne->update($validated);
        return redirect()->route('admin.epreuves-externes.index')->with('success','Épreuve modifiée.');
    }

    public function destroy(EpreuveExterne $epreuveExterne)
    {
        $epreuveExterne->delete();
        return back()->with('success','Supprimée.');
    }
}