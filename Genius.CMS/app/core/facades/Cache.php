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
 * @method static mixed get(string|array $key) Retrieve an item from the cache by key.
 * @method static bool put(string $key, mixed $value, int $seconds) Store an item in the cache for a given number of seconds.
 * @method static bool add(string $key, mixed $value, int $seconds) Store an item in the cache if the key doesn't exist.
 * @method static bool forever(string $key, mixed $value) Store an item in the cache indefinitely.
 * @method static bool forget(string $key) Remove an item from the cache.
 * @method static mixed remember(string $key, \DateTimeInterface|\DateInterval|int|null $ttl, \Closure $callback) Get an item from the cache, or execute the given Closure and store the result.
 * @method static mixed sear(string $key, \Closure $callback) Get an item from the cache, or execute the given Closure and store the result forever.
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
