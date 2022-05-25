<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="{{ url('images/gumanet-icon.png') }}" />
        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{ url('css/bootstrap.min.css') }}">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
        </style>
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="{{ url('css/signin.css') }}">
    </head>
    <body class="text-center">
        <div id="contentL">
            @yield('content')
        </div>
    </body>
    <footer id="_footer">
        <p>@yield('version')</p>
    </footer>
</html>
