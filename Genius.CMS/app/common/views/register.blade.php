@extends('layouts.box', [
'title' => 'Register',
'background' => $base_url . 'img/pexels-photo-3867210.jpeg'
])

@section('content')
<h2 class="-font-secondary -fw-700 -pb-3">@translate('Register')</h2>
<form id="register">
  <input type="hidden" name="action" value="Register" />
  <input type="hidden" name="nonce" value="@nonce('register')" />
  <div class="mb-3 pr-2">
    <div class="floating-input">
      <input class="floating-input__field" type="email" name="email" placeholder="@translate('Email address')">
      <label for="email">@translate('Email address')</label>
    </div>
  </div>
  <div class="mb-3 pr-2">
    <div class="floating-input">
      <input class="floating-input__field" type="password" name="password" placeholder="@translate('Password')">
      <label for="password">@translate('Password')</label>
    </div>
  </div>
  <div class="mb-3 pr-2">
    <div class="floating-input">
      <input class="floating-input__field" type="password" name="password_confirm"
        placeholder="@translate('Confirm Password')">
      <label for="password_confirm">@translate('Confirm Password')</label>
    </div>
  </div>
  <div class="-flex-center -mb-2">
    <img width="50" height="50" lazy class="-mr-1" src="@asset('img/svg/keepassxc-mono.svg')" alt="@translate('KeepassXC Monochrome logo')" />
    <div>
      <strong>Password too short?</strong>
      <br>
      <a target="_blank" rel="noopener nofollow" href="https://keepassxc.org/download/">Get a free and open source password manager for yourself</a>
    </div>
  </div>
  <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Register')</button>
  <a href="@url" class="btn btn-secondary btn-mobile">@translate('Back to home')</a>
</form>
@endsection
