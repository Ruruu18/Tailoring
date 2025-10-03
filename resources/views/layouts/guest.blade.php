<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/ICON.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
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
                background-color: rgba(0, 0, 0, 0.4);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }
            
            .login-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 16px;
                padding: 2rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                width: 100%;
                max-width: 400px;
                margin: 1rem;
            }
            
            .logo {
                display: block;
                margin: 0 auto 2rem auto;
                max-width: 120px;
                height: auto;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="overlay">
            <div class="login-container">
                <div class="text-center mb-6">
                    <a href="/">
                        <img src="{{ asset('images/LOGO.png') }}" alt="{{ config('app.name', 'Laravel') }}" class="logo">
                    </a>
                </div>
                
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
