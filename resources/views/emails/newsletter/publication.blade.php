@extends('emails.newsletter.layout')

@section('title', 'Nouvelle publication - ' . $publication->titre)

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Une nouvelle publication est disponible sur GRN-UCBC !</strong>
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $publication->titre }}
        </div>
        
        <div class="publication-meta">
            📚 Publication • Publié le {{ $publication->created_at->format('d/m/Y') }}
            @if($publication->categorie)
                • Catégorie: {{ $publication->categorie->nom }}
            @endif
        </div>
        
        @if($publication->auteurs && $publication->auteurs->count() > 0)
            <div style="margin: 15px 0; padding: 12px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1e472f;">
                <strong>Auteur(s) :</strong> {{ $publication->auteurs->pluck('nom')->join(', ') }}
            </div>
        @endif
        
        @if($publication->resume)
            <div class="publication-excerpt">
                {{ $publication->resume }}
            </div>
        @elseif($publication->description)
            <div class="publication-excerpt">
                {!! Str::limit(strip_tags($publication->description), 300) !!}
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('publication.show', $publication->slug ?? $publication->id) }}" class="btn">
                📖 Consulter la publication
            </a>
            
            @if($publication->fichier)
                <a href="{{ asset('storage/' . $publication->fichier) }}" class="btn btn-secondary" target="_blank">
                    📥 Télécharger le PDF
                </a>
            @endif
        </div>
    </div>
    
    <div class="content-text">
        Cette publication fait partie de nos travaux de recherche en gouvernance des ressources naturelles. 
        Nous espérons qu'elle enrichira vos connaissances et vos réflexions.
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('site.publications') }}" class="btn btn-secondary">
            Voir toutes nos publications
        </a>
    </div>
    
    <div class="content-text">
        Merci de votre intérêt pour nos travaux !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe de recherche GRN-UCBC<br>
        Centre de Gouvernance des Ressources Naturelles
    </div>
@endsection
