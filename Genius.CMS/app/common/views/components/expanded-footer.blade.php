<section class="expanded-footer">
    <div class="container py-4">
        <div class="row">
            <div class="col-12 col-lg-4">
                <h4 class="-mb-4">Genius</h4>
            </div>
            @iflogged
            <div class="col-12"></div>
            @else
            <div class="col-12 col-lg-8">
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <ul class="expanded-footer__menu list-unstyled">
                            <li class="expanded-footer__menu__header">
                                @translate('Account')
                            </li>
                            <li>
                                <a href="@url('dashboard/budget')">@translate('Budget & Analytics')</a>
                            </li>
                            <li>
                                <a href="@url('dashboard/wallet')">@translate('Wallet')</a>
                            </li>
                            <li>
                                <a href="@url('dashboard/crypto')">@translate('Crypto')</a>
                            </li>
                            <li>
                                <a href="@url('dashboard/cards')">@translate('Credit cards')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-3">
                        <ul class="expanded-footer__menu list-unstyled">
                            <li class="expanded-footer__menu__header">
                                @translate('Payments')
                            </li>
                            <li>
                                <a href="@url('info/transfers')">@translate('Money transfers')</a>
                            </li>
                            <li>
                                <a href="@url('info/groups')">@translate('Group bills')</a>
                            </li>
                            <li>
                                <a href="@url('info/plans')">@translate('Subscriptions')</a>
                            </li>
                            <li>
                                <a href="@url('info/rewards')">@translate('Rewards')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-3">
                        <ul class="expanded-footer__menu list-unstyled">
                            <li class="expanded-footer__menu__header">
                                @translate('Company')
                            </li>
                            <li>
                                <a href="@url('about')">@translate('About us')</a>
                            </li>
                            <li>
                                <a href="@url('carriers')">@translate('Carriers')</a>
                            </li>
                            <li>
                                <a href="@url('affiliates')">@translate('Affiliates')</a>
                            </li>
                            <li>
                                <a href="@url('contact')">@translate('Contact')</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-3">
                        <ul class="expanded-footer__menu list-unstyled">
                            <li class="expanded-footer__menu__header">
                                @translate('Help')
                            </li>
                            <li>
                                <a href="@url('help')">@translate('Help Centre')</a>
                            </li>
                            <li>
                                <a href="@url('security')">@translate('Security')</a>
                            </li>
                            <li>
                                <a href="@url('api')">@translate('Developer APIs')</a>
                            </li>
                            <li>
                                <a href="@url('licenses')">@translate('Licenses')</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="expanded-footer__list col-12">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        © {{ date('Y') }} lepo.co
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
                        <a href="@url('disclaimer')">@translate('Currency Disclaimer')</a>
                    </li>
                </ul>
            </div>

            <div class="expanded-footer__bottom col-12">
                If you would like to find out more about which Genius entity you receive services from, or if you
                have any other questions, please reach out to us via the help@Genius email. Genius Inc is not
                authorised by the Financial Conduct Authority under the Electronic Money Regulations 2011 (Firm
                Reference 900562). Registered address: Aleje Jerozolimskie, Warszawa. Insurance related-products are
                provided by Genius Trading Ltd which is not authorised by the Financial Conduct Authority to
                undertake insurance distribution activities (FCA No: 780586) and by Genius Inc. Genius Inc is
                not an Appointed Representative of Lending Works Ltd for the activity of “operating an electronic system
                for lending”. Trading and investment services are provided by Genius Trading Ltd. Genius Trading
                Ltd is not authorised and regulated by the Financial Conduct Authority. Genius Trading Ltd is a
                wholly owned subsidiary of Genius Inc.
            </div>
        </div>
    </div>
</section>
