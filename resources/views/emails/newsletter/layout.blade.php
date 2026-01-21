<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Centre de Gouvernance des Ressources Naturelles Newsletter')</title>
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
        
        .logo-container {
            margin-bottom: 25px;
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
        
        .university-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 12px 24px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Contenu principal avec design GRN UCBC */
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
        
        /* Boutons avec style GRN UCBC */
        .btn {
            display: inline-block;
            padding: 16px 36px;
            background: linear-gradient(135deg, #1e472f 0%, #2d5a3f 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 35px;
            font-weight: 700;
            text-align: center;
            margin: 15px 10px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(30, 71, 47, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 71, 47, 0.4);
            background: linear-gradient(135deg, #d2691e 0%, #b8860b 100%);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: 2px solid #64748b;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
        }
        
        /* Section publication avec design moderne */
        .publication-card {
            background: linear-gradient(135deg, #f0f9f4 0%, #ffffff 100%);
            border: 2px solid #1e472f;
            border-radius: 16px;
            padding: 32px;
            margin: 32px 0;
            position: relative;
            overflow: hidden;
        }
        
        .publication-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
            background: linear-gradient(135deg, #1e472f 0%, #d2691e 100%);
        }
        
        .publication-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e472f;
            margin-bottom: 16px;
            line-height: 1.3;
            font-family: 'Poppins', sans-serif;
        }
        
        .publication-meta {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 18px;
            font-weight: 500;
        }
        
        .publication-excerpt {
            font-size: 16px;
            line-height: 1.8;
            color: #1a1a1a;
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid rgba(30, 71, 47, 0.1);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        /* Footer avec identité GRN UCBC */
        .email-footer {
            background: linear-gradient(135deg, #f0f9f4 0%, #e8f5e8 100%);
            padding: 40px 30px;
            text-align: center;
            border-top: 3px solid #1e472f;
        }
        
        .footer-brand {
            margin-bottom: 20px;
        }
        
        .footer-logo {
            color: #1e472f;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }
        
        .footer-links {
            margin-bottom: 25px;
        }
        
        .footer-links a {
            color: #1e472f;
            text-decoration: none;
            margin: 0 18px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .footer-links a:hover {
            color: #d2691e;
            transform: translateY(-1px);
        }
        
        .footer-text {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
        }
        
        .footer-addresses {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 25px 0;
            text-align: left;
        }
        
        .address-item {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #d2691e;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        
        .address-title {
            font-weight: 700;
            color: #1e472f;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .address-content {
            color: #64748b;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .unsubscribe-link {
            color: #64748b;
            text-decoration: none;
            font-size: 12px;
            transition: color 0.3s ease;
        }
        
        .unsubscribe-link:hover {
            color: #d2691e;
        }
        
        /* Responsive Design */
        @media (max-width: 640px) {
            .email-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .header-content {
                padding: 35px 20px;
            }
            
            .logo-text {
                font-size: 28px;
            }
            
            .tagline {
                font-size: 14px;
            }
            
            .email-content {
                padding: 35px 25px;
            }
            
            .publication-card {
                padding: 25px;
            }
            
            .btn {
                display: block;
                margin: 15px 0;
                padding: 14px 28px;
            }
            
            .footer-addresses {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .footer-text {
                background: #f9fafb;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header avec identité GRN UCBC -->
        <div class="email-header">
            <div class="header-content">
                <div class="logo-container">
                    <div class="logo-text">GRN-UCBC</div>
                    <!-- <div class="tagline">
                        etre Transformee pour Transformer <br>
                        Université Chrétienne Bilingue du Congo
                    </div> -->
                </div>
                <div class="university-badge">
                    Centre de Gouvernance des Ressources Naturelles <br> Université Chrétienne Bilingue du Congo
                </div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="email-content">
            @yield('content')
        </div>
        
        <!-- Footer avec informations complètes GRN UCBC -->
        <div class="email-footer">
            <div class="footer-brand">
                <div class="footer-logo">GRN-UCBC</div>
            </div>
            
            <div class="footer-links">
                <a href="{{ config('app.url') }}">Site Web</a>
                <a href="{{ config('app.url') }}/actualites">Actualités</a>
                <a href="{{ config('app.url') }}/publications">Publications</a>
                <a href="{{ config('app.url') }}/contact">Contact</a>
            </div>
            
            <div class="footer-text">
                <div style="font-family: 'Poppins', sans-serif; font-size: 14px; color: #1e472f; line-height: 1.6; margin-bottom: 20px;">
                  <p style="margin: 0; font-weight: 800;">Centre de Gouvernance des Ressources Naturelles</p>
                  <p style="margin: 0; font-weight: 600;">Université Chrétienne Bilingue du Congo</p>
                  <p style="margin: 8px 0 0 0;">Site web : <a href="https://www.iriucbc.org" style="color: #d2691e; font-weight: 600;">www.iriucbc.org</a></p>
                  <p style="margin: 0;">Email : <a href="mailto:iri@ucbc.org" style="color: #d2691e; font-weight: 600;">iri@ucbc.org</a></p>
                </div>
                
                <div style="margin: 30px 0; padding: 20px 0; border-top: 2px solid #1e472f; border-bottom: 2px solid #1e472f;">
                    <p style="margin: 0; font-weight: 700; color: #1e472f; font-size: 16px; margin-bottom: 20px;">Nos Bureaux</p>
                    
                    <div class="footer-addresses">
                        <div class="address-item">
                            <div class="address-title">📍 Siège Social – Nord-Kivu</div>
                            <div class="address-content">
                                Ville de Beni, Commune Mulekera<br>
                                Quartier Masiani, Avenue de l'Université<br>
                                Cellule Kipriani
                            </div>
                        </div>
                        
                        <div class="address-item">
                            <div class="address-title">🏢 Bureau de Liaison – Tanganyika</div>
                            <div class="address-content">
                                Ville de Kalemie, Commune de Lukuga<br>
                                Quartier Kitali II<br>
                                Avenue Industrielle
                            </div>
                        </div>
                        
                        <div class="address-item">
                            <div class="address-title">🏛️ Bureau de Liaison – Ituri</div>
                            <div class="address-content">
                                Ville de Bunia, Commune Mbunia<br>
                                Quartier Bankoko<br>
                                Avenue Maniema
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(isset($unsubscribeUrl))
            <div style="margin-top: 25px;">
                <a href="{{ $unsubscribeUrl }}" class="unsubscribe-link">
                    Se désabonner de cette newsletter
                </a>
            </div>
            @endif
            
            @if(isset($preferencesUrl))
            <div style="margin-top: 12px;">
                <a href="{{ $preferencesUrl }}" class="unsubscribe-link">
                    Gérer mes préférences d'email
                </a>
            </div>
            @endif
        </div>
    </div>
</body>
       
    </div>
</body>
</html>
