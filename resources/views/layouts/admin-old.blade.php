<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Navbar fixe -->
<nav class="bg-white shadow fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="text-xl font-bold text-gray-700">Admin Panel</div>
        <div class="hidden md:flex items-center space-x-4">
            <span class="text-gray-600">{{ auth()->user()->name ?? 'Utilisateur' }}</span>
            <form method="POST" action="3">
                @csrf
                <button class="text-red-600 hover:underline">Se déconnecter</button>
            </form>
        </div>
    </div>
</nav>

<div class="flex pt-20 min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg hidden md:block">
        <div class="px-6 py-4 border-b">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto rounded-full bg-gray-300 flex items-center justify-center text-xl font-bold text-white">
                    {{ strtoupper(auth()->user()->name[0] ?? '?') }}
                </div>
                <h2 class="mt-2 font-semibold text-gray-700">{{ auth()->user()->name ?? 'Utilisateur' }}</h2>
                <p class="text-sm text-gray-500">{{ auth()->user()->email ?? '' }}</p>
            </div>
        </div>

       <nav class="mt-4 px-4 text-sm" x-data="{ openMenu: '' }">
    <h3 class="text-xs text-gray-500 uppercase tracking-wide mb-2">Contenu</h3>

    {{-- Liste des menus avec sous-menus --}}
    @php
        $menus = [
            'publications' => ['label' => '📚 Publications', 'routes' => ['Lister' => route('admin.publication.index'), 'Ajouter' => route('admin.publication.create')]],
            'actualites' => ['label' => '📰 Actualités', 'routes' => ['Lister' => route('admin.actualite.index'), 'Ajouter' => route('admin.actualite.create')]],
            'auteurs' => ['label' => '👤 Auteurs', 'routes' => ['Lister' => route('admin.auteur.index'), 'Ajouter' => route('admin.auteur.create')]],
            'newsletters' => ['label' => '📧 Newsletters', 'routes' => ['Lister' => route('admin.newsletter.index')]],
            'projets' => ['label' => '📁 Projets', 'routes' => ['Lister' => route('admin.projets.index'), 'Ajouter' => route('admin.projets.create')]],
            'rapports' => ['label' => '📄 Rapports', 'routes' => ['Lister' => route('admin.rapports.index'), 'Ajouter' => route('admin.rapports.create')]],
            'Mediathèque' => ['label' => '📄 Mediathèque', 'routes' => ['Lister' => route('admin.media.index'), 'Ajouter' => route('admin.media.create')]],
            'services' => ['label' => '🛠️ Services', 'routes' => ['Lister' =>  route('admin.service.index'), 'Ajouter' => route('admin.service.create')]],
        ];
    @endphp

    @foreach($menus as $key => $menu)
        <div class="mb-2">
            <button
                @click="openMenu === '{{ $key }}' ? openMenu = '' : openMenu = '{{ $key }}'"
                class="w-full flex justify-between items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 font-medium">
                {{ $menu['label'] }}
                <svg class="w-4 h-4 ml-1 transform"
                     :class="openMenu === '{{ $key }}' ? 'rotate-180' : 'rotate-0'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="openMenu === '{{ $key }}'" class="mt-1 ml-4 pl-2 border-l border-gray-300 space-y-1">
                @foreach($menu['routes'] as $label => $link)
                    <a href="{{ $link }}" class="block text-gray-700 hover:underline">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- Configurations --}}
    <h3 class="text-xs text-gray-500 uppercase tracking-wide mt-6 mb-2">Configurations</h3>
    <div class="mb-2">
        <button
            @click="openMenu === 'config' ? openMenu = '' : openMenu = 'config'"
            class="w-full flex justify-between items-center px-3 py-2 rounded bg-gray-100 hover:bg-gray-200 font-medium">
            ⚙️ Configurations
            <svg class="w-4 h-4 ml-1 transform"
                 :class="openMenu === 'config' ? 'rotate-180' : 'rotate-0'"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="openMenu === 'config'" class="mt-1 ml-4 pl-2 border-l border-gray-300 space-y-1">
            <a href="{{ route('admin.categorie.index') }}" class="block text-gray-700 hover:underline">📂 Catégories</a>
            <a href="#" class="block text-gray-700 hover:underline">👥 Gestion des utilisateurs</a>
            <a href="#" class="block text-gray-700 hover:underline">🔑 Gestion des rôles</a>
        </div>
    </div>
</nav>


        <div class="px-6 py-4 border-t mt-auto text-xs text-gray-400 text-center">
            Powered by Led Initiatives Sarl
        </div>
    </aside>

    <!-- Contenu principal -->
    <main class="flex-1 p-6">
        @if(session('alert'))
            <div class="mb-6">{!! session('alert') !!}</div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Une erreur est survenue :</strong>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
        @push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#texte'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
    </main>
</div>

<!-- Footer -->
<footer class="bg-white border-t mt-6 py-3 text-center text-sm text-gray-500">
    © {{ date('Y') }} Powered by Led Initiatives Sarl
</footer>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<!-- CKEditor Configuration -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin layout loaded, initializing CKEditor...');
    
    // Find all textareas with class 'wysiwyg'
    const textareas = document.querySelectorAll('textarea.wysiwyg');
    console.log('Found ' + textareas.length + ' WYSIWYG textareas');
    
    textareas.forEach(function(textarea, index) {
        console.log('Initializing CKEditor for textarea ' + (index + 1));
        
        ClassicEditor
            .create(textarea, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'link', '|',
                    'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                },
                language: 'fr'
            })
            .then(editor => {
                console.log('CKEditor initialized successfully for textarea ' + (index + 1));
                
                // Style the editor
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '120px', editor.editing.view.document.getRoot());
                });
                
                // Update textarea when content changes
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });
    
    console.log('CKEditor configuration complete');
});
</script>

</body>
@yield('scripts')
</html>
