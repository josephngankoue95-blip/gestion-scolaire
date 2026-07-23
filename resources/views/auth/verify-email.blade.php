@extends('layouts.guest')
@section('title', 'Vérification de l\'email')

@section('content')
<div class="login-card">

    <div class="login-header">
        <div class="login-logo">
            <i data-lucide="mail-check" class="w-7 h-7"></i>
        </div>
        <h1 class="login-title">Vérifiez votre adresse email</h1>
        <p class="login-subtitle">
            Merci de vous être inscrit ! Avant de commencer, veuillez vérifier votre adresse email
            en cliquant sur le lien que nous venons de vous envoyer. Si vous n'avez rien reçu,
            nous pouvons vous en renvoyer un.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success mb-4">
            Un nouveau lien de vérification a été envoyé à l'adresse email fournie lors de l'inscription.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="login-submit-btn mb-3">
            Renvoyer l'email de vérification
            <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    </form>

    <div class="login-divider"><span>ou</span></div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-outline w-full">
            <i data-lucide="log-out" class="w-4 h-4"></i> Se déconnecter
        </button>
    </form>
</div>
@endsection