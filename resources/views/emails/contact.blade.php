<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #1f2937; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border-radius: 0 0 8px 8px; border: 1px solid #e5e7eb; }
        .row { margin-bottom: 12px; }
        .label { font-weight: bold; color: #374151; }
        .message-box { background: white; padding: 15px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">Nouveau message de contact</h2>
        </div>
        <div class="content">
            <div class="row"><span class="label">Nom :</span> {{ $data['nom'] }}</div>
            <div class="row"><span class="label">Téléphone :</span> {{ $data['telephone'] }}</div>
            @if (!empty($data['email']))
            <div class="row"><span class="label">Email :</span> {{ $data['email'] }}</div>
            @endif
            <div class="row"><span class="label">Sujet :</span> {{ $data['sujet'] }}</div>

            <div class="message-box">
                <p style="margin:0;">{{ $data['message'] }}</p>
            </div>
        </div>
    </div>
</body>
</html>