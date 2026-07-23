@extends('layouts.public')
@section('title', "L'Université — " . ($etablissement->nom_universite ?? ''))

@section('content')

{{-- HERO --}}
<section class="uni-hero">
    <!-- <div class="uni-hero-blob"></div> -->

    <div class="fade-in-up" style="display:flex;justify-content:center;gap:24px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
        @if($etablissement->logo_universite)
            <img src="{{ asset('storage/'.$etablissement->logo_universite) }}" style="height:78px;background:#fff;border-radius:12px;padding:6px;">
        @endif
        @if($etablissement->logo_universite_partenaire)
            <img src="{{ asset('storage/'.$etablissement->logo_universite_partenaire) }}" style="height:78px;background:#fff;border-radius:12px;padding:6px;">
        @endif
    </div>

    @if($etablissement->autorisation_minesup)
    <p class="fade-in-up" style="animation-delay:.15s;font-size:10.5px;color:#ffd54a;font-weight:bold;letter-spacing:0.5px;">
        {{ $etablissement->autorisation_minesup }}
    </p>
    @endif

    <span class="fade-in-up uni-badge" style="animation-delay:.2s;">
        ANNÉE ACADÉMIQUE {{ $etablissement->annee_academique ?? '' }}
    </span>

    <h1 class="fade-in-up" style="animation-delay:.3s;font-size:32px;font-weight:900;margin-top:14px;">
        {{ $etablissement->nom_universite ?? "L'Institut" }}
    </h1>
    <p class="fade-in-up" style="animation-delay:.4s;font-size:15px;color:#dbeee0;margin-top:8px;">
        BTS / HND / Licence / Master — Formations bilingues dans le système LMD
    </p>
</section>

{{-- GALERIE PHOTOS — bien isolée, aucun chevauchement possible --}}
<section class="uni-gallery-section">
    <div class="uni-gallery-grid">
        <img src="{{ asset('images/universite-1.jpg') }}" onerror="this.style.display='none'" class="gallery-img" alt="">
        <img src="{{ asset('images/universite-2.jpg') }}" onerror="this.style.display='none'" class="gallery-img" alt="">
        <img src="{{ asset('images/universite-3.jpg') }}" onerror="this.style.display='none'" class="gallery-img" alt="">
        <img src="{{ asset('images/universite-4.jpg') }}" onerror="this.style.display='none'" class="gallery-img" alt="">
    </div>
</section>
<br><br><br><br><br>

{{-- CYCLES DE FORMATION — centrés --}}
@foreach ([
    ['key' => 'bts_hnd', 'titre' => 'Cycle BTS / HND', 'filieres' => $filieresBts, 'icon' => 'briefcase', 'color' => '#2f9e44'],
    ['key' => 'licence', 'titre' => 'Cycle Licence', 'filieres' => $filieresLicence, 'icon' => 'graduation-cap', 'color' => '#2563eb'],
    ['key' => 'master', 'titre' => 'Cycle Master', 'filieres' => $filieresMaster, 'icon' => 'award', 'color' => '#9333ea'],
] as $cycle)
<section class="uni-cycle-section reveal-on-scroll">
    <div class="uni-cycle-header">
        <div class="uni-cycle-icon" style="background:{{ $cycle['color'] }}1a;">
            <i data-lucide="{{ $cycle['icon'] }}" style="width:24px;height:24px;color:{{ $cycle['color'] }};"></i>
        </div>
        <h3>{{ $cycle['titre'] }}</h3>
        <p>En formation bilingue — présentiel et en ligne</p>
        <div class="uni-cycle-underline" style="background:{{ $cycle['color'] }};"></div>
    </div>

    @if($cycle['filieres']->isEmpty())
        <p style="color:#9ca3af;text-align:center;">Filières à venir.</p>
    @else
        @php $groupes = $cycle['filieres']->groupBy('categorie'); @endphp
        <div class="uni-filiere-grid">
        @foreach ($groupes as $categorie => $filieres)
            <div class="filiere-card reveal-on-scroll">
                @if($categorie)
                    <p class="filiere-categorie" style="color:{{ $cycle['color'] }};">{{ $categorie }}</p>
                @endif
                <ul class="filiere-list">
                    @foreach ($filieres as $f)
                    <li>
                        <i data-lucide="chevron-right" style="width:13px;height:13px;color:{{ $cycle['color'] }};flex-shrink:0;"></i>
                        {{ $f->nom }}
                    </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
        </div>
    @endif
</section>
@endforeach

{{-- COÛTS --}}
<section class="uni-cost-section">
    <div class="uni-cost-wrap">
        <div class="uni-section-title">
            <span class="uni-eyebrow" style="color:#2f9e44;">Tarifs</span>
            <h3>Coût de la formation</h3>
        </div>
        <div class="cost-table">
            <div class="cost-row cost-header">
                <div>Cycle</div><div>Inscription</div><div>Scolarité / an</div>
            </div>
            @foreach ([
                ['cycle' => 'BTS', 'insc' => '150 000 XAF', 'sco' => '250 000 - 300 000 XAF'],
                ['cycle' => 'Licence', 'insc' => '150 000 XAF', 'sco' => '450 000 XAF'],
                ['cycle' => 'Master', 'insc' => '200 000 XAF', 'sco' => '600 000 - 655 000 XAF'],
            ] as $c)
            <div class="cost-row">
                <div class="cost-cycle">{{ $c['cycle'] }}</div>
                <div>{{ $c['insc'] }}</div>
                <div>{{ $c['sco'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ATOUTS --}}
<section class="uni-atouts-section">
    <div class="uni-section-title">
        <span class="uni-eyebrow" style="color:#2f9e44;">Nos atouts</span>
        <h3>Pourquoi étudier avec nous</h3>
    </div>
    <div class="uni-atouts-grid">
        @foreach ($atouts as $atout)
        <div class="atout-item reveal-on-scroll">
            <i data-lucide="{{ $atout->icone ?? 'check-circle' }}"></i>
            <span>{{ $atout->libelle }}</span>
        </div>
        @endforeach
    </div>
</section>

{{-- PARTENAIRES --}}
@if($partenaires->isNotEmpty())
<section class="uni-partenaires-section">
    <h3>Étudier à l'étranger dans nos écoles partenaires</h3>
    <div class="uni-partenaires-list">
        @foreach ($partenaires as $p)
        <div class="partenaire-logo">
            @if($p->logo)
                <img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->nom }}">
            @else
                <span>{{ $p->nom }}</span>
            @endif
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- CAMPUS & CONTACT --}}
<section class="uni-contact-section">
    <h3>Nos Campus</h3>
    <div class="uni-campus-text">{{ $etablissement->campus }}</div>
    <div class="uni-contact-list">
        <div><i data-lucide="phone"></i> {{ $etablissement->telephones_universite }}</div>
        <div><i data-lucide="mail"></i> {{ $etablissement->email_universite }}</div>
        <div><i data-lucide="facebook"></i> {{ $etablissement->facebook_universite }}</div>
    </div>
</section>

@endsection

@push('styles')
<style>
@keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-16px);} }
@keyframes fadeInUp { from{opacity:0;transform:translateY(24px);} to{opacity:1;transform:translateY(0);} }
.fade-in-up { opacity:0; animation:fadeInUp .7s ease forwards; }

/* ── HERO ── */
.uni-hero {
    background: linear-gradient(135deg,#0b1f14,#123018);
    color: #fff;
    padding: 80px 24px;
    text-align: center;
    position: relative;
    overflow: hidden;
    isolation: isolate; /* empêche tout débordement d'enfants absolus hors de la section */
}
.uni-hero-blob {
    position: absolute;
    top: -40px; left: 8%;
    width: 220px; height: 220px;
    background: rgba(47,158,68,0.12);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
    z-index: 0;
    pointer-events: none;
}
.uni-hero > * { position: relative; z-index: 1; }

.uni-badge {
    background: rgba(255,255,255,0.1);
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 1px;
    font-weight: bold;
    display: inline-block;
    margin-top: 10px;
}

/* ── GALERIE : section 100% indépendante, aucun overlap possible ── */
.uni-gallery-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 50px 24px 10px;
    position: relative; /* nouveau contexte, isolé du hero */
}
.uni-gallery-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1.6fr;
    gap: 12px;
    height: 260px;
}
.gallery-img {
    width: 100%; height: 100%;
    object-fit: cover;
    border-radius: 14px;
    transition: transform .4s ease;
    cursor: pointer;
    display: block;
}
.gallery-img:hover { transform: scale(1.03); }

@media (max-width: 800px) {
    .uni-gallery-grid { grid-template-columns: 1fr 1fr; height: auto; }
    .uni-gallery-grid img { height: 140px; }
}

/* ── CYCLES — centrés ── */
.uni-cycle-section {
    max-width: 1000px;
    margin: 0 auto;
    padding: 60px 24px;
    text-align: center;
}
.uni-cycle-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 32px;
}
.uni-cycle-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
}
.uni-cycle-header h3 {
    font-size: 22px;
    font-weight: 800;
    color: #111;
}
.uni-cycle-header p {
    font-size: 12.5px;
    color: #6b7280;
    margin-top: 4px;
}
.uni-cycle-underline {
    width: 48px; height: 3px;
    border-radius: 2px;
    margin-top: 14px;
}

.uni-filiere-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    text-align: left;
}
@media (max-width: 700px) {
    .uni-filiere-grid { grid-template-columns: 1fr; }
}

.filiere-card {
    background: #fff;
    border: 1px solid #eef1f6;
    border-radius: 14px;
    padding: 22px;
    transition: transform .3s ease, box-shadow .3s ease;
}
.filiere-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.08);
}
.filiere-categorie {
    font-weight: 700;
    font-size: 13px;
    margin-bottom: 10px;
}
.filiere-list { list-style: none; padding: 0; margin: 0; }
.filiere-list li {
    padding: 7px 0;
    border-top: 1px solid #f3f4f6;
    font-size: 13.5px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}
.filiere-list li:first-child { border-top: none; }

/* ── COÛTS ── */
.uni-cost-section { background: #f8faff; padding: 60px 24px; }
.uni-cost-wrap { max-width: 800px; margin: 0 auto; }
.uni-section-title { text-align: center; margin-bottom: 30px; }
.uni-eyebrow { font-size: 12px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
.uni-section-title h3 { font-size: 24px; font-weight: 800; color: #111; margin-top: 6px; }

.cost-table {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}
.cost-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    padding: 14px;
    font-size: 13.5px;
    text-align: center;
}
.cost-row > div:first-child { text-align: left; }
.cost-header {
    background: #123018;
    color: #fff;
    font-weight: bold;
    font-size: 12.5px;
}
.cost-row:not(.cost-header) { border-top: 1px solid #f3f4f6; }
.cost-cycle { font-weight: 700; color: #111; }

/* ── ATOUTS ── */
.uni-atouts-section { max-width: 1000px; margin: 0 auto; padding: 60px 24px; text-align: center; }
.uni-atouts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-top: 10px;
}
@media (max-width: 700px) {
    .uni-atouts-grid { grid-template-columns: 1fr 1fr; }
}
.atout-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #eef1f6;
    border-radius: 10px;
    padding: 14px;
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    text-align: left;
}
.atout-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    border-color: #dbf5e0;
}
.atout-item i { width: 19px; height: 19px; color: #2f9e44; flex-shrink: 0; }
.atout-item span { font-size: 13px; font-weight: 500; color: #374151; }

/* ── PARTENAIRES ── */
.uni-partenaires-section { background: #f8faff; padding: 50px 24px; text-align: center; }
.uni-partenaires-section h3 { font-size: 18px; font-weight: 800; color: #111; margin-bottom: 24px; }
.uni-partenaires-list {
    display: flex;
    justify-content: center;
    gap: 28px;
    flex-wrap: wrap;
    align-items: center;
    max-width: 900px;
    margin: 0 auto;
}
.partenaire-logo { transition: .25s; }
.partenaire-logo img { height: 36px; filter: grayscale(1); opacity: .7; transition: .25s; }
.partenaire-logo:hover img { filter: grayscale(0); opacity: 1; }
.partenaire-logo span { font-weight: bold; color: #6b7280; font-size: 13px; }

/* ── CONTACT ── */
.uni-contact-section {
    max-width: 900px;
    margin: 0 auto;
    padding: 60px 24px;
    text-align: center;
}
.uni-contact-section h3 { font-size: 22px; font-weight: 800; color: #111; margin-bottom: 16px; }
.uni-campus-text { white-space: pre-line; color: #374151; margin-bottom: 26px; font-size: 13.5px; }
.uni-contact-list {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
    font-size: 13.5px;
    color: #374151;
}
.uni-contact-list div { display: flex; align-items: center; gap: 6px; }
.uni-contact-list i { width: 16px; height: 16px; color: #2f9e44; }
</style>
@endpush