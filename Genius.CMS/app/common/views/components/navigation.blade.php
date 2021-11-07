<section class="navbar navbar-expand-lg navbar-light {{ $navbarClass ?? '' }}">
  <div class="container {{ !$is_logged ? '-reveal' : '' }}">
    <a class="navbar-brand" href="@url">
      {{-- <img loading="lazy" src="@media('favicon.svg')" alt="Genius"/> --}}
      <p>Genius</p>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@translate('Toggle navigation')">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      </ul>
      <div class="d-flex -lg-mr-2">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          @iflogged
          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.main' ? ' active' : '' }}"
              href="@url('dashboard')">@translate('Dashboard')</a>
          </li>

          @ifpermission('create')
          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.add' ? ' active' : '' }}"
              href="@url('dashboard/add')">@translate('Add new')</a>
          </li>
          @endif

          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.account' ? ' active' : '' }}"
              href="@url('dashboard/account')">@translate('Account')</a>
          </li>

          @ifpermission('all')
          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.statistics' ? ' active' : '' }}"
              href="@url('dashboard/statistics')">@translate('Statistics')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.users' ? ' active' : '' }}"
              href="@url('dashboard/users')">@translate('Users')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link{{ $pagenow === 'dashboard.settings' ? ' active' : '' }}"
              href="@url('dashboard/settings')">@translate('Settings')</a>
          </li>
          @endif
          
          @endif

        </ul>
      </div>
      <div class="d-flex">

        @iflogged
        <a href="@url('signout')" class="btn btn-dark btn-mobile" type="submit">@translate('Sign Out')</a>
        @else
        <a href="@url('signin')" class="btn btn-secondary btn-mobile -lg-mr-1" type="submit">@translate('Sign in')</a>
        <a href="@url('register')" class="btn btn-dark btn-mobile" type="submit">@translate('Register for free')</a>
        @endif

      </div>
    </div>
  </div>
</section>
