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
 * @method static \App\Core\Http\Response setHeader(string $key, mixed $values, bool $replace = true) Adds header.
 * @method static \App\Core\Http\Response setCookie(\Symfony\Component\HttpFoundation\Cookie $cookie) Adds cookie header.
 * @method static \App\Core\Http\Response prepare() Prepares the Response before it is sent to the client.
 * @method static \App\Core\Http\Response sendHeaders() Sends HTTP headers.
 * @method static \App\Core\Http\Response sendContent() Sends content for the current web response.
 * @method static \App\Core\Http\Response send() Sends HTTP headers and content.
 * @method static \App\Core\Http\Response setContent() Sets the response content.
 * @method static string|false getContent() Gets the current response content.
 * @method static \App\Core\Http\Response setStatusCode(int $code, string $text = null) Sets the response status code.
 * @method static int getStatusCode() Retrieves the status code for the current web response.
 * @method static \App\Core\Http\Response setCharset(int $code, string $text = null) Sets the response charset.
 * @method static ?string getCharset() Retrieves the response charset.
 * @method static \App\Core\Http\Response setPrivate() Marks the response as "private".
 * @method static \App\Core\Http\Response setImmutable(bool $immutable = true) Marks the response as "immutable".
 * @method static \App\Core\Http\Response setMaxAge(int $value) Sets the number of seconds after which the response should no longer be considered fresh.
 * @method static void setContentSafe(bool $safe = true): Marks a response as safe according to RFC8674.
 * @method static void closeOutputBuffers(int $targetLevel, bool $flush) Cleans or flushes output buffers up to target level.
 */
final class Response extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'response';
  }
}
