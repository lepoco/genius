@extends('layouts.box', [
'title' => \App\Core\Facades\Translate::string('Sign In'),
'background' => $base_url . 'img/pexels-photo-373543.jpeg'
])

@section('content')
<h2 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Sign In')</h2>
<div>
  <form id="signin">
    <input type="hidden" name="action" value="SignIn" />
    <input type="hidden" name="nonce" value="@nonce('signin')" />
    <div class="mb-3 pr-2">
      <div class="floating-input -reveal">
        <input class="floating-input__field" type="email" name="email" placeholder="@translate('Email')">
        <label for="email">@translate('Email')</label>
      </div>
    </div>
    <div class="mb-3 pr-2">
      <div class="floating-input -reveal">
        <input class="floating-input__field" type="password" name="password" placeholder="@translate('Password')">
        <label for="password">@translate('Password')</label>
      </div>
    </div>
    <div class="-reveal -pb-1">
      <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Sign in')</button>
      <a href="@url" class="btn btn-secondary btn-mobile">@translate('Back to home')</a>
    </div>
  </form>
</div>
@endsection