<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use App\Services\NexahNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CandidatureController extends Controller
{
    public function __construct(protected NexahNotificationService $notification) {}

    public function index(Request $request)
    {
        $query = Candidature::with('section');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $candidatures = $query->latest()->paginate(10);

        return view('admin.candidatures.index', compact('candidatures'));
    }

    public function show(Candidature $candidature)
    {
        $candidature->load('documents', 'section');
        $classes = ClasseModel::where('section_id', $candidature->section_id)
            ->where('annee_scolaire_id', AnneeScolaire::getActive()?->id)
            ->get();

        return view('admin.candidatures.show', compact('candidature', 'classes'));
    }

    /** Accepter la candidature → conversion en élève + inscription */
    public function accepter(Request $request, Candidature $candidature)
{
    $request->validate([
        'classe_id' => 'nullable|exists:classes,id',
    ]);

    $eleve = DB::transaction(function () use ($candidature, $request) {
        $eleve = Eleve::create([
            'matricule' => Eleve::genererMatricule(),
            'nom' => $candidature->nom,
            'prenom' => $candidature->prenom,
            'date_naissance' => $candidature->date_naissance,
            'sexe' => $candidature->sexe,
            'lieu_naissance' => $candidature->lieu_naissance,
            'telephone_parent' => $candidature->telephone_parent,
            'adresse' => $candidature->adresse,
            'classe_id' => $request->classe_id,
        ]);
        $candidature->update([
            'statut' => 'acceptee',
            'classe_id' => $request->classe_id,
            'traite_par' => Auth::id(),
            'notifie_le' => now(),
        ]);
    });

    $this->notification->notifier(
        $candidature->telephone_parent,
        "Félicitations ! La candidature de {$candidature->nomComplet()} (réf. {$candidature->reference}) a été ACCEPTÉE."
    );

    return redirect()->route('admin.candidatures.index')
        ->with('success', 'Candidature acceptée avec succès.');
}

    public function refuser(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'motif_refus' => 'required|string|max:500',
        ]);

        $candidature->update([
            'statut' => 'refusee',
            'motif_refus' => $validated['motif_refus'],
            'traite_par' => Auth::id(),
            'notifie_le' => now(),
        ]);

        $this->notification->notifier(
            $candidature->telephone_parent,
            "Bonjour, nous regrettons de vous informer que la candidature de {$candidature->nomComplet()} (réf. {$candidature->reference}) n'a pas été retenue. Motif : {$validated['motif_refus']}"
        );

        return redirect()->route('admin.candidatures.index')
            ->with('success', 'Candidature refusée. Le parent a été notifié.');
    }

    public function marquerEnExamen(Candidature $candidature)
    {
        $candidature->update(['statut' => 'en_cours_examen']);
        return back()->with('success', 'Statut mis à jour : en cours d\'examen.');
    }
}