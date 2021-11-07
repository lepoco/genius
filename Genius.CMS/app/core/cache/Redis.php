<?php

namespace App\Core\Cache;

use App\Core\Cache\Memory;

/**
 * Responsible for storing information in the Redis database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Redis extends Memory implements \App\Core\Schema\Cache
{
  public function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback): mixed
  {
    // TODO: REMEMBER SHOULD HAVE TIMEOUT
    // $ttl

    if ($this->has($key)) {
      return $this->get($key, null);
    }

    $return = $callback();

    $this->put($key, $return);

    return $return;
  }

  public function forever(string $key, \Closure $callback): mixed
  {
    if ($this->has($key)) {
      return $this->get($key, null);
    }

    $return = $callback();

    $this->put($key, $return);

    return $return;
  }

  public function put(string $key, mixed $value): bool
  {
    $this->memoryPut($key, $value);

    // TODO: Implement redis
    // put also to redis

    return false;
  }

  public function get(string $key, mixed $default): mixed
  {
    if ($this->memoryHas($key)) {
      return $this->memoryGet($key, $default);
    }

    // TODO: Implement redis
    // if redis has
    // return from redis and put to memory

    return false;
  }

  public function has(string $key): bool
  {
    if ($this->memoryHas($key)) {
      return true;
    }

    // TODO: Implement redis
    // if redis has
    // return true and put to memoryd

    return false;
  }

  public function flush(): bool
  {
    // TODO: Implement redis
    // Flush also redis
    return $this->flushMemory();
  }

  public function forget(string $key): bool
  {
    // TODO: Implement redis
    // Forget also from redis
    return $this->forgetFromMemory($key);
  }
}
