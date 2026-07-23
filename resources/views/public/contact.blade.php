@extends('layouts.public')
@section('title', 'Contact')

@section('content')
<div class="container">

    <!-- HEADER -->
    <div class="topbar">
        <div class="title">Contactez-nous</div>
    </div>

    <!-- INTRO -->
    <div class="section">
        <div class="card">
            <div class="value" style="color:#4b5563;">
                Une question sur l'inscription, les programmes ou la vie scolaire ? Nous sommes à votre écoute.
            </div>
        </div>
    </div>

    <div class="grid" style="display:grid; grid-template-columns: 1fr; gap: 24px;">
        <div class="grid-card" style="grid-column: 1 / -1;">

            <div class="card">
                <div class="label">Adresse</div>
                <div class="value">Douala, Cameroun</div>
            </div>

            <div class="card">
                <div class="label">Téléphone / WhatsApp</div>
                <div class="value">+237 691 975 928</div>
            </div>

            <div class="card">
                <div class="label">Email</div>
                <div class="value">contact@etablissement.cm</div>
            </div>

            <div class="card">
                <div class="label">Horaires d'ouverture</div>
                <div class="value">
                    Lundi — Vendredi : 7h30 — 16h00<br>
                    Samedi : 8h00 — 12h00
                </div>
            </div>

        </div>
    </div>

    <!-- FORMULAIRE -->
    <div class="section">
        <div class="section-title">Envoyez-nous un message</div>

        <div class="card">
            <div class="value" style="color:#6b7280; margin-bottom: 18px;">
                Nous répondons généralement dans les 24 heures.
            </div>

            @if (session('success'))
                <div class="card" style="margin-bottom: 16px; border-left: 4px solid #16a34a;">
                    <div class="value" style="color:#166534;">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('public.contact') }}">
                @csrf

                <div class="grid-card">
                    <div class="card">
                        <div class="label">Nom complet <span class="required">*</span></div>
                        <input type="text" name="nom" value="{{ old('nom') }}" class="form-control" required>
                        @error('nom') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="card">
                        <div class="label">Téléphone <span class="required">*</span></div>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" class="form-control" required>
                        @error('telephone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="card">
                        <div class="label">Email</div>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="card">
                        <div class="label">Sujet <span class="required">*</span></div>
                        <select name="sujet" required class="form-control">
                            <option value="">-- Choisir --</option>
                            <option value="inscription" {{ old('sujet') === 'inscription' ? 'selected' : '' }}>Question sur l'inscription</option>
                            <option value="programme" {{ old('sujet') === 'programme' ? 'selected' : '' }}>Programmes pédagogiques</option>
                            <option value="frais" {{ old('sujet') === 'frais' ? 'selected' : '' }}>Frais de scolarité</option>
                            <option value="autre" {{ old('sujet') === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('sujet') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="card" style="grid-column: 1 / -1;">
                        <div class="label">Message <span class="required">*</span></div>
                        <textarea name="message" rows="5" required class="form-control">{{ old('message') }}</textarea>
                        @error('message') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="action-bar">
                    <button type="submit" class="btn-submit">
                        Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CARTE -->
    <div class="section">
        <div class="section-title">Localisation</div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <iframe
                src="https://www.google.com/maps?q=Douala,Cameroun&output=embed"
                width="100%"
                height="300"
                style="border:0;"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

</div>
@endsection