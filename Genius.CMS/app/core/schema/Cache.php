<?php

namespace App\Core\Schema;

/**
 * Base interface for Cache.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Cache
{
  /**
   * Get an item from the cache, or execute the given Closure and store the result for a given time.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param \DateTimeInterface|\DateInterval|int $ttl Time after which the value is to be cleared.
   * @param \Closure $callback Function that will be called if the entry does not exist.
   */
  public function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback): mixed;

  /**
   * Get an item from the cache, or execute the given Closure and store the result forever.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param \Closure $callback Function that will be called if the entry does not exist.
   */
  public function forever(string $key, \Closure $callback): mixed;

  /**
   * Store an item in the cache for a given number of seconds.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param mixed $value Value to be written under the given key.
   */
  public function put(string $key, mixed $value): bool;

  /**
   * Retrieve an item from the cache by key.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param mixed $default Any default value returned if the key does not exist.
   */
  public function get(string $key, mixed $default): mixed;

  /**
   * Check if the item exists in the cache.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   */
  public function has(string $key): bool;

  /**
   *  Remove an item from the cache.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   */
  public function forget(string $key): bool;

  /**
   * Remove all items from the cache.
   */
  public function flush(): bool;
}
