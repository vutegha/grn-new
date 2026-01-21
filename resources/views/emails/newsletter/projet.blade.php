@extends('emails.newsletter.layout')

@section('title', 'Nouveau projet - ' . $projet->nom)

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        <strong>Un nouveau projet a été lancé au sein de GRN-UCBC !</strong>
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $projet->nom }}
        </div>
        
        <div class="publication-meta">
            🚀 Projet • {{ ucfirst($projet->etat ?? 'en cours') }}
            @if($projet->service)
                • Service: {{ $projet->service->nom }}
            @endif
        </div>
        
        @if($projet->image)
            <div style="text-align: center; margin: 20px 0;">
                <img src="{{ asset('storage/' . $projet->image) }}" alt="{{ $projet->nom }}" 
                     style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
        @endif
        
        @if($projet->resume)
            <div class="publication-excerpt">
                {{ $projet->resume }}
            </div>
        @elseif($projet->description)
            <div class="publication-excerpt">
                {!! Str::limit(strip_tags($projet->description), 300) !!}
            </div>
        @endif
        
        @if($projet->date_debut || $projet->date_fin || $projet->budget)
            <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1e472f;">
                @if($projet->date_debut)
                    <div style="margin-bottom: 8px;">
                        <strong>📅 Date de début :</strong> {{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}
                    </div>
                @endif
                
                @if($projet->date_fin)
                    <div style="margin-bottom: 8px;">
                        <strong>📅 Date de fin prévue :</strong> {{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}
                    </div>
                @endif
                
                @if($projet->budget)
                    <div style="margin-bottom: 8px;">
                        <strong>💰 Budget :</strong> {{ number_format($projet->budget, 0, ',', ' ') }} USD
                    </div>
                @endif
                
                @if($projet->beneficiaires_total)
                    <div>
                        <strong>👥 Bénéficiaires visés :</strong> {{ number_format($projet->beneficiaires_total) }} personnes
                    </div>
                @endif
            </div>
        @endif
        
        <div style="text-align: center; margin-top: 25px;">
            <a href="{{ route('site.projet.show', $projet->slug ?? $projet->id) }}" class="btn">
                📖 Découvrir le projet
            </a>
        </div>
    </div>
    
    <div class="content-text">
        Ce projet s'inscrit dans notre mission de promotion de la bonne gouvernance des ressources naturelles. 
        Suivez son évolution et ses impacts sur notre site.
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('site.projets') }}" class="btn btn-secondary">
            Voir tous nos projets
        </a>
    </div>
    
    <div class="content-text">
        Merci de votre soutien et de votre engagement !
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        L'équipe de gestion de projets GRN-UCBC<br>
        Centre de Gouvernance des Ressources Naturelles
    </div>
@endsection
