@extends('layouts.iri')
@section('content')
@section('title', 'Galerie')

<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Galerie</h2>
    <p class="text-gray-600 mb-4">Découvrez nos médias : images et vidéos.</p>


<div class="text-center mb-6">
    <button onclick="filterMedia('all')"
        class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mx-1">
        Tout
    </button>
    <button onclick="filterMedia('Image')"
        class="inline-block bg-blue-200 hover:bg-blue-300 text-blue-800 font-bold py-2 px-4 rounded mx-1">
        Images
    </button>
    <button onclick="filterMedia('Vidéo')"
        class="inline-block bg-red-200 hover:bg-red-300 text-red-800 font-bold py-2 px-4 rounded mx-1">
        Vidéos
    </button>
</div>

<div x-data="{ count: 0 }">
    <button @click="count++" class="px-4 py-2 bg-blue-600 text-white rounded">Incrémenter</button>
    <span class="ml-4 text-xl" x-text="count"></span>
</div>


<!-- <div class="container mx-auto">
    <div class="flex flex-wrap items-center"> -->
        <!-- Bloc gauche -->
        

        <!-- Bloc droit avec les médias -->
        <!-- <div class="w-full  px-4"> -->
<div class="flex flex-wrap -mx-3 px-3 py-6">
    @foreach($medias as $index => $media)
        @php
            $file = $media->medias;
            $isVideo = Str::endsWith(strtolower($file), ['.mp4', '.webm', '.ogg', '.mov', '.m4v', '.qt']);
            $url = asset('storage/' . $file);
            $icon = $isVideo ? 'fa-play-circle' : 'fa-image';
            $type = $isVideo ? 'Vidéo' : 'Image';
        @endphp
        <div class="w-full max-w-full px-3 xl:w-3/12 mb-6 gallery-media-item" data-type="{{ $type }}">

            <div 
                onclick="showMediaModal({{ $index }})"
                class="relative cursor-pointer bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out overflow-hidden group"
            >
              <div class="relative w-full h-48 overflow-hidden">
                  @if(!$isVideo)
                      <img src="{{ $url }}" alt="{{ $media->titre }}"
                          class="w-full h-full object-cover rounded-t-xl transform group-hover:scale-105 transition duration-500 ease-in-out" />
                  @else
                      <video src="{{ $url }}" 
                            class="w-full h-full object-cover rounded-t-xl transform group-hover:scale-105 transition duration-500 ease-in-out"
                            muted preload="metadata"></video>
                      <div class="absolute inset-0 flex items-center justify-center">
                          <i class="fas fa-play-circle text-white text-5xl"></i>
                      </div>
                  @endif

                  <!-- Badge Type -->
                  <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm text-white text-xs px-3 py-1 rounded-full">
                      {{ $type }}
                  </div>

                  <!-- Overlay avec flou sous le titre -->
                  <div class="absolute bottom-0 left-0 w-full z-20 rounded-b-2xl px-3 py-1" style="background: rgba(0, 0, 0, 0.6);">
                      <span class="mr-2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30">
                          <i class="fas {{ $icon }} text-pink-600 text-sm"></i>
                      </span>
                      <h6 class="text-white text-sm font-semibold truncate">{{ $media->titre }}</br> {{ $media->created_at->format('d M Y') }}</h6>
                  </div>
              </div>

                
            </div>
        </div>
    @endforeach
</div>
<!-- Pagination -->
<div class="mt-8 flex justify-center">
    {{ $medias->links() }}
</div>


                <!-- Modal -->
                <div id="desc-modal-bg" class="modal-bg fixed ">
                    <div class="modal-content bg-white p-6 rounded-xl shadow-lg w-[95%] max-w-3xl relative">
                        <button class="absolute top-2 right-4 text-gray-600 text-3xl hover:text-red-600" onclick="closeModal()">&times;</button>
                        <h4 id="modal-title" class="text-xl font-bold mb-4 text-center"></h4>
                        <div id="modal-body" class="mb-4 max-h-[70vh] overflow-auto"></div>
                         <!-- BOUTON TÉLÉCHARGER -->
                        <div class="text-center mt-6  ">
                            <a id="modal-download-link" href="#" download
                              class="inline-block btn-ci bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition duration-300">
                                Télécharger
                            </a>
                        </div>
                    </div>
                </div>

                <script>
                    const mediaData = @json($medias->values());

                    function showMediaModal(index) {
                        const media = mediaData[index];
                        const fileUrl = "/storage/" + media.medias;
                        const extension = media.medias.split('.').pop().toLowerCase();

                        document.getElementById('modal-title').innerText = media.titre;

                        let content = '';
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
                            content = `<img src="${fileUrl}" class="w-full rounded shadow" alt="${media.titre}" />`;
                        } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
                            content = `<video controls class="w-full rounded shadow"><source src="${fileUrl}" type="video/${extension}"></video>`;
                        } else {
                            content = `<a href="${fileUrl}" download class="btn-ci inline-block px-4 py-2 bg-blue-600 text-white rounded shadow">Télécharger le fichier</a>`;
                        }

                        document.getElementById('modal-body').innerHTML = content;
                        document.getElementById('modal-download-link').href = fileUrl;
                        document.getElementById('desc-modal-bg').classList.remove('hidden');
                        document.getElementById('desc-modal-bg').classList.add('flex');
                    }

                    function closeModal() {
                        document.getElementById('desc-modal-bg').classList.remove('flex');
                        document.getElementById('desc-modal-bg').classList.add('hidden');
                    }

                    document.addEventListener('keydown', function(e) {
                        if (e.key === "Escape") closeModal();
                    });

                    function filterMedia(type) {
    document.querySelectorAll('.gallery-media-item').forEach(function(item) {
        if (type === 'all' || item.getAttribute('data-type') === type) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}
                </script>
            </div>
        <!-- </div>
    </div>
</div> -->





@endsection