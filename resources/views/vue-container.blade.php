<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sports Watchlist</title>

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    {{-- Script --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
  </head>
  <body>
    <div id="app"></div>
  </body>
</html>
