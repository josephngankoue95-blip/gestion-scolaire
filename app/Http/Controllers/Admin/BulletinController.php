<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Sequence;
use App\Models\Trimestre;
use App\Models\Etablissement;
use App\Models\ClasseModel;
use App\Services\MoyenneService;
use Barryvdh\DomPDF\Facade\Pdf;

class BulletinController extends Controller
{
    public function __construct(protected MoyenneService $svc) {}

    public function index()
    {
        $classes = ClasseModel::with('section')->orderBy('nom')->get();
        $sequences = Sequence::with('trimestre')->orderBy('numero')->get();
        $trimestres = Trimestre::orderBy('numero')->get();

        return view('admin.bulletins.index', compact('classes', 'sequences', 'trimestres'));
    }

    public function eleves(Request $request)
    {
        $eleves = Eleve::query();

        $classeId = $request->input('classe_id');
        $type = $request->input('type_bulletin');
        $sequenceId = $request->input('sequence_id');
        $trimestreId = $request->input('trimestre_id');

        $classes = ClasseModel::with('section')->orderBy('nom')->get();
        $sequences = Sequence::with('trimestre')->orderBy('numero')->get();
        $trimestres = Trimestre::orderBy('numero')->get();

        $classe = null;

        if ($classeId) {
            $classe = ClasseModel::with('section')->findOrFail($classeId);
            $eleves->where('classe_id', $classeId);
        }

        $eleves = $eleves->orderBy('nom')->get();

        return view('admin.bulletins.eleves', compact(
            'eleves',
            'classes',
            'sequences',
            'trimestres',
            'classe',
            'type',
            'sequenceId',
            'trimestreId'
        ));
    }

    public function sequence(Eleve $eleve, Sequence $sequence)
    {
        $classe = $eleve->classe;
        abort_if(!$classe, 404);

        $classe->load('section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres');

        $bulletin = $this->svc->construireBulletinSequence($eleve, $classe, $sequence);
        $etablissement = Etablissement::instance();
        $lang = strtolower($classe->section->code ?? '') === 'ang' ? 'en' : 'fr';

        $pdf = Pdf::loadView('admin.bulletins.pdf-sequence', [
            'eleve' => $eleve,
            'classe' => $classe,
            'periode' => $sequence,
            'bulletin' => $bulletin,
            'etablissement' => $etablissement,
            'lang' => $lang,
            'logoBase64' => $this->logoBase64($etablissement),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("sequence_{$eleve->matricule}_{$sequence->numero}.pdf");
    }

    public function trimestre(Eleve $eleve, Trimestre $trimestre)
    {
        $classe = $eleve->classe;
        abort_if(!$classe, 404);

        $classe->load('section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres');

        $bulletin = $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre);
        $etablissement = Etablissement::instance();
        $lang = strtolower($classe->section->code ?? '') === 'ang' ? 'en' : 'fr';

        $pdf = Pdf::loadView('admin.bulletins.pdf-trimestre', [
            'eleve' => $eleve,
            'classe' => $classe,
            'periode' => $trimestre,
            'bulletin' => $bulletin,
            'etablissement' => $etablissement,
            'lang' => $lang,
            'logoBase64' => $this->logoBase64($etablissement),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("trimestre_{$eleve->matricule}_{$trimestre->numero}.pdf");
    }

    public function annuel(Eleve $eleve)
    {
        $classe = $eleve->classe;
        abort_if(!$classe, 404);

        $classe->load('section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres');

        $trimestres = Trimestre::where('annee_scolaire_id', $classe->annee_scolaire_id)
            ->orderBy('numero')
            ->with('sequences')
            ->get();

        $bulletin = $this->svc->bulletinAnnuel($eleve, $classe, $trimestres->all());

        $etablissement = Etablissement::instance();
        $lang = strtolower($classe->section->code ?? '') === 'ang' ? 'en' : 'fr';

        $pdf = Pdf::loadView('admin.bulletins.pdf-annuel', [
            'eleve' => $eleve,
            'classe' => $classe,
            'bulletin' => $bulletin,
            'periode' => null,
            'lang' => $lang,
            'etablissement' => $etablissement,
            'logoBase64' => $this->logoBase64($etablissement),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("annuel_{$eleve->matricule}.pdf");
    }

    public function imprimerTous(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'type_bulletin' => 'required|in:sequentiel,trimestriel,annuel',
            'sequence_id' => 'nullable|exists:sequences,id',
            'trimestre_id' => 'nullable|exists:trimestres,id',
        ]);

        $classe = ClasseModel::with(['section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres'])
            ->findOrFail($request->classe_id);

        $eleves = $classe->eleves()->orderBy('nom')->get();
        $type = $request->type_bulletin;
        $lang = strtolower($classe->section->code ?? '') === 'ang' ? 'en' : 'fr';
        $etablissement = Etablissement::instance();

        $pdf = Pdf::loadView('admin.bulletins.pdf-tous', [
            'classe' => $classe,
            'eleves' => $eleves,
            'type' => $type,
            'sequenceId' => $request->sequence_id,
            'trimestreId' => $request->trimestre_id,
            'lang' => $lang,
            'etablissement' => $etablissement,
            'logoBase64' => $this->logoBase64($etablissement),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("bulletins_classe_{$classe->id}.pdf");
    }

    public function tous(Request $request)
    {
        $request->validate([
            'classe_id' => 'required|exists:classes,id',
            'type_bulletin' => 'required|in:sequentiel,trimestriel,annuel',
            'sequence_id' => 'nullable|exists:sequences,id',
            'trimestre_id' => 'nullable|exists:trimestres,id',
        ]);

        $classe = ClasseModel::with('section', 'anneeScolaire', 'professeurPrincipal.user', 'matieres')
            ->findOrFail($request->classe_id);

        $eleves = $classe->eleves()->orderBy('nom')->get();
        $type = $request->type_bulletin;
        $lang = strtolower($classe->section->code ?? '') === 'ang' ? 'en' : 'fr';
        $etablissement = Etablissement::instance();

        $bulletins = [];

        if ($type === 'sequentiel') {
            $sequence = Sequence::with('trimestre')->findOrFail($request->sequence_id);
            foreach ($eleves as $eleve) {
                $bulletins[] = [
                    'eleve' => $eleve,
                    'bulletin' => $this->svc->construireBulletinSequence($eleve, $classe, $sequence),
                    'periode' => $sequence,
                ];
            }
            $vue = 'admin.bulletins.pdf-sequence';
        } elseif ($type === 'trimestriel') {
            $trimestre = Trimestre::findOrFail($request->trimestre_id);
            foreach ($eleves as $eleve) {
                $bulletins[] = [
                    'eleve' => $eleve,
                    'bulletin' => $this->svc->construireBulletinTrimestre($eleve, $classe, $trimestre),
                    'periode' => $trimestre,
                ];
            }
            $vue = 'admin.bulletins.pdf-trimestre';
        } else {
            $trimestres = Trimestre::where('annee_scolaire_id', $classe->anneeScolaire->id)
                ->orderBy('numero')
                ->with('sequences')
                ->get();

            foreach ($eleves as $eleve) {
                $bulletins[] = [
                    'eleve' => $eleve,
                    'bulletin' => $this->svc->bulletinAnnuel($eleve, $classe, $trimestres->all()),
                    'periode' => null,
                ];
            }
            $vue = 'admin.bulletins.pdf-annuel';
        }

        $pdf = Pdf::loadView('admin.bulletins.pdf-tous', [
            'bulletins' => $bulletins,
            'classe' => $classe,
            'type' => $type,
            'vue' => $vue,
            'lang' => $lang,
            'etablissement' => $etablissement,
            'logoBase64' => $this->logoBase64($etablissement),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("bulletins_tous_{$classe->nom}.pdf");
    }

    protected function logoBase64(?Etablissement $etablissement): ?string
    {
        if (!$etablissement || empty($etablissement->logo)) {
            return null;
        }

        $path = public_path('storage/' . $etablissement->logo);

        if (!file_exists($path)) {
            return null;
        }

        $mime = @mime_content_type($path) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    protected function t(string $key, string $lang): string
    {
        $translations = [
            'titre_trim' => ['fr' => 'BULLETIN DE NOTES', 'en' => 'REPORT CARD'],
            'titre_seq' => ['fr' => 'BULLETIN SÉQUENTIEL', 'en' => 'SEQUENTIAL REPORT'],
            'titre_annuel' => ['fr' => 'BULLETIN ANNUEL', 'en' => 'ANNUAL REPORT'],
            'annee' => ['fr' => 'ANNÉE SCOLAIRE', 'en' => 'ACADEMIC YEAR'],
            'nom_prenom' => ['fr' => 'NOMS ET PRÉNOMS', 'en' => 'FULL NAME'],
            'matricule' => ['fr' => 'MATRICULE', 'en' => 'REGISTRATION No.'],
            'ddn' => ['fr' => 'DATE ET LIEU DE NAISSANCE', 'en' => 'DATE & PLACE OF BIRTH'],
            'classe' => ['fr' => 'CLASSE', 'en' => 'CLASS'],
            'sexe' => ['fr' => 'SEXE', 'en' => 'SEX'],
            'redoublant' => ['fr' => 'REDOUBLANT', 'en' => 'REPEATER'],
            'prof_principal' => ['fr' => 'PROFESSEUR PRINCIPAL', 'en' => 'FORM MASTER'],
            'effectif' => ['fr' => 'EFFECTIF', 'en' => 'CLASS SIZE'],
        ];

        return $translations[$key][$lang] ?? $translations[$key]['fr'] ?? $key;
    }
}