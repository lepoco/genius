@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Remove the expert system')
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Remove the expert system')</h4>
    </div>

    <div class="col-12 -mb-3 -reveal">
      <strong>@translate('Deleting the database records is irreversible, make sure you delete the correct system.')</strong>
    </div>
    <div class="col-12">
      <form id="deleteSystem" method="POST">
        <input type="hidden" name="action" value="DeleteSystem" />
        <input type="hidden" name="nonce" value="@nonce('deletesystem')" />
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '0' }}" />

        <div class="-reveal">
          <span>@translate('System name'):</span>
          <h5 class="-font-secondary -fw-700 -pb-3">{{ $system->getName() }}</h5>
        </div>

        <div class="-reveal">
          <span>@translate('Creation date'):</span>
          <h5 class="-font-secondary -fw-700 -pb-3">{{ $system->getCreatedAt() }}</h5>
        </div>

        <div class="form-check -reveal -mb-2">
          <input type="checkbox" class="form-check-input" id="accept_delete" name="accept_delete" name="subscribe" value="accept_delete">
          <label for="accept_delete">@translate('Aware of the irreversibility of the changes, I want to delete the saved data.')</label>
        </div>

        <div class="-reveal -pb-1">
          <button type="submit" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Delete')</button>
          <a href="{{ $edit_url ?? '#' }}" class="btn btn-outline-dark btn-mobile">@translate('Cancel')</a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection