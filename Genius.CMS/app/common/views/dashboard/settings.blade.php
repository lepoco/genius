@extends('layouts.app', [
'title' => 'Settings'
])

@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Settings')</h4>
    </div>

    <div class="col-12 -mt-5">
      <form id="dashboardSettings" method="POST">
        <input type="hidden" name="action" value="DashboardSettings" />
        <input type="hidden" name="nonce" value="@nonce('dashboardsettings')" />

        <div class="floating-input -reveal">
          <select class="floating-input__field" placeholder="@translate('Default language')"
           data-selected="@option('language', 'en_US')" name="language">
            <option value="en_US">@translate('English')</option>
            <option value="pl_PL">@translate('Polish')</option>
          </select>
          <label for="language">@translate('Default language')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field -keep-disabled" type="text" name="site_name"
            placeholder="@translate('Site name')" value="@option('site_name', 'Polkryptex')">
          <label for="site_name">@translate('Site name')</label>
        </div>

        <hr>

        <div class="floating-input -reveal">
          <input class="floating-input__field -keep-disabled" type="text" name="base_url"
            placeholder="@translate('Base URL')" value="@option('base_url', '#')">
          <label for="base_url">@translate('Base URL')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field -keep-disabled" type="text" name="home_url"
            placeholder="@translate('Home URL')" value="@option('home_url', '#')">
          <label for="home_url">@translate('Home URL')</label>
        </div>

        <hr>

        <div class="floating-input -reveal">
          <input class="floating-input__field -keep-disabled" type="number" name="signout_time"
            placeholder="@translate('Signout time')" value="@option('signout_time', 15)">
          <label for="signout_time">@translate('Signout time')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field -keep-disabled" type="text" name="cookie_name"
            placeholder="@translate('Privacy cookie name')" value="@option('cookie_name', '#')">
          <label for="cookie_name">@translate('Privacy cookie name')</label>
        </div>


        <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1 -reveal">@translate('Update')</button>
      </form>
    </div>
  </div>
</div>

@endsection
