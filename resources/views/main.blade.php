<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Souper</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <div>
                    {{-- <img src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24"> --}}
                    <span class="navbar-brand mb-0 h1">Souper</span>
                </div>
            </div>
        </nav>

        {{-- ***** Vue ***** --}}
        <div id="app">
            <hello-world
                :start-count="100"
            >
                Loading...
            </hello-world>
        </div>


    </body>
</html>
