@extends('layouts.iri')

@section('title', 'Candidature - ' . $job->title)

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        <!-- Breadcrumb Overlay -->
        <div class="absolute top-4 left-4 z-20">
            <nav class="flex space-x-2 text-sm text-white/90" aria-label="Breadcrumb">
                <a href="{{ route('site.home') }}" class="hover:text-white transition-colors">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <span class="text-white/60">›</span>
                <a href="{{ route('site.work-with-us') }}" class="hover:text-white transition-colors">Travailler avec nous</a>
                <span class="text-white/60">›</span>
                <span class="text-white font-medium">{{ $currentPage ?? 'Candidature' }}</span>
            </nav>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 drop-shadow-2xl">
                Candidature en ligne
            </h1>
            <h2 class="text-xl md:text-2xl text-white/90 font-semibold mb-4">
                {{ $job->title }}
            </h2>
            <div class="flex flex-wrap justify-center items-center space-x-6 text-white/80">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-building"></i>
                    <span>{{ $job->department }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $job->location }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-briefcase"></i>
                    <span>{{ $job->type }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form Section -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Job Summary -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Résumé de l'offre</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">Description</h4>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ Str::limit($job->description, 200) }}</p>
                    </div>
                    <div>
                        @if($job->requirements && count($job->requirements) > 0)
                            <h4 class="font-semibold text-gray-700 mb-2">Principales exigences</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach(array_slice($job->requirements, 0, 3) as $requirement)
                                    <li class="flex items-start space-x-2">
                                        <i class="fas fa-check text-iri-primary mt-1 text-xs"></i>
                                        <span>{{ $requirement }}</span>
                                    </li>
                                @endforeach
                                @if(count($job->requirements) > 3)
                                    <li class="text-gray-500 italic">... et {{ count($job->requirements) - 3 }} autre(s) exigence(s)</li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
                
                @if($job->application_deadline)
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center space-x-2 text-yellow-800">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-medium">Date limite de candidature: {{ $job->application_deadline->format('d/m/Y') }}</span>
                            @if($job->days_until_deadline <= 7)
                                <span class="text-sm">({{ $job->days_until_deadline }} jour(s) restant(s))</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Application Form -->
            <form action="{{ route('site.job.apply.submit', $job) }}" method="POST" enctype="multipart/form-data" 
                  class="bg-white rounded-2xl shadow-lg border border-gray-100" x-data="applicationForm()">
                @csrf
                
                <!-- Honeypot anti-spam (champ caché) -->
                <input type="text" name="website" id="website" style="display:none !important;" tabindex="-1" autocomplete="off" />
                
                <!-- Personal Information -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-user mr-2 text-iri-primary"></i>
                        Informations personnelles
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de naissance
                            </label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('birth_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Genre
                            </label>
                            <select id="gender" name="gender"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                                <option value="">Sélectionner</option>
                                <option value="masculin" {{ old('gender') === 'masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="feminin" {{ old('gender') === 'feminin' ? 'selected' : '' }}>Féminin</option>
                                <option value="autre" {{ old('gender') === 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Adresse
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">
                                Nationalité
                            </label>
                            <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">
                            @error('nationality')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-graduation-cap mr-2 text-iri-primary"></i>
                        Informations professionnelles
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="education" class="block text-sm font-medium text-gray-700 mb-2">
                                Formation et diplômes
                            </label>
                            <textarea id="education" name="education" rows="4" 
                                      placeholder="Décrivez votre parcours de formation, vos diplômes et certifications..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">{{ old('education') }}</textarea>
                            @error('education')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="experience" class="block text-sm font-medium text-gray-700 mb-2">
                                Expérience professionnelle
                            </label>
                            <textarea id="experience" name="experience" rows="4"
                                      placeholder="Décrivez vos expériences professionnelles pertinentes..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">{{ old('experience') }}</textarea>
                            @error('experience')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">
                                Compétences clés
                            </label>
                            <textarea id="skills" name="skills" rows="3"
                                      placeholder="Listez vos compétences techniques et soft skills..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">{{ old('skills') }}</textarea>
                            @error('skills')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dynamic Criteria (if any) -->
                @if($job->criteria && count($job->criteria) > 0)
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-clipboard-check mr-2 text-iri-primary"></i>
                        Critères spécifiques
                    </h3>
                    
                    <div class="space-y-6">
                        @foreach($job->criteria as $index => $criteria)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $criteria['question'] }}
                                    @if(isset($criteria['required']) && $criteria['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                
                                @if($criteria['type'] === 'text')
                                    <input type="text" name="criteria_responses[{{ $index }}]" 
                                           value="{{ old('criteria_responses.' . $index) }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors"
                                           {{ isset($criteria['required']) && $criteria['required'] ? 'required' : '' }}>
                                
                                @elseif($criteria['type'] === 'textarea')
                                    <textarea name="criteria_responses[{{ $index }}]" rows="3"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors"
                                              {{ isset($criteria['required']) && $criteria['required'] ? 'required' : '' }}>{{ old('criteria_responses.' . $index) }}</textarea>
                                
                                @elseif($criteria['type'] === 'select')
                                    <select name="criteria_responses[{{ $index }}]"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors"
                                            {{ isset($criteria['required']) && $criteria['required'] ? 'required' : '' }}>
                                        <option value="">Sélectionner une option</option>
                                        @foreach($criteria['options'] as $option)
                                            <option value="{{ $option }}" {{ old('criteria_responses.' . $index) === $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                
                                @elseif($criteria['type'] === 'radio')
                                    <div class="space-y-2">
                                        @foreach($criteria['options'] as $option)
                                            <label class="flex items-center space-x-3">
                                                <input type="radio" name="criteria_responses[{{ $index }}]" value="{{ $option }}"
                                                       {{ old('criteria_responses.' . $index) === $option ? 'checked' : '' }}
                                                       class="text-iri-primary focus:ring-iri-primary"
                                                       {{ isset($criteria['required']) && $criteria['required'] ? 'required' : '' }}>
                                                <span class="text-gray-700">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if(isset($criteria['description']))
                                    <p class="text-sm text-gray-500 mt-1">{{ $criteria['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Motivation Letter -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-heart mr-2 text-iri-primary"></i>
                        Lettre de motivation
                    </h3>
                    
                    <div>
                        <label for="motivation_letter" class="block text-sm font-medium text-gray-700 mb-2">
                            Expliquez-nous pourquoi vous souhaitez rejoindre notre équipe <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivation_letter" name="motivation_letter" rows="6" required
                                  placeholder="Exprimez votre motivation, vos objectifs de carrière et ce que vous pouvez apporter à notre organisation..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-transparent transition-colors">{{ old('motivation_letter') }}</textarea>
                        @error('motivation_letter')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Maximum 3000 caractères</p>
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="p-8 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-paperclip mr-2 text-iri-primary"></i>
                        Documents
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="cv_file" class="block text-sm font-medium text-gray-700 mb-2">
                                CV <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" required
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX (Max 5MB)</p>
                            @error('cv_file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="portfolio_file" class="block text-sm font-medium text-gray-700 mb-2">
                                Portfolio (optionnel)
                            </label>
                            <input type="file" id="portfolio_file" name="portfolio_file" accept=".pdf,.doc,.docx,.zip"
                                   class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, ZIP (Max 10MB)</p>
                            @error('portfolio_file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="p-8">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                        <div class="text-sm text-gray-600">
                            <p><span class="text-red-500">*</span> Champs obligatoires</p>
                            <p class="mt-1">Vos données seront traitées de manière confidentielle.</p>
                        </div>
                        
                        <div class="flex space-x-4">
                            <a href="{{ route('site.work-with-us') }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-iri-primary to-iri-secondary hover:from-iri-secondary hover:to-iri-accent text-white font-medium py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Soumettre ma candidature
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
function applicationForm() {
    return {
        // Vous pouvez ajouter des fonctionnalités JavaScript ici si nécessaire
    }
}
</script>

<!-- Alpine.js for interactive components -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
