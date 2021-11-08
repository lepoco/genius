@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Edit')
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Edit')</h4>
    </div>
    <div class="col-12">
      <div class="-mb-3 -reveal">
        <button class="btn btn-dark btn-mobile -lg-mr-1 -btn-add-product">@translate('Add product')</button>
        <button class="btn btn-outline-dark btn-mobile -lg-mr-1 -btn-add-condition">@translate('Add condition')</button>
        <button class="btn btn-outline-dark btn-mobile -btn-add-relation -lg-mr-1">@translate('Add relation')</button>
        <a href="{{ $delete_url ?? '#' }}">@translate('Remove the expert system')</a>
      </div>
    </div>
    <div class="col-12 -mb-2">
      <hr>
    </div>
    <div class="col-12">
      <form id="addProduct" method="POST">
        <input type="hidden" name="action" value="AddProduct" />
        <input type="hidden" name="nonce" value="@nonce('addproduct')" />

        <h5 class="-font-secondary -fw-700 -pb-1 -reveal">@translate('New product')</h5>

        <div class="-reveal">
          <p>@translate('Product is the final result of the application\'s operation. If the purpose of the system is to select the best garden gnomes, the product can be - Red Gnome by iGnome INC.')</p>
        </div>
        
        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')" name="system_name">
          <label for="system_name">@translate('Name')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="system_description">
          <label for="system_description">@translate('Description')</label>
        </div>

        <div class="-reveal -pb-2">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Add')</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection