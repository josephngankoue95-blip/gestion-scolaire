<?php
namespace App\Http\Controllers\Bibliotheque;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function dashboard()
    {
        $totalLivres = Livre::sum('quantite_totale');
        $disponibles = Livre::sum('quantite_disponible');
        $enCours = \App\Models\Emprunt::where('statut','en_cours')->count();
        $enRetard = \App\Models\Emprunt::where('statut','en_cours')->where('date_retour_prevue','<',now())->count();

        return view('bibliotheque.dashboard', compact('totalLivres','disponibles','enCours','enRetard'));
    }

    public function index(Request $request)
    {
        $query = Livre::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('titre','like',"%{$s}%")->orWhere('auteur','like',"%{$s}%");
        }
        $livres = $query->orderBy('titre')->paginate(20);
        return view('bibliotheque.livres.index', compact('livres'));
    }

    public function create() { return view('bibliotheque.livres.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'auteur' => 'nullable|string|max:150',
            'editeur' => 'nullable|string|max:150',
            'isbn' => 'nullable|string|max:50',
            'categorie' => 'nullable|string|max:100',
            'quantite_totale' => 'required|integer|min:1',
            'emplacement' => 'nullable|string|max:100',
        ]);
        $validated['quantite_disponible'] = $validated['quantite_totale'];
        Livre::create($validated);
        return redirect()->route('bibliotheque.livres.index')->with('success','Livre ajouté.');
    }

    public function edit(Livre $livre) { return view('bibliotheque.livres.edit', compact('livre')); }

    public function update(Request $request, Livre $livre)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'auteur' => 'nullable|string|max:150',
            'editeur' => 'nullable|string|max:150',
            'isbn' => 'nullable|string|max:50',
            'categorie' => 'nullable|string|max:100',
            'quantite_totale' => 'required|integer|min:1',
            'emplacement' => 'nullable|string|max:100',
        ]);

        $diff = $validated['quantite_totale'] - $livre->quantite_totale;
        $livre->update($validated);
        $livre->increment('quantite_disponible', $diff);

        return redirect()->route('bibliotheque.livres.index')->with('success','Livre modifié.');
    }

    public function destroy(Livre $livre)
    {
        if ($livre->emprunts()->where('statut','en_cours')->exists()) {
            return back()->with('error','Impossible : ce livre a des exemplaires empruntés.');
        }
        $livre->delete();
        return back()->with('success','Livre supprimé.');
    }
}