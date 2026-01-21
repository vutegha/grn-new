<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copie de votre message</title>
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
        .success-badge {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
        .contact-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .contact-box h4 {
            color: #1e40af;
            margin-top: 0;
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
        <h1>✅ Message Bien Reçu</h1>
        <p>Copie de confirmation - Site Web CI-UCBC</p>
    </div>

    <div class="content">
        <div class="success-badge">
            <strong>✓ Merci pour votre message !</strong><br>
            <small>Nous avons bien reçu votre message et vous répondrons dans les plus brefs délais.</small>
        </div>

        <p>Bonjour <strong>{{ $contact->nom }}</strong>,</p>
        <p>Ceci est une copie de confirmation du message que vous nous avez envoyé via notre formulaire de contact.</p>

        <div class="info-box">
            <div class="label">👤 Votre nom :</div>
            <div class="value">{{ $contact->nom }}</div>

            <div class="label">📧 Votre adresse email :</div>
            <div class="value">{{ $contact->email }}</div>

            <div class="label">📝 Sujet :</div>
            <div class="value">{{ $contact->sujet }}</div>

            <div class="label">📅 Date d'envoi :</div>
            <div class="value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
        </div>

        <div class="label">💬 Votre message :</div>
        <div class="message-content">
            {!! nl2br(e($contact->message)) !!}
        </div>

        <hr style="border: none; height: 1px; background: #e2e8f0; margin: 30px 0;">

        <p><strong>⏰ Prochaines étapes :</strong></p>
        <ul>
            <li><strong>Délai de réponse :</strong> Nous vous répondrons sous 24-48 heures ouvrables</li>
            <li><strong>Vérification :</strong> Surveillez votre boîte de réception (et vos spams)</li>
            <li><strong>Newsletter :</strong> Vous avez été automatiquement ajouté à notre liste de diffusion</li>
            <li><strong>Urgence :</strong> Pour les demandes urgentes, contactez-nous par téléphone</li>
        </ul>

        <div class="contact-box">
            <h4>📞 Autres moyens de nous contacter</h4>
            <p style="margin: 5px 0;"><strong>Téléphone :</strong> +243 992 405 948</p>
            <p style="margin: 5px 0;"><strong>Email :</strong> <a href="mailto:iri@ucbc.org" style="color: #2563eb;">iri@ucbc.org</a></p>
            <p style="margin: 5px 0;"><strong>Adresse :</strong> Programme Gouvernance des Ressources Naturelles - UCBC, Kinshasa</p>
            <p style="margin: 5px 0;"><strong>Site web :</strong> <a href="{{ url('/') }}" style="color: #2563eb;">{{ url('/') }}</a></p>
        </div>

        <div class="timestamp">
            Message envoyé le {{ $contact->created_at->format('d/m/Y à H:i') }}
        </div>
    </div>

    <div class="footer">
        <p><strong>Centre de Gouvernance des Ressources Naturelles</strong></p>
        <p>Programme Gouvernance des Ressources Naturelles - UCBC (CI-UCBC)</p>
        <p>📧 iri@ucbc.org | 📞 +243 992 405 948</p>
        <p style="margin-top: 15px; font-size: 12px; opacity: 0.8;">
            Ceci est un email automatique de confirmation. Merci de ne pas répondre à cette adresse.
        </p>
    </div>
</body>
</html>
