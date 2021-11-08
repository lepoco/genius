@extends('layouts.app', [
'title' => 'Dashboard'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Dashboard')</h4>
    </div>
    
    @isset($systems)

      @foreach($systems as $singleSystem)
      <div class="col-12 dashboard__section -mb-3 -reveal">
        <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
          <div>
            <h4>{{ $singleSystem->getName() ?? '__UNKNOWN__' }}</h4>
            <p>{{ $singleSystem->getDescription() ?? '__UNKNOWN__' }}</p>
            @php

            $sysPath = \App\Core\Http\Redirect::url('dashboard/sys/' . $singleSystem->getUUID());
            $editPath = \App\Core\Http\Redirect::url('dashboard/edit/' . $singleSystem->getUUID());

            @endphp
            <a href="{{ $sysPath ?? '#' }}" class="btn btn-outline-dark btn-mobile -lg-mr-1"
              type="button">@translate('Run')</a>
            <a href="{{ $editPath ?? '#' }}" class="btn btn-dark btn-mobile -lg-mr-1" type="button">@translate('Edit')</a>
          </div>
        </div>
      </div>
      @endforeach

    @endisset

  </div>
</div>

@endsection
