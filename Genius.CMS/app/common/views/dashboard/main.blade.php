@extends('layouts.app', [
'title' => 'Dashboard'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3">@translate('Dashboard')</h4>
    </div>
    <div class="col-12 dashboard__section">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h4>Sample Expert System</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex.</p>

          <a href="@url('dashboard/exchange')" class="btn btn-outline-dark btn-mobile -lg-mr-1"
            type="button">@translate('Run')</a>
          <a href="@url('dashboard/topup')" class="btn btn-dark btn-mobile -lg-mr-1" type="button">@translate('Edit')</a>
        </div>
      </div>
    </div>
    <div class="col-12 -mt-5">
      Your wallets
      <hr>
      <div class="row">
        <div class="col-12 col-lg-6"></div>
      </div>
    </div>
  </div>
</div>

@endsection
