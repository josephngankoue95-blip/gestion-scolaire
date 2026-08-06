<?php
// app/Exports/ElevesExport.php
namespace App\Exports;

use App\Models\Eleve;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ElevesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function __construct(protected array $filtres = []) {}

    public function collection()
    {
        $query = Eleve::with('classe.section');

        if (!empty($this->filtres['classe_id'])) $query->where('classe_id', $this->filtres['classe_id']);
        if (!empty($this->filtres['statut'])) $query->where('statut', $this->filtres['statut']);
        if (!empty($this->filtres['search'])) {
            $s = $this->filtres['search'];
            $query->where(fn($q) => $q->where('nom','like',"%{$s}%")->orWhere('prenom','like',"%{$s}%")->orWhere('matricule','like',"%{$s}%"));
        }

        return $query->orderBy('nom')->get();
    }

    public function headings(): array
    {
        return ['Matricule','Nom','Prénom','Sexe','Date naissance','Lieu naissance','Classe','Section','Téléphone parent','Adresse','Statut'];
    }

    public function map($eleve): array
    {
        return [
            $eleve->matricule,
            $eleve->nom,
            $eleve->prenom,
            $eleve->sexe === 'M' ? 'Masculin' : 'Féminin',
            $eleve->date_naissance?->format('d/m/Y'),
            $eleve->lieu_naissance,
            $eleve->classe?->nom ?? 'Non inscrit',
            $eleve->classe?->section?->nom ?? '-',
            $eleve->telephone_parent,
            $eleve->adresse,
            ucfirst($eleve->statut),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getFill()->setFillType('solid')->getStartColor()->setRGB('1A3A6B');
        $sheet->getStyle('A1:K1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        return [];
    }

    public function columnWidths(): array
    {
        return ['A'=>15,'B'=>18,'C'=>18,'D'=>12,'E'=>14,'F'=>16,'G'=>14,'H'=>10,'I'=>16,'J'=>24,'K'=>12];
    }
}