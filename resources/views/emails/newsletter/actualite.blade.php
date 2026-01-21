@extends('emails.newsletter.layout')

@section('title', 'Nouvelle actualité - ' . $actualite->titre)

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Une nouvelle actualité est disponible sur GRN-UCBC !</strong>
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $actualite->titre }}
        </div>
        
        <div class="publication-meta">
            📰 Actualité • Publié le {{ $actualite->created_at->format('d/m/Y') }}
            @if($actualite->service)
                • Service: {{ $actualite->service->nom }}
            @endif
        </div>
        
        @if($actualite->image)
            <div style="text-align: center; margin: 20px 0;">
                <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" 
                     style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
        @endif
        
        @if($actualite->resume)
            <div class="publication-excerpt">
                {{ $actualite->resume }}
            </div>
        @elseif($actualite->texte)
            <div class="publication-excerpt">
                {!! Str::limit(strip_tags($actualite->texte), 300) !!}
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('site.actualite.show', $actualite->slug ?? $actualite->id) }}" class="btn">
                📖 Lire l'actualité
            </a>
        </div>
    </div>
    
    <div class="content-text">
        Restez informé(e) de toutes nos activités et suivez l'actualité de la gouvernance des ressources naturelles.
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('site.actualites') }}" class="btn btn-secondary">
            Voir toutes nos actualités
        </a>
    </div>
    
    <div class="content-text">
        Merci de votre fidélité !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe de communication GRN-UCBC<br>
        Centre de Gouvernance des Ressources Naturelles
    </div>
@endsection
