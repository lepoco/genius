@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Expert System')
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Expert System')</h4>
    </div>

    <div class="col-12">
      @dump($is_question_has_pattern)
      @dump($conditions ?? null)
      @dump($products ?? null)
      @dump($relations ?? null)
    </div>
  </div>
</div>

@endsection