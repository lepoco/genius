<!doctype html>
<html lang="@domain(true)">

<head>
  <meta charset="utf-8">
  <link rel="preload" href="@asset('bundle.min.css')" as="style">
  <link rel="preload" href="@asset('bundle.min.js')" as="script">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
  <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#212529">
  <meta name="msapplication-starturl" content="/">
  <title>@option('site_name', 'Genius'){{ isset($title) ? ' - ' . $title : '' }}</title>
  <link rel="apple-touch-icon" href="@asset('img/icons/192.png')">
  <link rel="icon" href="@asset('img/icons/192.png')">
  <link rel="manifest" href="@asset('m.webmanifest')">
  <link defer type="text/css" rel="stylesheet" href="@asset('bundle.min.css')" nonce="@csp" as="style">
  <meta name="description" content="{{ $description ?? 'Genius - Expert Systems' }}">

  <script nonce="@csp">
    window.app = @json( $js_data, JSON_PRETTY_PRINT );
  </script>

  <script defer nonce="@csp" src="@asset('bundle.min.js')"></script>

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
