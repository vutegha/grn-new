<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Public Services Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/styles/tailwind.css">
    <link rel="stylesheet" href="https://demos.creative-tim.com/notus-js/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(to right, #007bff, #6610f2);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .service-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            background-color: #f8f9fa;
        }
        .service-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
        }
        .service-card .card-text {
            font-size: 1rem;
            color: #6c757d;
        }
        .service-card .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .service-card .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="container">
            @include('partials.navbar')
        </div>
    </div>

    <main class="container-full pt-4">
        @yield('content')

        <div class="container my-5">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card service-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Service Title</h5>
                            <p class="card-text">Service description goes here.</p>
                            <a href="#" class="btn btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Ajoutez d'autres cartes ici si besoin -->
            </div>
        </div>

        <div class="section">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-8 ml-auto mr-auto text-center">
                        <h2 class="title">Do you like what you see?</h2>
                        <p class="description">Cause if you do, it can be yours for Free. Hit the button below and download it. Start a new project or give an old Bootstrap 4 project a new look.</p>
                    </div>
                    <div class="col-md-5 ml-auto mr-auto download-area">
                        <a href="http://www.creative-tim.com/product/paper-kit-2" class="btn btn-danger btn-round">Download free HTML</a>
                    </div>
                </div>
                <div class="row text-center upgrade-pro">
                    <div class="col-md-8 ml-auto mr-auto">
                        <h2 class="title">Want more?</h2>
                        <p class="description">We've just launched
                            <a href="http://demos.creative-tim.com/paper-kit-2-pro/presentation.html?ref=utp-pk2-demos" class="text-danger" target="_blank">Paper Kit 2 PRO</a>. It has a huge number of components, sections and example pages.</p>
                    </div>
                    <div class="col-sm-5 ml-auto mr-auto">
                        <a href="https://www.creative-tim.com/product/paper-kit-2-pro?ref=utp-pk-demos" class="btn btn-info btn-round" target="_blank">
                            <i class="nc-icon nc-spaceship" aria-hidden="true"></i> Upgrade to PRO
                        </a>
                    </div>
                </div>
                <div class="row justify-content-md-center sharing-area text-center">
                    <div class="text-center col-md-12 col-lg-8">
                        <h3>Thank you for supporting us!</h3>
                    </div>
                    <div class="text-center col-md-12 col-lg-8">
                        <a href="#pablo" class="btn btn-twitter-bg twitter-sharrre btn-round" rel="tooltip" title="Tweet!">
                            <i class="fa fa-twitter"></i> Twitter
                        </a>
                        <a href="#pablo" class="btn btn-google-bg linkedin-sharrre btn-round" rel="tooltip" title="Share!">
                            <i class="fa fa-google-plus"></i> Google
                        </a>
                        <a href="#pablo" class="btn btn-facebook-bg facebook-sharrre btn-round" rel="tooltip" title="Share!">
                            <i class="fa fa-facebook-square"></i> Facebook
                        </a>
                        <a href="https://github.com/creativetimofficial/paper-kit" class="btn btn-github-bg btn-github sharrre btn-round" rel="tooltip" title="Star on Github">
                            <i class="fa fa-github"></i> Star
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer footer-black footer-white">
        <div class="container">
            <div class="row">
                <nav class="footer-nav">
                    <ul>
                        <li>
                            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a>
                        </li>
                        <li>
                            <a href="http://blog.creative-tim.com/" target="_blank">Blog</a>
                        </li>
                        <li>
                            <a href="https://www.creative-tim.com/license" target="_blank">Licenses</a>
                        </li>
                    </ul>
                </nav>
                <div class="credits ml-auto">
                    <span class="copyright">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="./assets/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="./assets/js/plugins/bootstrap-switch.js"></script>
    <script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <script src="./assets/js/plugins/moment.min.js"></script>
    <script src="./assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="./assets/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <script>
        $(document).ready(function() {
            if ($("#datetimepicker").length != 0) {
                $('#datetimepicker').datetimepicker({
                    icons: {
                        time: "fa fa-clock-o",
                        date: "fa fa-calendar",
                        up: "fa fa-chevron-up",
                        down: "fa fa-chevron-down",
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-screenshot',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    }
                });
            }
            function scrollToDownload() {
                if ($('.section-download').length != 0) {
                    $("html, body").animate({
                        scrollTop: $('.section-download').offset().top
                    }, 1000);
                }
            }
        });
    </script>
</body>
</html>
