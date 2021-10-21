@extends('layouts.app', [
'title' => 'Change password'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3">@translate('Password change')</h4>
    </div>
    <div class="col-12">
      <form id="changePassword" method="POST">
        <input type="hidden" name="action" value="ChangePassword" />
        <input type="hidden" name="nonce" value="@nonce('changepassword')" />
        <input type="hidden" name="id" value="{{ $user->getId() }}" />

        <div class="floating-input">
          <input class="floating-input__field" type="password" placeholder="@translate('Current password')"
            name="current_password">
          <label for="current_password">@translate('Current password')</label>
        </div>

        <div class="floating-input">
          <input class="floating-input__field" type="password" placeholder="@translate('New password')"
            name="new_password">
          <label for="new_password">@translate('New password')</label>
        </div>

        <div class="floating-input">
          <input class="floating-input__field" type="password" placeholder="@translate('Confirm new password')"
            name="new_password_confirm">
          <label for="new_password_confirm">@translate('Confirm new password')</label>
        </div>

        <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Update')</button>
        <a href="@url('dashboard/account')" class="btn btn-outline-dark btn-mobile">@translate('Back to account')</a>
      </form>
    </div>
  </div>
</div>

@endsection
