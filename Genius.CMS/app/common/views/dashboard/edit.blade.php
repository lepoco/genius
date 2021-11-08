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
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '' }}" />

        <h5 class="-font-secondary -fw-700 -pb-1 -reveal">@translate('New product')</h5>

        <div class="-reveal">
          <p>@translate('Product is the final result of the application\'s operation. If the purpose of the system is to
            select the best garden gnomes, the product can be - Red Gnome by iGnome INC.')</p>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')" name="product_name">
          <label for="product_name">@translate('Name')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="product_description">
          <label for="product_description">@translate('Description')</label>
        </div>

        <div class="-reveal -pb-2">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Add')</button>
        </div>
      </form>
    </div>

    <div class="col-12 -mb-2">
      <hr>
    </div>

    <div class="col-12">
      <form id="addCondition" method="POST">
        <input type="hidden" name="action" value="AddCondition" />
        <input type="hidden" name="nonce" value="@nonce('addcondition')" />
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '' }}" />

        <h5 class="-font-secondary -fw-700 -pb-1 -reveal">@translate('New condition')</h5>

        <div class="-reveal">
          <p>@translate('Condition is anything that meets or contradicts the presence of the product. For example, if
            the condition is "Is the gnome green?" It will exclude all gnomes that are red.')</p>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Name')" name="condition_name">
          <label for="condition_name">@translate('Name')</label>
        </div>

        <div class="floating-input -reveal">
          <input class="floating-input__field" type="text" placeholder="@translate('Description')"
            name="condition_description">
          <label for="condition_description">@translate('Description')</label>
        </div>

        <div class="-reveal -pb-2">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Add')</button>
        </div>
      </form>
    </div>

    <div class="col-12 -mb-2">
      <hr>
    </div>

    <div class="col-12">
      <form id="addRelation" method="POST">
        <input type="hidden" name="action" value="AddRelation" />
        <input type="hidden" name="nonce" value="@nonce('addrelation')" />
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '' }}" />

        <h5 class="-font-secondary -fw-700 -pb-1 -reveal">@translate('New relation')</h5>

        <div class="-reveal">
          <p>@translate('Relation explains the connection between the condition and the product.')</p>
        </div>

        @isset($products)
        <div class="floating-input -reveal">
          <select id="product_id" data-selected="{{ $selected ?? $value ?? '' }}" class="floating-input__field"
            name="product_id" placeholder="@translate('Product')">
            <option value="" selected disabled hidden>@translate('Choose product here...')</option>

            @foreach($products as $product)
            <option value="{{ $product->getId() ?? ' '}}">{{ $product->getName() ?? ' '}}</option>
            @endforeach

          </select>
          <label for="product_id">@translate('Product')</label>
        </div>
        @endisset

        <div class="floating-input -reveal">
          <select id="relation_id" data-selected="{{ $selected ?? $value ?? '' }}" class="floating-input__field"
            name="relation_id" placeholder="@translate('Relation')">

            <option value="1">@translate('Condition belongs to the product')</option>
            <option value="1">@translate('Condition contradicts the product')</option>
            <option value="1">@translate('Condition is inert to the product')</option>

          </select>
          <label for="relation_id">@translate('Relation')</label>
        </div>

        @isset($conditions)
        <div class="floating-input -reveal">
          <select id="condition_id" data-selected="{{ $selected ?? $value ?? '' }}" class="floating-input__field"
            name="condition_id" placeholder="@translate('Condition')">
            <option value="" selected disabled hidden>@translate('Choose condition here...')</option>

            @foreach($conditions as $condition)
            <option value="{{ $condition->getId() ?? ' '}}">{{ $condition->getName() ?? ' '}}</option>
            @endforeach

          </select>
          <label for="condition_id">@translate('Condition')</label>
        </div>
        @endisset

        <div class="-reveal -pb-2">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Add')</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection