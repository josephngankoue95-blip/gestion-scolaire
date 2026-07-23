<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequeteController extends Controller
{
    public function index(Request $request)
    {
        $query = Requete::with('eleve','traitePar');

        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('type'))   $query->where('type', $request->type);

        $requetes = $query->latest()->paginate(20);
        return view('admin.requetes.index', compact('requetes'));
    }

    public function show(Requete $requete)
    {
        $requete->load('eleve.scolariteActive.classe', 'traitePar');
        return view('admin.requetes.show', compact('requete'));
    }

    public function traiter(Request $request, Requete $requete)
    {
        $validated = $request->validate([
            'statut'  => 'required|in:en_cours,traitee,rejetee',
            'reponse' => 'nullable|string|max:1000',
        ]);

        $requete->update([
            ...$validated,
            'traitee_par' => Auth::id(),
            'traitee_le'  => now(),
        ]);

        return back()->with('success', 'Requête mise à jour.');
    }
}