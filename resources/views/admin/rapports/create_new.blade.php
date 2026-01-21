@extends('layouts.admin')

@section('title', 'Créer un Rapport')
@section('subtitle', 'Ajouter un nouveau rapport institutionnel')

@section('content')

<x-admin-form 
    title="Créer un Rapport"
    subtitle="Ajouter un nouveau rapport institutionnel"
    :action="route('admin.rapports.store')"
    method="POST"
    back-route="{{ route('admin.rapports.index') }}"
    back-label="Liste des rapports"
    submit-label="Créer le rapport"
    :multipart="true"
>

    <!-- Informations générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <x-form-field
                name="titre"
                type="text"
                label="Titre du rapport"
                placeholder="Entrez le titre du rapport"
                :value="old('titre')"
                required="true"
                help="Le titre principal du rapport"
            />
        </div>

        <div class="md:col-span-2">
            <x-form-field
                name="description"
                type="textarea"
                label="Description"
                placeholder="Décrivez le contenu du rapport"
                :value="old('description')"
                rows="4"
                help="Résumé du contenu du rapport"
            />
        </div>
    </div>

    <!-- Catégorie et dates -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-tags mr-2 text-coral"></i>
            Classification et dates
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="categorie_id"
                type="select"
                label="Catégorie"
                :value="old('categorie_id')"
                :options="$categories->pluck('nom', 'id')->prepend('Sélectionner une catégorie', '')"
                help="Catégorie du rapport"
            />

            <x-form-field
                name="date_publication"
                type="date"
                label="Date de publication"
                :value="old('date_publication', now()->format('Y-m-d'))"
                required="true"
            />
        </div>
    </div>

    <!-- Fichier PDF -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-file-pdf mr-2 text-coral"></i>
            Document PDF
        </h3>
        
        <x-form-field
            name="fichier"
            type="file"
            label="Fichier PDF"
            :value="old('fichier')"
            accept=".pdf"
            help="Sélectionnez le fichier PDF du rapport (max 50MB)"
        />
    </div>

    <!-- Métadonnées -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="auteur"
            type="text"
            label="Auteur(s)"
            placeholder="Nom de l'auteur ou des auteurs"
            :value="old('auteur')"
            help="Auteur(s) du rapport"
        />

        <x-form-field
            name="langue"
            type="select"
            label="Langue"
            :value="old('langue', 'fr')"
            :options="[
                'fr' => 'Français',
                'en' => 'Anglais',
                'es' => 'Espagnol',
                'de' => 'Allemand'
            ]"
        />

        <x-form-field
            name="pages"
            type="number"
            label="Nombre de pages"
            placeholder="0"
            :value="old('pages')"
            min="1"
        />

        <x-form-field
            name="taille_fichier"
            type="text"
            label="Taille du fichier"
            placeholder="Ex: 2.5 MB"
            :value="old('taille_fichier')"
            help="Taille approximative du fichier"
        />
    </div>

    <!-- Mots-clés et résumé -->
    <div class="grid grid-cols-1 gap-6 mb-6">
        <x-form-field
            name="mots_cles"
            type="text"
            label="Mots-clés"
            placeholder="Séparez les mots-clés par des virgules"
            :value="old('mots_cles')"
            help="Mots-clés pour faciliter la recherche"
        />

        <x-form-field
            name="resume_executif"
            type="textarea"
            label="Résumé exécutif"
            placeholder="Résumé exécutif du rapport"
            :value="old('resume_executif')"
            rows="3"
            help="Résumé concis des principales conclusions"
        />
    </div>

    <!-- Options de publication -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-eye mr-2 text-coral"></i>
            Options de publication
        </h3>
        
        <div class="space-y-4">
            <x-form-field
                name="is_public"
                type="checkbox"
                label="Rapport public"
                :value="old('is_public', true)"
                help="Le rapport peut être téléchargé librement"
            />

            @if(auth()->check() && auth()->user()->canModerate())
                <x-form-field
                    name="is_published"
                    type="checkbox"
                    label="Publier immédiatement"
                    :value="old('is_published')"
                    help="Le rapport sera visible sur le site public"
                />
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="bi bi-info-circle text-yellow-600 mr-2"></i>
                        <div class="text-sm text-yellow-700">
                            <p class="font-medium">Information</p>
                            <p>Votre rapport sera soumis à modération avant publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-form>

@endsection
