<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'DejaVu Sans',Arial,sans-serif;font-size:9px;color:#111; }
.header { text-align:center;border-bottom:2px solid #1a3a6b;padding-bottom:8px;margin-bottom:12px; }
.school { font-weight:bold;font-size:13px;color:#1a3a6b; }
.title { font-size:11px;font-weight:bold;margin-top:4px; }
.confidentiel { background:#fef2f2;color:#991b1b;text-align:center;padding:6px;font-size:8px;font-weight:bold;border-radius:4px;margin-bottom:10px; }
table { width:100%;border-collapse:collapse; }
th { background:#1a3a6b;color:#fff;padding:5px 4px;font-size:8px;border:1px solid #333;text-align:center; }
td { border:1px solid #aaa;padding:4px;font-size:8.5px; }
.mdp { font-family:monospace;background:#f3f4f6;padding:1px 4px;border-radius:3px; }
.footer { text-align:center;font-size:7px;color:#888;margin-top:15px;border-top:1px solid #ccc;padding-top:6px; }
</style>
</head>
<body>

<div class="header">
    <div class="school">{{ strtoupper($etablissement->nom ?? '') }}</div>
    <div class="title">LISTE DES IDENTIFIANTS DE CONNEXION</div>
    <div style="font-size:8px;margin-top:3px;">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
</div>

<div class="confidentiel">
    DOCUMENT CONFIDENTIEL — À DISTRIBUER UNIQUEMENT AUX PERSONNES CONCERNÉES — NE PAS DIFFUSER
</div>

<table>
<thead>
<tr>
    <th>Nom</th>
    <th>Email</th>
    <th>Mot de passe</th>
    <th>Rôle</th>
    <th>Élève lié</th>
</tr>
</thead>
<tbody>
@foreach ($comptes as $c)
<tr>
    <td>{{ $c->nom }}</td>
    <td>{{ $c->email }}</td>
    <td><span class="mdp">{{ $c->mot_de_passe }}</span></td>
    <td>{{ ucfirst(str_replace('_',' ',$c->role)) }}</td>
    <td>{{ $c->eleve_lie ?? '-' }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="footer">
    {{ strtoupper($etablissement->nom ?? '') }} — {{ $comptes->count() }} compte(s) — Document généré automatiquement
</div>

</body>
</html>