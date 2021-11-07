@extends('layouts.app', [
'title' => 'Create'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3">@translate('Create')</h4>
    </div>
    <div class="col-12">
      <form id="createNew" method="POST">
        <input type="hidden" name="action" value="CreateNew" />
        <input type="hidden" name="nonce" value="@nonce('createnew')" />

        <div class="floating-input">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')"
            name="system_name">
          <label for="system_name">@translate('Name')</label>
        </div>

        <div class="floating-input">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="system_description">
          <label for="system_description">@translate('Description')</label>
        </div>

        {{-- <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Next')</button> --}}
        <a href="@url('dashboard/edit')" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Next')</a>
        <a href="@url('dashboard')" class="btn btn-outline-dark btn-mobile">@translate('Cancel')</a>
      </form>
    </div>
  </div>
</div>

@endsection
