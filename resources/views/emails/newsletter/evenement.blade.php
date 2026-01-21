@extends('emails.newsletter.layout')

@section('title', 'Nouvel événement - ' . $evenement->nom)

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Un nouvel événement est organisé par GRN-UCBC !</strong>
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $evenement->nom }}
        </div>
        
        <div class="publication-meta">
            🎯 Événement • {{ $evenement->date_debut ? \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y') : 'Date à venir' }}
            @if($evenement->lieu)
                • 📍 {{ $evenement->lieu }}
            @endif
        </div>
        
        @if($evenement->image)
            <div style="text-align: center; margin: 20px 0;">
                <img src="{{ asset('storage/' . $evenement->image) }}" alt="{{ $evenement->nom }}" 
                     style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
        @endif
        
        @if($evenement->description)
            <div class="publication-excerpt">
                {!! Str::limit(strip_tags($evenement->description), 300) !!}
            </div>
        @endif
        
        @if($evenement->date_debut || $evenement->date_fin || $evenement->lieu)
            <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1e472f;">
                @if($evenement->date_debut)
                    <div style="margin-bottom: 8px;">
                        <strong>📅 Date de début :</strong> {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y à H:i') }}
                    </div>
                @endif
                
                @if($evenement->date_fin)
                    <div style="margin-bottom: 8px;">
                        <strong>📅 Date de fin :</strong> {{ \Carbon\Carbon::parse($evenement->date_fin)->format('d/m/Y à H:i') }}
                    </div>
                @endif
                
                @if($evenement->lieu)
                    <div style="margin-bottom: 8px;">
                        <strong>📍 Lieu :</strong> {{ $evenement->lieu }}
                    </div>
                @endif
                
                @if($evenement->places_disponibles)
                    <div>
                        <strong>👥 Places disponibles :</strong> {{ $evenement->places_disponibles }}
                    </div>
                @endif
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('site.evenement.show', $evenement->slug ?? $evenement->id) }}" class="btn">
                📖 Voir les détails
            </a>
            
            @if($evenement->inscription_requise)
                <a href="{{ route('site.evenement.show', $evenement->slug ?? $evenement->id) }}" class="btn btn-secondary">
                    📝 S'inscrire
                </a>
            @endif
        </div>
    </div>
    
    <div class="content-text">
        Cet événement s'inscrit dans notre mission de sensibilisation et de formation en gouvernance des ressources naturelles. 
        Nous vous invitons à y participer !
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('site.evenements') }}" class="btn btn-secondary">
            Voir tous nos événements
        </a>
    </div>
    
    <div class="content-text">
        Au plaisir de vous retrouver lors de cet événement !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe événementielle GRN-UCBC<br>
        Centre de Gouvernance des Ressources Naturelles
    </div>
@endsection
