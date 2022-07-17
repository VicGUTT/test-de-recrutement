<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{  config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('dist/app.css') }}">
    <script src="{{ mix('dist/app.js') }}" defer></script>

    @stack('styles')
</head>
<body>
    {{ $slot }}
</body>
</html>