<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>@yield('title', 'Tableau de bord')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-layout">

        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header"> 
            <img src="{{ asset('storage/' . $etablissement->logo) }}" 
                style="height:50px;border-radius:200px;display:block;margin:10px auto;" 
                alt="Logo">
            </div>

            <nav class="sidebar-nav">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Tableau de bord
    </a>

    {{-- ================= SCOLARITÉ ================= --}}
    @role('admin|proviseur|secretaire_intendant')
    <p class="sidebar-section-title">Scolarité</p>

    <a href="{{ route('admin.eleves.index') }}" class="sidebar-link {{ request()->routeIs('admin.eleves.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="users" class="w-5 h-5"></i> Élèves
    </a>

    <a href="{{ route('admin.scolarite.index') }}" class="sidebar-link {{ request()->routeIs('admin.scolarite.index') ? 'sidebar-link-active' : '' }}">
    <i data-lucide="wallet" class="w-5 h-5"></i> Dossiers élèves
    </a>

    <a href="{{ route('admin.scolarite.frais.index') }}" class="sidebar-link {{ request()->routeIs('admin.scolarite.frais.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="banknote" class="w-5 h-5"></i> Grilles de frais
    </a>
    
    <a href="{{ route('admin.scolarite.transport.index') }}" class="sidebar-link {{ request()->routeIs('admin.scolarite.transport.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="bus" class="w-5 h-5"></i> Zones transport
    </a>

    <a href="{{ route('admin.candidatures.index') }}" class="sidebar-link {{ request()->routeIs('admin.candidatures.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="file-check" class="w-5 h-5"></i> Candidatures
    </a>
    @endrole

    @role('admin|secretaire_intendant')
    <a href="{{ route('admin.paiements-momo.index') }}" class="sidebar-link {{ request()->routeIs('admin.paiements-momo.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="smartphone" class="w-5 h-5"></i> Paiements Mobile Money
    </a>
    @endrole

    {{--================== TRANSFERT ELEVES==============--}}
    @role('admin|proviseur|secretaire_intendant')
    <a href="{{ route('admin.transferts.index') }}"
    class="sidebar-link {{ request()->routeIs('admin.transferts.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="arrow-right-left" class="w-5 h-5"></i> Transferts
    </a>
    @endrole

    {{-- ================= VIE SCOLAIRE ================= --}}
    @role('admin|prefecture_etudes')
    <p class="sidebar-section-title">Vie scolaire</p>

    <a href="{{ route('admin.bulletins.index') }}" class="sidebar-link {{ request()->routeIs('admin.bulletins.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="file-text" class="w-5 h-5"></i> Bulletins
    </a>

    <a href="{{ route('admin.tableaux-honneur.index') }}" class="sidebar-link {{ request()->routeIs('admin.tableaux-honneur.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="award" class="w-5 h-5"></i> Tableau d'honneur
    </a>

    <a href="{{ route('admin.proces-verbaux.index') }}" class="sidebar-link {{ request()->routeIs('admin.proces-verbaux.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="clipboard-list" class="w-5 h-5"></i> Procès verbaux
    </a>

    <a href="{{ route('admin.cartes-scolaires.index') }}" class="sidebar-link {{ request()->routeIs('admin.cartes-scolaires.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="id-card" class="w-5 h-5"></i> Cartes scolaires
    </a>
    @endrole

    @role('admin|bibliothecaire')
    <a href="{{ route('bibliotheque.dashboard') }}" class="sidebar-link {{ request()->routeIs('bibliotheque.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="book" class="w-5 h-5"></i> Bibliothèque
    </a>
    @endrole

    {{-- Sidebar admin — ajout du lien Conseils de classe --}}
    @role('admin|proviseur|prefet_etudes')
    <a href="{{ route('admin.conseils.index') }}" class="sidebar-link {{ request()->routeIs('admin.conseils.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="gavel" class="w-5 h-5"></i> Conseils de classe
    </a>
    @endrole

    {{-- ================= PERSONNEL ================= --}}
    @role('admin|proviseur')
    <p class="sidebar-section-title">Personnel</p>

    <a href="{{ route('admin.enseignants.index') }}" class="sidebar-link {{ request()->routeIs('admin.enseignants.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="user-check" class="w-5 h-5"></i> Enseignants
    </a>

    <a href="{{ route('admin.releves.index') }}" class="sidebar-link {{ request()->routeIs('admin.releves.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="clipboard" class="w-5 h-5"></i> Relevés de notes
    </a>
    @endrole

    {{-- ================= ENSEIGNANT ================= --}}
    @role('enseignant')
    <p class="sidebar-section-title">Enseignant</p>
    <a href="{{ route('teacher.td.index') }}" class="sidebar-link {{ request()->routeIs('teacher.td.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="file-text" class="w-5 h-5"></i> Travaux dirigés
    </a>
    @endrole

    {{--====================SURVEILLANT================ --}}
@role('admin|surveillant_general')
<p class="sidebar-section-title">Surveillant</p>

<a href="{{ route('surveillant.absences.index') }}"
   class="sidebar-link {{ request()->routeIs('surveillant.absences.*') ? 'sidebar-link-active' : '' }}">
    <i data-lucide="calendar-x" class="w-5 h-5"></i>
    Absences
</a>

<a href="{{ route('surveillant.emplois-temps.index') }}"
   class="sidebar-link {{ request()->routeIs('surveillant.emplois-temps.*') ? 'sidebar-link-active' : '' }}">
    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
    Calendrier Emploi-Temps
</a>
@endrole

    {{-- ================= PARAMÈTRES ================= --}}
    @role('admin')
    <p class="sidebar-section-title">Paramètres</p>

    <a href="{{ route('admin.annees-scolaires.index') }}" class="sidebar-link {{ request()->routeIs('admin.annees-scolaires.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="calendar" class="w-5 h-5"></i> Années scolaires
    </a>

    <a href="{{ route('admin.niveaux.index') }}"
    class="sidebar-link {{ request()->routeIs('admin.niveaux.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="list-ordered" class="w-5 h-5"></i> Niveaux
    </a>

    <a href="{{ route('admin.classes.index') }}" class="sidebar-link {{ request()->routeIs('admin.classes.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="school" class="w-5 h-5"></i> Classes
    </a>

    <a href="{{ route('admin.matieres.index') }}" class="sidebar-link {{ request()->routeIs('admin.matieres.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="book-open" class="w-5 h-5"></i> Matières
    </a>

    <a href="{{ route('admin.emplois-temps.index') }}" class="sidebar-link {{ request()->routeIs('admin.emplois-temps.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="calendar-clock" class="w-5 h-5"></i> Emploi du temps
    </a>

    <a href="{{ route('admin.etablissement.edit') }}" class="sidebar-link {{ request()->routeIs('admin.etablissement.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="building-2" class="w-5 h-5"></i> Établissement
    </a>

    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="users-round" class="w-5 h-5"></i> Utilisateurs
    </a>
    @endrole

    @role('admin')
    <a href="{{ route('admin.comptes-generes.index') }}"
    class="sidebar-link {{ request()->routeIs('admin.comptes-generes.*') ? 'sidebar-link-active' : '' }}">
    <i data-lucide="key-round" class="w-5 h-5"></i> Identifiants générés
    </a>
    @endrole

    {{-- ================= MON ESPACE ================= --}}
    @role('enseignant')
    <p class="sidebar-section-title">Mon espace</p>

    <a href="{{ route('teacher.saisie.index') }}" class="sidebar-link {{ request()->routeIs('teacher.saisie.*') ? 'sidebar-link-active' : '' }}">
        <i data-lucide="pencil-line" class="w-5 h-5"></i> Saisie des notes
    </a>
    @endrole
</nav>
        </aside>

        <div id="sidebar-overlay" class="sidebar-overlay"></div>

        <div class="main-area">
            <header class="topbar">
                <button id="sidebar-toggle" class="topbar-toggle">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="topbar-title">@yield('title', 'Tableau de bord')</h1>
                <div class="topbar-user">
                    <span class="topbar-user-name">{{ auth()->user()->name ?? '' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="topbar-logout-btn"><i data-lucide="log-out" class="w-5 h-5"></i></button>
                    </form>
                </div>
            </header>

            <main class="page-content">
                @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if (session('error'))<div class="alert alert-error">{{ session('error') }}</div>@endif
                @yield('content')
            </main>
        </div>
    </div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
    @stack('scripts')
</body>
</html>