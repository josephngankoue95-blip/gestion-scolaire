<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\CandidatureDocument;
use App\Models\Section;
use App\Services\NexahNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatureController extends Controller
{
    public function __construct(protected NexahNotificationService $notification) {}

    public function create()
    {
        $sections = Section::all();
        return view('public.candidature.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'nullable|string|max:100',
            'sexe' => 'required|in:M,F',
            'classe_demandee' => 'required|string|max:50',
            'section_id' => 'required|exists:sections,id',
            'etablissement_origine' => 'nullable|string|max:150',
            'nom_parent' => 'required|string|max:150',
            'telephone_parent' => 'required|string|max:20',
            'email_parent' => 'nullable|email|max:150',
            'adresse' => 'nullable|string',
            'acte_naissance' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'bulletin_precedent' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'certificat_scolarite' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'photo' => 'nullable|image|max:2048',
        ]);

        $candidature = DB::transaction(function () use ($validated, $request) {
            $candidature = Candidature::create([
                ...collect($validated)->except([
                    'acte_naissance', 'bulletin_precedent', 'certificat_scolarite', 'photo',
                ])->toArray(),
                'reference' => Candidature::genererReference(),
                'statut' => 'en_attente',
            ]);

            $documentsMap = [
                'acte_naissance' => 'acte_naissance',
                'bulletin_precedent' => 'bulletin_precedent',
                'certificat_scolarite' => 'certificat_scolarite',
                'photo' => 'photo',
            ];

            foreach ($documentsMap as $champ => $type) {
                if ($request->hasFile($champ)) {
                    $file = $request->file($champ);
                    $chemin = $file->store('candidatures/' . $candidature->reference, 'public');

                    CandidatureDocument::create([
                        'candidature_id' => $candidature->id,
                        'type' => $type,
                        'nom_fichier' => $file->getClientOriginalName(),
                        'chemin' => $chemin,
                    ]);
                }
            }

            return $candidature;
        });

        // Notification de confirmation de dépôt
        $this->notification->notifier(
            $candidature->telephone_parent,
            "Bonjour, votre dossier de candidature pour {$candidature->nomComplet()} a été reçu. Référence : {$candidature->reference}. Vous serez notifié(e) de la décision sous peu."
        );

        return redirect()->route('public.candidature.suivi', $candidature->reference)
            ->with('success', 'Votre candidature a été soumise avec succès.');
    }

    /** Page de suivi public via la référence */
    public function suivi(string $reference)
    {
        $candidature = Candidature::where('reference', $reference)->firstOrFail();
        return view('public.candidature.suivi', compact('candidature'));
    }

    public function rechercherSuivi(Request $request)
    {
        $request->validate(['reference' => 'required|string']);

        $candidature = Candidature::where('reference', $request->reference)->first();

        if (!$candidature) {
            return back()->with('error', 'Aucune candidature trouvée avec cette référence.');
        }

        return redirect()->route('public.candidature.suivi', $candidature->reference);
    }
}