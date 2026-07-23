@extends('layouts.guest')
@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="login-card">

    <div class="login-header">
        <div class="login-logo">LC</div>
        <h1 class="login-title">Réinitialiser le mot de passe</h1>
        <p class="login-subtitle">Choisissez un nouveau mot de passe sécurisé</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="mail" class="w-4 h-4"></i> Email
            </label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                   class="form-input @error('email') login-input-error @enderror">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i> Nouveau mot de passe
            </label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required
                       placeholder="••••••••"
                       class="form-input @error('password') login-input-error @enderror">
                <button type="button" data-toggle-password="#password" class="password-toggle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i> Confirmer le mot de passe
            </label>
            <div class="password-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       placeholder="••••••••"
                       class="form-input @error('password_confirmation') login-input-error @enderror">
                <button type="button" data-toggle-password="#password_confirmation" class="password-toggle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password_confirmation') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="login-submit-btn">
            Réinitialiser le mot de passe
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>

    <div class="login-divider"><span>ou</span></div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="login-back-link">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Retour à la connexion
        </a>
    </div>
</div>
@endsection