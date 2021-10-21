@extends('layouts.app', [
])
@section('content')
<div class="container -pt-5">
    <div class="row">

        <div class="col-12 col-lg-6 -pb-3 -mh-70 -flex-center">
            <div>
                <h2 class="-font-secondary -fw-700">@translate('Artificial intelligence made simple')</h2>
                <p>
                    @translate('Genius is a tool for creating expert systems.')
                    <br>
                    <a href="@url('register')">@translate('Open your free account now')</a>
                </p>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            @include('components.phone', ['notch' => true, 'image' => $base_url . 'img/home-phone-screen.png?v=' .
            $version])
        </div>
    </div>
</div>

@endsection
