<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe - GRN-UCBC</title>
    <style>
        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.7;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f0f9f4 0%, #e8f5e8 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 680px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 
                0 10px 25px rgba(30, 71, 47, 0.15),
                0 4px 10px rgba(30, 71, 47, 0.08);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(30, 71, 47, 0.1);
        }
        
        /* Header avec couleurs officielles GRN UCBC */
        .email-header {
            background: linear-gradient(135deg, #1e472f 0%, #2d5a3f 50%, #d2691e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="8" height="8" patternUnits="userSpaceOnUse"><path d="M 8 0 L 0 0 0 8" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.4;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
            padding: 45px 30px;
            text-align: center;
        }
        
        .security-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: #ffffff;
        }
        
        .logo-text {
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 10px;
            color: #ffffff;
            text-shadow: 0 3px 6px rgba(0,0,0,0.2);
            font-family: 'Poppins', sans-serif;
        }
        
        .tagline {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.5;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Contenu principal */
        .email-content {
            padding: 45px 35px;
            background: #ffffff;
        }
        
        .greeting {
            font-size: 22px;
            margin-bottom: 28px;
            color: #1e472f;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
        }
        
        .content-text {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 24px;
            color: #1a1a1a;
        }
        
        .security-notice {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            position: relative;
        }
        
        .security-notice::before {
            content: '⚠️';
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 20px;
        }
        
        .security-notice-content {
            margin-left: 35px;
            font-size: 14px;
            color: #92400e;
            font-weight: 500;
        }
        
        /* Bouton de réinitialisation principal */
        .reset-button {
            display: inline-block;
            padding: 18px 40px;
            background: linear-gradient(135deg, #1e472f 0%, #2d5a3f 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 35px;
            font-weight: 700;
            text-align: center;
            margin: 25px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 6px 20px rgba(30, 71, 47, 0.3);
            width: 100%;
            max-width: 300px;
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(30, 71, 47, 0.4);
            background: linear-gradient(135deg, #d2691e 0%, #b8860b 100%);
        }
        
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        
        /* Section informative */
        .info-section {
            background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
            border: 2px solid rgba(30, 71, 47, 0.1);
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .info-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e472f;
            margin-bottom: 15px;
            font-family: 'Poppins', sans-serif;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .info-list li {
            margin-bottom: 12px;
            padding-left: 30px;
            position: relative;
            color: #374151;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .info-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 0;
            color: #1e472f;
            font-weight: bold;
            font-size: 16px;
        }
        
        /* Footer */
        .email-footer {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 35px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: #1e472f;
            margin-bottom: 15px;
            font-family: 'Poppins', sans-serif;
        }
        
        .footer-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        
        .contact-info {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 20px;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .email-container {
                margin: 10px auto;
            }
            
            .email-content {
                padding: 25px 20px;
            }
            
            .header-content {
                padding: 30px 20px;
            }
            
            .logo-text {
                font-size: 28px;
            }
            
            .reset-button {
                width: 100%;
                max-width: none;
                padding: 16px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="header-content">
                <div class="security-icon">🔐</div>
                <div class="logo-text">GRN-UCBC</div>
                <div class="tagline">
                    Centre de Gouvernance des Ressources Naturelles<br>
                    Université Chrétienne Bilingue du Congo
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="email-content">
            <div class="greeting">
                Réinitialisation de votre mot de passe
            </div>
            
            <div class="content-text">
                Bonjour,
            </div>
            
            <div class="content-text">
                Vous avez demandé la réinitialisation de votre mot de passe pour votre compte <strong>{{ $email }}</strong> 
                sur la plateforme GRN-UCBC.
            </div>
            
            <div class="security-notice">
                <div class="security-notice-content">
                    <strong>Important :</strong> Ce lien est valide pendant 60 minutes pour des raisons de sécurité. 
                    Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.
                </div>
            </div>
            
            <div class="content-text">
                Pour créer un nouveau mot de passe sécurisé, cliquez sur le bouton ci-dessous :
            </div>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔑 Réinitialiser mon mot de passe
                </a>
            </div>
            
            <div class="info-section">
                <div class="info-title">Critères de sécurité du nouveau mot de passe :</div>
                <ul class="info-list">
                    <li>Au minimum 8 caractères</li>
                    <li>Au moins une lettre majuscule</li>
                    <li>Au moins une lettre minuscule</li>
                    <li>Au moins un chiffre</li>
                    <li>Au moins un caractère spécial (@$!%*?&)</li>
                </ul>
            </div>
            
            <div class="content-text">
                Si le bouton ne fonctionne pas, copiez et collez cette URL dans votre navigateur :
            </div>
            
            <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; font-family: monospace; word-break: break-all; font-size: 13px; color: #374151; border: 1px solid #d1d5db;">
                {{ $resetUrl }}
            </div>
            
            <div class="content-text" style="margin-top: 30px;">
                Pour votre sécurité, ne partagez jamais ce lien avec personne.
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-logo">GRN-UCBC</div>
            <div class="footer-text">
                Centre de Gouvernance des Ressources Naturelles<br>
                Université Chrétienne Bilingue du Congo
            </div>
            <div class="footer-text">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.
            </div>
            <div class="contact-info">
                Pour toute question, contactez notre support technique<br>
                © {{ date('Y') }} GRN-UCBC. Tous droits réservés.
            </div>
        </div>
    </div>
</body>
</html>
