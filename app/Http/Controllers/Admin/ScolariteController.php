<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scolarite;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\ZoneTransport;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function index(Request $request)
    {
        $annee = AnneeScolaire::getActive();

        $query = Scolarite::with([
                'eleve',
                'classe.section',
                'classe.niveau',
                'zoneTransport'
            ])
            ->where('annee_scolaire_id', $annee?->id);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('statut')) {

            switch ($request->statut) {

                case 'solde':
                    $query->whereRaw('
                        (paye_inscription+paye_tranche1+paye_tranche2+paye_tranche3+paye_transport)
                        >=
                        (frais_inscription+montant_tranche1+montant_tranche2+montant_tranche3+montant_transport)
                    ');
                    break;

                case 'dette':
                    $query->whereRaw('
                        (paye_inscription+paye_tranche1+paye_tranche2+paye_tranche3+paye_transport)
                        <
                        (frais_inscription+montant_tranche1+montant_tranche2+montant_tranche3+montant_transport)
                    ');
                    break;
            }
        }

        $scolarites = $query->latest()->paginate(20);

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with(['section', 'niveau'])
            ->orderBy('niveau_id')
            ->orderBy('nom')
            ->get();

        $totaux = Scolarite::where('annee_scolaire_id', $annee?->id)
            ->selectRaw('
                SUM(frais_inscription + montant_tranche1 + montant_tranche2 + montant_tranche3 + montant_transport) AS total_du,
                SUM(paye_inscription + paye_tranche1 + paye_tranche2 + paye_tranche3 + paye_transport) AS total_paye
            ')
            ->first();

        $totalDu = (float) ($totaux->total_du ?? 0);
        $totalPaye = (float) ($totaux->total_paye ?? 0);

        return view('admin.scolarite.index', compact(
            'scolarites',
            'classes',
            'totalDu',
            'totalPaye',
            'annee'
        ));
    }

    public function create()
    {
        $annee = AnneeScolaire::getActive();

        $eleves = Eleve::where('statut', 'actif')
            ->whereNotIn(
                'id',
                Scolarite::where('annee_scolaire_id', $annee?->id)
                    ->pluck('eleve_id')
            )
            ->orderBy('nom')
            ->get();

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with(['section', 'niveau'])
            ->orderBy('niveau_id')
            ->orderBy('nom')
            ->get();

        $zones = ZoneTransport::where('annee_scolaire_id', $annee?->id)
            ->where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.scolarite.create', compact(
            'eleves',
            'classes',
            'zones',
            'annee'
        ));
    }

    public function store(Request $request)
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

    $existante = Scolarite::where('eleve_id', $validated['eleve_id'])
        ->where('annee_scolaire_id', $annee?->id)
        ->first();

    if ($existante) {
        // Si c'est un placeholder auto-glissé (aucun frais renseigné ni payé encore),
        // on le COMPLÈTE au lieu de bloquer.
        $estPlaceholder = $existante->totalDu() == 0 && $existante->totalPaye() == 0;

        if (!$estPlaceholder) {
            return back()->withInput()->with('error', 'Cet élève est déjà inscrit pour cette année scolaire.');
        }

        $validated['montant_transport'] = $validated['montant_transport'] ?? 0;
        $existante->update($validated);

        \App\Models\Eleve::where('id', $validated['eleve_id'])->update(['classe_id' => $validated['classe_id']]);

        return redirect()->route('admin.scolarite.index')
            ->with('success', 'Inscription complétée avec succès.');
    }

    $validated['annee_scolaire_id'] = $annee?->id;
    $validated['montant_transport']  = $validated['montant_transport'] ?? 0;

    Scolarite::create($validated);

    \App\Models\Eleve::where('id', $validated['eleve_id'])->update(['classe_id' => $validated['classe_id']]);

    return redirect()->route('admin.scolarite.index')
        ->with('success', 'Inscription enregistrée avec succès.');
}


    public function show(Scolarite $scolarite)
    {
        $scolarite->load([
            'eleve',
            'classe.section',
            'classe.niveau',
            'zoneTransport',
            'paiements.enregistrePar'
        ]);

        return view('admin.scolarite.show', compact('scolarite'));
    }

    public function destroy(Scolarite $scolarite)
    {
        $scolarite->delete();

        return redirect()
            ->route('admin.scolarite.index')
            ->with('success', 'Inscription supprimée avec succès.');
    }
}