@extends('layouts.box', [
'title' => \App\Core\Facades\Translate::string('Not found'),
'background' => $base_url . 'img/pexels-person-holding-bitcoin.jpeg'
])

@section('content')
<h1 class="accent -reveal">@translate('Ups!')</h1>
<div class="-reveal">
  <p>@translate('The page you are looking for has not been found.')</p>
  <a href="@url" class="btn btn-dark">@translate('Back to the home page')</a>
</div>

@endsection