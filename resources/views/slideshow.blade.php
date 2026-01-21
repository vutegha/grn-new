@extends('layouts.iri')
@section('content')
<!-- Project Cards-->
<div class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen bg-gray-50 transition-all duration-200">
      <div class="w-full p-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
          
          <div class="flex-none w-full max-w-full px-3 mt-6">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
              <div class="p-4 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="mb-1">Projects</h6>
                <p class="leading-normal text-sm">Architects design houses</p>
              </div>
              <div class="flex-auto p-4">
                <div class="relative">
@php
    $slides = [
        ['img' => '../assets/img/home-decor-1.jpg', 'title' => 'Modern', 'desc' => 'As Uber works through a huge amount of internal management turmoil.', 'project' => 'Project #1'],
        ['img' => '../assets/img/home-decor-2.jpg', 'title' => 'Scandinavian', 'desc' => 'Music is something that every person has his or her own specific opinion about.', 'project' => 'Project #2'],
        ['img' => '../assets/img/home-decor-3.jpg', 'title' => 'Minimalist', 'desc' => 'Different people have different taste, and various types of music.', 'project' => 'Project #3'],
        ['img' => '../assets/img/bruce-mars.jpg', 'title' => 'Classic', 'desc' => 'Classic design never goes out of style.', 'project' => 'Project #4'],
        ['img' => '../assets/img/home-decor-1.jpg', 'title' => 'Industrial', 'desc' => 'Industrial style with a modern twist.', 'project' => 'Project #5'],
        ['img' => '../assets/img/home-decor-2.jpg', 'title' => 'Bohemian', 'desc' => 'Bohemian vibes for creative minds.', 'project' => 'Project #6'],
        ['img' => '../assets/img/home-decor-3.jpg', 'title' => 'Coastal', 'desc' => 'Bring the beach to you', 'project' => 'Project #7'],
        ['img' => '../assets/img/home-decor-2.jpg', 'title' => 'Rustic', 'desc' => 'Rustic charm and warmth.', 'project' => 'Project #8'],
    ];
@endphp
<div class="splide" id="project-carousel">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach($slides as $slide)
            <li class="splide__slide">
                <div class="carousel-slide flex-shrink-0 w-full px-4 transition-transform duration-500 ease-in-out">
                    <div class="relative">
                        <a class="block shadow-xl rounded-2xl">
                            <img src="{{ $slide['img'] }}" alt="img-blur-shadow" class="max-w-full shadow-soft-2xl rounded-2xl" />
                        </a>
                    </div>
                    <div class="flex-auto px-1 pt-6">
                        <p class="relative z-10 mb-2 leading-normal text-transparent bg-gradient-to-tl from-gray-900 to-slate-800 text-sm bg-clip-text">{{ $slide['project'] }}</p>
                        <a href="javascript:;">
                            <h5>{{ $slide['title'] }}</h5>
                        </a>
                        <p class="mb-6 leading-normal text-sm">{{ $slide['desc'] }}</p>
                        <div class="flex items-center justify-between">
                            <button type="button" class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro ease-soft-in text-xs hover:scale-102 active:shadow-soft-xs tracking-tight-soft border-fuchsia-500 text-fuchsia-500 hover:border-fuchsia-500 hover:bg-transparent hover:text-fuchsia-500 hover:opacity-75 hover:shadow-none active:bg-fuchsia-500 active:text-white active:hover:bg-transparent active:hover:text-fuchsia-500">View Project</button>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
<!-- SplideJS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Splide('#project-carousel', {
            type      : 'loop',
            perPage   : 4,
            perMove   : 1,
            gap       : '1rem',
            autoplay  : true,
            interval  : 2500,
            pauseOnHover: true,
            speed     : 700,
            easing    : 'cubic-bezier(0.4, 0, 0.2, 1)',
            arrows    : true,
            pagination: false,
            breakpoints: {
                1536: { perPage: 4 },
                1280: { perPage: 4 },
                1024: { perPage: 3 },
                768 : { perPage: 2 },
                0   : { perPage: 1 }
            }
        }).mount();
    });
</script>
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const track = document.getElementById('carousel-track');
                    const slides = Array.from(track.children);
                    const leftBtn = document.getElementById('carousel-left');
                    const rightBtn = document.getElementById('carousel-right');
                    let slideCount = slides.length;
                    let current = 0;
                    let slideWidth = slides[0].offsetWidth;
                    let visibleSlides = 1;
                    let autoScrollInterval = null;
                    let isHovered = false;

                    function updateVisibleSlides() {
                        if (window.innerWidth >= 1280) {
                            visibleSlides = 3; // xl:w-4/12 = 3 slides
                        } else if (window.innerWidth >= 1024) {
                            visibleSlides = 3; // lg:w-4/12 = 3 slides
                        } else if (window.innerWidth >= 768) {
                            visibleSlides = 2; // md:w-4/12 = 2 slides
                        } else {
                            visibleSlides = 1; // Only 1 slide on small screens
                        }
                        // On large screens (>=1280px), show 4 slides
                        if (window.innerWidth >= 1280) {
                            visibleSlides = 4;
                        }
                        slideWidth = slides[0].offsetWidth;
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
     <!-- End Project Cards -->

@endsection