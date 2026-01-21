@extends('emails.newsletter.layout')

@section('title', 'Nouvelle publication - Centre de Gouvernance des Ressources Naturelles')

@section('content')
    <div class="greeting">
        Bonjour {{ $newsletter->nom ?? 'Cher(e) abonné(e)' }},
    </div>
    
    <div class="content-text">
        Nous avons le plaisir de vous informer qu'un nouveau contenu vient d'être publié sur notre site :
    </div>
    
    <div class="publication-card">
        <div class="publication-title">
            {{ $publication->titre }}
        </div>
        
        <div class="publication-meta">
            @switch($contentType)
                @case('actualites')
                    📰 Actualité
                    @break
                @case('publications')
                    📚 Publication de recherche
                    @break
                @case('rapports')
                    📊 Rapport d'activité
                    @break
                @case('evenements')
                    🎓 Événement
                    @break
                @default
                    📄 Contenu
            @endswitch
            
            @if(isset($publication->created_at))
                • Publié le {{ $publication->created_at->format('d/m/Y') }}
            @endif
            
            @if($publication->auteurs && $publication->auteurs->count() > 0)
                • Par {{ $publication->auteurs->pluck('nom')->join(', ') }}
            @endif
        </div>
        
        @if(isset($publication->resume) && $publication->resume)
            <div class="publication-excerpt">
                {{ Str::limit($publication->resume, 200) }}
            </div>
        @elseif(isset($publication->description) && $publication->description)
            <div class="publication-excerpt">
                {{ Str::limit($publication->description, 200) }}
            </div>
        @elseif(isset($publication->contenu) && $publication->contenu)
            <div class="publication-excerpt">
                {{ Str::limit(strip_tags($publication->contenu), 200) }}
            </div>
        @endif
    </div>
    
    <div style="text-align: center; margin: 30px 0;">
        @php
            $readUrl = '';
            switch($contentType) {
                case 'actualites':
                    $readUrl = route('actualite.show', $publication->slug ?? $publication->id);
                    break;
                case 'publications':
                    $readUrl = route('publication.show', $publication->slug ?? $publication->id);
                    break;
                case 'rapports':
                    $readUrl = route('publication.show', $publication->slug ?? $publication->id);
                    break;
                case 'evenements':
                    $readUrl = route('evenement.show', $publication->slug ?? $publication->id);
                    break;
                default:
                    $readUrl = config('app.url');
            }
        @endphp
        
        <a href="{{ $readUrl }}" class="btn">
            Lire l'article complet
        </a>
    </div>
    
    <div class="content-text">
        Nous espérons que ce contenu vous intéressera. N'hésitez pas à le partager avec vos collègues et contacts 
        qui pourraient également être intéressés.
    </div>
    
    <div class="content-text">
        Pour ne manquer aucune de nos publications, suivez-nous également sur nos réseaux sociaux 
        et consultez régulièrement notre site web.
    </div>
    
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-style: italic; color: #6b7280;">
        Cordialement,<br>
        L'équipe du Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo 
    </div>
    
    <div style="margin-top: 20px; padding: 15px; background-color: #f0f9ff; border-left: 4px solid #3b82f6; font-size: 14px; color: #1e40af;">
        💡 <strong>Conseil :</strong> Vous pouvez gérer vos préférences d'emails et choisir les types de contenu 
        que vous souhaitez recevoir en cliquant sur le lien "Gérer mes préférences" en bas de cet email.
    </div>
@endsection
