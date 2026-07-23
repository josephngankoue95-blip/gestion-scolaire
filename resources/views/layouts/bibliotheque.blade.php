<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Espace Bibliothèque</title>
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
            <a href="{{ route('bibliotheque.dashboard') }}" class="sidebar-link {{ request()->routeIs('bibliotheque.dashboard') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Tableau de bord
            </a>
            <p class="sidebar-section-title">Gestion</p>
            <a href="{{ route('bibliotheque.livres.index') }}" class="sidebar-link {{ request()->routeIs('bibliotheque.livres*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="wallet" class="w-5 h-5"></i> Livres
            </a>

            <a href="{{ route('bibliotheque.emprunts.index') }}" class="sidebar-link {{ request()->routeIs('bibliotheque.emprunts*') ? 'sidebar-link-active' : '' }}">
                <i data-lucide="user-plus" class="w-5 h-5"></i> Emprunts
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