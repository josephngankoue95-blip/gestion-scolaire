{{-- resources/views/emails/candidature-statut.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:24px;">
    <div style="max-width:520px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1a3a6b,#2563eb);padding:24px;text-align:center;">
            <h2 style="color:#fff;margin:0;">Suivi de candidature</h2>
        </div>
        <div style="padding:24px;">
            <p>Bonjour,</p>
            <p>
                Nous vous informons que la candidature de
                <strong>{{ $candidature->prenom_candidat }} {{ $candidature->nom_candidat }}</strong>
                a été mise à jour :
            </p>
            <div style="background:#f0f4ff;border-radius:8px;padding:14px;text-align:center;margin:16px 0;">
                <span style="font-size:16px;font-weight:bold;color:#1a3a6b;">{{ $statutLibelle }}</span>
            </div>
            @if($candidature->observation)
            <p style="color:#6b7280;font-size:13px;">
                <strong>Observation :</strong> {{ $candidature->observation }}
            </p>
            @endif
            <p style="margin-top:20px;">Pour toute question, contactez notre administration.</p>
            <p style="color:#9ca3af;font-size:12px;margin-top:24px;">Ce message est généré automatiquement, merci de ne pas y répondre directement.</p>
        </div>
    </div>
</body>
</html>