<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }
        .footer {
            background: #1f2937;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 14px;
        }
        .info-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .value {
            color: #1f2937;
            margin-bottom: 15px;
        }
        .message-content {
            background: white;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }
        .timestamp {
            color: #6b7280;
            font-size: 14px;
            text-align: right;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔔 Nouveau Message de Contact</h1>
        <p>Site Web CI-UCBC</p>
    </div>

    <div class="content">
        <p>Bonjour,</p>
        <p>Vous avez reçu un nouveau message via le formulaire de contact du site web.</p>

        <div class="info-box">
            <div class="label">👤 Expéditeur :</div>
            <div class="value">{{ $contact->nom }}</div>

            <div class="label">📧 Adresse email :</div>
            <div class="value">
                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
            </div>

            <div class="label">📝 Sujet :</div>
            <div class="value">{{ $contact->sujet }}</div>

            <div class="label">📅 Date d'envoi :</div>
            <div class="value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
        </div>

        <div class="label">💬 Message :</div>
        <div class="message-content">
            {!! nl2br(e($contact->message)) !!}
        </div>

        <hr style="border: none; height: 1px; background: #e2e8f0; margin: 30px 0;">

        <p><strong>Actions possibles :</strong></p>
        <ul>
            <li>Répondre directement à cet email (l'adresse de réponse est configurée automatiquement)</li>
            <li>Consulter et gérer ce message dans l'interface d'administration</li>
            <li>L'email {{ $contact->email }} a été automatiquement ajouté à votre liste de diffusion</li>
        </ul>

        <div class="timestamp">
            Message généré automatiquement le {{ now()->format('d/m/Y à H:i') }}
        </div>
    </div>

    <div class="footer">
        <p><strong>Centre de Gouvernance des Ressources Naturelles</strong></p>
        <p>Programme Gouvernance des Ressources Naturelles - UCBC (CI-UCBC)</p>
        <p>📧 iri@ucbc.org | 📞 +243 992 405 948</p>
    </div>
</body>
</html>
