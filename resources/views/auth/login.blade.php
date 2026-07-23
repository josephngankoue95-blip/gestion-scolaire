@extends('layouts.guest')
@section('title', 'Connexion')

@section('content')
<div class="login-card">

    <div class="login-header">
        <img src="{{ asset('storage/' . $etablissement->logo) }}" 
                style="height:150px;border-radius:200px;display:block;margin:0 auto;" 
                alt="Logo">
        <h1 class="login-title">Connexion à l'espace de gestion</h1>
        <p class="login-subtitle">Réservé au personnel et aux parents inscrits</p>
    </div>

    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="mail" class="w-4 h-4"></i> Email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="votre.email@exemple.com"
                   class="login-input @error('email') login-input-error @enderror">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i> Mot de passe
            </label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required
                       placeholder="••••••••"
                       class="login-input @error('password') login-input-error @enderror">
                <button type="button" data-toggle-password="#password" class="password-toggle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="login-options">
            <label class="login-checkbox-label">
                <input type="checkbox" name="remember">
                Se souvenir de moi
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="login-link">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit" class="login-submit-btn">
            Se connecter
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>
    <!-- <div class="login-divider"><span>ou</span></div>
    <div class="text-center">
        <a href="{{ route('public.home') }}" class="login-back-link">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour au site
        </a>
    </div> -->
</div>
@endsection