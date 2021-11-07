<section class="navbar navbar-expand-lg navbar-light {{ $navbarClass ?? '' }}">
  <div class="container">
    <a class="navbar-brand" href="@url">
      <p>Genius</p>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@translate('Toggle navigation')">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="@url">@translate('Home')</a>
        </li>
      </ul>
    </div>
    <div class="d-flex -lg-mr-2">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link{{ $pagenow === 'panel.main' ? ' active' : '' }}"
            href="@url('panel')">@translate('Dashboard')</a>
        </li>
        <li class="nav-item">
          <a class="nav-link{{ $pagenow === 'panel.statistics' ? ' active' : '' }}"
            href="@url('panel/statistics')">@translate('Statistics')</a>
        </li>
        <li class="nav-item">
          <a class="nav-link{{ $pagenow === 'panel.tools' ? ' active' : '' }}"
            href="@url('panel/tools')">@translate('Tools')</a>
        </li>
        <li class="nav-item">
          <a class="nav-link{{ $pagenow === 'panel.users' ? ' active' : '' }}"
            href="@url('panel/users')">@translate('Users')</a>
        </li>
        <li class="nav-item">
          <a class="nav-link{{ $pagenow === 'panel.settings' ? ' active' : '' }}"
            href="@url('panel/settings')">@translate('Settings')</a>
        </li>
      </ul>
    </div>
    <div class="d-flex">
      @iflogged
      <a href="@url('signout')" class="btn btn-dark" type="submit">@translate('Sign Out')</a>
      @else
      <a href="@url('signin')" class="btn btn-secondary -lg-mr-1" type="submit">@translate('Sign in')</a>
      <a href="@url('register')" class="btn btn-dark" type="submit">@translate('Register for free')</a>
      @endif
    </div>
  </div>
</section>
