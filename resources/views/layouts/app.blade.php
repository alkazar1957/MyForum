<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Scripts -->

    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/overrides.css') }}" rel="stylesheet">

    <style>
        body {
            /* background-color: #000; */
        }
        #app {
            max-width: 99%;
            padding-top: 50px;
        }
         .navbar-inverse {
            background-color: #222;
            border-color: #080808;
        }
        .navbar-inverse .navbar-toggler .icon-bar {
            background-color: #888;
        }

        .navbar-toggler .icon-bar {
            display: block;
            width: 22px;
            height: 2px;
            margin-bottom: 4px;
            border-radius: 1px;
        }
        .navbar-toggler:hover, .navbar-toggler:active {
            outline: none!important;
        }
        .nav-link:hover {
            color: #3490dc!important;
            font-weight: 600;
            text-decoration: underline;
            font-style: italic;
        }
        .container-fluid>.row>.col-sm-9, .container-fluid>.row>.col-md-9 {
            padding-right: 0px;
        }
        .card, .card-header {
            border-top-left-radius: 20px!important;
            border-top-right-radius: 20px!important;
        }
/*         .card-body {
            background-color: var(--dark);
            color: white;
        }
 */
         .best-reply {
            background-color: #79d4a0;
        }
        .navbar-input-search {
            border-radius: 30px!important;
            border: 2px solid #333333;
            height: calc(1.6rem + 2px);
            margin-top: 6px;
        }
        .navbar-input-search:focus {
            border: 2px solid #999;
        }
         span.input-group-btn > button {
            height: calc(1.6rem + 2px);
            margin-top: 0.34rem;
            padding: 0 10px!important;
            font-size: 1.7rem;
            color: #999;
        }
        #navbarSupportedContent {
            font-weight: 600;
        }
        .navbar-fixed-bottom, .navbar-fixed-top {
            position: fixed;
            right: 0;
            left: 0;
            z-index: 1030;
            top: 0;
            border-width: 0 0 1px;
        }
        .p-10pc {
            padding: 10%;
        }
        .fa {
            color: blue;
            margin-right: 1px;
            font-size:24px;
        }
    </style>
    <script>
        var textarea = null;
        if (window.document.querySelector("textarea")) {
            window.addEventListener("load", function() {
                textarea = window.document.querySelector("textarea");
                textarea.addEventListener("keypress", function() {
                    if(textarea.scrollTop != 0){
                        textarea.style.height = textarea.scrollHeight + "px";
                    }
                }, false);
            }, false);
        }
    </script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.0.0/trix.css">

@yield('header')

</head>
<body>
    <div id="app">

        @auth
            @include('layouts.navbar')
        @endauth
        
        <main class="py-4 row w-100 ml-1 pr-0">

            @auth
            <div class="col-sm-2 pr-0">
                    @include('layouts.left_sidebar')
            </div>
            @endauth

            <div class="col-sm-10 px-0">
                @yield('content')
            </div>

        </main>

    <flash message="{{ session('flash') }}"></flash>
    </div>
</body>
</html>
