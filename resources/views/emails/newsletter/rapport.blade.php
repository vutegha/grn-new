@extends('emails.newsletter.layout')

@section('title', 'Nouveau rapport disponible - ' . $rapport->titre)

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Un nouveau rapport est disponible sur GRN-UCBC !</strong>
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $rapport->titre }}
        </div>
        
        <div class="publication-meta">
            📊 Rapport • Publié le {{ $rapport->created_at->format('d/m/Y') }}
            @if($rapport->categorie)
                • Catégorie: {{ $rapport->categorie->nom }}
            @endif
        </div>
        
        @if($rapport->description)
            <div class="publication-excerpt">
                {!! Str::limit(strip_tags($rapport->description), 300) !!}
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('publication.show', $rapport->slug ?? $rapport->id) }}" class="btn">
                📖 Consulter le rapport
            </a>
            
            @if($rapport->fichier)
                <a href="{{ asset('storage/' . $rapport->fichier) }}" class="btn btn-secondary" target="_blank">
                    📥 Télécharger le PDF
                </a>
            @endif
        </div>
    </div>
    
    <div class="content-text">
        Ce rapport fait partie de nos recherches continues sur la gouvernance des ressources naturelles. 
        Nous espérons qu'il vous sera utile dans vos travaux et réflexions.
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('site.publications') }}" class="btn btn-secondary">
            Voir toutes nos publications
        </a>
    </div>
    
    <div class="content-text">
        Merci de votre intérêt pour nos travaux de recherche !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe du Programme GRN-UCBC<br>
        Centre de Gouvernance des Ressources Naturelles
    </div>
@endsection
