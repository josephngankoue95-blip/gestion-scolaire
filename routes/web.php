<?php

use Illuminate\Support\Facades\Route;

// Profile (Breeze)
use App\Http\Controllers\Admin\AnneeScolaireController;

// Admin
use App\Http\Controllers\Proviseur\ProviseurController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\NiveauController;
use App\Http\Controllers\Admin\EleveController;
use App\Http\Controllers\Admin\TransfertController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\Admin\ScolariteController;
use App\Http\Controllers\Admin\FraisScolariteController;
use App\Http\Controllers\Admin\ZoneTransportController;
use App\Http\Controllers\Admin\InscriptionScolariteController;
use App\Http\Controllers\Admin\PaiementEleveController;
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\Admin\AffectationController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\GroupeMatiereController;
use App\Http\Controllers\Admin\ClasseMatiereController;
use App\Http\Controllers\Admin\EtablissementController;
use App\Http\Controllers\Surveillant\EmploiTempsController as SurveillantEmploiController;
use App\Http\Controllers\Admin\TableauHonneurController;
use App\Http\Controllers\Admin\ProcesVerbalController;
use App\Http\Controllers\Admin\ReleveNoteController;
use App\Http\Controllers\Admin\CartesScolairesController;
use App\Http\Controllers\Admin\BulletinController;
use App\Http\Controllers\Admin\BulletinSelectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaiementScolariteController;
use App\Http\Controllers\Admin\RequeteController;
use App\Http\Controllers\Eleve\EleveSpaceController;
use App\Http\Controllers\Secretaire\SecretaireController;
use App\Http\Controllers\Admin\CompteGenereController;
use App\Http\Controllers\Bibliotheque\LivreController;
use App\Http\Controllers\Bibliotheque\EmpruntController;
use App\Http\Controllers\Parent\PaiementMobileController;
use App\Http\Controllers\Admin\PaiementMobileAdminController;
use App\Http\Controllers\Public\SitePublicController;
use App\Http\Controllers\Admin\EvenementController;

//Notes & Bulletins
use App\Http\Controllers\Teacher\SaisieNoteController;
use App\Http\Controllers\Teacher\TravailDirigeController;


//Gestion des utilisateurs
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggle');
    Route::get('users/eleves-par-classe', [UserController::class, 'elevesByClasse'])->name('users.elevesByClasse');
});

/**PROVISEUR */
Route::middleware(['auth','role:proviseur'])->prefix('proviseur')->name('proviseur.')->group(function () {
    Route::get('dashboard',     [ProviseurController::class, 'dashboard'])->name('dashboard');
    Route::get('scolarite',     [ProviseurController::class, 'scolarite'])->name('scolarite');
    Route::get('performances',  [ProviseurController::class, 'performances'])->name('performances');
    Route::get('absences',      [ProviseurController::class, 'absences'])->name('absences');
    Route::get('enseignants',   [ProviseurController::class, 'enseignants'])->name('enseignants');
    Route::get('rapport',       [ProviseurController::class, 'rapportPdf'])->name('rapport');
});

/**BiBLIOTHEQUE */
Route::middleware(['auth','role:admin|bibliothecaire'])->prefix('bibliotheque')->name('bibliotheque.')->group(function () {
    Route::get('dashboard', [LivreController::class,'dashboard'])->name('dashboard');

    Route::resource('livres', LivreController::class)->except(['show']);
    Route::resource('emprunts', EmpruntController::class)->only(['index','create','store']);
    Route::post('emprunts/{emprunt}/retour', [EmpruntController::class,'retourner'])->name('emprunts.retour');
    Route::post('emprunts/{emprunt}/perdu', [EmpruntController::class,'declarerPerdu'])->name('emprunts.perdu');
});

/**Paiement Mobile Money */
Route::middleware(['auth','role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('paiement-momo', [PaiementMobileController::class,'create'])->name('paiement-momo.create');
    Route::post('paiement-momo', [PaiementMobileController::class,'store'])->name('paiement-momo.store');
    Route::get('paiement-momo/historique', [PaiementMobileController::class,'historique'])->name('paiement-momo.historique');
});

Route::middleware(['auth','role:admin|secretaire_intendant'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('paiements-momo', [PaiementMobileAdminController::class,'index'])->name('paiements-momo.index');
    Route::post('paiements-momo/{paiement}/valider', [PaiementMobileAdminController::class,'valider'])->name('paiements-momo.valider');
    Route::post('paiements-momo/{paiement}/rejeter', [PaiementMobileAdminController::class,'rejeter'])->name('paiements-momo.rejeter');
});

//Route SColarité
Route::middleware(['auth','role:admin|proviseur|secretaire_intendant'])->prefix('admin')->name('admin.')->group(function () {

    // Frais scolarité
    Route::get('scolarite/frais', [FraisScolariteController::class, 'index'])->name('scolarite.frais.index');
    Route::post('scolarite/frais', [FraisScolariteController::class, 'store'])->name('scolarite.frais.store');
    Route::put('scolarite/frais/{fraisScolarite}', [FraisScolariteController::class, 'update'])->name('scolarite.frais.update');
    Route::delete('scolarite/frais/{fraisScolarite}', [FraisScolariteController::class, 'destroy'])->name('scolarite.frais.destroy');
    Route::get('scolarite/frais/pour-classe', [FraisScolariteController::class, 'pourClasse'])->name('scolarite.frais.pour-classe');

    // Zones transport
    Route::get('scolarite/transport', [ZoneTransportController::class, 'index'])->name('scolarite.transport.index');
    Route::post('scolarite/transport', [ZoneTransportController::class, 'store'])->name('scolarite.transport.store');
    Route::put('scolarite/transport/{zoneTransport}', [ZoneTransportController::class, 'update'])->name('scolarite.transport.update');
    Route::delete('scolarite/transport/{zoneTransport}', [ZoneTransportController::class, 'destroy'])->name('scolarite.transport.destroy');

    // Scolarité élèves
    Route::get('scolarite', [ScolariteController::class, 'index'])->name('scolarite.index');
    Route::get('scolarite/create', [ScolariteController::class, 'create'])->name('scolarite.create');
    Route::post('scolarite', [ScolariteController::class, 'store'])->name('scolarite.store');
    Route::get('scolarite/{scolarite}', [ScolariteController::class, 'show'])->name('scolarite.show');
    Route::delete('scolarite/{scolarite}', [ScolariteController::class, 'destroy'])->name('scolarite.destroy');

    // Paiements
    Route::post('scolarite/{scolarite}/paiements', [PaiementScolariteController::class, 'store'])->name('scolarite.paiements.store');
    Route::get('scolarite/paiements/{paiementScolarite}/recu', [PaiementScolariteController::class, 'recu'])->name('scolarite.paiements.recu');
    Route::delete('scolarite/paiements/{paiementScolarite}', [PaiementScolariteController::class, 'destroy'])->name('scolarite.paiements.destroy');
});

/**Transfert Vers une classe Supérieure */
Route::middleware(['auth','role:admin|proviseur|secretaire_intendant'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('transferts', [TransfertController::class, 'index'])->name('transferts.index');
    Route::post('transferts', [TransfertController::class, 'store'])->name('transferts.store');
    Route::delete('transferts/{transfert}', [TransfertController::class, 'destroy'])->name('transferts.destroy');
    Route::get('transferts/eleves-classe', [TransfertController::class, 'elevesClasse'])->name('transferts.eleves-classe');
});

/**SECRETAIRE */
Route::middleware(['auth','role:secretaire_intendant'])->prefix('secretaire')->name('secretaire.')->group(function () {
    Route::get('dashboard', [SecretaireController::class, 'dashboard'])->name('dashboard');
    Route::get('scolarite', [SecretaireController::class, 'scolarite'])->name('scolarite');
    Route::get('scolarite/create', [SecretaireController::class, 'createScolarite'])->name('scolarite.create');
    Route::post('scolarite', [SecretaireController::class, 'storeScolarite'])->name('scolarite.store');
    Route::get('scolarite/{scolarite}', [SecretaireController::class, 'showScolarite'])->name('scolarite.show');
    Route::post('scolarite/{scolarite}/paiement', [SecretaireController::class, 'paiement'])->name('scolarite.paiement');
    Route::get('requetes', [SecretaireController::class, 'requetes'])->name('requetes');
    Route::post('requetes/{requete}/traiter', [SecretaireController::class, 'traiterRequete'])->name('requetes.traiter');
    Route::get('profil', [SecretaireController::class, 'profil'])->name('profil');
    Route::post('profil', [SecretaireController::class, 'updateProfil'])->name('profil.update');
    Route::get('scolarite/frais/pour-classe', [SecretaireController::class, 'fraisPourClasse'])->name('scolarite.frais.pour-classe');
});


//Tableaux d'honneur
Route::middleware(['auth', 'role:admin|proviseur|prefecture_etudes'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('tableaux-honneur', [TableauHonneurController::class, 'index'])->name('tableaux-honneur.index');
    Route::get('tableaux-honneur/show', [TableauHonneurController::class, 'show'])->name('tableaux-honneur.show');
    Route::get('tableaux-honneur/imprimer', [TableauHonneurController::class, 'imprimer'])->name('tableaux-honneur.imprimer');

    Route::get('proces-verbaux', [ProcesVerbalController::class, 'index'])->name('proces-verbaux.index');
    Route::get('proces-verbaux/show', [ProcesVerbalController::class, 'show'])->name('proces-verbaux.show');
    Route::get('proces-verbaux/imprimer', [ProcesVerbalController::class, 'imprimer'])->name('proces-verbaux.imprimer');
});

//Releve de note 
Route::middleware(['auth','role:admin|secretaire_intendant|prefecture_etudes'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('releves', [ReleveNoteController::class, 'index'])->name('releves.index');
    Route::get('releves/classe', [ReleveNoteController::class, 'parClasse'])->name('releves.classe');
    Route::get('releves/enseignant', [ReleveNoteController::class, 'parEnseignant'])->name('releves.enseignant');
});

//Carte Scolaire
Route::middleware(['auth','role:admin|secretaire_intendant|prefecture_etudes'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('cartes-scolaires', [CartesScolairesController::class, 'index'])->name('cartes-scolaires.index');
    Route::get('cartes-scolaires/imprimer', [CartesScolairesController::class, 'imprimer'])->name('cartes-scolaires.imprimer');
});

/**TRAVAUX DIRIGES */
Route::middleware(['auth','role:enseignant'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::resource('td', TravailDirigeController::class)->except(['index'])->names([
        'create'  => 'td.create',
        'store'   => 'td.store',
        'show'    => 'td.show',
        'edit'    => 'td.edit',
        'update'  => 'td.update',
        'destroy' => 'td.destroy',
    ]);
    Route::get('td', [TravailDirigeController::class, 'index'])->name('td.index');
}); 

// Accessible aussi par les parents (via route publique auth)
Route::middleware(['auth'])->get('td/{travailDirige}/consulter', [TravailDirigeController::class, 'consulter'])->name('td.consulter');

//Emploi du temps, Absences, Dashboard

use App\Http\Controllers\Surveillant\AbsenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\EmploiTempsController;

//Site vitrine & Candidatures
use App\Http\Controllers\Public\CandidatureController as PublicCandidatureController;
use App\Http\Controllers\Admin\CandidatureController as AdminCandidatureController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES — SITE VITRINE (sans authentification)
|--------------------------------------------------------------------------
*/
Route::name('public.')->group(function () {
    Route::get('/', function () {
        return view('public.home'); })->name('home');
    Route::get('/a-propos', function () {
        return view('public.about'); })->name('about');
    Route::get('/contact', function () {
        return view('public.contact'); })->name('contact');

    Route::get('/candidature', [PublicCandidatureController::class, 'create'])->name('candidature.create');
    Route::post('/candidature', [PublicCandidatureController::class, 'store'])->name('candidature.store');
    Route::get('/candidature/suivi/{reference}', [PublicCandidatureController::class, 'suivi'])->name('candidature.suivi');
    Route::post('/candidature/suivi', [PublicCandidatureController::class, 'rechercherSuivi'])->name('candidature.suivi.search');
});

Route::post('/contact', [\App\Http\Controllers\Public\ContactController::class, 'send'])->name('contact.send');

/**SITE PUBLIQUE */
Route::name('public.')->group(function () {
    Route::get('/', [SitePublicController::class, 'accueil'])->name('home');
    Route::get('/secondaire', [SitePublicController::class, 'secondaire'])->name('secondaire');
    Route::get('/universite', [SitePublicController::class, 'universite'])->name('universite');
    Route::get('/admissions', [SitePublicController::class, 'admissions'])->name('admissions');
    Route::get('/contact', [SitePublicController::class, 'contact'])->name('contact');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('evenements', \App\Http\Controllers\Admin\EvenementController::class)->except(['show']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

Route::middleware(['auth', 'role:surveillant_general|admin|proviseur'])
    ->prefix('surveillant')->name('surveillant.')->group(function () {
        // Absences (déjà présentes)
        Route::get('absences', [AbsenceController::class, 'index'])->name('absences.index');
        Route::get('absences/classe/{classe}', [AbsenceController::class, 'create'])->name('absences.create');
        Route::post('absences/classe/{classe}', [AbsenceController::class, 'store'])->name('absences.store');
        Route::post('absences/{absence}/justifier', [AbsenceController::class, 'justifier'])->name('absences.justifier');
        Route::delete('absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');

        // Emploi du temps (lecture seule)
        Route::get('emplois-temps', [SurveillantEmploiController::class, 'index'])->name('emplois-temps.index');
        Route::get('emplois-temps/{classe}', [SurveillantEmploiController::class, 'show'])->name('emplois-temps.show');
    });

/*
|--------------------------------------------------------------------------
| DASHBOARD DYNAMIQUE (selon rôle)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN : Classes, Élèves, Enseignants, Affectations
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);

    Route::resource('eleves', EleveController::class)->parameters(['eleves' => 'eleve']);
    Route::resource('enseignants', EnseignantController::class);
    Route::get('/admin/enseignants', [EnseignantController::class, 'index'])->name('admin.enseignants.index');

});

/**CompteGeneration */
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('comptes-generes', [CompteGenereController::class, 'index'])->name('comptes-generes.index');
    Route::get('comptes-generes/export-pdf', [CompteGenereController::class, 'exportPdf'])->name('comptes-generes.export-pdf');
    Route::get('comptes-generes/export-excel', [CompteGenereController::class, 'exportExcel'])->name('comptes-generes.export-excel');
    Route::post('comptes-generes/purger', [CompteGenereController::class, 'purger'])->name('comptes-generes.purger');
    Route::delete('comptes-generes/{compteGenere}', [CompteGenereController::class, 'destroy'])->name('comptes-generes.destroy');
});

/**NIVEAUX DE CLASSE */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('niveaux', [NiveauController::class, 'index'])->name('niveaux.index');
    Route::post('niveaux', [NiveauController::class, 'store'])->name('niveaux.store');
    Route::put('niveaux/{niveau}', [NiveauController::class, 'update'])->name('niveaux.update');
    Route::delete('niveaux/{niveau}', [NiveauController::class, 'destroy'])->name('niveaux.destroy');
    Route::get('niveaux/par-section', [NiveauController::class, 'parSection'])->name('niveaux.par-section');
});

// Espace élève
Route::middleware(['auth', 'role:eleve'])->prefix('eleve')->name('eleve.')->group(function () {
    Route::get('dashboard', [EleveSpaceController::class, 'dashboard'])->name('dashboard');

    Route::get('bulletins', [EleveSpaceController::class, 'bulletins'])->name('bulletins');
    Route::get('bulletins/voir', [EleveSpaceController::class, 'voirBulletin'])->name('bulletins.voir');

    Route::get('emploi-du-temps', [EleveSpaceController::class, 'emploiDuTemps'])->name('emploi-du-temps');

    Route::get('travaux', [EleveSpaceController::class, 'travaux'])->name('travaux');
    Route::get('travaux/{travailDirige}', [EleveSpaceController::class, 'voirTravail'])->name('travaux.show');

    Route::get('requetes', [EleveSpaceController::class, 'requetes'])->name('requetes');
    Route::post('requetes', [EleveSpaceController::class, 'storeRequete'])->name('requetes.store');

    Route::get('profil', [EleveSpaceController::class, 'profil'])->name('profil');
    Route::post('profil', [EleveSpaceController::class, 'updateProfil'])->name('profil.update');
});

// Gestion requêtes côté admin/secrétaire
Route::middleware(['auth','role:admin|proviseur|secretaire_intendant'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('requetes', [RequeteController::class, 'index'])->name('requetes.index');
    Route::get('requetes/{requete}', [RequeteController::class, 'show'])->name('requetes.show');
    Route::post('requetes/{requete}/traiter', [RequeteController::class, 'traiter'])->name('requetes.traiter');
});

/* AFFECTATION ENSEIGNANT */
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('enseignants/{enseignant}/affectations',[AffectationController::class, 'index'])->name('enseignants.affectations');
    Route::post('enseignants/{enseignant}/affectations',[AffectationController::class, 'store'])->name('enseignants.affectations.store');
    Route::delete('enseignants/{enseignant}/affectations/{affectation}',[AffectationController::class, 'destroy'])->name('enseignants.affectations.destroy');
    });

/*CLASSE EN FONCTION DES MATIERES */
Route::middleware(['auth', 'role:admin|proviseur'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('classes/{classe}/matieres', [ClasseMatiereController::class, 'index'])->name('classes.matieres.index');
    Route::post('classes/{classe}/matieres', [ClasseMatiereController::class, 'store'])->name('classes.matieres.store');
    Route::patch('classes/{classe}/matieres/{matiere}', [ClasseMatiereController::class, 'update'])->name('classes.matieres.update');
    Route::delete('classes/{classe}/matieres/{matiere}', [ClasseMatiereController::class, 'destroy'])->name('classes.matieres.destroy');
    Route::post('classes/{classe}/matieres/importer-groupe', [ClasseMatiereController::class, 'importerGroupe'])->name('classes.matieres.importer');
});    

/*MATIERE/GROUPE MATIERE */
Route::middleware(['auth', 'role:admin|proviseur'])->prefix('admin')->name('admin.')->group(function () {
    // Matières
    Route::resource('matieres', MatiereController::class)->except(['show']);

});


/* CONTROLEUR ANNEE SCOLAIRE */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('annees-scolaires', [AnneeScolaireController::class, 'index'])->name('annees-scolaires.index');
    Route::get('annees-scolaires/create', [AnneeScolaireController::class, 'create'])->name('annees-scolaires.create');
    Route::post('annees-scolaires', [AnneeScolaireController::class, 'store'])->name('annees-scolaires.store');
    Route::post('annees-scolaires/{anneeScolaire}/activer', [AnneeScolaireController::class, 'activer'])->name('annees-scolaires.activer');
    Route::delete('annees-scolaires/{anneeScolaire}', [AnneeScolaireController::class, 'destroy'])->name('annees-scolaires.destroy');
});

/*
|--------------------------------------------------------------------------
| ENSEIGNANT : Saisie de notes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:enseignant'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('dashboard', [SaisieNoteController::class, 'dashboard'])->name('dashboard');
    Route::get('saisie', [SaisieNoteController::class, 'index'])->name('saisie.index');
    Route::get('saisie/form', [SaisieNoteController::class, 'saisir'])->name('saisie.form');
    Route::post('saisie', [SaisieNoteController::class, 'store'])->name('saisie.store');
    Route::get('saisie/evaluations', [SaisieNoteController::class, 'evaluations'])->name('saisie.evaluations');
    // AJAX
    Route::get('ajax/classes', [SaisieNoteController::class, 'classesBySection'])->name('ajax.classes');
    Route::get('ajax/matieres', [SaisieNoteController::class, 'matieresByClasse'])->name('ajax.matieres');
});

/**BULLETINS PDF */
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

        // Page principale des bulletins
        Route::get('/bulletins', [BulletinController::class, 'index'])->name('bulletins.index');

        // Liste des élèves
        Route::get('/bulletins/eleves', [BulletinController::class, 'eleves'])->name('bulletins.eleves');

        // Bulletin de séquence
        Route::get('/bulletins/{eleve}/sequence/{sequence}', [BulletinController::class, 'sequence'])
            ->name('bulletins.sequence');

        // Bulletin de trimestre
        Route::get('/bulletins/{eleve}/trimestre/{trimestre}', [BulletinController::class, 'trimestre'])
            ->name('bulletins.trimestre');

        // Bulletin annuel
        Route::get('/bulletins/{eleve}/annuel', [BulletinController::class, 'annuel'])->name('bulletins.annuel');
            Route::get('/bulletins/tous', [BulletinController::class, 'tous'])
                ->name('bulletins.imprimerTous');
    });

/**CONSULTATION BULLETIN PARENT */
Route::middleware(['auth','role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('dashboard', [ParentController::class, 'dashboard'])->name('dashboard');
    Route::get('bulletins', [ParentController::class, 'bulletins'])->name('bulletins');
    Route::get('bulletins/{eleve}/voir', [ParentController::class, 'voirBulletin'])->name('bulletins.voir');
    Route::get('emploi-du-temps', [ParentController::class, 'emploiDuTemps'])->name('emploi-du-temps');
    Route::get('absences', [ParentController::class, 'absences'])->name('absences');
    Route::get('scolarite', [ParentController::class, 'scolarite'])->name('scolarite');
});


/*
|--------------------------------------------------------------------------
| ADMIN : Emploi du temps
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('emplois-temps', [EmploiTempsController::class, 'index'])->name('emplois-temps.index');
    Route::post('emplois-temps', [EmploiTempsController::class, 'store'])->name('emplois-temps.store');
    Route::delete('emplois-temps/{emploiTemp}', [EmploiTempsController::class, 'destroy'])->name('emplois-temps.destroy');
});

/*
|--------------------------------------------------------------------------
| SURVEILLANT GÉNÉRAL : Absences
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:surveillant_general|admin'])->prefix('surveillant')->name('surveillant.')->group(function () {
    Route::get('absences', [AbsenceController::class, 'index'])->name('absences.index');
    Route::get('absences/classe/{classe}', [AbsenceController::class, 'create'])->name('absences.create');
    Route::post('absences/classe/{classe}', [AbsenceController::class, 'store'])->name('absences.store');
    Route::post('absences/{absence}/justifier', [AbsenceController::class, 'justifier'])->name('absences.justifier');
    Route::delete('absences/{absence}', [AbsenceController::class, 'destroy'])->name('absences.destroy');
});

/**Conseil de classe */
use App\Http\Controllers\Admin\ConseilClasseController;

Route::middleware(['auth', 'role:admin|proviseur|prefet_etudes'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('conseils', [ConseilClasseController::class, 'index'])->name('conseils.index');
    Route::get('conseils/create', [ConseilClasseController::class, 'create'])->name('conseils.create');
    Route::post('conseils', [ConseilClasseController::class, 'store'])->name('conseils.store');
    Route::get('conseils/{conseil}', [ConseilClasseController::class, 'show'])->name('conseils.show');
    Route::post('conseils/{conseil}/decision', [ConseilClasseController::class, 'storeDecision'])->name('conseils.decision');
    Route::post('conseils/{conseil}/cloturer', [ConseilClasseController::class, 'cloturer'])->name('conseils.cloturer');
    Route::get('conseils/{conseil}/pv', [ConseilClasseController::class, 'pvPdf'])->name('conseils.pv');
    Route::delete('conseils/{conseil}', [ConseilClasseController::class, 'destroy'])->name('conseils.destroy');
});

/**Prefet etudes controleur */
use App\Http\Controllers\Prefet\PrefetController;

Route::middleware(['auth', 'role:prefet_etudes'])->prefix('prefet')->name('prefet.')->group(function () {
    Route::get('dashboard', [PrefetController::class, 'dashboard'])->name('dashboard');

    Route::get('saisie', [PrefetController::class, 'saisieIndex'])->name('saisie.index');
    Route::get('saisie/form', [PrefetController::class, 'saisieForm'])->name('saisie.form');
    Route::post('saisie', [PrefetController::class, 'saisieStore'])->name('saisie.store');
    Route::get('ajax/matieres', [PrefetController::class, 'ajaxMatieres'])->name('ajax.matieres');
});

// Accès partagés avec l'admin (bulletins, tableau d'honneur, cartes scolaires, procès-verbaux)
Route::middleware(['auth', 'role:admin|proviseur|prefet_etudes'])->prefix('admin')->name('admin.')->group(function () {
    // Ces routes existent déjà (bulletins.*, tableaux-honneur.*, cartes-scolaires.*, proces-verbaux.*, conseils.*)
    // Il suffit d'ajouter 'prefet_etudes' au middleware role: existant sur ces groupes de routes.
});

/*
|--------------------------------------------------------------------------
| ADMIN : Candidatures en ligne
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|proviseur|secretaire_intendant|prefecture_etudes'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('candidatures', [AdminCandidatureController::class, 'index'])->name('candidatures.index');
    Route::get('candidatures/{candidature}', [AdminCandidatureController::class, 'show'])->name('candidatures.show');
    Route::post('candidatures/{candidature}/accepter', [AdminCandidatureController::class, 'accepter'])->name('candidatures.accepter');
    Route::post('candidatures/{candidature}/refuser', [AdminCandidatureController::class, 'refuser'])->name('candidatures.refuser');
    Route::post('candidatures/{candidature}/examen', [AdminCandidatureController::class, 'marquerEnExamen'])->name('candidatures.examen');
});

/** CONFIGURATION ETABLISSEMENT */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Afficher le formulaire
        Route::get('etablissement', [EtablissementController::class, 'edit'])->name('etablissement.edit');

        // Enregistrer les modifications
        Route::put('etablissement', [EtablissementController::class, 'update'])->name('etablissement.update');
    });