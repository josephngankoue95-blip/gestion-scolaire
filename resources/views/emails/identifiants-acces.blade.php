<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:24px;">
    <div style="max-width:480px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);padding:24px;text-align:center;">
            <h2 style="color:#fff;margin:0;">{{ $etablissement->nom ?? '' }}</h2>
            <p style="color:#dbe9ff;font-size:13px;margin-top:4px;">Vos identifiants de connexion</p>
        </div>
        <div style="padding:24px;">
            <p>Bonjour <strong>{{ $compte->nom }}</strong>,</p>
            <p>Voici vos identifiants pour accéder à la plateforme de gestion scolaire :</p>

            <div style="background:#f0f4ff;border-radius:8px;padding:16px;margin:16px 0;">
                <p style="margin:0 0 8px;font-size:13px;color:#555;">Email de connexion</p>
                <p style="margin:0 0 14px;font-weight:bold;font-size:15px;color:#1a3a6b;">{{ $compte->email }}</p>
                <p style="margin:0 0 8px;font-size:13px;color:#555;">Mot de passe</p>
                <p style="margin:0;font-weight:bold;font-size:15px;color:#1a3a6b;">{{ $compte->mot_de_passe }}</p>
            </div>

            <p style="font-size:12.5px;color:#6b7280;">
                Pour des raisons de sécurité, nous vous recommandons de modifier votre mot de passe
                dès votre première connexion.
            </p>

            <a href="{{ route('login') }}" style="display:inline-block;margin-top:12px;background:#1a3a6b;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:13px;">
                Se connecter maintenant
            </a>

            <p style="color:#9ca3af;font-size:11px;margin-top:24px;">
                Ce message est confidentiel, merci de ne pas le transférer.
            </p>
        </div>
    </div>
</body>
</html>