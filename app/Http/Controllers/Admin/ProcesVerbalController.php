<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\Trimestre;
use App\Models\Sequence;
use App\Models\AnneeScolaire;
use App\Services\MoyenneService;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcesVerbalController extends Controller
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

        $sequences = Sequence::whereHas('trimestre', fn ($q) =>
                $q->where('annee_scolaire_id', $annee?->id)
            )
            ->with('trimestre')
            ->orderBy('numero')
            ->get();

        return view('admin.proces-verbaux.index', compact('classes', 'trimestres', 'sequences'));
    }

    protected function construireResultats(ClasseModel $classe, string $type, ?int $periodeId): array
    {
        $eleves = $classe->eleves()->get();
        $resultats = [];

        if ($type === 'sequentiel') {
            $sequence = Sequence::with('trimestre')->findOrFail($periodeId);

            foreach ($eleves as $eleve) {
                $b = $this->svc->construireBulletinSequence($eleve, $classe, $sequence);
                $resultats[] = ['eleve' => $eleve, 'bulletin' => $b];
            }
        } elseif ($type === 'trimestriel') {
            $trimestre = Trimestre::findOrFail($periodeId);

            foreach ($eleves as $eleve) {
                $b = $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre);
                $resultats[] = ['eleve' => $eleve, 'bulletin' => $b];
            }
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)
                ->orderBy('numero')
                ->with('sequences')
                ->get();

            foreach ($eleves as $eleve) {
                $moyennes = $trimestres->map(function ($t) use ($eleve, $classe) {
                    return $this->svc->construireBulletinTrimestre($eleve, $classe, $t)['moyenne_generale'] ?? null;
                })->filter();

                $moy = $moyennes->isEmpty() ? null : round($moyennes->avg(), 2);

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

        return $resultats;
    }

    public function show(Request $request)
    {
        $validated = $request->validate([
            'classe_id'    => ['required', 'exists:classes,id'],
            'type'         => ['required', Rule::in(['sequentiel', 'trimestriel', 'annuel'])],
            'sequence_id'  => ['nullable', 'integer'],
            'trimestre_id' => ['nullable', 'integer'],
            'ordre'        => ['required', Rule::in(['merite', 'alphabetique'])],
        ]);

        $classe = ClasseModel::with('eleves', 'anneeScolaire', 'section')
            ->findOrFail($validated['classe_id']);

        if ($validated['type'] === 'sequentiel' && empty($validated['sequence_id'])) {
            return back()->withInput()->withErrors(['sequence_id' => 'La séquence est obligatoire.']);
        }

        if ($validated['type'] === 'trimestriel' && empty($validated['trimestre_id'])) {
            return back()->withInput()->withErrors(['trimestre_id' => 'Le trimestre est obligatoire.']);
        }

        $periodeId = null;
        if ($validated['type'] === 'sequentiel') {
            $periodeId = (int) $validated['sequence_id'];
        } elseif ($validated['type'] === 'trimestriel') {
            $periodeId = (int) $validated['trimestre_id'];
        }

        $resultats = $this->construireResultats($classe, $validated['type'], $periodeId);

        if ($validated['ordre'] === 'merite') {
            usort($resultats, fn ($a, $b) =>
                ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
            );
        } else {
            usort($resultats, fn ($a, $b) =>
                strcmp($a['eleve']->nomComplet(), $b['eleve']->nomComplet())
            );
        }

        $parMerite = $resultats;
        usort($parMerite, fn ($a, $b) =>
            ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
        );

        $rangs = [];
        foreach ($parMerite as $i => $r) {
            $rangs[$r['eleve']->id] = $i + 1;
        }

        $periode = null;
        if ($validated['type'] === 'sequentiel') {
            $periode = Sequence::with('trimestre')->find($periodeId);
        } elseif ($validated['type'] === 'trimestriel') {
            $periode = Trimestre::find($periodeId);
        }

        return view('admin.proces-verbaux.show', compact('classe', 'resultats', 'rangs', 'periode'));
    }

    public function imprimer(Request $request)
    {
        $validated = $request->validate([
            'classe_id'    => ['required', 'exists:classes,id'],
            'type'         => ['required', Rule::in(['sequentiel', 'trimestriel', 'annuel'])],
            'sequence_id'  => ['nullable', 'integer'],
            'trimestre_id' => ['nullable', 'integer'],
            'ordre'        => ['required', Rule::in(['merite', 'alphabetique'])],
        ]);

        $classe = ClasseModel::with('eleves', 'anneeScolaire', 'section', 'professeurPrincipal.user')
            ->findOrFail($validated['classe_id']);

        if ($validated['type'] === 'sequentiel' && empty($validated['sequence_id'])) {
            return back()->withInput()->withErrors(['sequence_id' => 'La séquence est obligatoire.']);
        }

        if ($validated['type'] === 'trimestriel' && empty($validated['trimestre_id'])) {
            return back()->withInput()->withErrors(['trimestre_id' => 'Le trimestre est obligatoire.']);
        }

        $periodeId = null;
        if ($validated['type'] === 'sequentiel') {
            $periodeId = (int) $validated['sequence_id'];
        } elseif ($validated['type'] === 'trimestriel') {
            $periodeId = (int) $validated['trimestre_id'];
        }

        $resultats = $this->construireResultats($classe, $validated['type'], $periodeId);

        if ($validated['ordre'] === 'merite') {
            usort($resultats, fn ($a, $b) =>
                ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
            );
        } else {
            usort($resultats, fn ($a, $b) =>
                strcmp($a['eleve']->nomComplet(), $b['eleve']->nomComplet())
            );
        }

        $parMerite = $resultats;
        usort($parMerite, fn ($a, $b) =>
            ($b['bulletin']['moyenne_generale'] ?? 0) <=> ($a['bulletin']['moyenne_generale'] ?? 0)
        );

        $rangs = [];
        foreach ($parMerite as $i => $r) {
            $rangs[$r['eleve']->id] = $i + 1;
        }

        $periode = null;
        if ($validated['type'] === 'sequentiel') {
            $periode = Sequence::with('trimestre')->find($periodeId);
        } elseif ($validated['type'] === 'trimestriel') {
            $periode = Trimestre::find($periodeId);
        }

        $pdf = Pdf::loadView('admin.proces-verbaux.pdf', [
            'classe' => $classe,
            'resultats' => $resultats,
            'rangs' => $rangs,
            'type' => $validated['type'],
            'ordre' => $validated['ordre'],
            'periode' => $periode,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("pv_{$classe->nom}_{$validated['type']}.pdf");
    }
}