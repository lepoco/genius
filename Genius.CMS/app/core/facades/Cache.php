<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Stores values temporarily.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static mixed get(string $key, mixed $default) Retrieve an item from the cache by key.
 * @method static bool put(string $key, mixed $value, int $seconds) Store an item in the cache for a given number of seconds.
 * @method static bool forget(string $key) Remove an item from the cache.
 * @method static mixed remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback) Get an item from the cache, or execute the given Closure and store the result for a given time.
 * @method static mixed forever(string $key, \Closure $callback) Get an item from the cache, or execute the given Closure and store the result forever.
 * @method static bool flush() Remove all items from the cache.
 */
final class Cache extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'cache';
  }
}
