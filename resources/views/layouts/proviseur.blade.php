<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Espace Proviseur/Principal(e)</title>
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
            <a href="{{ route('proviseur.dashboard') }}" class="sidebar-link {{ request()->routeIs('proviseur.dashboard') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Tableau de bord
            </a>

            <p class="sidebar-section-title">Vue d'ensemble</p>

            <a href="{{ route('proviseur.scolarite') }}" class="sidebar-link {{ request()->routeIs('proviseur.scolarite*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5"></i> Finances & Scolarité
            </a>

            <a href="{{ route('proviseur.performances') }}" class="sidebar-link {{ request()->routeIs('proviseur.performances*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i> Performances
            </a>

            <a href="{{ route('proviseur.absences') }}" class="sidebar-link {{ request()->routeIs('proviseur.absences*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="calendar-x" class="w-5 h-5"></i> Absences
            </a>

            <a href="{{ route('proviseur.enseignants') }}" class="sidebar-link {{ request()->routeIs('proviseur.enseignants*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="user-check" class="w-5 h-5"></i> Enseignants
            </a>

            <p class="sidebar-section-title">Rapports</p>

            <a href="{{ route('proviseur.rapport') }}" target="_blank" class="sidebar-link">
                <i data-lucide="printer" class="w-5 h-5"></i> Rapport PDF global
            </a>

            <p class="sidebar-section-title">Compte</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link w-full text-left">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Déconnexion
                </button>
            </form>
        </nav>
    </aside>

    {{-- Bandeau lecture seule --}}
    <div style="position:fixed;top:0;right:0;left:260px;z-index:40;background:#fff3cd;border-bottom:1px solid #fcd34d;padding:4px 16px;font-size:11px;color:#92400e;display:flex;align-items:center;gap:6px;">
        <i data-lucide="eye" style="width:14px;height:14px;"></i>
        <strong>Mode consultation uniquement</strong> — Aucune modification n'est possible depuis cet espace.
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    <div class="main-area" style="padding-top:28px;">
        <header class="topbar" style="top:28px;">
            <button id="sidebar-toggle" class="topbar-toggle"><i data-lucide="menu" class="w-6 h-6"></i></button>
            <h1 class="topbar-title">@yield('title')</h1>
            <div class="topbar-user">
                <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                <span style="font-size:10px;color:#9ca3af;margin-left:4px;">(Proviseur)</span>
            </div>
        </header>
        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@stack('scripts')
</body>
</html>