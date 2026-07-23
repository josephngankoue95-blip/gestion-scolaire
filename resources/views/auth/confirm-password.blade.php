@extends('layouts.guest')
@section('title', 'Confirmer le mot de passe')

@section('content')
<div class="login-card">

    <div class="login-header">
        <div class="login-logo">
            <i data-lucide="shield-alert" class="w-7 h-7"></i>
        </div>
        <h1 class="login-title">Zone sécurisée</h1>
        <p class="login-subtitle">Veuillez confirmer votre mot de passe avant de continuer</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">
                <i data-lucide="lock" class="w-4 h-4"></i> Mot de passe
            </label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required autofocus
                       placeholder="••••••••"
                       class="form-input @error('password') login-input-error @enderror">
                <button type="button" data-toggle-password="#password" class="password-toggle">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </button>
            </div>
            @error('password') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="login-submit-btn">
            Confirmer
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>
</div>
@endsection