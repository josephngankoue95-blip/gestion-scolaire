<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    protected $table = 'etablissement';

    protected $fillable = [
        //Secondaire
        'nom', 'sigle', 'logo', 'adresse', 'ville', 'pays',
        'telephone', 'telephone2', 'email', 'site_web', 'bp',
        'region', 'ministre_tutelle', 'ordre_enseignement',
        'type_etablissement', 'code_etablissement', 'devise',

        //Université
        'nom_universite',
        'sigle_universite',
        'logo_universite',
        'autorisation_minesup',
        'universite_partenaire',
        'logo_universite_partenaire',
        'annee_academique',
        'campus',
        'telephones_universite',
        'email_universite',
        'facebook_universite',
    ];

    /**
     * Retourne l'unique instance (singleton).
     * Si elle n'existe pas, retourne un objet vide.
     */
    public static function instance(): self
    {
        return static::first() ?? new self();
    }

public function logoUrl(): string
{
    return $this->logo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo)
        : asset('images/Logo.png');
}
}