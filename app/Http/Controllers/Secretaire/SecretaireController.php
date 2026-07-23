<?php
namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Scolarite;
use App\Models\PaiementScolaire;
use App\Models\AnneeScolaire;
use App\Models\ClasseModel;
use App\Models\ZoneTransport;
use App\Models\Eleve;
use App\Models\Requete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecretaireController extends Controller
{
    public function dashboard()
    {
        $annee      = AnneeScolaire::getActive();
        $scolarites = Scolarite::where('annee_scolaire_id', $annee?->id)->get();

        $totalDu        = $scolarites->sum(fn($s) => $s->totalDu());
        $totalPaye      = $scolarites->sum(fn($s) => $s->totalPaye());
        $nbSoldes       = $scolarites->filter(fn($s) => $s->solde() <= 0)->count();
        $nbDettes       = $scolarites->filter(fn($s) => $s->solde() > 0)->count();
        $tauxRecouv     = $totalDu > 0 ? round(($totalPaye / $totalDu) * 100, 1) : 0;
        $requetesEnAttente = Requete::where('statut','en_attente')->count();

        $paiementsAujourdhui = PaiementScolaire::whereDate('date_paiement', today())
            ->with('scolarite.eleve')
            ->latest()->get();

        return view('secretaire.dashboard', compact(
            'annee','totalDu','totalPaye','nbSoldes','nbDettes',
            'tauxRecouv','requetesEnAttente','paiementsAujourdhui'
        ));
    }

    public function scolarite(Request $request)
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('niveau_id')->get();

        $query = Scolarite::with('eleve','classe.section','zoneTransport')
            ->where('annee_scolaire_id', $annee?->id);

        if ($request->filled('classe_id')) $query->where('classe_id', $request->classe_id);
        if ($request->filled('statut')) {
            match($request->statut) {
                'solde' => $query->whereRaw('(paye_inscription+paye_tranche1+paye_tranche2+paye_tranche3+paye_transport) >= (frais_inscription+montant_tranche1+montant_tranche2+montant_tranche3+montant_transport)'),
                'dette' => $query->whereRaw('(paye_inscription+paye_tranche1+paye_tranche2+paye_tranche3+paye_transport) < (frais_inscription+montant_tranche1+montant_tranche2+montant_tranche3+montant_transport)'),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('eleve', fn($q) =>
                $q->where('nom','like',"%{$s}%")->orWhere('prenom','like',"%{$s}%")->orWhere('matricule','like',"%{$s}%")
            );
        }

        $scolarites = $query->latest()->paginate(20);
        return view('secretaire.scolarite', compact('scolarites','classes','annee'));
    }

    public function showScolarite(\App\Models\Scolarite $scolarite)
    {
        $scolarite->load('eleve','classe.section','zoneTransport','paiements.enregistrePar');
        return view('secretaire.scolarite-show', compact('scolarite'));
    }

    public function paiement(Request $request, Scolarite $scolarite)
    {
        $validated = $request->validate([
            'type'          => 'required|in:inscription,tranche1,tranche2,tranche3,transport',
            'montant'       => 'required|numeric|min:1',
            'date_paiement' => 'required|date',
            'note'          => 'nullable|string|max:255',
        ]);

        $champDu   = $validated['type'] === 'inscription' ? 'frais_inscription' : "montant_{$validated['type']}";
        $champPaye = $validated['type'] === 'inscription' ? 'paye_inscription'  : "paye_{$validated['type']}";
        $reste     = (float)$scolarite->$champDu - (float)$scolarite->$champPaye;

        if ($validated['montant'] > $reste + 0.01) {
            return back()->with('error', "Le montant dépasse le solde restant ({$reste} FCFA).");
        }

        $paiement = PaiementScolaire::create([
            ...$validated,
            'scolarite_id'   => $scolarite->id,
            'numero_recu'    => PaiementScolaire::genererNumeroRecu(),
            'enregistre_par' => Auth::id(),
        ]);
        $scolarite->increment($champPaye, $validated['montant']);

        return back()->with('success', "Paiement enregistré — Reçu N° {$paiement->numero_recu}");
    }

    public function requetes(Request $request)
    {
        $query = Requete::with('eleve','traitePar');
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        $requetes = $query->latest()->paginate(20);
        return view('secretaire.requetes', compact('requetes'));
    }

    public function traiterRequete(Request $request, Requete $requete)
    {
        $validated = $request->validate([
            'statut'  => 'required|in:en_cours,traitee,rejetee',
            'reponse' => 'nullable|string|max:1000',
        ]);
        $requete->update([...$validated, 'traitee_par' => Auth::id(), 'traitee_le' => now()]);
        return back()->with('success', 'Requête mise à jour.');
    }

    public function profil()
    {
        return view('secretaire.profil', ['user' => Auth::user()]);
    }

    public function updateProfil(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'telephone' => 'nullable|string|max:20',
            'password'  => 'nullable|string|min:6|confirmed',
        ]);
        $data = ['name' => $validated['name'], 'telephone' => $validated['telephone'] ?? null];
        if (!empty($validated['password'])) $data['password'] = Hash::make($validated['password']);
        Auth::user()->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }

    public function createScolarite()
    {
        $annee   = AnneeScolaire::getActive();

        $eleves = Eleve::where('statut', 'actif')
            ->whereNotIn('id', Scolarite::where('annee_scolaire_id', $annee?->id)->pluck('eleve_id'))
            ->orderBy('nom')->get();

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with('section')->orderBy('nom')->get();

        $zones = ZoneTransport::where('annee_scolaire_id', $annee?->id)
            ->where('actif', true)->get();

        return view('secretaire.scolarite-create', compact('eleves', 'classes', 'zones', 'annee'));
    }

    /** Enregistrement de l'inscription — pas de configuration des frais/zones */
    public function storeScolarite(Request $request)
    {
        $validated = $request->validate([
            'eleve_id'          => 'required|exists:eleves,id',
            'classe_id'         => 'required|exists:classes,id',
            'date_inscription'  => 'required|date',
            'type_inscription'  => 'required|in:nouvelle,redoublant,transfert',
            'zone_transport_id' => 'nullable|exists:zones_transport,id',
            'frais_inscription' => 'required|numeric|min:0',
            'montant_tranche1'  => 'required|numeric|min:0',
            'montant_tranche2'  => 'required|numeric|min:0',
            'montant_tranche3'  => 'required|numeric|min:0',
            'montant_transport' => 'nullable|numeric|min:0',
        ]);

        $annee = AnneeScolaire::getActive();

        if (Scolarite::where('eleve_id', $validated['eleve_id'])->where('annee_scolaire_id', $annee?->id)->exists()) {
            return back()->withInput()->with('error', 'Cet élève est déjà inscrit pour cette année scolaire.');
        }

        $validated['annee_scolaire_id'] = $annee?->id;
        $validated['montant_transport']  = $validated['montant_transport'] ?? 0;

        $scolarite = Scolarite::create($validated);

        // Met aussi à jour la classe de l'élève (cohérence avec eleve.classe_id)
        Eleve::where('id', $validated['eleve_id'])->update(['classe_id' => $validated['classe_id']]);

        return redirect()->route('secretaire.scolarite.show', $scolarite)
            ->with('success', 'Inscription scolarité enregistrée avec succès.');
    }

    /** AJAX — récupérer les frais définis par l'admin pour une classe donnée */
    public function fraisPourClasse(Request $request)
    {
        $classe = ClasseModel::findOrFail($request->classe_id);
        $frais  = FraisScolarite::pourClasse($classe);
        return response()->json($frais);
    }
}