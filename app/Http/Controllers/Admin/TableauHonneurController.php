<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Trimestre;
use App\Models\Sequence;
use App\Models\AnneeScolaire;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TableauHonneurController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    public function index()
    {
        $annee = AnneeScolaire::getActive();

        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)
            ->with('section')
            ->get();

        $trimestres = Trimestre::where('annee_scolaire_id', $annee?->id)
            ->orderBy('numero')
            ->get();

        $sequences = Sequence::whereHas('trimestre', function ($q) use ($annee) {
                $q->where('annee_scolaire_id', $annee?->id);
            })
            ->with('trimestre')
            ->orderBy('numero')
            ->get();

        return view('admin.tableaux-honneur.index', compact(
            'classes',
            'trimestres',
            'sequences'
        ));
    }

    public function show(Request $request)
    {
        $validated = $request->validate([
            'classe_id'    => ['required', 'exists:classes,id'],
            'type'         => ['required', Rule::in(['sequentiel', 'trimestriel', 'annuel'])],
            'sequence_id'  => ['nullable', 'integer'],
            'trimestre_id' => ['nullable', 'integer'],
            'seuil'        => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $classe = ClasseModel::with(['eleves', 'anneeScolaire', 'section'])
            ->findOrFail($validated['classe_id']);

        $type = $validated['type'];
        $seuil = (float) ($validated['seuil'] ?? 12);
        $resultats = [];
        $periode = null;

        if ($type === 'sequentiel') {
            if (empty($validated['sequence_id'])) {
                return back()->withInput()->withErrors([
                    'sequence_id' => 'La séquence est obligatoire pour ce type de tableau.',
                ]);
            }

            $sequence = Sequence::with('trimestre')->findOrFail($validated['sequence_id']);

            foreach ($classe->eleves as $eleve) {
                $bulletin = $this->svc->construireBulletinSequence($eleve, $classe, $sequence);

                if (($bulletin['moyenne_generale'] ?? 0) >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => $bulletin,
                    ];
                }
            }

            $periode = $sequence;
        } elseif ($type === 'trimestriel') {
            if (empty($validated['trimestre_id'])) {
                return back()->withInput()->withErrors([
                    'trimestre_id' => 'Le trimestre est obligatoire pour ce type de tableau.',
                ]);
            }

            $trimestre = Trimestre::findOrFail($validated['trimestre_id']);

            foreach ($classe->eleves as $eleve) {
                $bulletin = $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre);

                if (($bulletin['moyenne_generale'] ?? 0) >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => $bulletin,
                    ];
                }
            }

            $periode = $trimestre;
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)
                ->orderBy('numero')
                ->with('sequences')
                ->get();

            foreach ($classe->eleves as $eleve) {
                $moyennes = $trimestres->map(function ($t) use ($eleve, $classe) {
                    $b = $this->svc->construireBulletinTrimestre($eleve, $classe, $t);
                    return $b['moyenne_generale'] ?? null;
                })->filter();

                $moy = $moyennes->isEmpty() ? null : round($moyennes->avg(), 2);

                if ($moy !== null && $moy >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => [
                            'moyenne_generale' => $moy,
                            'rang' => '-',
                            'mention' => $this->svc->mention($moy),
                        ],
                    ];
                }
            }
        }

        usort($resultats, fn ($a, $b) =>
            ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
        );

        return view('admin.tableaux-honneur.show', compact(
            'classe',
            'resultats',
            'seuil',
            'type',
            'periode'
        ));
    }

    public function imprimer(Request $request)
    {
        $validated = $request->validate([
            'classe_id'    => ['required', 'exists:classes,id'],
            'type'         => ['required', Rule::in(['sequentiel', 'trimestriel', 'annuel'])],
            'sequence_id'  => ['nullable', 'integer'],
            'trimestre_id' => ['nullable', 'integer'],
            'seuil'        => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $classe = ClasseModel::with(['eleves', 'anneeScolaire', 'section'])
            ->findOrFail($validated['classe_id']);

        $type = $validated['type'];
        $seuil = (float) ($validated['seuil'] ?? 12);
        $resultats = [];
        $periode = null;

        if ($type === 'sequentiel') {
            if (empty($validated['sequence_id'])) {
                return back()->withInput()->withErrors([
                    'sequence_id' => 'La séquence est obligatoire pour ce type de tableau.',
                ]);
            }

            $sequence = Sequence::with('trimestre')->findOrFail($validated['sequence_id']);

            foreach ($classe->eleves as $eleve) {
                $b = $this->svc->construireBulletinSequence($eleve, $classe, $sequence);

                if (($b['moyenne_generale'] ?? 0) >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => $b,
                    ];
                }
            }

            $periode = $sequence;
        } elseif ($type === 'trimestriel') {
            if (empty($validated['trimestre_id'])) {
                return back()->withInput()->withErrors([
                    'trimestre_id' => 'Le trimestre est obligatoire pour ce type de tableau.',
                ]);
            }

            $trimestre = Trimestre::findOrFail($validated['trimestre_id']);

            foreach ($classe->eleves as $eleve) {
                $b = $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre);

                if (($b['moyenne_generale'] ?? 0) >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => $b,
                    ];
                }
            }

            $periode = $trimestre;
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)
                ->orderBy('numero')
                ->with('sequences')
                ->get();

            foreach ($classe->eleves as $eleve) {
                $moyennes = $trimestres->map(function ($t) use ($eleve, $classe) {
                    $b = $this->svc->construireBulletinTrimestre($eleve, $classe, $t);
                    return $b['moyenne_generale'] ?? null;
                })->filter();

                $moy = $moyennes->isEmpty() ? null : round($moyennes->avg(), 2);

                if ($moy !== null && $moy >= $seuil) {
                    $resultats[] = [
                        'eleve' => $eleve,
                        'bulletin' => [
                            'moyenne_generale' => $moy,
                            'mention' => $this->svc->mention($moy),
                        ],
                    ];
                }
            }
        }

        usort($resultats, fn ($a, $b) =>
            ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
        );

        $pdf = Pdf::loadView('admin.tableaux-honneur.pdf', [
            'classe' => $classe,
            'resultats' => $resultats,
            'seuil' => $seuil,
            'type' => $type,
            'periode' => $periode,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("tableau_honneur_{$classe->nom}.pdf");
    }
}