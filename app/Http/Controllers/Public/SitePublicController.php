<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use App\Models\FiliereUniversite;
use App\Models\AtoutUniversite;
use App\Models\PartenaireUniversite;
use App\Models\Niveau;
use App\Models\Section;

class SitePublicController extends Controller
{
public function accueil()
{
    $etablissement = Etablissement::instance();
    $atouts        = AtoutUniversite::orderBy('ordre')->get();
    $partenaires   = PartenaireUniversite::orderBy('ordre')->get();

    $evenements = \App\Models\Evenement::where('publie', true)
        ->orderByDesc('date_evenement')
        ->take(6)
        ->get();

    $totalEleves     = \App\Models\Eleve::where('statut', 'actif')->count();
    $totalClasses    = \App\Models\ClasseModel::where('annee_scolaire_id', \App\Models\AnneeScolaire::getActive()?->id)->count();
    $totalFilieres   = \App\Models\FiliereUniversite::count();

    return view('public.accueil', compact(
        'etablissement', 'atouts', 'partenaires', 'evenements',
        'totalEleves', 'totalClasses', 'totalFilieres'
    ));
}

    /** Page "Le Secondaire" — niveaux, sections, filière collège/lycée */
    public function secondaire()
    {
        $etablissement = Etablissement::instance();
        $sections      = Section::with('niveaux')->get();
        $niveaux       = Niveau::orderBy('section_id')->orderBy('ordre')->get()->groupBy('section.nom');

        return view('public.secondaire', compact('etablissement', 'sections', 'niveaux'));
    }

    /** Page "Université / ISSPED" — cycles, filières, coûts, atouts */
    public function universite()
    {
        $etablissement = Etablissement::instance();

        $filieresBts     = FiliereUniversite::cycle('bts_hnd')->get();
        $filieresLicence = FiliereUniversite::cycle('licence')->get();
        $filieresMaster  = FiliereUniversite::cycle('master')->get();

        $atouts      = AtoutUniversite::orderBy('ordre')->get();
        $partenaires = PartenaireUniversite::orderBy('ordre')->get();

        return view('public.universite', compact(
            'etablissement', 'filieresBts', 'filieresLicence', 'filieresMaster', 'atouts', 'partenaires'
        ));
    }

    /** Page Admissions — dossier requis, tarifs, durée */
    // public function admissions()
    // {
    //     $etablissement = Etablissement::instance();
    //     return view('public.admissions', compact('etablissement'));
    // }

    public function contact()
    {
        $etablissement = Etablissement::instance();
        return view('public.contact', compact('etablissement'));
    }
}