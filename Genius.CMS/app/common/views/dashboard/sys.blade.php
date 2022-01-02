@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Expert System')
])
@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12 -mb-3">
      <h4 class="-font-secondary -fw-700 -reveal">{{ $system->getName() ?? '__UNKNOWN__' }}</h4>
      <p class="-reveal">{{ $system->getDescription() ?? '' }}</p>
    </div>

    <div class="col-12 -reveal">
      @if($is_question_has_pattern)
      <h4 class="-font-secondary -fw-700 -pb-3"><span data-question="{{ $question ?? '' }}"
          class="--current_condition -pattern">{{ $question ?? '' }}</span></h4>
      @else
      <p><strong>{{ $question ?? '' }}</strong></p>
      <h4 class="-font-secondary -fw-700 -pb-3"><span class="--current_condition">{{ $currentCondition ?? '#' }}</span></h4>
      @endif
    </div>

    <div class="col-12 -reveal">
      <form id="answer" method="POST">
        <input type="hidden" name="action" value="Answer" />
        <input type="hidden" name="nonce" value="@nonce('answer')" />
        <input type="hidden" name="system_id" value="{{ $system->getId() ?? '0' }}" />
        <input type="hidden" name="system_uuid" value="{{ $system->getUUID() ?? '' }}" />
        <input type="hidden" name="system_session" value="@json( $systemSession, JSON_PRETTY_PRINT )" />

        <button type="submit" id="submit_yes" name="submit_yes" value="yes" class="-yes btn btn-dark btn-mobile -lg-mr-1">@translate('Yes')</button>
        <button type="submit" id="submit_no" name="submit_no" value="no" class="-no btn btn-outline-dark btn-mobile -lg-mr-1">@translate('No')</button>
        <button type="submit" id="submit_dontknow" name="submit_dontknow" value="dontknow" class="-dontknow btn btn-outline-dark btn-mobile">@translate('I do not know')</button>
      </form>
    </div>

    {{-- <div class="col-12 -reveal">
      <hr>
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('DEBUG')</h4>
      @dump($conditions ?? null)
      @dump($products ?? null)
      @dump($relations ?? null)
    </div> --}}
  </div>
</div>

@endsection