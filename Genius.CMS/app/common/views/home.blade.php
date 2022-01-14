@extends('layouts.app', [
])
@section('content')
<div class="container -pt-5">
    <div class="row">

        <div class="col-12 col-lg-6 -pb-3 -mh-70 -flex-center -reveal">
            <div>
                <h2 class="-font-secondary -fw-700">@translate('Artificial intelligence made simple')</h2>
                <p>
                    @translate('Genius is a tool for creating expert systems.')
                </p>
            </div>
        </div>
        <div class="col-12 col-lg-6 -flex-center -reveal">
            {{-- https://www.vectorstock.com/royalty-free-vector/stylized-brain-circuit-board-texture-electricity-vector-19304897 --}}
            <img lazy src="@asset('img/mind-circuit.png')" alt="" style="width: 100%;" />
        </div>
    </div>
</div>

@endsection