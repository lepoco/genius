@extends('layouts.app', [
'title' => 'Statistics'
])

@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Statistics')</h4>
    </div>

    <div class="col-12 col-lg-4 -pb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h3 class="-flex"> <i class="icon-ic_fluent_people_20 -s-24 -mr-1"></i> {{ $users_count ?? 0 }}</h3>
          <p>@translate('Users')</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4 -pb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h3 class="-flex"> <i class="icon-ic_fluent_payment_24 -s-24 -mr-1"></i> {{ $systems_count ?? 0 }}</h3>
          <p>@translate('Expert systems')</p>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4 -pb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h3 class="-flex"> <i class="icon-ic_fluent_fingerprint_24 -s-24 -mr-1"></i> {{ $reqests_today ?? 0 }}</h3>
          <p>@translate('Requests today')</p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
