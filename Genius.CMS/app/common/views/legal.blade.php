@extends('layouts.app', [
  'title' => \App\Core\Facades\Translate::string('Legal Agreements'),
])
@section('content')
<div class="container -pt-5">
  <div class="row">

    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Legal Agreements')</h4>
    </div>

    <div class="col-12 -mb-5">
      <p class="-reveal">
        Genius is not a real website. It's just a college project. Don't use it.
      </p>
    </div>
  </div>
</div>

@endsection