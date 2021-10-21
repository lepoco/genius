@include('components.header')
@include('components.navigation')

@yield('content')

@include('components.expanded-footer')
{{-- @include('components.notifications') --}}
@include('components.transition')
@include('components.footer')
