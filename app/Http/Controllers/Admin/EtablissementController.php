<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtablissementController extends Controller
{
    public function edit()
    {
        $etablissement = Etablissement::first();

        if (!$etablissement) {
            $etablissement = Etablissement::create([]);
        }

        return view('admin.etablissement.edit', compact('etablissement'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nom'                       => 'required|string|max:200',
            'sigle'                     => 'nullable|string|max:20',
            'adresse'                   => 'nullable|string|max:255',
            'ville'                     => 'nullable|string|max:100',
            'pays'                      => 'nullable|string|max:100',
            'telephone'                 => 'nullable|string|max:20',
            'telephone2'                => 'nullable|string|max:20',
            'email'                     => 'nullable|email|max:150',
            'site_web'                  => 'nullable|url|max:200',
            'bp'                        => 'nullable|string|max:50',
            'region'                    => 'nullable|string|max:100',
            'ministre_tutelle'          => 'nullable|string|max:100',
            'ordre_enseignement'        => 'nullable|string|max:100',
            'type_etablissement'        => 'nullable|string|max:100',
            'code_etablissement'        => 'nullable|string|max:50',
            'devise'                    => 'nullable|string|max:255',
            'logo'                      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'signature_proviseur'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',

            'nom_universite'            => 'nullable|string|max:200',
            'sigle_universite'          => 'nullable|string|max:20',
            'logo_universite'           => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'autorisation_minesup'      => 'nullable|string|max:150',
            'universite_partenaire'     => 'nullable|string|max:150',
            'logo_universite_partenaire'=> 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'annee_academique'          => 'nullable|string|max:20',
            'campus'                    => 'nullable|string|max:1000',
            'telephones_universite'     => 'nullable|string|max:150',
            'email_universite'          => 'nullable|email|max:150',
            'facebook_universite'       => 'nullable|string|max:150',
        ]);

        $etablissement = Etablissement::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            if ($etablissement->logo && Storage::disk('public')->exists($etablissement->logo)) {
                Storage::disk('public')->delete($etablissement->logo);
            }
            $validated['logo'] = $request->file('logo')->store('etablissement', 'public');
        }

        if ($request->hasFile('logo_universite')) {
            if ($etablissement->logo_universite && Storage::disk('public')->exists($etablissement->logo_universite)) {
                Storage::disk('public')->delete($etablissement->logo_universite);
            }
            $validated['logo_universite'] = $request->file('logo_universite')->store('etablissement', 'public');
        }

        if ($request->hasFile('logo_universite_partenaire')) {
            if ($etablissement->logo_universite_partenaire && Storage::disk('public')->exists($etablissement->logo_universite_partenaire)) {
                Storage::disk('public')->delete($etablissement->logo_universite_partenaire);
            }
            $validated['logo_universite_partenaire'] = $request->file('logo_universite_partenaire')->store('etablissement', 'public');
        }

        if ($request->hasFile('signature_proviseur')) {
    if ($etablissement?->signature_proviseur) {
        Storage::disk('public')->delete($etablissement->signature_proviseur);
    }
    $validated['signature_proviseur'] = $request->file('signature_proviseur')->store('signatures', 'public');
}

        $etablissement->update($validated);

        return back()->with('success', "Informations de l'établissement mises à jour.");
    }
}