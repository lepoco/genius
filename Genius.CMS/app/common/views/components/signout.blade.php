<section class="signout">
  <div class="signout__modal">
    <div class="signout__modal__body">
      <h4 class="-font-secondary -fw-700"><span class="signout__modal--timer">00:60</span></h4>
      <strong>@translate('Session time passes')</strong>
      <br>
      <span>@translate('You have been inactive for some time, you will be logged out in a moment.')</span>
    </div>
    <div class="signout__modal__footer -mt-3">
      <a href="@currenturl" class="btn btn-dark btn-mobile -lg-mr-1">@translate('Stay on the page')</a>
      <a href="@url('signout')" class="btn btn-secondary btn-mobile">@translate('Log out')</a>
    </div>
  </div>
</section>