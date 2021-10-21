@extends('layouts.app', [
'title' => 'Billing'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3">@translate('Billing')</h4>
    </div>
    <div class="col-12">
      <form id="updateBilling" method="POST">
        <input type="hidden" name="action" value="UpdateBilling" />
        <input type="hidden" name="nonce" value="@nonce('updatebilling')" />
        <input type="hidden" name="id" value="{{ $user->getId() }}" />

        <div class="row">
          <div class="col-12 col-lg-6">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('First name')" name="first_name" value="{{ $billing->getFirstName() ?? '' }}">
              <label for="first_name">@translate('First name')</label>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('Last name')" name="last_name" value="{{ $billing->getLastName() ?? '' }}">
              <label for="last_name">@translate('Last name')</label>
            </div>
          </div>

          <div class="col-12 col-lg-4">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('Street')" name="street" value="{{ $billing->getStreet() ?? '' }}">
              <label for="street">@translate('Street')</label>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('Postal code')"
                name="postal_code" value="{{ $billing->getPostalCode() ?? '' }}">
              <label for="postal_code">@translate('Postal code')</label>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('City')" name="city" value="{{ $billing->getCity() ?? '' }}">
              <label for="city">@translate('City')</label>
            </div>
          </div>

          <div class="col-12">
            <div class="floating-input">
              <input class="floating-input__field" type="text" placeholder="@translate('Province')" name="province" value="{{ $billing->getProvince() ?? '' }}">
              <label for="province">@translate('Province')</label>
            </div>
            @include('components.forms.input-country', ['selected' => $billing->getCountry() ?? ''])
          </div>

          <div class="col-12 -mb-2">
            <hr>
          </div>

          <div class="col-12 col-lg-6">
            <div class="floating-input">
              <input class="floating-input__field" type="tel" placeholder="@translate('Phone')" name="phone" value="{{ $billing->getPhone() ?? '' }}">
              <label for="phone">@translate('Phone')</label>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="floating-input">
              <input class="floating-input__field" type="email" placeholder="@translate('Email')" name="email" value="{{ $billing->getEmail() ?? '' }}">
              <label for="email">@translate('Email')</label>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Update')</button>
        <a href="@url('dashboard/account')" class="btn btn-outline-dark btn-mobile">@translate('Back to account')</a>
      </form>
    </div>
  </div>
</div>

@endsection