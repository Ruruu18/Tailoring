<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/ICON.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                height: 100vh;
                background-image: url('{{ asset('images/BACKDROP.jpeg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .content {
                text-align: center;
                color: white;
                z-index: 1;
            }

            .auth-links-center {
                 display: flex;
                 gap: 15px;
                 justify-content: center;
                 margin-top: 1rem;
             }
             
             .auth-links-center a {
                 color: white;
                 text-decoration: none;
                 padding: 12px 24px;
                 border: 1px solid rgba(255, 255, 255, 0.3);
                 border-radius: 5px;
                 transition: all 0.3s ease;
                 font-weight: 500;
             }
             
             .auth-links-center a:hover {
                 background-color: rgba(255, 255, 255, 0.1);
                 border-color: rgba(255, 255, 255, 0.5);
                 transform: translateY(-2px);
             }
        </style>
    </head>
    <body>
        <div class="overlay">
            <div class="content">
                <h1 style="font-size: 3rem; margin-bottom: 1rem; font-weight: 600;">Welcome to {{ config('app.name', 'Laravel') }}</h1>
                <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 2rem;">Your tailoring friendly</p>
                
                @if (Route::has('login'))
                    <div class="auth-links-center">
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
