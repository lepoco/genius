@extends('layouts.box', [
'title' => \App\Core\Facades\Translate::string('Installer'),
'background' => $base_url . 'img/pexels-photo-8386434.jpeg'
])

@section('content')
<h2 class="-font-secondary -fw-700 -pb-3">@translate('Installer')</h2>
<form id="install">
    <input type="hidden" name="action" value="Install" />
    <div class="row">
        <div class="col-12 mb-3">
            <strong>@translate('Database')</strong>
        </div>

        <div class="col-12 col-lg-6">
            <div class="floating-input">
                <input class="floating-input__field" type="text" name="user" placeholder="@translate('User')">
                <label for="user">@translate('User')</label>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="floating-input">
                <input class="floating-input__field" type="text" name="password" placeholder="@translate('Password')">
                <label for="password">@translate('Password')</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="floating-input">
                <input class="floating-input__field" type="text" name="host" placeholder="@translate('Host')"
                    value="127.0.0.1">
                <label for="host">@translate('Host')</label>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="floating-input">
                <input class="floating-input__field" type="text" name="database" placeholder="@translate('Database')"
                    value="genius">
                <label for="database">@translate('Database')</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3 mt-3">
            <strong>@translate('User')</strong>
        </div>

        <div class="col-12">
            <div class="floating-input">
                <input class="floating-input__field" type="email" name="admin_email" autocomplete="email"
                    placeholder="@translate('Email')">
                <label for="admin_email">@translate('Email')</label>
            </div>
            <div class="floating-input">
                <input class="floating-input__field" type="password" name="admin_password" autocomplete="new-password"
                    placeholder="@translate('Password')">
                <label for="admin_password">@translate('Password')</label>
            </div>
        </div>
    </div>

    <input type="hidden" name="submit" id="submit" value="123" />

    <button type="submit" class="btn btn-dark btn-mobile mt-3">@translate('Begin installation')</button>
</form>
@endsection
