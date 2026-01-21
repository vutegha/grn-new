@extends('layouts.iri')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 space-y-12">
    @if(session('alert'))
    <div class="my-4 p-4 bg-light-green text-olive rounded shadow text-center">
        {!! session('alert') !!}
    </div>
    @endif

    <h1 class="text-3xl font-bold text-olive mb-8">Images converties à partir des publications PDF</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($publications as $pub)
            <div class="bg-light-gray p-6 rounded-xl shadow flex flex-col items-center justify-center space-y-4">
                <h2 class="text-xl font-bold text-olive">{{ $pub->titre }}</h2>

                @if($pub->fichier_pdf && file_exists(public_path('storage/'.$pub->fichier_pdf)))
                    <canvas id="pdf-canvas-{{$pub->id}}" 
                            class="rounded shadow w-full h-auto" 
                            data-pdf-url="{{ asset('storage/'.$pub->fichier_pdf) }}">
                    </canvas>
                    <p class="text-sm text-olive">Première page du PDF</p>
                @else
                    <p class="text-coral font-semibold">🚫 Aucun fichier PDF trouvé pour cette publication.</p>
                @endif

                <div>
                    <a href="{{ route('publication.show', $pub->slug) }}" class="btn-ci">
                        ← lire plus 
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    document.querySelectorAll('canvas[data-pdf-url]').forEach(canvas => {
        const url = canvas.getAttribute('data-pdf-url');
        const ctx = canvas.getContext('2d');
        const containerWidth = canvas.parentElement.offsetWidth;

        pdfjsLib.getDocument(url).promise.then(pdf => {
            return pdf.getPage(1);
        }).then(page => {
            const viewport = page.getViewport({ scale: 1 });
            const scale = containerWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale });

            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;

            return page.render({ canvasContext: ctx, viewport: scaledViewport }).promise;
        }).catch(err => {
            console.error("Erreur lors du rendu du PDF :", err);
        });
    });
});
</script>
@endsection
