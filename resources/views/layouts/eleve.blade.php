<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Espace Élève</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
<div class="app-layout">
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">EL</div>
            <span class="sidebar-brand">Espace Élève</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('eleve.dashboard') }}" class="sidebar-link {{ request()->routeIs('eleve.dashboard') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Tableau de bord
            </a>
            <a href="{{ route('eleve.bulletins') }}" class="sidebar-link {{ request()->routeIs('eleve.bulletins*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="file-text" class="w-5 h-5"></i> Notes & Bulletins
            </a>
            <a href="{{ route('eleve.emploi-du-temps') }}" class="sidebar-link {{ request()->routeIs('eleve.emploi-du-temps') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="calendar-clock" class="w-5 h-5"></i> Emploi du temps
            </a>
            <a href="{{ route('eleve.travaux') }}" class="sidebar-link {{ request()->routeIs('eleve.travaux*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="book-marked" class="w-5 h-5"></i> Travaux Dirigés
            </a>
            <a href="{{ route('eleve.requetes') }}" class="sidebar-link {{ request()->routeIs('eleve.requetes*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="mail" class="w-5 h-5"></i> Requêtes
            </a>
            <a href="{{ route('eleve.profil') }}" class="sidebar-link {{ request()->routeIs('eleve.profil*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="user" class="w-5 h-5"></i> Mon Profil
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