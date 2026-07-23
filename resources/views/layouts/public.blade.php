<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Accueil')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        /* ── ANIMATIONS GLOBALES ── */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        html { scroll-behavior: smooth; }

        /* ── HEADER ── */
        .public-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid #eef1f6;
            transition: box-shadow .3s ease, background .3s ease;
            animation: fadeInDown .5s ease;
        }
        .public-header.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            background: rgba(255,255,255,0.97);
        }
        .public-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
        }

        .site-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 17px;
            color: #111;
            text-decoration: none;
            transition: opacity .2s;
        }
        .site-brand:hover { opacity: .8; }
        .site-brand-logo {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg,#1a3a6b,#2563eb);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px;
        }

        .public-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .public-nav a {
            position: relative;
            padding: 8px 14px;
            font-size: 13.5px;
            font-weight: 600;
            color: #4b5563;
            text-decoration: none;
            border-radius: 8px;
            transition: color .2s, background .2s;
        }
        .public-nav a::after {
            content: '';
            position: absolute;
            left: 14px; right: 14px; bottom: 3px;
            height: 2px;
            background: #2563eb;
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .25s ease;
        }
        .public-nav a:hover { color: #1a3a6b; background: #f3f6ff; }
        .public-nav a:hover::after { transform: scaleX(1); }
        .public-nav a.active { color: #1a3a6b; }
        .public-nav a.active::after { transform: scaleX(1); }

        .public-nav-cta {
            background: linear-gradient(135deg,#1a3a6b,#2563eb) !important;
            color: #fff !important;
            padding: 9px 18px !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 14px rgba(37,99,235,0.25);
        }
        .public-nav-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37,99,235,0.35);
        }
        .public-nav-cta::after { display: none; }

        .public-mobile-toggle {
            display: none;
            background: #f3f6ff;
            border: none;
            border-radius: 8px;
            width: 38px; height: 38px;
            align-items: center; justify-content: center;
            cursor: pointer;
            color: #1a3a6b;
        }

        .public-mobile-menu {
            display: none;
            flex-direction: column;
            gap: 4px;
            padding: 12px 20px 18px;
            border-top: 1px solid #eef1f6;
            animation: fadeInDown .3s ease;
        }
        .public-mobile-menu.open { display: flex; }
        .public-mobile-menu a {
            padding: 10px 12px;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            transition: background .2s;
        }
        .public-mobile-menu a:hover { background: #f3f6ff; }
        .public-mobile-menu a.is-cta {
            background: linear-gradient(135deg,#1a3a6b,#2563eb);
            color: #fff;
            text-align: center;
            margin-top: 6px;
        }

        @media (max-width: 900px) {
            .public-nav { display: none; }
            .public-mobile-toggle { display: flex; }
        }

        /* ── ALERTES ── */
        .section { max-width: 1200px; margin: 16px auto 0; padding: 0 24px; }
        .alert-success, .alert-error {
            animation: fadeInDown .4s ease;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 13.5px;
            font-weight: 500;
        }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── FOOTER ── */
        /* ── FOOTER ── */
.public-footer {
    background: linear-gradient(135deg,#0d2440,#123018);
    color: #dbe9ff;
    margin-top: 90px;
    position: relative;
}
.public-footer::before {
    content: '';
    position: absolute;
    top: -1px; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg,#2563eb,#2f9e44,#2563eb);
}

.public-footer-top {
    max-width: 1200px;
    margin: 0 auto;
    padding: 44px 24px 0;
}
.footer-brand {
    display: flex;
    align-items: center;
    gap: 14px;
    padding-bottom: 32px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.footer-brand-name {
    color: #fff;
    font-size: 19px;
    font-weight: 800;
}
.footer-brand-devise {
    color: #8fd9b0;
    font-size: 12.5px;
    font-style: italic;
    margin-top: 2px;
}

.public-footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 24px 30px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 36px;
}
.footer-col h4 {
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 16px;
    letter-spacing: 0.3px;
}
.footer-col ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.footer-col ul a {
    color: #b8cbe8;
    text-decoration: none;
    font-size: 13.5px;
    transition: color .2s ease, padding-left .2s ease;
    display: inline-block;
}
.footer-col ul a:hover {
    color: #fff;
    padding-left: 5px;
}
.footer-info-line {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 13px;
    color: #b8cbe8;
    line-height: 1.5;
}
.footer-info-line i {
    width: 15px; height: 15px;
    color: #2f9e44;
    flex-shrink: 0;
    margin-top: 1px;
}

.footer-socials {
    display: flex;
    gap: 10px;
}
.footer-socials a {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.06);
    display: flex; align-items: center; justify-content: center;
    color: #dbe9ff;
    transition: background .2s ease, transform .2s ease;
}
.footer-socials a:hover {
    background: linear-gradient(135deg,#2563eb,#2f9e44);
    transform: translateY(-3px);
    color: #fff;
}
.footer-socials i { width: 16px; height: 16px; }

.public-footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.08);
    text-align: center;
    padding: 20px 24px;
    font-size: 12px;
    color: #7d93b8;
    max-width: 1200px;
    margin: 0 auto;
}
.footer-dot { margin: 0 8px; opacity: .5; }

@media (max-width: 900px) {
    .public-footer-inner { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
    .public-footer-inner { grid-template-columns: 1fr; }
    .footer-brand { flex-direction: column; text-align: center; }
}

        /* ── Reveal au scroll (utilisable dans les pages enfants) ── */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header class="public-header" id="public-header">

        <div class="public-header-inner">

            <a href="{{ route('public.home') }}" class="site-brand">
                @if ($etablissement->logo)
                    <img src="{{ asset('storage/' . $etablissement->logo) }}" style="height:42px;border-radius:8px;" alt="Logo">
                @else
                    <div class="site-brand-logo">{{ substr($etablissement->sigle ?? 'LC', 0, 2) }}</div>
                @endif
                {{ $etablissement->nom ?? 'Lycée / Collège' }}
            </a>

            <nav class="public-nav">
                <a href="{{ route('public.home') }}" class="{{ request()->routeIs('public.home') ? 'active' : '' }}">Accueil</a>
                <a href="{{ route('public.secondaire') }}" class="{{ request()->routeIs('public.secondaire') ? 'active' : '' }}">Le Secondaire</a>
                <a href="{{ route('public.universite') }}" class="{{ request()->routeIs('public.universite') ? 'active' : '' }}">L'Université</a>
                <a href="{{ route('public.contact') }}" class="{{ request()->routeIs('public.contact') ? 'active' : '' }}">Contact</a>
                <a href="{{ route('public.candidature.create') }}" class="public-nav-cta">Étudier un dossier</a>
            </nav>

            <button id="mobile-menu-toggle" class="public-mobile-toggle">
                <i data-lucide="menu"></i>
            </button>

        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="public-mobile-menu">
            <a href="{{ route('public.home') }}">Accueil</a>
            <a href="{{ route('public.secondaire') }}">Le Secondaire</a>
            <a href="{{ route('public.universite') }}">L'Université</a>
            <a href="{{ route('public.candidature.create') }}">Étudier un dossier</a>
            <a href="{{ route('public.contact') }}">Contact</a>
            <a href="{{ route('login') }}" class="is-cta">Connexion</a>
        </div>

    </header>

    <!-- ALERTES -->
    @if (session('success'))
        <section class="section">
            <div class="alert-success">{{ session('success') }}</div>
        </section>
    @endif

    @if (session('error'))
        <section class="section">
            <div class="alert-error">{{ session('error') }}</div>
        </section>
    @endif

    <!-- CONTENU -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <!-- FOOTER -->
<footer class="public-footer">

    <div class="public-footer-top">
        <div class="footer-brand">
            @if ($etablissement->logo)
                <img src="{{ asset('storage/' . $etablissement->logo) }}" style="height:44px;border-radius:8px;" alt="Logo">
            @endif
            <div>
                <p class="footer-brand-name">{{ $etablissement->nom ?? 'Établissement' }}</p>
                @if($etablissement->devise)
                <p class="footer-brand-devise">{{ $etablissement->devise }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="public-footer-inner">

        <div class="footer-col">
            <h4>Liens rapides</h4>
            <ul>
                <li><a href="{{ route('public.home') }}">Accueil</a></li>
                <li><a href="{{ route('public.secondaire') }}">Le Secondaire</a></li>
                <li><a href="{{ route('public.universite') }}">L'Université</a></li>
                <li><a href="{{ route('public.candidature.create') }}">Étudier un dossier</a></li>
                <li><a href="{{ route('public.contact') }}">Contact</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Établissement</h4>
            <ul>
                <li class="footer-info-line"><i data-lucide="map-pin" class="w-4 h-4"></i> {{ $etablissement->adresse ?? '' }} {{ $etablissement->ville ?? '' }}</li>
                <li class="footer-info-line"><i data-lucide="phone" class="w-4 h-4"></i> {{ $etablissement->telephone ?? '+237 691 975 928' }}</li>
                <li class="footer-info-line"><i data-lucide="mail" class="w-4 h-4"></i> {{ $etablissement->email ?? 'contact@etablissement.com' }}</li>
            </ul>
        </div>

        @if($etablissement->nom_universite)
        <div class="footer-col">
            <h4>{{ $etablissement->sigle_universite ?? "L'Université" }}</h4>
            <ul>
                <li class="footer-info-line"><i data-lucide="phone" class="w-4 h-4"></i> {{ $etablissement->telephones_universite ?? '-' }}</li>
                <li class="footer-info-line"><i data-lucide="mail" class="w-4 h-4"></i> {{ $etablissement->email_universite ?? '-' }}</li>
                @if($etablissement->facebook_universite)
                <li class="footer-info-line"><i data-lucide="facebook" class="w-4 h-4"></i> {{ $etablissement->facebook_universite }}</li>
                @endif
            </ul>
        </div>
        @endif

        <div class="footer-col">
            <h4>Suivez-nous</h4>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                <a href="#" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                <a href="#" aria-label="Youtube"><i data-lucide="youtube"></i></a>
                <a href="#" aria-label="Mail"><i data-lucide="mail"></i></a>
            </div>
        </div>

    </div>

    <div class="public-footer-bottom">
        © {{ date('Y') }} {{ $etablissement->nom ?? 'Établissement' }}. Tous droits réservés.
        <span class="footer-dot">•</span>
        Conçu avec soin pour l'excellence académique.
    </div>

</footer>

<!-- LUCIDE -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    // Header scroll effect
    const header = document.getElementById('public-header');
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 10);
    });

    // Menu mobile
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    toggleBtn?.addEventListener('click', () => {
        mobileMenu.classList.toggle('open');
        const icon = toggleBtn.querySelector('i');
        icon.setAttribute('data-lucide', mobileMenu.classList.contains('open') ? 'x' : 'menu');
        lucide.createIcons();
    });

    // Reveal au scroll (IntersectionObserver)
    const revealEls = document.querySelectorAll('.reveal-on-scroll, .fade-in-up, .reveal-card, .niveau-card, .filiere-card, .event-card, .pole-card, .gallery-img, .atout-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    revealEls.forEach(el => observer.observe(el));
</script>
@stack('scripts')

</body>
</html>