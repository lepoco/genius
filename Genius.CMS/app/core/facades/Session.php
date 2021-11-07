<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Stores information about from the current session.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static array all() Get all of the session data.
 * @method static bool has(string $key) Checks if a key is present and not null.
 * @method static mixed get(string $key, mixed $default = null) Get an item from the session.
 * @method static void put(string $key, mixed $value = null) Put a key / value pair or array of key / value pairs in the session.
 * @method static void remember(string $key, \Closure $callback) Get an item from the session, or store the default value.
 * @method static void push(string $key, mixed $value) Push a value onto a session array.
 * @method static bool isStarted() Determine if the session has been started.
 * @method static int getId() Get the current session ID.
 * @method static void setId(string $name) Set the session ID.
 * @method static string token() Get the CSRF token value.
 * @method static void regenerateToken() Regenerate the CSRF token value.
 */
final class Session extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'session';
  }
}
