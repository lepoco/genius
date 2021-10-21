<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Stores information about the current http request.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static string method() Get the request method.
 * @method static string root() Get the root URL for the application.
 * @method static string url() Get the URL (no query string) for the request.
 * @method static string fullUrl() Get the full URL for the request.
 * @method static string path() Get the current path info for the request.
 * @method static string|null segment(int $index, string|null $default = null) Get a segment from the URI (1 based index).
 * @method static string segments() Get all of the segments for the request path.
 * @method static bool ajax() Determine if the request is the result of an AJAX call.
 * @method static bool secure() Determine if the request is over HTTPS.
 * @method static string|null ip() Get the client IP address.
 * @method static string|null userAgent() Get the client user agent.
 * @method static mixed get(string $key, mixed $default = null) This method belongs to Symfony HttpFoundation and is not usually needed when using Laravel. Instead, you may use the "input" method.
 * @method static array toArray() Get all of the input and files for the request.
 * @method static bool hasFile(string $key)
 * @method static \Illuminate\Http\UploadedFile file(string $key)
 */
final class Request extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'request';
  }
}
