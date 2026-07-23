@extends('layouts.public')
@section('title', 'À propos')

@section('content')

<!-- En-tête de page -->
<section class="bg-gradient-to-br from-primary-700 to-primary-900 text-white">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-16 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-3">À propos de notre établissement</h1>
        <p class="text-primary-100 max-w-2xl mx-auto">
            Découvrez notre histoire, nos valeurs et notre engagement envers l'excellence éducative.
        </p>
    </div>
</section>

<!-- Présentation générale -->
<section class="max-w-5xl mx-auto px-4 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Notre histoire</h2>
            <p class="text-gray-600 leading-relaxed mb-4">
                Notre établissement accueille les élèves du premier cycle au second cycle, en sections
                francophone et anglophone. Depuis sa création, il s'est donné pour mission de former des
                citoyens responsables, compétents et ouverts sur le monde.
            </p>
            <p class="text-gray-600 leading-relaxed">
                Implanté à Douala, notre lycée/collège accompagne chaque année des centaines de familles
                dans la réussite scolaire de leurs enfants, grâce à un encadrement pédagogique rigoureux
                et un suivi personnalisé.
            </p>
        </div>
        <div class="card">
            <div class="grid grid-cols-2 gap-6 text-center">
                <div>
                    <p class="text-3xl font-bold text-primary-600">2</p>
                    <p class="text-sm text-gray-500 mt-1">Sections (Fr/Ang)</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-primary-600">6</p>
                    <p class="text-sm text-gray-500 mt-1">Niveaux d'étude</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-primary-600">3</p>
                    <p class="text-sm text-gray-500 mt-1">Trimestres / an</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-primary-600">100%</p>
                    <p class="text-sm text-gray-500 mt-1">Suivi digitalisé</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission / Vision / Valeurs -->
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-10">Notre mission</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="card text-center">
                <i data-lucide="target" class="w-10 h-10 text-primary-600 mx-auto mb-3"></i>
                <h3 class="font-semibold mb-2">Mission</h3>
                <p class="text-sm text-gray-500">
                    Offrir un enseignement de qualité, accessible et adapté aux réalités bilingues du Cameroun.
                </p>
            </div>
            <div class="card text-center">
                <i data-lucide="eye" class="w-10 h-10 text-primary-600 mx-auto mb-3"></i>
                <h3 class="font-semibold mb-2">Vision</h3>
                <p class="text-sm text-gray-500">
                    Devenir un établissement de référence reconnu pour l'excellence académique et l'intégrité.
                </p>
            </div>
            <div class="card text-center">
                <i data-lucide="heart-handshake" class="w-10 h-10 text-primary-600 mx-auto mb-3"></i>
                <h3 class="font-semibold mb-2">Valeurs</h3>
                <p class="text-sm text-gray-500">
                    Rigueur, respect, intégrité, bilinguisme et ouverture sur le monde.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sections pédagogiques -->
<section class="max-w-7xl mx-auto px-4 lg:px-8 py-16">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-10">Nos filières</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-primary-100 text-primary-700 rounded-lg flex items-center justify-center">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Section Francophone</h3>
            </div>
            <p class="text-sm text-gray-500">
                Programme conforme aux exigences du système éducatif francophone, du premier au second cycle,
                avec un encadrement structuré par séquences, trimestres et bulletins détaillés.
            </p>
        </div>
        <div class="card">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-primary-100 text-primary-700 rounded-lg flex items-center justify-center">
                    <i data-lucide="languages" class="w-5 h-5"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Section Anglophone</h3>
            </div>
            <p class="text-sm text-gray-500">
                Programme aligné sur le système anglophone, garantissant aux élèves une formation complète
                et un suivi académique rigoureux.
            </p>
        </div>
    </div>
</section>

<!-- Call to action -->
<section class="bg-primary-700 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold mb-3">Envie d'inscrire votre enfant chez nous ?</h2>
        <p class="text-primary-100 mb-6">Déposez son dossier de candidature en ligne dès aujourd'hui.</p>
    </div>
</section>

@endsection