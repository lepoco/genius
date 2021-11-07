</div>
@include('components.toast-container')
@include('components.offline')

@iflogged
  @include('components.signout')
@endif
</body>

</html>