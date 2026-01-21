@extends('layouts.iri')

@section('content')


<!-- Fullscreen Landing Page -->
<div class="page-header min-h-screen flex items-center justify-center relative" data-parallax="true" style="bac                    <h4 class="text-xl font-bold text-white">
                        Our Mission
                    </h4>
                    <p class="text-md font-light mt-2" style="max-height: none !important; overflow: visible !important; display: block !important; -webkit-line-clamp: unset !important;">
                        L'Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo (IRI-UCBC) a pour mission de promouvoir la recherche scientifique, l'innovation et le développement durable au service de la société congolaise et africaine. À travers des projets interdisciplinaires, l'institut vise à renforcer les capacités, encourager la collaboration et produire des connaissances utiles pour répondre aux défis locaux qu sein des communautés locales en République Démocratique du Congo.
                    </p>d-image: url('../assets/img/research.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-black opacity-60 filter"></div>
    <div class="container relative z-10 flex flex-col items-center justify-center h-full">
        <div class="motto text-center text-white relative">
            <div class="absolute inset-0 bg-gradient-to-b from-fuchsia-900/70 via-black/60 to-fuchsia-900/70 rounded-none"></div>
            <div class="w-full px-6 mx-auto">
                <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl">
                    <!-- <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-70"></span> -->
                </div>
                <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
                    <div class="flex flex-wrap -mx-3">
                        <!-- Left: Main Text & Actions -->
                        <div class="w-full md:w-7/12 flex-none max-w-full px-3 my-auto text-left">
                            <div class="relative z-1 h-full">
                                <div class="motto">
                                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold" style="line-height: 1.1;">
                                        La recherche appliquée en réponse aux besoins sociétaux
                                    </h1>
                                </div>
                                <h3 class="font-semibold leading-normal text-sm text-2xl mb-8 text-gray-700 drop-shadow-[0_2px_8px_rgba(255,255,255,0.7)]"></h3>
                                <!-- Badge "Actualité" -->
                                <div class="flex justify-start mb-2">
                                    <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider badge-iri rounded shadow">
                                        Actualité en vedette
                                    </span>
                                </div>
                                <!-- Vertical News Carousel -->
                                <div id="vertical-news-carousel" class="relative w-full max-w-xl mx-auto overflow-hidden rounded-xl shadow-lg bg-white">
                                    <ul id="news-carousel-list" class="flex flex-col transition-transform duration-700 ease-in-out will-change-transform">
                                        @php
                                        
                                            $news = [
                                                [
                                                    'id' => 1,
                                                    'img' => '../assets/img/iri.jpg',
                                                    'title' => 'Nouveau projet communautaire',
                                                    'desc' => 'Lancement d’un nouveau projet visant à améliorer l’accès à l’eau potable dans les zones rurales. Ce projet mobilise chercheurs et habitants pour des solutions durables.',
                                                ],
                                                [
                                                    'id' => 2,
                                                    'img' => '../assets/img/research.jpg',
                                                    'title' => 'Conférence internationale',
                                                    'desc' => 'Nos chercheurs ont participé à la conférence internationale sur la santé publique, partageant leurs dernières découvertes et renforçant les collaborations mondiales.',
                                                ],
                                                [
                                                    'id' => 3,
                                                    'img' => '../assets/img/home-decor-1.jpg',
                                                    'title' => 'Atelier de formation',
                                                    'desc' => 'Un atelier de formation a été organisé pour les jeunes sur les techniques de recherche participative, favorisant l’engagement communautaire.',
                                                ],
                                                [
                                                    'id' => 4,
                                                    'img' => '../assets/img/home-decor-2.jpg',
                                                    'title' => 'Publication scientifique',
                                                    'desc' => 'Une nouvelle publication met en lumière l’impact des initiatives locales sur le développement durable dans la région.',
                                                ],
                                            ];
                                        @endphp

                                        @foreach($actualites as $item)
                                            <li class="flex flex-row items-center p-4 min-h-[6.5rem]">
                                                <img src="{{ asset('storage/'.$item->image) }}" alt="News image" class="w-24 h-24 object-cover rounded-lg flex-shrink-0 mr-4 shadow-md" />
                                                <div class="flex flex-col flex-1">
                                                    <!-- Titre cliquable -->
                                                    <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" class="font-semibold text-lg text-gray-900 mb-1 hover:underline">
                                                        {{ $item->titre }}
                                                    </a>
                                                    <!-- Texte descriptif (masqué sur petits écrans) -->
                                                    <p class="text-gray-600 text-sm" data-role="desc">
                                                        {{ \Illuminate\Support\Str::limit($item->resume, 150) }}
                                                    </p>
                                                    <!-- Bouton (masqué sur petits écrans) -->
                                                    <div class="mt-2 " data-role="btn">
                                                        <a href="{{ route('site.actualite.show', ['slug' => $item->slug]) }}" class="btn-iri-secondary text-xs">
                                                            Lire plus
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Masquer scrollbar -->
                                <style>
                                    #vertical-news-carousel::-webkit-scrollbar,
                                    #news-carousel-list::-webkit-scrollbar {
                                        display: none;
                                    }
                                </style>
                            </div>
                        </div>
                        <!-- Right: Tabs -->
                        <div class="w-full md:w-5/12 max-w-full px-3 mx-auto mt-4 sm:my-auto sm:mr-0 flex-none">
                            <div class="relative right-0">
                                <div class="motto text-center">
                                    <h5>
                                        Nous mettons la recherche scientifique au cœur des solutions pour répondre aux problèmes 
                                        réels que vivent les communautés en R.D.Congo.
                                    </h5>
                                    <a id="openModalBtn"
                                    class="btn-iri-primary inline-flex items-center cursor-pointer">
                                        <!-- YouTube favicon SVG -->
                                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                            <g>
                                                <path fill="#fff" d="M23.498 6.186a2.994 2.994 0 0 0-2.107-2.12C19.18 3.5 12 3.5 12 3.5s-7.18 0-9.391.566A2.994 2.994 0 0 0 .502 6.186C0 8.406 0 12 0 12s0 3.594.502 5.814a2.994 2.994 0 0 0 2.107 2.12C4.82 20.5 12 20.5 12 20.5s7.18 0 9.391-.566a2.994 2.994 0 0 0 2.107-2.12C24 15.594 24 12 24 12s0-3.594-.502-5.814z"/>
                                                <path fill="#f00" d="M9.545 15.568V8.432L15.818 12z"/>
                                            </g>
                                        </svg>
                                        Qui sommes-nous ?
                                    </a>

                                     



                                </div>
                                <ul class="relative flex flex-col p-1 list-none bg-transparent rounded-xl space-y-2" nav-pills role="tablist">   
                                    <li class="z-30 text-center">
                                        <a class="z-30 block w-full px-0 py-3 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700 btn-iri-primary"
                                           nav-link active href="javascript:;" role="tab" aria-selected="true">
                                            <!-- Publications Icon -->
                                            <svg class="inline-block align-middle mr-2 text-white" width="16px" height="16px" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="none" fill-rule="evenodd">
                                                    <g fill="#000000" fill-rule="nonzero">
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path class="fill-white" d="M22.7597136,19.3090182 L38.8987031,11.2395234 C39.3926816,10.9925342 39.592906,10.3918611 39.3459167,9.89788265 C39.249157,9.70436312 39.0922432,9.5474453 38.8987261,9.45068056 L20.2741875,0.1378125 C19.905375,-0.04725 19.469625,-0.04725 19.0995,0.1378125 L3.1011696,8.13815822 C2.60720568,8.38517662 2.40701679,8.98586148 2.6540352,9.4798254 C2.75080129,9.67332903 2.90771305,9.83023153 3.10122239,9.9269862 L21.8652864,19.3090182 C22.1468139,19.4497819 22.4781861,19.4497819 22.7597136,19.3090182 Z"></path>
                                                                    <path class="fill-white" d="M23.625,22.429159 L23.625,39.8805372 C23.625,40.4328219 24.0727153,40.8805372 24.625,40.8805372 C24.7802551,40.8805372 24.9333778,40.8443874 25.0722402,40.7749511 L41.2741875,32.673375 C41.719125,32.4515625 42,31.9974375 42,31.5 L42,14.241659 C42,13.6893742 41.5522847,13.241659 41,13.241659 C40.8447549,13.241659 40.6916418,13.2778041 40.5527864,13.3472318 L24.1777864,21.5347318 C23.8390024,21.7041238 23.625,22.0503869 23.625,22.429159 Z" opacity="0.7"></path>
                                                                    <path class="fill-white" d="M20.4472136,21.5347318 L1.4472136,12.0347318 C0.953235098,11.7877425 0.352562058,11.9879669 0.105572809,12.4819454 C0.0361450918,12.6208008 6.47121774e-16,12.7739139 0,12.929159 L0,30.1875 C0,30.6849375 0.280875,31.1390625 0.7258125,31.3621875 L19.5528096,40.7750766 C20.0467945,41.0220531 20.6474623,40.8218132 20.8944388,40.3278283 C20.963859,40.1889789 21,40.0358742 21,39.8806379 L21,22.429159 C21,22.0503869 20.7859976,21.7041238 20.4472136,21.5347318 Z" opacity="0.7"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="ml-1 align-middle ">
                                                Nos Publications 
                                                <span class="badge-iri inline-block rounded-full align-middle">76</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="z-30 text-center">
                                        <a class="z-30 block w-full px-0 py-3 mb-0 transition-all border-0 rounded-lg ease-soft-in-out bg-inherit text-slate-700"
                                           nav-link href="javascript:;" role="tab" aria-selected="false">
                                            <!-- Messages Icon -->
                                            <svg class="inline-block align-middle mr-2 text-slate-700" width="16px" height="16px" viewBox="0 0 40 44" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="none" fill-rule="evenodd">
                                                    <g fill="#FFFFFF" fill-rule="nonzero">
                                                        <g>
                                                            <g>
                                                                <g>
                                                                    <path class="fill-slate-800" d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z" opacity="0.603585379"></path>
                                                                    <path class="fill-slate-800" d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="ml-1 align-middle">Nous contacter</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- flex flex-wrap -->
                </div> <!-- relative flex flex-col -->
            </div> <!-- w-full px-6 mx-auto -->
        </div> <!-- motto text-center -->
    </div> <!-- container -->
</div>
<!-- End Fullscreen Landing Page -->

<!-- section on Mission and Vision -->
<section class="relative pt-16">
<div class="container mx-auto">
    <div class="flex flex-wrap items-center">
        <div class="w-10/12 md:w-6/12 lg:w-4/12 px-12 md:px-4 mr-auto ml-auto -mt-78">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg bg-pink-500">
                <img alt="..." src="../assets/img/iri.jpg" class="w-full align-middle rounded-t-lg">
                <blockquote class="relative p-8 mb-4">
                    <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 583 95" class="absolute left-0 w-full block h-95-px -top-94-px">
                        <polygon <polygon points="-30,95 583,95 583,65" fill="#ee6751" stroke="none" /> class="text-pink-500 fill-current"></polygon>
                    </svg>
                    <h4 class="text-xl font-bold text-white">
                        Our Mission
                    </h4>
                    <p class="text-md font-light mt-2">
                        L’Institut de Recherche Intégré à l’Université Chrétienne Bilingue du Congo (IRI-UCBC) a pour mission de promouvoir la recherche scientifique, l’innovation et le développement durable au service de la société congolaise et africaine. À travers des projets interdisciplinaires, l’institut vise à renforcer les capacités, encourager la collaboration et produire des connaissances utiles pour répondre aux défis locaux qu sein des communautés locales en République Démocratique du Congo.
                    </p>
                </blockquote>
            </div>
        </div>

        <div class="w-full md:w-6/12 px-4">
            <div class="flex flex-wrap">
                
                        @php
                                $descriptions = [
                                        "gouvernance" => "Ce secteur regroupe les efforts de recherche et d’action de l’IRI-UCBC dans les domaines de la gouvernance foncière, de la planification urbaine et territoriale, et de l’utilisation des systèmes d’information géographique (SIG). À travers des projets de réforme foncière, de création de registres communautaires et de résolution des conflits de limites, l’institut œuvre pour une gestion équitable et inclusive des ressources naturelles. En parallèle, l’UCBC accompagne les autorités locales dans la planification des villes secondaires comme Beni, avec des approches participatives et basées sur des données fiables. Les outils numériques (QGIS, KoboToolbox, STDM) sont intégrés dans les formations et les processus décisionnels pour renforcer la transparence, la participation citoyenne, et l’efficacité des politiques publiques en matière de terres, d’habitat et d’environnement.",
                                        "agribusiness" => "Le secteur de l’agribusiness, notamment à travers la filière café, constitue un levier stratégique du relèvement socio-économique dans l’est de la RDC. L’IRI-UCBC mène des recherches appliquées visant à renforcer les capacités des producteurs, des jeunes entrepreneurs et des coopératives locales. Ces initiatives incluent la cartographie des zones agricoles, l’amélioration des pratiques de production, le développement d’outils numériques tels que l’Atlas du Café, et l’accompagnement à la commercialisation équitable. À travers ces actions, l’institut favorise la résilience économique des communautés, la création d’emplois ruraux et la valorisation durable des ressources agricoles locales.",
                                        "justice" => "Dans un contexte marqué par des décennies de conflits armés, l’IRI-UCBC s’intéresse à la justice transitionnelle et à la recherche de la paix durable. À travers des recherches participatives, des ateliers de dialogue communautaire et des enquêtes de terrain, l’institut documente les récits des victimes, les dynamiques locales de réconciliation et les mécanismes coutumiers de résolution des conflits. Ce travail vise à nourrir les politiques de justice réparatrice, à renforcer la mémoire collective et à promouvoir une cohabitation pacifique dans les zones post-conflit, notamment en Ituri et au Nord-Kivu.",
                                        "innovation" => "L’UCBC, à travers son modèle d’université centrée sur la communauté, encourage l’innovation sociale comme levier de transformation locale. Ce secteur regroupe des projets interdisciplinaires qui mobilisent les étudiants, chercheurs et citoyens autour de défis tels que la gestion participative des déchets, la réinvention de l’économie locale, ou encore l’entrepreneuriat social. L’objectif est de développer des solutions concrètes, enracinées dans les réalités locales, capables d’améliorer la qualité de vie, de restaurer la dignité et de bâtir des communautés résilientes et responsables.",
                                ];
                                function truncate($text, $limit = 250) {
                                        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit) . '...' : $text;
                                }
                        @endphp

                        <style>
                              
                        </style>

                        <div class="relative flex flex-col mt-4">
                            <div class="px-4 py-5 flex-auto" onclick="showModal('gouvernance')">
                                <div class="flex items-center mb-1">
                                    <span class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-white mr-3">
                                        <i class="fas fa-leaf"></i>
                                    </span>
                                    <h6 class="text-xl font-semibold">Gouvernance des ressources naturelles</h6>
                                </div>
                                <p class="mb-4 text-blueGray-500" style="cursor:pointer;">
                                    {{ truncate($descriptions['gouvernance']) }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-col min-w-0">
                            <div class="px-4 py-5 flex-auto" onclick="showModal('agribusiness')">
                                <div class="flex items-center mb-1">
                                    <span class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-white mr-3">
                                        <i class="fas fa-seedling"></i>
                                    </span>
                                    <h6 class="text-xl font-semibold">Agribusiness et relèvement socio-économique</h6>
                                </div>
                                <p class="mb-4 text-blueGray-500" style="cursor:pointer;">
                                    {{ truncate($descriptions['agribusiness']) }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-col min-w-0 mt-4">
                            <div class="px-4 py-5 flex-auto" onclick="showModal('justice')">
                                <div class="flex items-center mb-1">
                                    <span class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-white mr-3">
                                        <i class="fas fa-balance-scale"></i>
                                    </span>
                                    <h6 class="text-xl font-semibold">Justice transitionnelle et consolidation de la paix</h6>
                                </div>
                                <p class="mb-4 text-blueGray-500" style="cursor:pointer;">
                                    {{ truncate($descriptions['justice']) }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-col min-w-0">
                            <div class="px-4 py-5 flex-auto" onclick="showModal('innovation')">
                                <div class="flex items-center mb-1">
                                    <span class="text-blueGray-500 p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-white mr-3">
                                        <i class="fas fa-lightbulb"></i>
                                    </span>
                                    <h6 class="text-xl font-semibold">Innovation sociale et transformation communautaire</h6>
                                </div>
                                <p class="mb-4 text-blueGray-500" style="cursor:pointer;">
                                    {{ truncate($descriptions['innovation']) }}
                                </p>
                            </div>
                        </div>

                        <div id="desc-modal-bg" class="modal-bg">
                            <div class="modal-content">
                                <button class="modal-close" onclick="closeModal()">&times;</button>
                                <h4 id="modal-title" class="text-xl font-bold mb-4"></h4>
                                <p id="modal-desc" class="text-blueGray-500"></p>
                                <div class="mt-6 text-right">
                                    <a id="modal-link" href="#" class="inline-block btn-ci font-semibold px-5 py-2 rounded shadow transition">
                                        Voir les réalisations du secteur
                                    </a>
                                </div>
                            </div>
                        </div>
                        <script>
                            const descriptions = @json($descriptions);
                            const titles = {
                                "gouvernance": "Gouvernance des ressources naturelles",
                                "agribusiness": "Agribusiness et relèvement socio-économique",
                                "justice": "Justice transitionnelle et consolidation de la paix",
                                "innovation": "Innovation sociale et transformation communautaire"
                            };
                            const links = {
                                "gouvernance": "{{ url('/realisations/gouvernance') }}",
                                "agribusiness": "{{ url('/realisations/agribusiness') }}",
                                "justice": "{{ url('/realisations/justice') }}",
                                "innovation": "{{ url('/realisations/innovation') }}"
                            };
                            function showModal(key) {
                                document.getElementById('modal-title').innerText = titles[key];
                                document.getElementById('modal-desc').innerText = descriptions[key];
                                document.getElementById('modal-link').setAttribute('href', links[key]);
                                document.getElementById('desc-modal-bg').classList.add('active');
                            }
                            function closeModal() {
                                document.getElementById('desc-modal-bg').classList.remove('active');
                            }
                            document.getElementById('desc-modal-bg').addEventListener('click', function(e) {
                                if (e.target === this) closeModal();
                            });
                            document.addEventListener('keydown', function(e) {
                                if (e.key === "Escape") closeModal();
                            });
                        </script>

                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>


<!-- Project Carousel Blade View -->


<div class="container-full flex flex-col lg:flex-row gap-6">
  <!-- Left: Project Carousel (optimized layout) -->
  <div class="w-full lg:w-8/12 order-1 lg:order-1">
    <!-- Project Carousel Blade View -->
    <div class="ease-soft-in-out relative h-full bg-gray-50 transition-all duration-200">
    <div class="w-full p-6 mx-auto bg-white">
        <div class="flex flex-wrap">
          <div class="w-full mt-6">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="flex justify-start mb-2">
                <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider btn-ci rounded shadow">
                  Publications et Articles à la une...
                </span>
              </div>
              <div class="flex-auto p-4">
                
             

                <div class="splide" id="project-carousel">
                  <div class="splide__track">
                    <ul class="splide__list">
                      @foreach($publications as $slide)
                      <li class="splide__slide">
                        <div class="relative flex flex-col w-full h-full min-w-0 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border">
                          <div class="relative w-full group overflow-hidden rounded-2xl">
                            <a class="block w-full overflow-hidden">
                              <img src="'../assets/img/iri.jpg" alt="thundernail"
                                   class="transition-transform duration-500 ease-in-out transform group-hover:scale-105 w-full h-[250px] sm:h-[300px] object-cover rounded-2xl" />
                                    <div class="absolute bottom-0 left-0 w-full z-20 rounded-b-2xl"
                                                            style="background: rgba(0, 0, 0, 0.6);">
                                                            <a href="#"
                                                                class="block text-white text-sm px-4 py-2 hover:underline">
                                                                {{ $slide['titre'] }}
                                                            </a>
                                                        </div>
                            </a>
                          </div>
                          <div class="flex-auto pt-4 w-full">
                            <!-- <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">
                              {{ $slide['project'] }}
                            </p>-->
                             <p class="mb-4 leading-normal text-sm">
                              {{ Str::limit($slide['resume'], 100) }}
                            </p> 
                          </div>
                        </div>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                </div>

                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
                <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
                <script>
                  document.addEventListener('DOMContentLoaded', function () {
                    new Splide('#project-carousel', {
                      type: 'loop',
                      perPage: 4,
                      perMove: 1,
                      gap: '1rem',
                      autoplay: true,
                      interval: 2500,
                      pauseOnHover: true,
                      speed: 700,
                      easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                      arrows: true,
                      pagination: false,
                      breakpoints: {
                        1536: { perPage: 3 },
                        1280: { perPage: 3 },
                        1024: { perPage: 2 },
                        768 : { perPage: 1 },
                        0   : { perPage: 1 }
                      }
                    }).mount();
                  });
                </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Right: Aside - Featured Articles beside carousel -->
<aside class="w-full lg:w-4/12 order-2 lg:order-2 mt-6 lg:mt-0 lg:ml-6 lg:mr-4">
    <div class="bg-white shadow rounded-xl h-full">
        <div class="flex justify-start mb-2">
                <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider btn-ci rounded shadow">
                  Evenements en vedette
                </span>
              </div>
        <ul class="space-y-4">
            <li class="flex gap-4 p-2">
                <img src="../assets/img/home-decor-1.jpg" alt="" class="w-16 h-16 object-cover rounded bg-gray-100" />
                <div class="py-1 pl-4">
                     <a href="#" class="text-sm font-semibold text-gray-800 hover:underline focus:underline" style="text-decoration-thickness: 2px;">
                        Titre de l'article 1
                    </a>
                    <p class="text-xs text-gray-600">Un petit aperçu de l'article en question avec une limite de caractères…</p>
                </div>
            </li>
            <li class="flex gap-4 p-2">
                <img src="../assets/img/home-decor-2.jpg" alt="" class="w-16 h-16 object-cover rounded bg-gray-100" />
                <div class="py-1 pl-4">
                     <a href="#" class="text-sm font-semibold text-gray-800 hover:underline focus:underline" style="text-decoration-thickness: 2px;">
                        Titre de l'article 1
                    </a>
                    <p class="text-xs text-gray-600">Encore un extrait qui donne bbbxb dfsfsd dsfsfs sfdsf fdsfdf envie de cliquer et d’en savoir plus…</p>
                </div>
            </li>
        </ul>

                  
                
<!-- Twitter Timeline Carousel -->
<!-- Twitter Timeline & Replies Carousel -->
<div class="mt-6 flex flex-col items-center gap-4">
    <!-- Timeline: Tweets & Replies -->
    <a class="twitter-timeline"
         data-theme="light"
         data-width="400"
         data-height="500"
         data-chrome="noheader nofooter"
         data-tweet-limit="5"
         data-show-replies="true"
         <a href="https://twitter.com/x?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-lang="en" data-dnt="true" data-show-count="false">Follow @x</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        Tweets by @IRI_UCBC
    </a>
    
</div>
<!-- Script nécessaire pour charger le widget Twitter -->
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Script nécessaire pour charger le widget Twitter -->





    </div>

    
</aside>
</div>








@endsection

<!-- Script du carrousel -->
<script>
   
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('news-carousel-list');
    if (!list) return;
    const items = list.children;
    const carouselContainer = document.getElementById('vertical-news-carousel');
    const itemCount = items.length;
    let current = 0;
    let direction = 1;

    function getItemHeight(index) {
        return items[index].getBoundingClientRect().height;
    }

    function updateCarouselHeight() {
        const itemHeight = getItemHeight(current);
        list.style.height = itemHeight + 'px';
        carouselContainer.style.height = itemHeight + 'px';
    }

    function randomDirection() {
        return Math.random() > 0.5 ? 1 : -1;
    }

    function scrollNews() {
        direction = randomDirection();
        current = (current + direction + itemCount) % itemCount;
        const itemHeight = getItemHeight(current);
        list.style.transform = `translateY(-${current * itemHeight}px)`;
        updateCarouselHeight();
    }

    function toggleResponsiveElements() {
        const isMobile = window.innerWidth < 640;

        document.querySelectorAll('[data-role="desc"]').forEach(el => {
            el.style.display = isMobile ? 'none' : 'block';
        });

        document.querySelectorAll('[data-role="btn"]').forEach(el => {
            el.style.display = isMobile ? 'none' : 'inline-block';
        });

        updateCarouselHeight();
    }

    toggleResponsiveElements();
    updateCarouselHeight();
    setInterval(scrollNews, 3500);
    window.addEventListener('resize', toggleResponsiveElements);
});
</script>
