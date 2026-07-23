@extends('layouts.admin')
@section('title', 'Nouveau conseil de classe')

@section('content')
<div class="card" style="max-width:560px;">
    <h3 class="font-semibold text-gray-800 mb-4">Nouveau conseil de classe</h3>

    <form method="POST" action="{{ route('admin.conseils.store') }}" class="space-y-4">
        @csrf
        <div class="form-group">
            <label class="form-label">Classe *</label>
            <select name="classe_id" required class="form-select">
                <option value="">-- Choisir --</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }} ({{ $c->section->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Trimestre concerné</label>
            <select name="trimestre_id" class="form-select">
                <option value="">-- Aucun (conseil ponctuel) --</option>
                @foreach ($trimestres as $t)
                    <option value="{{ $t->id }}">{{ $t->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Date du conseil *</label>
            <input type="date" name="date_conseil" required class="form-input" value="{{ date('Y-m-d') }}">
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.conseils.index') }}" class="btn-secondary w-full">Annuler</a>
            <button type="submit" class="btn-primary w-full">Créer</button>
        </div>
    </form>
</div>
@endsection