<section class="expanded-footer">
    <div class="container py-4">
        <div class="row">
            <div class="col-12 col-lg-4 {{ !$is_logged ? '-reveal' : '' }}">
                <h4 class="-mb-4">Genius</h4>
            </div>

            <div class="col-12"></div>

            <div class="expanded-footer__list col-12 {{ !$is_logged ? '-reveal' : '' }}">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        © {{ date('Y') }} dev.lepo.co
                    </li>
                    <li class="list-inline-item">
                        <a href="@url('terms')">@translate('Website Terms')</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="@url('legal')">@translate('Legal Agreements')</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="@url('privacy')">@translate('Privacy Policy')</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://github.com/lepoco/Genius" target="_blank" rel="noopener">@translate('Source Code')</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="@url('licenses')">@translate('Licenses')</a>
                    </li>
                </ul>
            </div>

            <div class="expanded-footer__bottom col-12 {{ !$is_logged ? '-reveal' : '' }}">
                If you would like to find out more about which genius entity you receive services from, or if you have any other questions, please reach out to us via the help@genius.lepo.co email.
            </div>
        </div>
    </div>
</section>
