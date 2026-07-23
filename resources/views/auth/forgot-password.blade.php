@extends('layouts.guest')
@section('title', 'Mot de passe oublié')

@section('content')
<div class="card">
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-primary-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-3">LC</div>
        <h1 class="text-xl font-bold text-gray-800">Mot de passe oublié</h1>
        <p class="text-sm text-gray-500">
            Indiquez votre email, nous vous envoyons un lien de réinitialisation.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-input">
            @error('email') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn-primary w-full">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <div class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-600">
            ← Retour à la connexion
        </a>
    </div>
</div>
@endsection