@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Change password')
])
@section('content')

<div class="dashboard container -mt-5 -mb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Password change')</h4>
    </div>
    <div class="col-12">
      <form id="changePassword" method="POST">
        <input type="hidden" name="action" value="ChangePassword" />
        <input type="hidden" name="nonce" value="@nonce('changepassword')" />
        <input type="hidden" name="id" value="{{ $user->getId() }}" />

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="password" placeholder="@translate('Current password')"
            name="current_password">
          <label for="current_password">@translate('Current password')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="password" placeholder="@translate('New password')"
            name="new_password">
          <label for="new_password">@translate('New password')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="password" placeholder="@translate('Confirm new password')"
            name="new_password_confirm">
          <label for="new_password_confirm">@translate('Confirm new password')</label>
        </div>

        <div class="-flex-center -mb-2 -reveal">
          <img width="50" height="50" lazy class="-mr-1" src="@asset('img/svg/keepassxc-mono.svg')" alt="@translate('KeepassXC Monochrome logo')" />
          <div>
            <strong>@translate('Password too short?')</strong>
            <br>
            <a target="_blank" rel="noopener nofollow" href="https://keepassxc.org/download/">@translate('Get a free and open source password manager for yourself.')</a>
          </div>
        </div>

        <div class="-reveal -pb-1">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Update')</button>
          <a href="@url('dashboard/account')" class="btn btn-outline-dark btn-mobile">@translate('Back to account')</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
