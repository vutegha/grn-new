@extends('layouts.admin')

@section('title', 'Créer un Projet')
@section('subtitle', 'Ajouter un nouveau projet de recherche')

@section('content')

<x-admin-form 
    title="Créer un Projet"
    subtitle="Ajouter un nouveau projet de recherche"
    :action="route('admin.projets.store')"
    method="POST"
    back-route="{{ route('admin.projets.index') }}"
    back-label="Liste des projets"
    submit-label="Créer le projet"
    :multipart="false"
>

    <!-- Informations générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="md:col-span-2">
            <x-form-field
                name="titre"
                type="text"
                label="Titre du projet"
                placeholder="Entrez le titre du projet"
                :value="old('titre')"
                required="true"
                help="Le titre principal du projet de recherche"
            />
        </div>

        <div class="md:col-span-2">
            <x-form-field
                name="description"
                type="textarea"
                label="Description"
                placeholder="Décrivez le projet en détail"
                :value="old('description')"
                rows="4"
                help="Description complète des objectifs et méthodologies"
            />
        </div>
    </div>

    <!-- Dates et état -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-calendar-range mr-2 text-coral"></i>
            Planification et état
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-form-field
                name="date_debut"
                type="date"
                label="Date de début"
                :value="old('date_debut')"
                required="true"
            />

            <x-form-field
                name="date_fin"
                type="date"
                label="Date de fin prévue"
                :value="old('date_fin')"
                help="Date prévue de fin du projet"
            />

            <x-form-field
                name="etat"
                type="select"
                label="État du projet"
                :value="old('etat', 'en cours')"
                :options="[
                    'en cours' => 'En cours',
                    'terminé' => 'Terminé',
                    'suspendu' => 'Suspendu'
                ]"
                required="true"
            />
        </div>
    </div>

    <!-- Budget et bénéficiaires -->
    <div class="bg-gray-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-cash-stack mr-2 text-coral"></i>
            Budget et impact
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-form-field
                name="budget"
                type="number"
                label="Budget (€)"
                placeholder="0.00"
                :value="old('budget')"
                step="0.01"
                min="0"
                help="Budget total alloué au projet"
            />

            <x-form-field
                name="beneficiaires"
                type="text"
                label="Bénéficiaires"
                placeholder="Population ciblée"
                :value="old('beneficiaires')"
                help="Description des bénéficiaires du projet"
            />

            <x-form-field
                name="beneficiaires_attendu"
                type="number"
                label="Nombre de bénéficiaires attendus"
                placeholder="0"
                :value="old('beneficiaires_attendu')"
                min="0"
            />
        </div>
    </div>

    <!-- Partenaires et équipe -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="partenaires"
            type="textarea"
            label="Partenaires"
            placeholder="Liste des partenaires du projet"
            :value="old('partenaires')"
            rows="3"
            help="Organismes et institutions partenaires"
        />

        <x-form-field
            name="responsable"
            type="text"
            label="Responsable du projet"
            placeholder="Nom du responsable principal"
            :value="old('responsable')"
        />
    </div>

    <!-- Localisation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <x-form-field
            name="localisation"
            type="text"
            label="Localisation"
            placeholder="Lieu de mise en œuvre"
            :value="old('localisation')"
        />

        <x-form-field
            name="secteur"
            type="text"
            label="Secteur d'activité"
            placeholder="Domaine de recherche"
            :value="old('secteur')"
        />
    </div>

    <!-- Options de publication -->
    <div class="bg-blue-50 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="bi bi-eye mr-2 text-coral"></i>
            Options de publication
        </h3>
        
        <div class="space-y-4">
            @if(auth()->check() && auth()->user()->canModerate())
                <x-form-field
                    name="is_published"
                    type="checkbox"
                    label="Publier immédiatement"
                    :value="old('is_published')"
                    help="Le projet sera visible sur le site public"
                />
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="bi bi-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">Modération requise</p>
                            <p>Ce projet sera soumis à modération avant publication.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-form>

@endsection
