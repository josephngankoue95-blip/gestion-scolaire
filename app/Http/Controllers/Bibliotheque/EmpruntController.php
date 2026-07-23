<?php
namespace App\Http\Controllers\Bibliotheque;

use App\Http\Controllers\Controller;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Eleve;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpruntController extends Controller
{
    public function index(Request $request)
    {
        $query = Emprunt::with('livre','eleve','enseignant');
        if ($request->filled('statut')) {
            $request->statut === 'retard'
                ? $query->where('statut','en_cours')->where('date_retour_prevue','<',now())
                : $query->where('statut', $request->statut);
        }
        $emprunts = $query->latest()->paginate(20);
        return view('bibliotheque.emprunts.index', compact('emprunts'));
    }

    public function create()
    {
        $livres = Livre::where('quantite_disponible','>',0)->orderBy('titre')->get();
        $eleves = Eleve::where('statut','actif')->orderBy('nom')->get();
        $enseignants = Enseignant::with('user')->where('statut','actif')->get();
        return view('bibliotheque.emprunts.create', compact('livres','eleves','enseignants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'livre_id' => 'required|exists:livres,id',
            'type_emprunteur' => 'required|in:eleve,enseignant',
            'eleve_id' => 'nullable|exists:eleves,id',
            'enseignant_id' => 'nullable|exists:enseignants,id',
            'date_emprunt' => 'required|date',
            'date_retour_prevue' => 'required|date|after:date_emprunt',
        ]);

        $livre = Livre::findOrFail($validated['livre_id']);
        if ($livre->quantite_disponible <= 0) {
            return back()->with('error','Aucun exemplaire disponible.');
        }

        Emprunt::create([
            ...$validated,
            'enregistre_par' => Auth::id(),
        ]);
        $livre->decrement('quantite_disponible');

        return redirect()->route('bibliotheque.emprunts.index')->with('success','Emprunt enregistré.');
    }

    public function retourner(Emprunt $emprunt)
    {
        $emprunt->update(['statut'=>'retourne','date_retour_effective'=>now()]);
        $emprunt->livre->increment('quantite_disponible');
        return back()->with('success','Retour enregistré.');
    }

    public function declarerPerdu(Emprunt $emprunt)
    {
        $emprunt->update(['statut'=>'perdu']);
        return back()->with('success','Livre déclaré perdu.');
    }
}