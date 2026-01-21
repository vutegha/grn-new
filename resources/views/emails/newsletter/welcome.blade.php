@extends('emails.newsletter.layout')

@section('title', 'Bienvenue à la newsletter - Centre de Gouvernance des Ressources Naturelles')

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Bienvenue dans la communauté GRN-UCBC !</strong>
    </div>
    
    <div class="content-text">
        Nous sommes ravis de vous compter parmi nos abonnés. Vous recevrez désormais nos dernières actualités, 
        publications de recherche, rapports d'activités et informations sur nos événements directement dans votre boîte mail.
    </div>
    
    <div class="content-text">
        Le Centre de Gouvernance des Ressources Naturelles de l'Université Chrétienne Bilingue du Congo s'engage à partager avec vous :
    </div>
    
    <ul style="margin: 20px 0; padding-left: 30px; color: #4b5563;">
        <li style="margin-bottom: 8px;">📰 <strong>Actualités</strong> : Les dernières nouvelles de notre centre</li>
        <li style="margin-bottom: 8px;">📚 <strong>Publications</strong> : Nos recherches et découvertes scientifiques</li>
        <li style="margin-bottom: 8px;">📊 <strong>Rapports</strong> : Nos analyses et études approfondies</li>
        <li style="margin-bottom: 8px;">🎓 <strong>Événements</strong> : Conférences, séminaires et formations</li>
    </ul>
    
    <div class="content-text">
        Vous pouvez à tout moment personnaliser vos préférences de contenu ou vous désabonner en utilisant les liens 
        disponibles en bas de chaque email.
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.url') }}" class="btn">
            Découvrir notre site web
        </a>
        
        <a href="{{ $preferencesUrl }}" class="btn btn-secondary">
            Gérer mes préférences
        </a>
    </div>
    
    <div class="content-text">
        Merci de votre confiance et à très bientôt dans votre boîte mail !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe GRN-UCBC
    </div>
@endsection
