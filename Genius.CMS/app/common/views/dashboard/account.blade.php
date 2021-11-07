@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Account')
])
@section('content')

<div class="dashboard container -mt-5 -mb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Account')</h4>
    </div>
    <div class="col-12 dashboard__section">
      <div class="dashboard__banner h-100 p-5 bg-light -rounded-2 -reveal">
        <div class="dashboard__banner__picture">
          <img class="editable__picture" data-errorsrc="@asset('img/pexels-photo-8386434.jpeg')" src="{{ $user->getImage(true) ?? '' }}"
            alt="Stack Overflow logo and icons and such">
        </div>
        <div>
          <h4>@translate('Hello,') <span class="editable__displayname">{{ $user->getDisplayName() }}</span></h4>
          <p>{{ $user->getEmail() }}</p>
        </div>
      </div>
    </div>
    <div class="col-12 -mt-5">
      <form id="account" method="POST">
        <input type="hidden" name="action" value="Account" />
        <input type="hidden" name="nonce" value="@nonce('account')" />
        <input type="hidden" name="id" value="{{ $user->getId() }}" />

        <div class="floating-input -reveal">
          <input disabled="disabled" class="floating-input__field -keep-disabled" type="text" name="email"
            placeholder="@translate('Email')" value="{{ $user->getEmail() }}">
          <label for="email">@translate('Email')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Display name')"
            value="{{ $user->getDisplayName() }}" name="displayname">
          <label for="displayname">@translate('Display name')</label>
        </div>

        <div class="floating-input -reveal">
          <select class="floating-input__field" placeholder="@translate('Language')"
            data-selected="{{ $user->getLanguage() ?? 'en_US' }}" name="language">
            <option value="en_US">@translate('English')</option>
            <option value="pl_PL">@translate('Polish')</option>
          </select>
          <label for="language">@translate('Language')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="file" placeholder="@translate('Profile picture')" value=""
            name="picture">
          <label for="picture">@translate('Profile picture')</label>
        </div>

        <div class="-pb-1 -reveal">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Update')</button>
          <a href="@url('dashboard/billing')" class="btn btn-outline-dark btn-mobile -lg-mr-1">@translate('Edit your billing details')</a>
          <a href="@url('dashboard/password')" class="btn btn-outline-dark btn-mobile">@translate('Change your password')</a>
          {{-- <a href="@dashurl('account/two-step')" class="btn btn-outline-dark btn-mobile"
            type="button">@translate('Two-step
            authentication')</a> --}}
        </div>

      </form>
    </div>
  </div>
</div>

@endsection