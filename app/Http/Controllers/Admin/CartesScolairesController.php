<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClasseModel;
use App\Models\AnneeScolaire;
use App\Models\Etablissement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CartesScolairesController extends Controller
{
    public function index()
    {
        $annee   = AnneeScolaire::getActive();
        $classes = ClasseModel::where('annee_scolaire_id', $annee?->id)->with('section')->orderBy('niveau_id')->get();
        return view('admin.cartes-scolaires.index', compact('classes'));
    }

public function imprimer(Request $request)
{
    $request->validate([
        'classe_id' => 'required|exists:classes,id'
    ]);

    $classe = ClasseModel::with([
        'section',
        'anneeScolaire',
        'eleves' // ✅ IMPORTANT
    ])->findOrFail($request->classe_id);

    // ✅ LES ÉLÈVES VIENNENT DIRECTEMENT DE LA CLASSE
    $eleves = $classe->eleves->sortBy('nom');

    $pdf = Pdf::loadView('admin.cartes-scolaires.pdf', [
        'classe'        => $classe,
        'eleves'        => $eleves,
        'etablissement' => Etablissement::instance(),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream("cartes_{$classe->nom}.pdf");
}
}