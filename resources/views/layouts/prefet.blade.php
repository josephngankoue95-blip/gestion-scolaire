<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Préfecture des Études</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<div class="app-layout">
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('storage/' . $etablissement->logo) }}" 
                style="height:50px;border-radius:200px;display:block;margin:0 auto;" 
                alt="Logo">
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('prefet.dashboard') }}" class="sidebar-link {{ request()->routeIs('prefet.dashboard') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Tableau de bord
            </a>

            <p class="sidebar-section-title">Pédagogie</p>

            <a href="{{ route('prefet.saisie.index') }}" class="sidebar-link {{ request()->routeIs('prefet.saisie.*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="pencil-line" class="w-5 h-5"></i> Saisir des notes
            </a>

            <a href="{{ route('admin.bulletins.index') }}" class="sidebar-link {{ request()->routeIs('admin.bulletins.*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="file-text" class="w-5 h-5"></i> Bulletins
            </a>

            <a href="{{ route('admin.conseils.index') }}" class="sidebar-link {{ request()->routeIs('admin.conseils.*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="gavel" class="w-5 h-5"></i> Conseils de classe
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
        </nav>
    </aside>
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    <div class="main-area">
        <header class="topbar">
            <button id="sidebar-toggle" class="topbar-toggle"><i data-lucide="menu" class="w-6 h-6"></i></button>
            <h1 class="topbar-title">@yield('title')</h1>
            <div class="topbar-user">
                <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="topbar-logout-btn"><i data-lucide="log-out" class="w-5 h-5"></i></button>
                </form>
            </div>
        </header>
        <main class="page-content">
            @if(session('success'))<div class="alert-success mb-4">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert-error mb-4">{{ session('error') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@stack('scripts')
</body>
</html>