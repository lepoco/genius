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
 * @method static array only(array $keys) Get a subset of the session data.
 * @method static bool exists(string $key) Checks if a key exists.
 * @method static bool missing(string $key) Determine if the given key is missing from the session data.
 * @method static bool has(string $key) Checks if a key is present and not null.
 * @method static mixed get(string $key, mixed $default = null) Get an item from the session.
 * @method static mixed pull(string $key, mixed $default = null) Get the value of a given key and then forget it.
 * @method static void replace(array $attributes) Replace the given session attributes entirely.
 * @method static void put(string $key, mixed $value = null) Put a key / value pair or array of key / value pairs in the session.
 * @method static void remember(string $key, \Closure $callback) Get an item from the session, or store the default value.
 * @method static void push(string $key, mixed $value) Push a value onto a session array.
 * @method static mixed|int increment(string $key, int $amount = 1) Increment the value of an item in the session.
 * @method static mixed|int decrement(string $key, int $amount = 1) Decrement the value of an item in the session.
 * @method static void flash(string $key, mixed|bool $value = true) Flash a key / value pair to the session.
 * @method static void now(string $key, mixed $value) Flash a key / value pair to the session for immediate use.
 * @method static void reflash() Reflash all of the session flash data.
 * @method static void keep(array|mixed $keys = null) Reflash a subset of the current flash data.
 * @method static bool isStarted() Determine if the session has been started.
 * @method static string getName() Get the name of the session.
 * @method static void setName(string $name) Set the name of the session
 * @method static int getId() Get the current session ID.
 * @method static void setId(string $name) Set the session ID.
 * @method static string token() Get the CSRF token value.
 * @method static void regenerateToken() Regenerate the CSRF token value.
 * @method static void passwordConfirmed() Specify that the user has confirmed their password.
 * @method static string|null previousUrl() Get the previous URL from the session.
 * @method static void setPreviousUrl(string $url) Set the "previous" URL in the session.
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
    return 'request';
  }

  /**
   * Responds to a static call to the Facade class and then tries to execute a method with the same name on the application object.
   * @param mixed|array $arguments
   * @return mixed
   */
  public static function __callStatic(string $name, $arguments)
  {
    /** @var \Illuminate\Http\Request $instance Request instance. */
    $instance = self::getProperty(static::getFacadeAccessor());

    if (false === $instance) {
      return false;
    }

    if (!$instance->hasSession()) {
      return false;
    }

    return $instance->session()->{$name}(...$arguments);
  }
}
