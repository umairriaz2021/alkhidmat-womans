<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>{{(!empty($page['props']['page']['meta_title'])) ? $page['props']['page']['meta_title'] : $page['props']['settings']['site_title']}} | {{$page['props']['settings']['site_tag']}}</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{$page['props']['page']['meta_description']}}" />
        @inertiaHead

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Components/{$page['component']}.jsx"])
       
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
