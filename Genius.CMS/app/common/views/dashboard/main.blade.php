@extends('layouts.app', [
'title' => 'Dashboard'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Dashboard')</h4>
    </div>
    <div class="col-12 dashboard__section -mb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h4>Sample Expert System</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex.</p>

          <a href="@url('dashboard/sys')" class="btn btn-outline-dark btn-mobile -lg-mr-1"
            type="button">@translate('Run')</a>
          <a href="@url('dashboard/edit')" class="btn btn-dark btn-mobile -lg-mr-1" type="button">@translate('Edit')</a>
        </div>
      </div>
    </div>
    <div class="col-12 dashboard__section -mb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h4>Another Expert System</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex.</p>

          <a href="@url('dashboard/sys')" class="btn btn-outline-dark btn-mobile -lg-mr-1"
            type="button">@translate('Run')</a>
          <a href="@url('dashboard/edit')" class="btn btn-dark btn-mobile -lg-mr-1" type="button">@translate('Edit')</a>
        </div>
      </div>
    </div>
    <div class="col-12 dashboard__section -mb-3 -reveal">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2">
        <div>
          <h4>Yet another Expert System</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex.</p>

          <a href="@url('dashboard/sys')" class="btn btn-outline-dark btn-mobile -lg-mr-1"
            type="button">@translate('Run')</a>
          <a href="@url('dashboard/edit')" class="btn btn-dark btn-mobile -lg-mr-1" type="button">@translate('Edit')</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection