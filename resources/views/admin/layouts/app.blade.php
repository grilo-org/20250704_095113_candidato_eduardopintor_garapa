<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($title))
    <title>{{ $title }} &raquo; {{ config('app.name', 'Laravel') }} Admin</title>
    @else
    <title>{{ config('app.name', 'Laravel') }}</title>
    @endif
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-brown navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}" title="Voltar ao Painel">
                    {{ config('app.name', 'Laravel') }}
                </a>
                @if(Request::route()->getName() != 'login' && Request::route()->getName() != 'register' && Request::route()->getName() != 'password.request')
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a href="{{ route('home') }}" class="nav-link{{ (\Request::route()->getName() == 'home') ? ' active' : '' }}">Painel</a></li>
                        <li class="nav-item"><a href="{{ route('curadores') }}" class="nav-link{{ (\Request::route()->getPrefix() == 'admin/curadores') ? ' active' : '' }}">Curadores</a></li>
                        <li class="nav-item"><a href="{{ route('experiencias') }}" class="nav-link{{ (\Request::route()->getPrefix() == 'admin/experiencias') ? ' active' : '' }}">Experiências</a></li>
                        @if(Auth::user()->hasRole('admin'))
                        <li class="nav-item"><a href="{{ route('comidas') }}" class="nav-link{{ (\Request::route()->getPrefix() == 'admin/comidas') ? ' active' : '' }}">Comidas</a></li>
                        <li class="nav-item"><a href="{{ route('usuarios') }}" class="nav-link{{ (\Request::route()->getPrefix() == 'admin/usuarios') ? ' active' : '' }}">Usuários</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @else
                        <li class="nav-item"><a href="/" class="nav-link"><i class="fas fa-home"></i> Voltar ao site</a></li>
                        <li><span class="nav-link">|</span></li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="fas fa-user-tie"></i> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->hasRole('curator'))
                                <a class="dropdown-item" href="{{ route('curadores') }}/edit/{{ Auth::user()->curator_id }}">
                                    {{ __('Perfil') }}
                                </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
                @endif
            </div>
        </nav>

        <main class="py-4">
            @if(Request::route()->getName() != 'login' && Request::route()->getName() != 'register' && Request::route()->getName() != 'password.request')
            <div class="container">
                <h1 class="page-title">{{ (isset($title)) ? $title : 'GarAppa' }}</h1>
            </div>
            @endif
            <div class="container">

                @if(Session::has('flash_message'))
                    @if(Session::has('flash_message_type'))
                        <div class="alert alert-{{ session('flash_message_type') }}">{!! session('flash_message') !!}</div>
                    @else
                        <div class="alert alert-success">{!! session('flash_message') !!}</div>
                    @endif
                @endif

                @yield('content')

            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
      // Initialize Firebase
      var firebase_config = {
        apiKey: "{{ config('services.firebase.api_key') }}",
        authDomain: "{{ config('services.firebase.auth_domain') }}",
        databaseURL: "{{ config('services.firebase.database_url') }}",
        storageBucket: "{{ config('services.firebase.storage_bucket') }}",
        projectId: "{{ config('services.firebase.project_id') }}",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}"
    };
</script>    
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
