<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompteGenere;
use App\Models\Etablissement;
use App\Services\SmsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CompteGenereController extends Controller
{
    /**
     * Rôles autorisés dans l'application.
     */
    private function rolesAutorises(): array
    {
        return [
            'admin',
            'proviseur',
            'prefet_etudes',
            'parent',
            'eleve',
            'enseignant',
            'secretaire_intendant',
            'surveillant_general',
            'bibliothecaire',
        ];
    }

    /**
     * Construit la requête avec les filtres role, search et tri.
     */
    private function construireRequete(Request $request)
    {
        $query = CompteGenere::query();

        /*
         * Le rôle est obligatoire pour l'affichage de la liste.
         */
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        /*
         * Recherche par nom ou email.
         */
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        /*
         * Tri des résultats.
         */
        match ($request->input('tri', 'recent')) {
            'ancien' => $query->oldest(),
            'nom' => $query->orderBy('nom', 'asc'),
            default => $query->latest(),
        };

        return $query;
    }

    /**
     * Affiche la liste des comptes générés.
     */
    // CompteGenereController::index() — version qui élimine tout effet de bord

public function index(Request $request)
{
    if (!$request->filled('role')) {
        $comptes = collect(); // vide, pas de pagination Eloquent pour éviter les soucis de withQueryString
        return view('admin.comptes-generes.index', ['comptes' => CompteGenere::whereRaw('1=0')->paginate(1)]);
    }

    $query = CompteGenere::query()->where('role', $request->string('role'));

    if ($request->filled('search')) {
        $s = $request->string('search');
        $query->where(fn($q) => $q->where('nom', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
    }

    $tri = $request->input('tri', 'recent');
    match ($tri) {
        'ancien' => $query->oldest(),
        'nom'    => $query->orderBy('nom'),
        default  => $query->latest(),
    };

    $comptes = $query->paginate(30)->appends($request->query()); // appends() au lieu de withQueryString()

    return view('admin.comptes-generes.index', compact('comptes'));
}
    /**
     * Export PDF avec les mêmes filtres que la liste.
     */
    public function exportPdf(Request $request)
    {
        if (
            $request->filled('role') &&
            !in_array($request->input('role'), $this->rolesAutorises(), true)
        ) {
            return back()->with('error', 'Le rôle sélectionné est invalide.');
        }

        $comptes = $this->construireRequete($request)->get();

        $pdf = Pdf::loadView('admin.comptes-generes.pdf', [
            'comptes' => $comptes,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream(
            'comptes_acces_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    /**
     * Export CSV avec les mêmes filtres que la liste.
     */
    public function exportExcel(Request $request)
    {
        if (
            $request->filled('role') &&
            !in_array($request->input('role'), $this->rolesAutorises(), true)
        ) {
            return back()->with('error', 'Le rôle sélectionné est invalide.');
        }

        $comptes = $this->construireRequete($request)->get();

        $filename = 'comptes_acces_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($comptes) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Nom',
                'Email',
                'Mot de passe',
                'Rôle',
                'Élève lié',
                'Date création',
                'Envoyé le',
            ], ';');

            foreach ($comptes as $compte) {
                fputcsv($file, [
                    $compte->nom,
                    $compte->email,
                    $compte->mot_de_passe,
                    ucfirst(str_replace('_', ' ', $compte->role)),
                    $compte->eleve_lie ?? '-',
                    optional($compte->created_at)->format('d/m/Y H:i'),
                    optional($compte->envoye_le)->format('d/m/Y H:i') ?? '-',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Envoie les identifiants par email à un compte.
     */
    public function envoyerParMail(CompteGenere $compteGenere)
    {
        if (!$compteGenere->email) {
            return back()->with(
                'error',
                'Aucun email associé à ce compte.'
            );
        }

        try {
            Mail::to($compteGenere->email)->send(
                new \App\Mail\IdentifiantsAccesMail($compteGenere)
            );

            $compteGenere->update([
                'envoye_le' => now(),
            ]);

            return back()->with(
                'success',
                "Identifiants envoyés par email à {$compteGenere->email}."
            );
        } catch (\Throwable $e) {
            Log::error(
                'Échec envoi identifiants : ' . $e->getMessage()
            );

            return back()->with(
                'error',
                "Échec de l'envoi de l'email : " . $e->getMessage()
            );
        }
    }

    /**
     * Envoie les identifiants par email à tous les comptes d'un rôle.
     */
    public function envoyerGroupe(Request $request)
    {
        $request->validate([
            'role' => [
                'required',
                'string',
                'in:' . implode(',', $this->rolesAutorises()),
            ],
        ]);

        $comptes = CompteGenere::query()
            ->where('role', $request->input('role'))
            ->whereNotNull('email')
            ->where('email', '<>', '')
            ->get();

        $envoyes = 0;
        $echecs = 0;

        foreach ($comptes as $compte) {
            try {
                Mail::to($compte->email)->send(
                    new \App\Mail\IdentifiantsAccesMail($compte)
                );

                $compte->update([
                    'envoye_le' => now(),
                ]);

                $envoyes++;
            } catch (\Throwable $e) {
                $echecs++;

                Log::error(
                    "Échec envoi identifiants pour {$compte->email} : "
                    . $e->getMessage()
                );
            }
        }

        $message = "{$envoyes} email(s) envoyé(s) avec succès.";

        if ($echecs > 0) {
            $message .= " {$echecs} échec(s).";
        }

        return back()->with('success', $message);
    }

    /**
     * Envoie les identifiants par SMS.
     */
    public function envoyerParSms(
        CompteGenere $compteGenere,
        SmsService $sms
    ) {
        $user = $compteGenere->user;
        $telephone = $user?->telephone;

        if (!$telephone) {
            return back()->with(
                'error',
                'Aucun numéro de téléphone associé à ce compte.'
            );
        }

        $message = $sms->messageIdentifiants(
            $compteGenere->email,
            $compteGenere->mot_de_passe
        );

        $envoye = $sms->envoyer($telephone, $message);

        if ($envoye) {
            return back()->with(
                'success',
                "SMS envoyé à {$telephone}."
            );
        }

        return back()->with(
            'error',
            "Échec de l'envoi du SMS. Vérifiez la configuration Twilio."
        );
    }

    /**
     * Supprime un compte généré.
     */
    public function destroy(CompteGenere $compteGenere)
    {
        $compteGenere->delete();

        return back()->with(
            'success',
            'Entrée supprimée.'
        );
    }
}