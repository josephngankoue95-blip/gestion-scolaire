<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InscriptionScolarite;
use App\Models\Eleve;
use App\Models\ClasseModel;
use App\Models\FraisScolarite;
use App\Models\ZoneTransport;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscriptionScolariteController extends Controller
{
    public function create()
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('niveau')->get();
        $zones   = ZoneTransport::where('annee_scolaire_id', $annee?->id)->where('actif', true)->get();
        $eleves  = Eleve::where('statut', 'actif')->orderBy('nom')->get();

        return view('admin.scolarite.inscription.create', compact('classes', 'zones', 'eleves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'eleve_id'          => 'required|exists:eleves,id',
            'classe_id'         => 'required|exists:classes,id',
            'date_inscription'  => 'required|date',
            'type'              => 'required|in:nouvelle,redoublant,transfert',
            'zone_transport_id' => 'nullable|exists:zones_transport,id',
            'frais_inscription' => 'required|numeric|min:0',
            'montant_tranche1'  => 'required|numeric|min:0',
            'montant_tranche2'  => 'required|numeric|min:0',
            'montant_tranche3'  => 'required|numeric|min:0',
            'montant_transport' => 'nullable|numeric|min:0',
        ]);

        $annee = AnneeScolaire::getActive();

        if (InscriptionScolarite::where('eleve_id', $validated['eleve_id'])
            ->where('annee_scolaire_id', $annee?->id)->exists()) {
            return back()->with('error', 'Cet élève est déjà inscrit pour cette année scolaire.');
        }

        $validated['annee_scolaire_id'] = $annee?->id;
        $validated['montant_transport']  = $validated['montant_transport'] ?? 0;
        InscriptionScolarite::create($validated);

        return redirect()->route('admin.scolarite.index')
            ->with('success', 'Inscription enregistrée avec succès.');
    }

    public function show(InscriptionScolarite $inscriptionScolarite)
    {
        $inscriptionScolarite->load('eleve', 'classe.section', 'zoneTransport', 'paiements.enregistrePar');
        return view('admin.scolarite.inscription.show', compact('inscriptionScolarite'));
    }

    /** Chargement des frais selon section/niveau (AJAX) */
    public function getFrais(Request $request)
    {
        $classe  = ClasseModel::with('section')->findOrFail($request->classe_id);
        $annee   = AnneeScolaire::getActive();

        $frais = FraisScolarite::where('section_id', $classe->section_id)
            ->where('annee_scolaire_id', $annee?->id)
            ->where(fn($q) => $q->where('niveau', $classe->niveau)->orWhereNull('niveau'))
            ->orderByRaw("CASE WHEN niveau IS NOT NULL THEN 0 ELSE 1 END")
            ->first();

        return response()->json($frais);
    }
}