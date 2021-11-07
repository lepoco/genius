@php
/**
 * Copyright (c) 2021 by RaKesh Mandal (https://codepen.io/rKalways/pen/VwwQKpV)
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

$font = 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans",
sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';

$background = '#f2f3f8';

@endphp
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office" style="height: 100%;background: {{$background}}"
  bgcolor="{{ $background }}">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!--[if gte mso 9]><xml>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
  <o:PixelsPerInch>96</o:PixelsPerInch>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
  <meta name="x-apple-disable-message-reformatting" />
  <style type="text/css">
    html {
      height: 100%;
      background-color: {{ $background }};
    }
    a {
      color: #6c6c6c
    }
    a:hover {
      text-decoration: none !important;
    }
    a.button {
      transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    a.button:hover {
      text-decoration: none;
      color: rgb(25, 28, 31);
      background: #fff;
    }
  </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" leftmargin="0"
  style="width: 100% !important; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; -moz-text-size-adjust: none; text-size-adjust: none; color: #000000; font-family: {{ $font }}; font-weight: normal; text-align: left; font-size: 15px; line-height: 18px; background: {{ $background }}; margin: 0; padding: 0;"
  bgcolor="{{ $background }}">
  <!--100% body table-->
  <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="{{ $background }}"
    style="font-family: {{ $font }};">
    <tr>
      <td>
        <table style="background-color: {{ $background }}; max-width:670px;  margin:0 auto;" width="100%" border="0"
          align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td style="height:80px;">&nbsp;</td>
          </tr>
          <tr>
            <td style="text-align:center;">
              <a href="{{ $base_url ?? '#' }}" title="{{ $site_name ?? 'Website' }}" target="_blank">
                <img width="{{ $logo_width ?? '160'}}" src="{{ $logo ?? '#' }}"
                  title="{{ $site_name ?? 'Website' }} logo" alt="{{ $site_name ?? 'Website' }} logo">
              </a>
            </td>
          </tr>
          <tr>
            <td style="height:40px;">&nbsp;</td>
          </tr>
          <tr>
            <td>
              <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                <tr>
                  <td style="height:40px;">&nbsp;</td>
                </tr>
                <tr>
                  <td style="padding:0 35px;">

                    @isset($header)
                    <h1
                      style="color:#1e1e2d; font-weight:500; margin:0;line-height: 36px;font-size:32px;font-family:{{ $font }};">
                      {{ $header }}
                    </h1>
                    <span
                      style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                    @endisset

                    @isset($message)
                    <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                      {{ $message }}
                    </p>
                    @endisset
                  </td>
                </tr>

                @isset($action_url)
                <tr>
                  <td style="height:40px;">&nbsp;</td>
                </tr>
                <tr>
                  <td>
                    <a class="button" href="{{ $action_url ?? '#'}}"
                      style="display: inline-block;min-width:120px;color: #fff;background-color: #191c1f;border-color: #191c1f;padding: 14px;line-height: 15px;font-size: 13px;align-items: center;border-radius: 10px;font-size: 17px;justify-content: center;font-weight: 500;text-decoration: none;">
                      {{ $action_title ?? 'Action'}}
                    </a>
                  </td>
                </tr>

                @endisset

                @yield('content')

                <tr>
                  <td style="height:40px;">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height:20px;">&nbsp;</td>
          </tr>
          <tr>
            <td>
              <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="max-width:670px;">
                <tr>
                  <td style="padding:0;">
                    <p
                      style="font-size:10px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">
                      {{ $site_name ?? '' }} respects your privacy. To learn more please read our <a
                        href="{{ $privacy_url ?? '#' }}" style="color: #6c6c6c">Privacy Statement</a>.</p>

                    @isset($support_url)
                    <p
                      style="font-size:10px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">
                      Account questions? <a href="{{ $support_url ?? '#' }}" style="color: #6c6c6c">Visit Customer Support</a>.</p>
                    @endisset

                    @yield('footer')

                    <br>
                    <p
                      style="font-size:10px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">
                      &copy; {{ $legal ?? '' }}</p>
                  </td>
                </tr>
                <tr>
                  <td style="height:40px;">&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="height:80px;">&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <!--/100% body table-->
</body>

</html>