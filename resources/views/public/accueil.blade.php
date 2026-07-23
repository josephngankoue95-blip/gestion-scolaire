@extends('layouts.public')
@section('title', 'Accueil')

@section('content')

{{-- ══════════════ HERO ══════════════ --}}
<section class="hero-section" style="background:linear-gradient(135deg,#0d2440 0%,#1a3a6b 55%,#123018 100%);color:#fff;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;background:rgba(255,255,255,0.05);border-radius:50%;animation:float 6s ease-in-out infinite;"></div>
    <div style="position:absolute;bottom:-80px;left:-40px;width:220px;height:220px;background:rgba(47,158,68,0.15);border-radius:50%;animation:float 8s ease-in-out infinite reverse;"></div>

    <div class="max-w-7xl mx-auto px-4 lg:px-8" style="padding:90px 24px;text-align:center;position:relative;z-index:2;">
        <div class="fade-in-up" style="animation-delay:0.1s;">
            @if($etablissement->logo)
                <img src="{{ $etablissement->logoUrl() }}" style="height:90px;border-radius:50%;margin:0 auto 20px;border:3px solid rgba(255,255,255,0.3);display:block;">
            @endif
        </div>
        <h1 class="fade-in-up" style="animation-delay:0.2s;font-size:44px;font-weight:900;margin-bottom:14px;line-height:1.15;">
            Bienvenue à {{ $etablissement->nom ?? "l'établissement" }}
        </h1>
        <p class="fade-in-up" style="animation-delay:0.3s;font-size:16px;color:#dbe9ff;max-width:640px;margin:0 auto 32px;">
            {{ $etablissement->devise ?? "Un enseignement de qualité, du secondaire à l'université, pour préparer les leaders de demain." }}
        </p>
        <div class="fade-in-up" style="animation-delay:0.4s;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('public.secondaire') }}" style="background:#fff;color:#1a3a6b;padding:13px 26px;border-radius:10px;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;transition:transform .2s;">
                <i data-lucide="school" class="w-4 h-4"></i> Découvrir le Secondaire
            </a>
            <a href="{{ route('public.universite') }}" style="background:rgba(255,255,255,0.12);color:#fff;border:1px solid rgba(255,255,255,0.4);padding:13px 26px;border-radius:10px;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;transition:transform .2s;">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i> Découvrir l'Université
            </a>
        </div>
    </div>
</section>

{{-- ══════════════ STATS ══════════════ --}}
<section style="background:#fff;padding:0 24px;margin-top:-40px;position:relative;z-index:3;">
    <div class="max-w-7xl mx-auto" style="background:#fff;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.08);display:grid;grid-template-columns:repeat(4,1fr);overflow:hidden;">
        @foreach ([
            ['icon' => 'users', 'val' => $totalEleves, 'label' => 'Élèves inscrits', 'color' => '#2563eb'],
            ['icon' => 'school', 'val' => $totalClasses, 'label' => 'Classes actives', 'color' => '#1a7a1a'],
            ['icon' => 'book-open', 'val' => $totalFilieres, 'label' => 'Filières universitaires', 'color' => '#e67e22'],
            ['icon' => 'award', 'val' => '15+', 'label' => "Années d'expérience", 'color' => '#9333ea'],
        ] as $stat)
        <div style="padding:28px 20px;text-align:center;border-right:1px solid #f3f4f6;">
            <i data-lucide="{{ $stat['icon'] }}" style="width:26px;height:26px;color:{{ $stat['color'] }};margin:0 auto 8px;display:block;"></i>
            <div style="font-size:26px;font-weight:900;color:#111;">{{ $stat['val'] }}</div>
            <div style="font-size:12px;color:#6b7280;">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ══════════════ POURQUOI NOUS CHOISIR ══════════════ --}}
<section class="max-w-7xl mx-auto px-4 lg:px-8" style="padding:70px 24px;">
    <div style="text-align:center;margin-bottom:44px;">
        <span style="color:#2563eb;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">Nos atouts</span>
        <h2 style="font-size:28px;font-weight:800;color:#111;margin-top:6px;">Pourquoi nous choisir ?</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach ([
            ['icon' => 'languages', 'titre' => 'Formation bilingue', 'texte' => 'Filières francophone et anglophone, du secondaire à l\'université.'],
            ['icon' => 'graduation-cap', 'titre' => 'Encadrement rigoureux', 'texte' => 'Suivi pédagogique individualisé, bulletins et statistiques en temps réel.'],
            ['icon' => 'smartphone', 'titre' => 'Plateforme numérique', 'texte' => 'Inscription en ligne, suivi de scolarité et paiement Mobile Money.'],
        ] as $i => $item)
        <div class="reveal-card" style="background:#fff;border:1px solid #eef1f6;border-radius:14px;padding:28px;text-align:center;transition:.3s;">
            <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i data-lucide="{{ $item['icon'] }}" style="width:26px;height:26px;color:#2563eb;"></i>
            </div>
            <h3 style="font-weight:700;font-size:16px;margin-bottom:8px;">{{ $item['titre'] }}</h3>
            <p style="font-size:13px;color:#6b7280;line-height:1.6;">{{ $item['texte'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ══════════════ NOS DEUX PÔLES ══════════════ --}}
<section style="background:#f8faff;padding:70px 24px;">
    <div class="max-w-7xl mx-auto">
        <div style="text-align:center;margin-bottom:44px;">
            <span style="color:#2563eb;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">Notre offre</span>
            <h2 style="font-size:28px;font-weight:800;color:#111;margin-top:6px;">Deux filières, une même excellence</h2>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
            {{-- Carte Secondaire --}}
            <a href="{{ route('public.secondaire') }}" class="pole-card" style="display:block;border-radius:18px;overflow:hidden;position:relative;height:280px;text-decoration:none;">
                <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(26,58,107,0.2),rgba(13,36,64,0.9));"></div>
                <img src="{{ asset('images/secondaire-cover.jpg') }}" onerror="this.style.display='none'" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                <div style="position:relative;z-index:2;padding:28px;height:100%;display:flex;flex-direction:column;justify-content:flex-end;color:#fff;">
                    <i data-lucide="school" style="width:32px;height:32px;margin-bottom:10px;"></i>
                    <h3 style="font-size:22px;font-weight:800;">Le Secondaire</h3>
                    <p style="font-size:13px;color:#dbe9ff;margin-top:4px;">1er et 2nd cycle, sections francophone & anglophone</p>
                    <span style="margin-top:14px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        Découvrir <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </div>
            </a>

            {{-- Carte Université --}}
            <a href="{{ route('public.universite') }}" class="pole-card" style="display:block;border-radius:18px;overflow:hidden;position:relative;height:280px;text-decoration:none;">
                <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(47,158,68,0.15),rgba(11,31,20,0.92));"></div>
                <img src="{{ asset('images/universite-cover.jpg') }}" onerror="this.style.display='none'" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0;">
                <div style="position:relative;z-index:2;padding:28px;height:100%;display:flex;flex-direction:column;justify-content:flex-end;color:#fff;">
                    <i data-lucide="graduation-cap" style="width:32px;height:32px;margin-bottom:10px;"></i>
                    <h3 style="font-size:22px;font-weight:800;">L'Université — {{ $etablissement->sigle_universite ?? 'ISSPED' }}</h3>
                    <p style="font-size:13px;color:#dbe9ff;margin-top:4px;">BTS / HND / Licence / Master dans le système LMD</p>
                    <span style="margin-top:14px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                        Découvrir <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════ ÉVÉNEMENTS / ACTUALITÉS ══════════════ --}}
<section class="max-w-7xl mx-auto px-4 lg:px-8" style="padding:70px 24px;">
    <div style="text-align:center;margin-bottom:44px;">
        <span style="color:#2563eb;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">Actualités</span>
        <h2 style="font-size:28px;font-weight:800;color:#111;margin-top:6px;">Nos derniers événements</h2>
    </div>

    @if($evenements->isEmpty())
        <p style="text-align:center;color:#9ca3af;">Aucun événement publié pour le moment.</p>
    @else
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:22px;">
        @foreach ($evenements as $ev)
        <div class="event-card" style="background:#fff;border:1px solid #eef1f6;border-radius:14px;overflow:hidden;transition:.3s;">
            <div style="height:170px;overflow:hidden;position:relative;">
                @if($ev->image)
                    <img src="{{ asset('storage/'.$ev->image) }}" style="width:100%;height:100%;object-fit:cover;transition:transform .4s;">
                @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a3a6b,#2563eb);display:flex;align-items:center;justify-content:center;">
                        <i data-lucide="calendar" style="width:30px;height:30px;color:#fff;"></i>
                    </div>
                @endif
                <span style="position:absolute;top:10px;left:10px;background:#fff;color:#1a3a6b;font-size:10px;font-weight:bold;padding:3px 10px;border-radius:20px;">
                    {{ $ev->date_evenement->format('d M Y') }}
                </span>
            </div>
            <div style="padding:16px;">
                <span style="font-size:10px;font-weight:bold;color:{{ $ev->categorie === 'universite' ? '#2f9e44' : '#2563eb' }};text-transform:uppercase;letter-spacing:0.5px;">
                    {{ $ev->categorie === 'universite' ? 'Université' : ($ev->categorie === 'secondaire' ? 'Secondaire' : 'Général') }}
                </span>
                <h4 style="font-size:15px;font-weight:700;margin-top:4px;color:#111;">{{ $ev->titre }}</h4>
                @if($ev->description)
                <p style="font-size:12.5px;color:#6b7280;margin-top:6px;line-height:1.5;">{{ Str::limit($ev->description, 90) }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

{{-- ══════════════ APPEL À L'ACTION ══════════════ --}}
<section style="background:linear-gradient(135deg,#123018,#0d2440);padding:60px 24px;text-align:center;color:#fff;">
    <h2 style="font-size:24px;font-weight:800;margin-bottom:10px;">Prêt à rejoindre notre établissement ?</h2>
    <p style="color:#dbe9ff;font-size:14px;margin-bottom:26px;">Consultez les conditions d'admission et déposez votre dossier dès aujourd'hui.</p>
    <a href="{{ route('public.admissions') }}" style="background:#fff;color:#1a3a6b;padding:13px 30px;border-radius:10px;font-weight:700;font-size:14px;display:inline-flex;align-items:center;gap:8px;">
        Voir les admissions <i data-lucide="arrow-right" class="w-4 h-4"></i>
    </a>
</section>

@endsection

@push('styles')
<style>
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.7s ease forwards;
}
.reveal-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.08);
    border-color: #dbe9ff;
}
.pole-card {
    transition: transform .35s ease;
}
.pole-card:hover { transform: scale(1.02); }
.pole-card:hover img { filter: brightness(1.05); }
.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 26px rgba(0,0,0,0.08);
}
.event-card:hover img { transform: scale(1.08); }
</style>
@endpush