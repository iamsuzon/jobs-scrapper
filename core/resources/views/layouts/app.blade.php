<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Styles -->
    @yield('styles')
</head>
<body>
<style>
    .nav-link {
        color: #fff;
        border-bottom: transparent 2px solid;
    }

    .nav-link:is(:hover, :focus, .active) {
        background: #ffffff;
        color: #0d6efd;
        border-bottom-color: #ff002f;
    }
</style>
<div id="app">
    @auth()
        <nav class="bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link {{url()->current() == route('home') ? 'active' : ''}}" href="{{route('home')}}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{url()->current() == route('keywords') ? 'active' : ''}}"
                                   href="{{route('keywords')}}">Keywords</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{url()->current() == route('jobs.all') ? 'active' : ''}}"
                                   href="{{route('jobs.all')}}">Scrap Job List</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    @endauth

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<!-- Scripts -->
@yield('scripts')
</body>
</html>
