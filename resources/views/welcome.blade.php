<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
            }
            .navbar {
                background-color: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
            .card {
                border: none;
                border-radius: 15px;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
            .icon-circle {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
                color: white;
            }
            .footer {
                background-color: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" height="30">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
            @if (Route::has('login'))
                    @auth
                                <li class="nav-item">
                                    <a href="{{ url('/home') }}" class="nav-link">Home</a>
                                </li>
                    @else
                                <li class="nav-item">
                                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                                </li>
                        @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container py-5 mt-5">
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <img src="https://laravel.com/img/logotype.min.svg" alt="Laravel" class="img-fluid mb-4" style="max-height: 50px;">
                </div>
                </div>

            <div class="row g-4">
                <!-- Documentation Card -->
                <div class="col-md-6">
                    <a href="https://laravel.com/docs" class="text-decoration-none">
                        <div class="card h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-4">
                                    <i class="fas fa-book fa-lg"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3 text-dark">Documentation</h3>
                                    <p class="text-muted mb-0">
                                    Laravel has wonderful documentation covering every aspect of the framework. Whether you are a newcomer or have prior experience with Laravel, we recommend reading our documentation from beginning to end.
                                </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Laracasts Card -->
                <div class="col-md-6">
                    <a href="https://laracasts.com" class="text-decoration-none">
                        <div class="card h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-4">
                                    <i class="fas fa-video fa-lg"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3 text-dark">Laracasts</h3>
                                    <p class="text-muted mb-0">
                                    Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development. Check them out, see for yourself, and massively level up your development skills in the process.
                                </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Laravel News Card -->
                <div class="col-md-6">
                    <a href="https://laravel-news.com" class="text-decoration-none">
                        <div class="card h-100 p-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle me-4">
                                    <i class="fas fa-newspaper fa-lg"></i>
                                </div>
                                <div>
                                    <h3 class="h4 mb-3 text-dark">Laravel News</h3>
                                    <p class="text-muted mb-0">
                                    Laravel News is a community driven portal and newsletter aggregating all of the latest and most important news in the Laravel ecosystem, including new package releases and tutorials.
                                </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Vibrant Ecosystem Card -->
                <div class="col-md-6">
                    <div class="card h-100 p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-4">
                                <i class="fas fa-globe fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="h4 mb-3 text-dark">Vibrant Ecosystem</h3>
                                <p class="text-muted mb-0">
                                    Laravel's robust library of first-party tools and libraries, such as 
                                    <a href="https://forge.laravel.com" class="text-decoration-none">Forge</a>, 
                                    <a href="https://vapor.laravel.com" class="text-decoration-none">Vapor</a>, 
                                    <a href="https://nova.laravel.com" class="text-decoration-none">Nova</a>, and 
                                    <a href="https://envoyer.io" class="text-decoration-none">Envoyer</a> help you take your projects to the next level.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3">
            <div class="container text-center">
                <p class="text-muted mb-0">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
