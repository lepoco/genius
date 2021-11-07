@extends('layouts.app', [
'title' => 'Edit'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3">@translate('Edit')</h4>
    </div>
    <div class="col-12">
      <div class="-mb-3">
        <button class="btn btn-dark btn-mobile -lg-mr-1 -btn-add-condition">@translate('Add condition')</button>
        <button class="btn btn-outline-dark btn-mobile -lg-mr-1 -btn-add-product">@translate('Add product')</button>
        <button class="btn btn-outline-dark btn-mobile -btn-add-relation">@translate('Add relation')</button>
      </div>
    </div>
    <div class="col-12 -mb-2">
      <hr>
    </div>
    <div class="col-12">
      <form id="addCondition" method="POST">
        <input type="hidden" name="action" value="AddCondition" />
        <input type="hidden" name="nonce" value="@nonce('addcondition')" />

        <h5 class="-font-secondary -fw-700 -pb-1">New condition</h5>
        
        <div class="floating-input">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')" name="system_name">
          <label for="system_name">@translate('Name')</label>
        </div>

        <div class="floating-input">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="system_description">
          <label for="system_description">@translate('Description')</label>
        </div>

        <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Save')</button>
      </form>
    </div>
  </div>
</div>

@endsection