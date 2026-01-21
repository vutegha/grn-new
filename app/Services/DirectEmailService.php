<?php

namespace App\Services;

use App\Models\Publication;
use App\Models\Newsletter;
use App\Models\Actualite;
use App\Models\Projet;
use App\Models\Rapport;
use Exception;

class DirectEmailService
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;
    
    public function __construct()
    {
        $this->host = config('mail.mailers.smtp.host');
        $this->port = config('mail.mailers.smtp.port');
        $this->username = config('mail.mailers.smtp.username');
        $this->password = config('mail.mailers.smtp.password');
        $this->encryption = config('mail.mailers.smtp.encryption');
    }
    
    public function sendPublicationNewsletter(Publication $publication, Newsletter $subscriber)
    {
        try {
            $subject = '📚 Nouvelle Publication : ' . $publication->titre;
            $html = $this->buildPublicationHtml($publication, $subscriber);
            
            return $this->sendEmail($subscriber->email, $subject, $html);
            
        } catch (Exception $e) {
            \Log::error('Erreur envoi newsletter direct: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendActualiteNewsletter(Actualite $actualite, Newsletter $subscriber)
    {
        try {
            $subject = '📰 Nouvelle Actualité : ' . $actualite->titre;
            $html = $this->buildActualiteHtml($actualite, $subscriber);
            
            return $this->sendEmail($subscriber->email, $subject, $html);
            
        } catch (Exception $e) {
            \Log::error('Erreur envoi actualité newsletter direct: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendProjectNewsletter(Projet $projet, Newsletter $subscriber)
    {
        try {
            $subject = '🚀 Nouveau Projet : ' . $projet->nom;
            $html = $this->buildProjectHtml($projet, $subscriber);
            
            return $this->sendEmail($subscriber->email, $subject, $html);
            
        } catch (Exception $e) {
            \Log::error('Erreur envoi projet newsletter direct: ' . $e->getMessage());
            return false;
        }
    }
    
    public function sendRapportNewsletter(Rapport $rapport, Newsletter $subscriber)
    {
        try {
            $subject = '📊 Nouveau Rapport : ' . $rapport->titre;
            $html = $this->buildRapportHtml($rapport, $subscriber);
            
            return $this->sendEmail($subscriber->email, $subject, $html);
            
        } catch (Exception $e) {
            \Log::error('Erreur envoi rapport newsletter direct: ' . $e->getMessage());
            return false;
        }
    }
    
    private function buildPublicationHtml(Publication $publication, Newsletter $subscriber)
    {
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Publication - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Nouvelle Publication</h1>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        <div class="content">
            <p>Bonjour ' . htmlspecialchars($subscriber->nom ?? 'Cher abonné') . ',</p>
            
            <p>Nous avons le plaisir de vous annoncer la publication de :</p>
            
            <h2 style="color: #ee6751;">' . htmlspecialchars($publication->titre) . '</h2>';
            
        if ($publication->description) {
            $html .= '<p>' . nl2br(htmlspecialchars(substr($publication->description, 0, 500))) . '</p>';
        }
        
        $html .= '
            <p>Cordialement,<br>
            L\'équipe du Centre de Gouvernance des Ressources Naturelles</p>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }
    
    private function buildActualiteHtml(Actualite $actualite, Newsletter $subscriber)
    {
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Actualité - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📰 Nouvelle Actualité</h1>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        <div class="content">
            <p>Bonjour ' . htmlspecialchars($subscriber->nom ?? 'Cher abonné') . ',</p>
            
            <p>Nous avons le plaisir de vous informer de cette nouvelle actualité :</p>
            
            <h2 style="color: #ee6751;">' . htmlspecialchars($actualite->titre) . '</h2>';
            
        if ($actualite->description) {
            $html .= '<p>' . nl2br(htmlspecialchars(substr($actualite->description, 0, 500))) . '</p>';
        }
        
        $html .= '
            <p>Cordialement,<br>
            L\'équipe du Centre de Gouvernance des Ressources Naturelles</p>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }
    
    private function buildProjectHtml(Projet $projet, Newsletter $subscriber)
    {
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Projet - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Nouveau Projet</h1>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        <div class="content">
            <p>Bonjour ' . htmlspecialchars($subscriber->nom ?? 'Cher abonné') . ',</p>
            
            <p>Nous sommes heureux de vous présenter notre nouveau projet :</p>
            
            <h2 style="color: #ee6751;">' . htmlspecialchars($projet->nom) . '</h2>';
            
        if ($projet->description) {
            $html .= '<p>' . nl2br(htmlspecialchars(substr($projet->description, 0, 500))) . '</p>';
        }
        
        $html .= '
            <p>Cordialement,<br>
            L\'équipe du Centre de Gouvernance des Ressources Naturelles</p>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }
    
    private function buildRapportHtml(Rapport $rapport, Newsletter $subscriber)
    {
        $html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Rapport - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Nouveau Rapport</h1>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>

        <div class="content">
            <p>Bonjour ' . htmlspecialchars($subscriber->nom ?? 'Cher abonné') . ',</p>
            
            <p>Un nouveau rapport est maintenant disponible :</p>
            
            <h2 style="color: #ee6751;">' . htmlspecialchars($rapport->titre) . '</h2>';
            
        if ($rapport->description) {
            $html .= '<p>' . nl2br(htmlspecialchars(substr($rapport->description, 0, 500))) . '</p>';
        }
        
        $html .= '
            <p>Cordialement,<br>
            L\'équipe du Centre de Gouvernance des Ressources Naturelles</p>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous êtes abonné à notre newsletter.</p>
            <p>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</p>
        </div>
    </div>
</body>
</html>';

        return $html;
    }
    
    private function sendEmail($to, $subject, $html)
    {
        // Utiliser Symfony Mailer directement
        $dsn = sprintf(
            'smtp://%s:%s@%s:%s?encryption=%s',
            urlencode($this->username),
            urlencode($this->password),
            $this->host,
            $this->port,
            $this->encryption
        );
        
        $transport = \Symfony\Component\Mailer\Transport::fromDsn($dsn);
        $mailer = new \Symfony\Component\Mailer\Mailer($transport);
        
        $message = (new \Symfony\Component\Mime\Email())
            ->from($this->username, 'Centre de Gouvernance des Ressources Naturelles')
            ->to($to)
            ->subject($subject)
            ->html($html);
        
        $mailer->send($message);
        return true;
    }
}
