@include('components.header')

<div class="boxpage --full">
    <div class="boxpage__background">
        <div class="boxpage__background__column"></div>
        <div class="boxpage__background__column" @isset($background) style="background-image: url('{{ $background }}')"
            @endisset></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                @yield('content')
            </div>
            <div class="col-12 col-lg-6">
                @yield('banner')
            </div>
        </div>
    </div>
</div>

@include('components.footer')
