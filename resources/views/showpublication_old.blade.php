 @extends('layouts.iri')

@section('title', 'Publication - ' . $publication->titre)

@section('content')
<!-- Breadcrumb -->
@include('partials.breadcrumb', [
    'breadcrumbs' => [
        ['title' => 'Publications', 'url' => route('site.publications')],
        ['title' => $publication->titre, 'url' => null]
    ]
])

<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    @php
                        $categoryName = $publication->categorie->nom ?? 'Non catégorisé';
                        $badgeClass = match ($categoryName) {
                            'Rapport' => 'bg-blue-500/20 text-blue-100 border-blue-300/30',
                            'Article' => 'bg-yellow-500/20 text-yellow-100 border-yellow-300/30',
                            'Document' => 'bg-purple-500/20 text-purple-100 border-purple-300/30',
                            'Publication scientifique' => 'bg-emerald-500/20 text-emerald-100 border-emerald-300/30',
                            'Actualité' => 'bg-red-500/20 text-red-100 border-red-300/30',
                            default => 'bg-white/20 text-white border-white/30',
                        };
                    @endphp
                    
                    <div class="inline-flex items-center {{ $badgeClass }} border backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $categoryName }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ $publication->titre }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>{{ $publication->created_at->format('d M Y') }}</span>
                        </div>
                        @if($publication->auteur)
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                <span>{{ $publication->auteur->nom }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button onclick="showToastAgain()"
                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-3 px-6 rounded-lg hover:bg-white/30 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-book-open mr-2"></i>
                            Lire le résumé
                        </button>
                        
                        @if($publication->fichier_pdf)
                            <a href="{{ asset('storage/'.$publication->fichier_pdf) }}" 
                               target="_blank"
                               class="bg-iri-gold/80 backdrop-blur-sm border border-iri-gold/50 text-white font-semibold py-3 px-6 rounded-lg hover:bg-iri-gold transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger PDF
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Publication Preview -->
                <div class="lg:col-span-1">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                        @if($publication->fichier_pdf)
                            <canvas id="pdf-preview" 
                                    class="w-full rounded-lg shadow-lg" 
                                    data-pdf-url="{{ asset('storage/'.$publication->fichier_pdf) }}">
                            </canvas>
                        @else
                            <div class="aspect-[3/4] bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-white/50 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

<div id="resumeToast"
     class="fixed left-6 top-[20vh] bg-light-gray text-olive rounded-xl shadow-lg max-w-sm w-full overflow-y-auto z-50 hidden"
     style="top: 20vh; max-height: 80vh; ">
    
    <!-- En-tête sticky -->
    <div class="sticky top-0 bg-light-gray p-4 flex justify-between items-center border-b border-olive z-10">
        <h4 class="text-lg font-bold">Résumé</h4>
        <button onclick="closeToast()" class="text-2xl leading-none hover:text-coral">&times;</button>
    </div>

    <!-- Contenu -->
    <div class="p-4">
        <p class="text-sm text-slate-700">
            {{ $publication->resume }}
        </p>
    </div>
</div>


<!-- Bouton flottant fixe en bas à gauche -->
<button onclick="showToastAgain()"
        class="fixed  bottom-6 left-6 bg-olive text-light-gray rounded-full shadow-lg w-12 h-12 flex items-center justify-center hover:bg-coral transition z-[60]">
    <i class="fas fa-rotate-right"></i>
</button>

<script>
    function closeToast() {
        const toast = document.getElementById('resumeToast');
        toast.classList.add('hidden');
    }

    function showToastAgain() {
        const toast = document.getElementById('resumeToast');
        toast.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            showToastAgain();
        }, 3000);
    });
</script>


        

        <div>
            <a href="{{ route('site.publications') }}" class="btn-iri">
                ← Retour à la liste des ressources
            </a>
        </div>

        @if($autresPublications->count())
            <div class="pt-4 border-t border-gray-400">
                <h4 class="font-semibold text-md mb-3 text-olive">
                    Lire aussi...
                </h4>
                <ul class="space-y-2 text-sm list-disc list-inside pl-4">
                    @foreach($autresPublications as $otherPub)
                        @php
                            $fileUrl = Storage::url($otherPub->fichier_pdf ?? '');
                            $ext = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION));
                        @endphp
                        <li>
                            <a href="{{ route('publication.show', $otherPub->slug) }}" class="link-dark hover:underline">
                                {{ $otherPub->titre }}
                            </a>
                            @if($ext)
                                <span class="ml-2 bg-gray-700 text-gray-300 px-2 py-0.5 rounded text-xs">{{ strtoupper($ext) }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Bloc droit : PDF et lecteur -->
    <div class="flex-1 w-full xl:w-7/12 bg-grayish p-6 rounded-xl shadow space-y-6">

        <div>
            <h3 class="text-4xl font-extrabold text-center text-olive mb-6">
                {{ $publication->titre }}
            </h3>

            
        </div>
        




        @if ($extension === 'pdf')
            @php
                $file = Storage::url($publication->fichier_pdf ?? '');
            @endphp
            <div class="sticky top-0 z-50 bg-white py-2 px-4 border-b border-gray-300 shadow flex flex-wrap items-center gap-4">
                <input type="text" id="searchText" placeholder="Rechercher dans ce document..." class="border px-3 py-1 rounded w-64">
                <button id="searchBtn" class="btn-light-green px-3 py-1 rounded"><i class="fas fa-search"></i></button>
                <button id="resetBtn" class="hidden btn-iri px-3 py-1 rounded flex items-center gap-2"><i class="fas fa-rotate-right"></i> </button>
                <button id="prevMatch" class="hidden btn-iri px-3 py-1 rounded">←</button>
                <button id="nextMatch" class="hidden btn-iri px-3 py-1 rounded">→</button>
                <span id="matchCount" class="hidden text-sm text-gray-700"></span>
                <span id="pageCount" class="ml-auto text-sm text-gray-700"></span>
                
                <button id="fullscreenBtn" class="btn-light-green px-3 py-1 rounded flex items-center gap-2" ><i class="fas fa-expand"></i></button>
                <button id="downloadBtn" class="btn-light-green px-3 py-1 rounded flex items-center gap-2"><i class="fas fa-download"></i> </button>
            </div>

            <div id="pdfLoader" class="flex items-center justify-center p-6 bg-light-gray rounded-xl shadow text-olive font-semibold text-lg space-x-2">
                🕗 Ce PDF est volumineux, patience pendant le chargement...
            </div>
            <div id="pdfProgressContainer" class="w-full bg-gray-300 rounded-full h-4 mb-4">
            <div id="pdfProgressBar" class="bg-olive h-4 rounded-full transition-all duration-300" style="width: 0%;"></div>
            </div>
            <div id="pdfProgressText" class="text-sm text-olive font-semibold mb-4">Chargement : 0%</div>


            <div id="pdfViewer" class="px-4 space-y-8"></div>
        @else
            <p class="text-red-500">Ce type de fichier n’est pas pris en charge pour l’aperçu direct.</p>
        @endif

        <div class="mt-6">
            
                @if($publication->fichier_pdf)
                    <p class="text-sm italic text-slate-700">
                        <a href="{{ $publication->fichier_pdf }}" class="btn-iri block mx-auto w-max" target="_blank">
                            Télécharger le fichier
                        </a>
                         <strong>Comment cité cette resources: </strong> {{$publication->citation ?? 'Aucune citation disponible.' }}
                    </p>
                
                 @endif   
                
        </div>
    </div>
</div>

<style>
    #pdfViewer canvas {
        display: block;
        margin: 0 auto;
        width: 100% !important;
        height: auto !important;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }
    .highlighted {
        background-color: rgba(255, 255, 0, 0.4) !important;
        color: inherit !important;
        padding: 1px 2px;
        border-radius: 2px;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    const url = "{{ $file }}";
    const container = document.getElementById('pdfViewer');
    const pageCountDisplay = document.getElementById('pageCount');
    const matchCountDisplay = document.getElementById('matchCount');
    const prevBtn = document.getElementById('prevMatch');
    const nextBtn = document.getElementById('nextMatch');
    let scaleNormal = 1.2;
    let scaleFullscreen = 2.5;
    let scale = scaleNormal;
    let pdfDoc = null;
    let searchText = '';
    let matches = [];
    let currentMatch = 0;
    // let fullTextIndex = [];

    //     for (let i = 1; i <= pdfDoc.numPages; i++) {
    //         pdfDoc.getPage(i).then(page => {
    //             page.getTextContent().then(textContent => {
    //                 fullTextIndex.push({
    //                     page: i,
    //                     text: textContent.items.map(item => item.str).join(' ')
    //                 });
    //             });
    //         });
    //     }


    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

function renderPDF() {
    container.innerHTML = '';
    matches = [];
    currentMatch = 0;
    prevBtn.classList.add('hidden');
    nextBtn.classList.add('hidden');
    matchCountDisplay.classList.add('hidden');
    matchCountDisplay.textContent = '';

    const loadingTask = pdfjsLib.getDocument(url);

loadingTask.onProgress = function (progressData) {
    const percent = Math.round((progressData.loaded / progressData.total) * 100);
    document.getElementById('pdfProgressBar').style.width = percent + '%';
    document.getElementById('pdfProgressText').textContent = `Chargement : ${percent}%`;
};

loadingTask.promise.then(pdf => {
    pdfDoc = pdf;
    pageCountDisplay.textContent = `Nombre de pages : ${pdf.numPages}`;

    for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
        const pageDiv = document.createElement('div');
        pageDiv.classList.add('pdf-page');
        pageDiv.dataset.pageNumber = pageNumber;
        pageDiv.style.minHeight = '500px';
        pageDiv.style.marginBottom = '2rem';
        container.appendChild(pageDiv);
    }

    observePages();

    // quand le PDF est prêt, on cache la progression
    document.getElementById('pdfProgressContainer').style.display = 'none';
    document.getElementById('pdfProgressText').style.display = 'none';
});

    pdfjsLib.getDocument(url).promise.then(pdf => {
        pdfDoc = pdf;
        pageCountDisplay.textContent = `Nombre de pages : ${pdf.numPages}`;

        // On prépare les containers vides
        for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
            const pageDiv = document.createElement('div');
            pageDiv.classList.add('pdf-page');
            pageDiv.dataset.pageNumber = pageNumber;
            pageDiv.style.minHeight = '500px'; // garde la place
            pageDiv.style.marginBottom = '2rem';
            container.appendChild(pageDiv);
        }

        observePages(); // démarrer l'observation lazy loading
    });
}

function observePages() {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const pageDiv = entry.target;
                const pageNumber = parseInt(pageDiv.dataset.pageNumber);
                if (!pageDiv.dataset.rendered) {
                    renderPage(pageNumber, pageDiv);
                    pageDiv.dataset.rendered = 'true';
                }
            }
        });
    }, {
        root: null,
        rootMargin: '200px', // charge un peu avant d'entrer vraiment
        threshold: 0.1
    });

    document.querySelectorAll('.pdf-page').forEach(div => observer.observe(div));
}

function renderPage(pageNumber, pageDiv) {
    pdfDoc.getPage(pageNumber).then(page => {
        const viewport = page.getViewport({ scale });
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = { canvasContext: context, viewport };
        page.render(renderContext).promise.then(() => {
            page.getTextContent().then(textContent => {
                // dès la première page rendue, on cache le loader
                if (pageNumber === 1) {
                    document.getElementById('pdfLoader').style.display = 'none';
                }

                const wrapper = document.createElement('div');
                wrapper.style.position = 'relative';
                wrapper.appendChild(canvas);

                const textLayer = document.createElement('div');
                textLayer.style.position = 'absolute';
                textLayer.style.top = 0;
                textLayer.style.left = 0;
                textLayer.style.width = canvas.width + 'px';
                textLayer.style.height = canvas.height + 'px';
                textLayer.style.pointerEvents = 'none';

                textContent.items.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item.str;
                    div.style.position = 'absolute';
                    div.style.left = item.transform[4] + 'px';
                    div.style.top = (canvas.height - item.transform[5]) + 'px';
                    div.style.fontSize = item.height + 'px';
                    div.style.whiteSpace = 'pre';
                    div.style.backgroundColor = 'transparent';
                    div.style.color = 'transparent';
                    div.style.pointerEvents = 'none';

                    if (searchText && item.str.toLowerCase().includes(searchText)) {
                        div.classList.add('highlighted');
                        div.style.color = 'inherit';
                        matches.push(div);
                    }

                    textLayer.appendChild(div);
                });

                wrapper.appendChild(textLayer);
                pageDiv.appendChild(wrapper);

                if (matches.length > 0) {
                    prevBtn.classList.remove('hidden');
                    nextBtn.classList.remove('hidden');
                    resetBtn.classList.remove('hidden');
                    matchCountDisplay.classList.remove('hidden');
                    matchCountDisplay.textContent = `Occurrences : ${matches.length}`;
                }
            });
        });
    });
}








    document.getElementById('searchBtn').addEventListener('click', function () {
        searchText = document.getElementById('searchText').value.trim().toLowerCase();
        if (searchText.length > 0) {
            renderPDF();
        }
    });
    document.getElementById('resetBtn').addEventListener('click', function () {
    document.getElementById('searchText').value = '';
    searchText = '';
    renderPDF();
    this.classList.add('hidden');
});

document.getElementById('downloadBtn').addEventListener('click', function() {
    const a = document.createElement('a');
    a.href = "{{ Storage::url($publication->fichier_pdf) }}"; // lien vers ton fichier
    a.setAttribute('download', '');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
});
    document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            scale = scaleFullscreen;
        } else {
            scale = scaleNormal;
        }
        renderPDF();
    });

    prevBtn.addEventListener('click', () => {
        if (matches.length > 0 && currentMatch > 0) {
            currentMatch--;
            highlightMatch();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (matches.length > 0 && currentMatch < matches.length - 1) {
            currentMatch++;
            highlightMatch();
        }
    });

    function highlightMatch() {
        matches.forEach((el, idx) => el.style.outline = idx === currentMatch ? '2px solid #2563eb' : 'none');
        matches[currentMatch].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    renderPDF();

    document.getElementById('fullscreenBtn').addEventListener('click', function () {
        const elem = document.getElementById('pdfViewer');
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    });
</script>

<div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white text-olive p-6 rounded shadow-xl max-w-md w-full mx-4 relative">
        <button id="closeModalBtn" class="absolute top-2 right-2 text-xl">&times;</button>
        <div class="text-center">
            <i class="fas fa-search text-3xl mb-2"></i>
            <p class="mt-2 text-md font-semibold">
                Recherche uniquement sur les pages déjà consultées.
            </p>
        </div>
    </div>
</div>

@endsection
