<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompteGenere;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompteGenereController extends Controller
{
    public function index(Request $request)
    {
        // Aucun affichage tant qu'aucun rôle n'est sélectionné
        if (!$request->filled('role')) {
            $comptes = CompteGenere::whereRaw('1=0')->paginate(30);
            return view('admin.comptes-generes.index', compact('comptes'));
        }

        $query = CompteGenere::where('role', $request->role);

        if ($request->filled('statut')) {
            $request->statut === 'exporte'
                ? $query->where('exporte', true)
                : $query->where('exporte', false);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nom', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
        }

        $comptes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.comptes-generes.index', compact('comptes'));
    }

    /** Export PDF de tous les comptes filtrés */ 
    public function exportPdf(Request $request)
    {
        $query = CompteGenere::query();
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('non_exportes_only')) $query->where('exporte', false);

        $comptes = $query->orderBy('role')->orderBy('nom')->get();

        $pdf = Pdf::loadView('admin.comptes-generes.pdf', [
            'comptes'       => $comptes,
            'etablissement' => Etablissement::instance(),
        ])->setPaper('a4', 'portrait');

        // Marquer comme exportés
        CompteGenere::whereIn('id', $comptes->pluck('id'))
            ->update(['exporte' => true, 'exporte_le' => now()]);

        return $pdf->stream('comptes_acces_' . now()->format('Ymd_His') . '.pdf');
    }

    /** Export Excel (CSV) */
    public function exportExcel(Request $request)
    {
        $query = CompteGenere::query();
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('non_exportes_only')) $query->where('exporte', false);

        $comptes = $query->orderBy('role')->orderBy('nom')->get();

        $filename = 'comptes_acces_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($comptes) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($file, ['Nom', 'Email', 'Mot de passe', 'Rôle', 'Élève lié', 'Date création'], ';');

            foreach ($comptes as $c) {
                fputcsv($file, [
                    $c->nom,
                    $c->email,
                    $c->mot_de_passe,
                    ucfirst(str_replace('_', ' ', $c->role)),
                    $c->eleve_lie ?? '-',
                    $c->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($file);
        };

        // Marquer comme exportés
        CompteGenere::whereIn('id', $comptes->pluck('id'))
            ->update(['exporte' => true, 'exporte_le' => now()]);

        return response()->stream($callback, 200, $headers);
    }

    /** Purge des comptes déjà exportés (sécurité) */
    public function purger(Request $request)
    {
        $request->validate(['confirmation' => 'required|accepted']);

        $nb = CompteGenere::where('exporte', true)->delete();

        return back()->with('success', "{$nb} mot(s) de passe purgé(s) de la base pour des raisons de sécurité.");
    }

    public function destroy(CompteGenere $compteGenere)
    {
        $compteGenere->delete();
        return back()->with('success', 'Entrée supprimée.');
    }
}