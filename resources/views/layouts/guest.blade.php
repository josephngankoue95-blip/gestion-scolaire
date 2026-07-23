<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Connexion')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="guest-body">

    <div class="guest-bg-shape guest-bg-shape-1"></div>
    <div class="guest-bg-shape guest-bg-shape-2"></div>

    <div class="guest-wrapper">
        @yield('content')
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>

</html>