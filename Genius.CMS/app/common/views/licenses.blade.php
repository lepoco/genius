@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Licenses'),
])
@section('content')
<div class="container -pt-5">
  <div class="row">

    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Licenses')</h4>
    </div>

    <div class="col-12 -mb-5">
      <div>

        <p class="-reveal">
          Genius - Implementation of an expert system for diagnosing diseases as part of the diploma thesis
          <br>
          <small>GNU General Public License v3.0 -</small> <a target="_blank" rel="noopener nofollow"
            href="https://github.com/lepoco/Genius"><small>https://github.com/lepoco/Genius</small></a>
        </p>

        <hr>

        @foreach($softwareList as $software)
        <p class="-reveal">
          {{ $software['name'] ?? '' }}
          <br>
          <small>{{ $software['license'] ?? '' }} -</small> <a target="_blank" rel="noopener nofollow"
            href="{{ $software['url'] ?? '' }}"><small>{{ $software['url'] ?? '' }}</small></a>
        </p>
        @endforeach

      </div>
    </div>
  </div>
</div>

@endsection