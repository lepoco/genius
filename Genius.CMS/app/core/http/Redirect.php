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
  /**
   * Sets the header location and resets the response content. Then exits the application.
   */
  public static function to(string $path, bool $internal = true, array $query = []): void
  {
    if ($internal) {
      $path = self::url($path);
    }

    \App\Core\Facades\Response::setHeader('Location', $path . self::buildQuery($query));
    \App\Core\Facades\Response::setStatusCode(Status::TEMPORARY_REDIRECT);

    App::close();
  }

  /**
   * Creates a new internal url from the previously saved base URL and adds an optional urlencode-encoded query.
   */
  public static function url(string $path = '', array $query = []): string
  {
    return rtrim(Option::remember('base_url', fn () => Request::root()), '/') . '/' . $path . self::buildQuery($query);
  }

  private static function buildQuery(array $query = []): string
  {
    $queryString = '';
    $c = 0;

    foreach ($query as $key => $value) {
      $queryString .= ($c++ > 0 ? '&' : '?') . urlencode($key) . '=' . urlencode($value);
    }

    return $queryString;
  }
}
