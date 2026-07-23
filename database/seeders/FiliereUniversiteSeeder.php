<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FiliereUniversite;
use App\Models\AtoutUniversite;

class FiliereUniversiteSeeder extends Seeder
{
    public function run(): void
    {
        // Licences
        $licences = [
            'Licence en Kinésithérapie', 'Licence en imagerie médicale', 'Licence en optique et Réfraction',
            'Licence en Sciences Infirmières', 'Licence en Analyses Biomédicales', 'Licence en Techniques Pharmaceutiques',
            'Licence en Banque et Finance', 'Licence en Management', 'Licence en Gestion Logistique et Transport',
            'Licence en Santé de Reproduction',
        ];
        foreach ($licences as $i => $nom) {
            FiliereUniversite::create(['cycle' => 'licence', 'nom' => $nom, 'ordre' => $i]);
        }

        // Masters
        $masters = [
            'Master en management des Etablissements Médico-Sanitaires (MEMS)',
            'Master en Santé Publique (Epidémiologie, Santé Communautaire)',
            'Master en Biologie-Clinique (Hématologie-Immunologie, Bactério-Virologie, Biochimie)',
            'Master en Sciences Infirmières (Anesthésie-Réanimation)',
            'Master of Business Administration (MBA)',
            'Master en Comptabilité Contrôle Audit (CCA)',
            'Master en Gestion Logistique et Transport (GLT)',
            'Master en Santé de reproduction',
        ];
        foreach ($masters as $i => $nom) {
            FiliereUniversite::create(['cycle' => 'master', 'nom' => $nom, 'ordre' => $i]);
        }

        // BTS/HND par catégorie
        $bts = [
            'Gestion & Management' => ['Banque et Finance', 'Douane et Transit', 'Comptabilité et Gestion des entreprises', 'Gestion Logistique et Transport'],
            'Santé & Paramédical'  => ['Sciences infirmières', 'Kinésithérapie', 'Sages-femmes', 'Auxiliaire de vie sociale', 'Sciences Pharmaceutiques'],
            'Bien-Être & Beauté'   => ['Coiffure Professionnelle', 'Esthétique cosmétique'],
            'Informatique et TIC'  => ['Génie logiciel', 'Infographie et Web design', 'Webmaster', 'Marketing Digital et E-commerce', 'Maintenance des systèmes informatiques'],
        ];
        $ordre = 0;
        foreach ($bts as $categorie => $noms) {
            foreach ($noms as $nom) {
                FiliereUniversite::create(['cycle' => 'bts_hnd', 'categorie' => $categorie, 'nom' => $nom, 'ordre' => $ordre++]);
            }
        }

        // Atouts
        $atouts = [
            ['Bibliothèque', 'book-open'],
            ['Cours du Jour, du soir et en ligne', 'clock'],
            ['Cours flexibles et Enseignants qualifiés', 'graduation-cap'],
            ['Transport étudiants gratuit', 'bus'],
            ['Support de cours pris en charge', 'file-text'],
            ['Salles climatisées', 'wind'],
            ['Excursion en immersion professionnelle', 'briefcase'],
            ['Wifi illimité', 'wifi'],
            ['Laboratoire de recherche', 'flask-conical'],
            ['Plaque solaire disponible', 'sun'],
            ['Stage pris en charge', 'award'],
        ];
        foreach ($atouts as $i => [$libelle, $icone]) {
            AtoutUniversite::create(['libelle' => $libelle, 'icone' => $icone, 'ordre' => $i]);
        }
    }
}