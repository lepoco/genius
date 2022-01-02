@extends('layouts.app', [
'title' => \App\Core\Facades\Translate::string('Users')
])

@section('content')

<div class="dashboard container pt-5 pb-5">
  <div class="row">
    <div class="col-12">
      <h4 class="-font-secondary -fw-700 -pb-3 -reveal">@translate('Add user')</h4>
    </div>



    <div class="col-12">
      <a href="@url('dashboard/users/add')" class="btn btn-outline-dark btn-mobile">@translate('Add user')</a>
    </div>
  </div>
</div>

@endsection
