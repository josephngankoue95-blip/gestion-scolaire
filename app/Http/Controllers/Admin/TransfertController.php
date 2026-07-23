<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfert;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use App\Models\Scolarite;
use App\Models\Note;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransfertController extends Controller
{
    public function index(Request $request)
    {
        $annee = AnneeScolaire::getActive();

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with('section')
            ->orderBy('nom')
            ->get();

        $transferts = Transfert::with('eleve', 'classeSource', 'classeDestination', 'effectuePar')
            ->where('annee_scolaire_id', $annee?->id)
            ->latest()
            ->paginate(20);

        return view('admin.transferts.index', compact('classes', 'transferts', 'annee'));
    }

    public function elevesClasse(Request $request)
    {
        $classe = ClasseModel::with('niveau')->findOrFail($request->classe_id);

        $eleves = Eleve::where('classe_id', $classe->id)
            ->where('statut', 'actif')
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom', 'matricule'])
            ->map(fn($e) => [
                'id'        => $e->id,
                'nom'       => $e->nomComplet(),
                'matricule' => $e->matricule,
            ]);

        return response()->json([
            'eleves'         => $eleves,
            'est_terminale'  => (bool) ($classe->niveau?->est_terminale ?? false),
            'niveau_libelle' => $classe->niveau?->nom,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleves_ids'             => 'required|array|min:1',
            'eleves_ids.*'           => 'exists:eleves,id',
            'classe_source_id'       => 'required|exists:classes,id',
            'classe_destination_id'  => 'required|exists:classes,id|different:classe_source_id',
            'date_transfert'         => 'required|date',
            'motif'                  => 'nullable|string|max:500',
            'confirmation_terminale' => 'nullable|boolean',
            'confirmation_purge'     => 'required|accepted',
        ]);

        $annee        = AnneeScolaire::getActive();
        $classeSource = ClasseModel::with('niveau')->findOrFail($validated['classe_source_id']);

        if ($classeSource->niveau?->est_terminale && !$request->boolean('confirmation_terminale')) {
            return back()->withInput()->with('error',
                "La classe {$classeSource->nom} est une classe terminale (fin de cycle). " .
                "Les élèves ne sont normalement pas transférables. Cochez la confirmation pour continuer."
            );
        }

        $nb = 0;

        DB::transaction(function () use ($validated, $annee, &$nb) {
            foreach ($validated['eleves_ids'] as $eleveId) {

                $eleve = Eleve::where('id', $eleveId)
                    ->where('classe_id', $validated['classe_source_id'])
                    ->first();

                if (!$eleve) continue;

                // ── 1. Historique du transfert (conservé pour toujours) ──
                Transfert::create([
                    'eleve_id'              => $eleveId,
                    'classe_source_id'      => $validated['classe_source_id'],
                    'classe_destination_id' => $validated['classe_destination_id'],
                    'annee_scolaire_id'     => $annee?->id,
                    'date_transfert'        => $validated['date_transfert'],
                    'motif'                 => $validated['motif'] ?? null,
                    'effectue_par'          => Auth::id(),
                ]);

                // ── 2. Purge STRICTEMENT limitée à l'ANNÉE SCOLAIRE ACTIVE ──
                // On ne touche jamais les Scolarite/Note/Absence des années précédentes :
                // l'historique de l'élève reste consultable en changeant l'année dans sa fiche.

                $scolarite = Scolarite::where('eleve_id', $eleveId)
                    ->where('classe_id', $validated['classe_source_id'])
                    ->where('annee_scolaire_id', $annee?->id) // ✅ verrouillé sur l'année active
                    ->first();

                if ($scolarite) {
                    $scolarite->paiements()->delete();
                    $scolarite->delete();
                }

                // Notes de cette classe, filtrées via la séquence → trimestre → année active
                Note::where('eleve_id', $eleveId)
                    ->where('classe_id', $validated['classe_source_id'])
                    ->whereHas('sequence.trimestre', fn($q) => $q->where('annee_scolaire_id', $annee?->id))
                    ->delete();

                // Absences de cette classe, filtrées sur la période de l'année active
                Absence::where('eleve_id', $eleveId)
                    ->where('classe_id', $validated['classe_source_id'])
                    ->when($annee?->date_debut, fn($q) => $q->where('date_absence', '>=', $annee->date_debut))
                    ->when($annee?->date_fin, fn($q) => $q->where('date_absence', '<=', $annee->date_fin))
                    ->delete();

                // ── 3. Affectation à la nouvelle classe (cache eleve.classe_id) ──
                // Les comptes (parent_user_id, eleve_user_id) restent intacts
                $eleve->update(['classe_id' => $validated['classe_destination_id']]);

                $nb++;
            }
        });

        return redirect()->route('admin.transferts.index')
            ->with('success', "{$nb} élève(s) transféré(s). Scolarité, notes et absences de l'année active ont été purgées pour l'ancienne classe. L'historique des années précédentes et les comptes d'accès sont conservés.");
    }

    public function destroy(Transfert $transfert)
    {
        DB::transaction(function () use ($transfert) {
            $eleve = Eleve::find($transfert->eleve_id);
            if ($eleve) {
                $eleve->update(['classe_id' => $transfert->classe_source_id]);
            }
            $transfert->delete();
        });

        return back()->with('success', 'Transfert annulé, élève remis dans la classe source (les données purgées ne sont pas récupérables).');
    }
}