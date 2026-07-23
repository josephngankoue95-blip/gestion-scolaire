@extends('layouts.public')
@section('title', 'Le Secondaire — ' . ($etablissement->nom ?? ''))

@section('content')

{{-- HERO --}}
<section class="sec-hero">
    <!-- <div class="sec-hero-blob"></div> -->
    <div class="fade-in-up">
        @if($etablissement->logo)
            <img src="{{ $etablissement->logoUrl() }}" style="height:80px;border-radius:50%;margin:0 auto 18px;border:3px solid rgba(255,255,255,0.3);display:block;">
        @endif
        <span class="sec-badge">ENSEIGNEMENT SECONDAIRE</span>
        <h1>{{ strtoupper($etablissement->nom ?? '') }}</h1>
        <p>Un parcours bilingue complet, du 1er au 2nd cycle, pensé pour la réussite de chaque élève.</p>
    </div>
</section>

{{-- GALERIE --}}
<section class="sec-gallery-section">
    <div class="sec-gallery-grid">
        <img src="{{ asset('images/secondaire-1.jpg') }}" onerror="this.style.display='none'" class="gallery-img big">
        <img src="{{ asset('images/secondaire-2.jpg') }}" onerror="this.style.display='none'" class="gallery-img">
        <img src="{{ asset('images/secondaire-3.jpg') }}" onerror="this.style.display='none'" class="gallery-img">
        <img src="{{ asset('images/secondaire-4.jpg') }}" onerror="this.style.display='none'" class="gallery-img">
        <img src="{{ asset('images/secondaire-5.jpg') }}" onerror="this.style.display='none'" class="gallery-img">
    </div>
</section>

{{-- SECTIONS & NIVEAUX — centrés --}}
<section class="sec-niveaux-section">
    <div class="sec-section-title">
        <span class="uni-eyebrow" style="color:#2563eb;">Nos formations</span>
        <h2>Sections et niveaux</h2>
    </div>

    <div class="sec-niveaux-grid">
        @foreach ($niveaux as $sectionNom => $liste)
        <div class="niveau-card reveal-on-scroll">
            <div class="niveau-card-header">
                <div class="niveau-icon">
                    <i data-lucide="flag"></i>
                </div>
                <h4>{{ $sectionNom }}</h4>
            </div>
            <ul class="niveau-tags">
                @foreach ($liste as $niveau)
                <li class="{{ $niveau->est_terminale ? 'is-terminal' : '' }}">
                    {{ $niveau->nom }}
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</section>

{{-- AVANTAGES --}}
<section class="sec-avantages-section">
    <div class="sec-section-title">
        <span class="uni-eyebrow" style="color:#2563eb;">Nos avantages</span>
        <h2>Un cadre propice à la réussite</h2>
    </div>
    <div class="sec-avantages-grid">
        @foreach ([
            ['icon' => 'book-open', 'txt' => 'Bulletins numériques et suivi en temps réel'],
            ['icon' => 'shield-check', 'txt' => 'Encadrement disciplinaire rigoureux'],
            ['icon' => 'users', 'txt' => 'Classes à effectifs maîtrisés'],
            ['icon' => 'trophy', 'txt' => "Tableau d'honneur et excellence académique"],
        ] as $item)
        <div class="avantage-item reveal-on-scroll">
            <i data-lucide="{{ $item['icon'] }}"></i>
            <p>{{ $item['txt'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="sec-cta-section">
    <p>Pour toute inscription au secondaire, contactez l'administration de l'établissement.</p>
    <a href="{{ route('public.admissions') }}" class="sec-cta-btn">
        Voir les conditions d'admission <i data-lucide="arrow-right" class="w-4 h-4"></i>
    </a>
</section>

@endsection

@push('styles')
<style>
@keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-16px);} }
@keyframes fadeInUp { from{opacity:0;transform:translateY(24px);} to{opacity:1;transform:translateY(0);} }
.fade-in-up { opacity:0; animation:fadeInUp .7s ease forwards; }

/* ── HERO ── */
.sec-hero {
    background: linear-gradient(135deg,#0d2440,#1a3a6b);
    color: #fff;
    padding: 80px 24px;
    text-align: center;
    position: relative;
    overflow: hidden;
    isolation: isolate;
}
.sec-hero-blob {
    position: absolute;
    top: -50px; right: 10%;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    animation: float 7s ease-in-out infinite;
    pointer-events: none;
    z-index: 0;
}
.sec-hero > * { position: relative; z-index: 1; }
.sec-badge {
    background: rgba(255,255,255,0.12);
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 1px;
    font-weight: bold;
    display: inline-block;
}
.sec-hero h1 { font-size: 36px; font-weight: 900; margin-top: 16px; }
.sec-hero p {
    font-size: 15px;
    color: #dbe9ff;
    margin-top: 8px;
    max-width: 520px;
    margin-left: auto;
    margin-right: auto;
}

/* ── GALERIE ── */
.sec-gallery-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 50px 24px 10px;
}
.sec-gallery-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    grid-template-rows: 1fr 1fr;
    gap: 12px;
    height: 340px;
}
.gallery-img {
    width: 100%; height: 100%;
    object-fit: cover;
    border-radius: 14px;
    transition: transform .4s ease;
    cursor: pointer;
    display: block;
}
.gallery-img.big { grid-row: 1/3; }
.gallery-img:hover { transform: scale(1.03); }
@media (max-width: 800px) {
    .sec-gallery-grid { grid-template-columns: 1fr 1fr; height: auto; }
    .gallery-img.big { grid-row: auto; }
    .gallery-img { height: 140px; }
}

/* ── SECTIONS TITLE commun ── */
.sec-section-title { text-align: center; margin-bottom: 40px; }
.sec-section-title h2 { font-size: 26px; font-weight: 800; color: #111; margin-top: 6px; }

/* ── NIVEAUX — centrés ── */
.sec-niveaux-section {
    background: #f8faff;
    padding: 70px 24px;
}
.sec-niveaux-grid {
    max-width: 900px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 26px;
}
@media (max-width: 700px) {
    .sec-niveaux-grid { grid-template-columns: 1fr; }
}
.niveau-card {
    background: #fff;
    border: 1px solid #eef1f6;
    border-radius: 16px;
    padding: 26px;
    text-align: center;
    transition: transform .3s ease, box-shadow .3s ease;
}
.niveau-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.08);
    border-color: #dbe9ff;
}
.niveau-card-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 16px;
}
.niveau-icon {
    width: 44px; height: 44px;
    background: #eff6ff;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 8px;
}
.niveau-icon i { width: 20px; height: 20px; color: #2563eb; }
.niveau-card-header h4 { color: #1a3a6b; font-weight: 800; font-size: 17px; }

.niveau-tags {
    list-style: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}
.niveau-tags li {
    background: #f3f4f6;
    color: #374151;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    transition: transform .2s ease;
}
.niveau-tags li:hover { transform: translateY(-2px); }
.niveau-tags li.is-terminal { background: #fef2f2; color: #991b1b; }

/* ── AVANTAGES ── */
.sec-avantages-section {
    max-width: 1000px;
    margin: 0 auto;
    padding: 70px 24px;
    text-align: center;
}
.sec-avantages-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}
@media (max-width: 800px) {
    .sec-avantages-grid { grid-template-columns: 1fr 1fr; }
}
.avantage-item {
    background: #fff;
    border: 1px solid #eef1f6;
    border-radius: 14px;
    padding: 24px 18px;
    text-align: center;
    transition: transform .3s ease, box-shadow .3s ease;
}
.avantage-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.07);
}
.avantage-item i {
    width: 26px; height: 26px;
    color: #2563eb;
    margin: 0 auto 10px;
    display: block;
}
.avantage-item p { font-size: 13px; color: #374151; font-weight: 500; }

/* ── CTA ── */
.sec-cta-section {
    background: #f8faff;
    padding: 60px 24px;
    text-align: center;
}
.sec-cta-section p { font-size: 14px; color: #374151; margin-bottom: 18px; }
.sec-cta-btn {
    background: #1a3a6b;
    color: #fff;
    padding: 13px 30px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: transform .2s ease, box-shadow .2s ease;
}
.sec-cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26,58,107,0.25);
}
</style>
@endpush