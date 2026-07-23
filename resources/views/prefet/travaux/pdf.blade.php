<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:11px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:8px;margin-bottom:16px; }
.school { font-weight:bold;font-size:14px;color:#1a3a6b; }
.title { font-size:16px;font-weight:bold;margin-top:10px; }
.meta { font-size:10px;color:#666;margin-top:6px; }
.content { margin-top:20px;white-space:pre-wrap;line-height:1.6; }
</style>
</head>
<body>
<div class="header">
    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="title">{{ $travailDirige->titre }}</div>
    <div class="meta">
        {{ $travailDirige->matiere->nom }} — {{ $travailDirige->classe->nom }} ·
        Enseignant : {{ $travailDirige->enseignant->user->name }} ·
        Publié le {{ $travailDirige->date_publication->format('d/m/Y') }}
    </div>
</div>
<div class="content">{{ $travailDirige->contenu }}</div>
</body>
</html>