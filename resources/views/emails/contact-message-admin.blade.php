<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - Centre de Gouvernance des Ressources Naturelles</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.7;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .email-wrapper {
            max-width: 680px;
            margin: 0 auto;
            background: transparent;
        }
        
        .email-container {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 
                0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -1px rgba(0, 0, 0, 0.06),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .header {
            background: linear-gradient(135deg, #0f4c3a 0%, #1a7f5c 50%, #22c55e 100%);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo-section {
            margin-bottom: 20px;
        }
        
        .university-name {
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            letter-spacing: -0.5px;
        }
        
        .program-name {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .notification-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 12px 24px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
        }
        
        .notification-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 35px;
        }
        
        .priority-alert {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }
        
        .priority-alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #f59e0b;
        }
        
        .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .alert-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            background: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            margin-top: 2px;
        }
        
        .alert-text {
            flex: 1;
        }
        
        .alert-title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 4px;
            font-size: 15px;
        }
        
        .alert-description {
            color: #a16207;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .contact-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 28px;
            border: 1px solid #e2e8f0;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f4c3a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-icon {
            width: 20px;
            height: 20px;
            fill: #22c55e;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 0;
        }
        
        .info-item {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .info-item:hover {
            border-color: #22c55e;
            box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.1);
        }
        
        .info-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #22c55e;
            border-radius: 0 2px 2px 0;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .info-item:hover::before {
            opacity: 1;
        }
        
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 500;
            word-break: break-word;
        }
        
        .message-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #22c55e;
            border-radius: 12px;
            padding: 28px;
            margin: 28px 0;
            position: relative;
            overflow: hidden;
        }
        
        .message-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #22c55e;
        }
        
        .message-label {
            font-weight: 700;
            color: #0f4c3a;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .message-content {
            background: #ffffff;
            padding: 24px;
            border-radius: 8px;
            border: 1px solid #d1fae5;
            white-space: pre-line;
            line-height: 1.8;
            font-size: 15px;
            color: #374151;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .actions-section {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #3b82f6;
            border-radius: 12px;
            padding: 28px;
            margin: 28px 0;
            position: relative;
        }
        
        .actions-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
        }
        
        .actions-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            color: #1e40af;
            font-weight: 700;
            font-size: 16px;
        }
        
        .actions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .actions-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            color: #1e40af;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .actions-list li:last-child {
            margin-bottom: 0;
        }
        
        .action-bullet {
            flex-shrink: 0;
            width: 6px;
            height: 6px;
            background: #3b82f6;
            border-radius: 50%;
            margin-top: 8px;
        }
        
        .footer {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 32px 35px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .footer-brand {
            margin-bottom: 16px;
        }
        
        .footer-title {
            color: #0f4c3a;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .footer-subtitle {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 16px;
        }
        
        .footer-meta {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.5;
            font-family: 'Monaco', 'Menlo', monospace;
        }
        
        .footer-meta strong {
            color: #6b7280;
        }
        
        .divider {
            color: #d1d5db;
            margin: 0 8px;
        }
        
        /* Responsive Design */
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .email-wrapper {
                max-width: 100%;
            }
            
            .header-content {
                padding: 30px 20px;
            }
            
            .university-name {
                font-size: 24px;
            }
            
            .program-name {
                font-size: 14px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .contact-info,
            .message-section,
            .actions-section,
            .priority-alert {
                padding: 20px;
            }
            
            .footer {
                padding: 24px 20px;
            }
            
            .notification-badge {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .university-name {
                font-size: 20px;
            }
            
            .program-name {
                font-size: 12px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .info-item {
                padding: 16px;
            }
            
            .message-content {
                padding: 20px;
                font-size: 14px;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .footer-meta {
                background: #f9fafb;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <div class="header-content">
                    <div class="logo-section">
                        <div class="university-name">GRN-UCBC</div>
                        <div class="program-name">Programme Gouvernance des Ressources Naturelles</div>
                    </div>
                    
                    <div class="notification-badge">
                        <svg class="notification-icon" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        Nouveau Message de Contact
                    </div>
                </div>
            </div>
            
            <div class="content">
                <div class="priority-alert">
                    <div class="alert-content">
                        <div class="alert-icon">!</div>
                        <div class="alert-text">
                            <div class="alert-title">Action Prioritaire Requise</div>
                            <div class="alert-description">
                                Un nouveau message de contact a été reçu via le formulaire officiel du site web. 
                                Une réponse rapide est recommandée pour maintenir l'excellence de notre service.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-info">
                    <div class="section-title">
                        <svg class="section-icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Informations du Contact
                    </div>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 1L13.5 2.5L16.17 5.17L10.59 10.75C10.21 11.13 10.21 11.75 10.59 12.13L11.87 13.41C12.25 13.79 12.87 13.79 13.25 13.41L18.83 7.83L21.5 10.5L21 9Z"/>
                                </svg>
                                Nom Complet
                            </div>
                            <div class="info-value">{{ $contact->nom }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z"/>
                                </svg>
                                Adresse Email
                            </div>
                            <div class="info-value">{{ $contact->email }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21,6V8H3V6H21M3,18H12V16H3V18M3,13H21V11H3V13Z"/>
                                </svg>
                                Objet du Message
                            </div>
                            <div class="info-value">{{ $contact->sujet }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z"/>
                                </svg>
                                Date de Réception
                            </div>
                            <div class="info-value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="message-section">
                    <div class="message-label">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4C22,2.89 21.1,2 20,2Z"/>
                        </svg>
                        Message Reçu
                    </div>
                    <div class="message-content">{{ $contact->message }}</div>
                </div>
                
                <div class="actions-section">
                    <div class="actions-title">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z"/>
                        </svg>
                        Plan d'Action Recommandé
                    </div>
                    <ul class="actions-list">
                        <li>
                            <div class="action-bullet"></div>
                            <span>Répondre dans les 24 heures pour maintenir l'excellence de notre service client</span>
                        </li>
                        <li>
                            <div class="action-bullet"></div>
                            <span>Vérifier si ce contact existe déjà dans notre base de données institutionnelle</span>
                        </li>
                        <li>
                            <div class="action-bullet"></div>
                            <span>Marquer ce message comme traité une fois la réponse personnalisée envoyée</span>
                        </li>
                        <li>
                            <div class="action-bullet"></div>
                            <span>Considérer l'ajout automatique à notre newsletter académique (déjà effectué)</span>
                        </li>
                        <li>
                            <div class="action-bullet"></div>
                            <span>Archiver la correspondance selon nos standards de documentation</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer">
                <div class="footer-brand">
                    <div class="footer-title">Centre de Gouvernance des Ressources Naturelles - UCBC</div>
                    <div class="footer-subtitle">Programme Gouvernance des Ressources Naturelles - UCBC</div>
                </div>
                
                <div class="footer-meta">
                    <strong>Métadonnées Système:</strong><br>
                    ID Message: #{{ $contact->id }} <span class="divider">|</span> 
                    IP Client: {{ request()->ip() ?? 'N/A' }} <span class="divider">|</span><br>
                    User Agent: {{ Str::limit(request()->userAgent() ?? 'N/A', 60) }}<br>
                    <em>Email automatique généré par le système de contact officiel</em>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
