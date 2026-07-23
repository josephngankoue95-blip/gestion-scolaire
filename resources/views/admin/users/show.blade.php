@extends('layouts.admin')

@section('title', 'Profil — ' . $user->name)

@section('content')
<div class="container">

    <div class="topbar">
        <div class="title">Profil utilisateur</div>

        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-outline">Modifier</a>
            <a href="{{ route('admin.users.index') }}" class="btn-back">← Retour</a>
        </div>
    </div>

    <div class="section">
        <div class="card" style="max-width:560px; margin:0 auto;">
            <div class="section-title">{{ $user->name }}</div>

            <div class="grid-card">
                <div class="card">
                    <div class="label">Nom complet</div>
                    <div class="value">{{ $user->name }}</div>
                </div>

                <div class="card">
                    <div class="label">Email</div>
                    <div class="value">{{ $user->email }}</div>
                </div>

                <div class="card">
                    <div class="label">Téléphone</div>
                    <div class="value">{{ $user->telephone ?? '-' }}</div>
                </div>

                <div class="card">
                    <div class="label">Rôle</div>
                    <div class="value">
                        @forelse($user->roles as $role)
                            <span class="badge badge-blue">{{ str_replace('_', ' ', $role->name) }}</span>
                        @empty
                            <span class="text-gray-500">-</span>
                        @endforelse
                    </div>
                </div>

                <div class="card">
                    <div class="label">Statut</div>
                    <div class="value">
                        @if($user->actif)
                            <span class="badge badge-green">Actif</span>
                        @else
                            <span class="badge badge-red">Inactif</span>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="label">Créé le</div>
                    <div class="value">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection