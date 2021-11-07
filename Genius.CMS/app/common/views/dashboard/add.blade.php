@extends('layouts.app', [
'title' => 'Add'
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Add')</h4>
    </div>
    <div class="col-12">
      <form id="addSystem" method="POST">
        <input type="hidden" name="action" value="AddSystem" />
        <input type="hidden" name="nonce" value="@nonce('addsystem')" />

        <h5 class="-font-secondary -fw-700 -pb-1 -reveal">@translate('New expert system')</h5>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')" name="system_name">
          <label for="system_name">@translate('Name')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="system_description">
          <label for="system_description">@translate('Description')</label>
        </div>

        <div class="floating-input -reveal">
          <select id="system_type" data-selected="{{ $selected ?? $value ?? '' }}" class="floating-input__field" name="system_type"
            placeholder="@translate('Type')">
            <option value="relational">@translate('Relation based')</option>
            <option disabled value="fuzzy">@translate('Fuzzy set method (weight)')</option>
          </select>
          <label for="system_type">@translate('Type')</label>
        </div>

        <div class="-mb-2">
          <hr>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Primary question')"
            name="system_question" value="@translate('Does your gnome have {condition}?')">
          <label for="system_question">@translate('Primary question')</label>
        </div>
        
        <div class="-mb-3 -reveal">
          <i>@translate('Main question is displayed during system running. Presents the name of the condition inside the bracket {condition}.')</i>
          <br>
          <i>@translate('If the question has no bracket pattern, condition will be displayed below.')</i>
        </div>

        <div class="-reveal -pb-1">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Create')</button>
          <a href="@url('dashboard')" class="btn btn-outline-dark btn-mobile">@translate('Cancel')</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection