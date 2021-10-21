<!doctype html>
<html lang="@option('language', 'en')">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Cache-control" content="private">
  <link rel="preload" href="@asset('bundle.min.css'){!! '?v=' . $version !!}" as="style">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
  <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#212529">
  <meta name="msapplication-starturl" content="/">
  <title>@option('sitename', 'Genius'){{ isset($title) ? ' - ' . $title : '' }}</title>
  <link rel="apple-touch-icon" href="@asset('img/icons/192.png'){!! '?v=' . $version !!}">
  <link rel="icon" href="@asset('img/icons/192.png'){!! '?v=' . $version !!}">
  <link rel="manifest" href="@asset('m.webmanifest'){!! '?v=' . $version !!}">
  <link type="text/css" rel="stylesheet" href="@asset('bundle.min.css'){!! '?v=' . $version !!}"
    crossorigin="anonymous">
  <meta name="description" content="{{ $description ?? 'Genius - Expert Systems' }}">

  <script>
    window.app = @json( $js_data, JSON_PRETTY_PRINT );
  </script>

  <script src="@asset('bundle.min.js'){!! '?v=' . $version !!}" crossorigin="anonymous" defer></script>

  <noscript>
    <style>
      section.transition {
        display: none !important;
      }
    </style>
  </noscript>

</head>

<body class="{{ implode(' ', $body_classes) }}">

  @include('components.cookie')

  <div id="app">
