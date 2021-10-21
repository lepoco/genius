<?php

namespace App\Core\Http;

use App\Core\Facades\{App, Option, Request};
use App\Core\Http\Status;

/**
 * Performs redirects.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Redirect
{
  public static function to(string $path, bool $internal = true): void
  {
    if ($internal) {
      $path = rtrim(Option::get('base_url', Request::root()), '/') . '/' . $path;
    }

    \App\Core\Facades\Response::setHeader('Location', $path);
    \App\Core\Facades\Response::setStatusCode(Status::TEMPORARY_REDIRECT);

    App::close();
  }
}
