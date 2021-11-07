<?php

namespace App\Core\Cache;

/**
 * Cache that stores data in memory as an array.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
class Memory implements \App\Core\Schema\Cache
{
  /**
   * Stores all entries.
   */
  protected array $records = [];

  /**
   * Stores information about how long an element is to be kept in memory.
   */
  protected array $timeout = [];

  /**
   * @inheritdoc
   */
  public function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback): mixed
  {
    // TODO: REMEMBER SHOULD HAVE TIMEOUT
    // $ttl

    if ($this->has($key)) {
      return $this->get($key, null);
    }

    $return = $callback();

    $this->put($key, $return, $ttl);

    return $return;
  }

  /**
   * @inheritdoc
   */
  public function forever(string $key, \Closure $callback): mixed
  {
    if ($this->has($key)) {
      return $this->get($key, null);
    }

    $return = $callback();

    $this->put($key, $return);

    return $return;
  }

  /**
   * @inheritdoc
   */
  public function put(string $key, mixed $value): bool
  {
    return $this->memoryPut($key, $value);
  }

  /**
   * @inheritdoc
   */
  public function get(string $key, mixed $default): mixed
  {
    return $this->memoryGet($key, $default);
  }

  /**
   * @inheritdoc
   */
  public function has(string $key): bool
  {
    return $this->memoryHas($key);
  }

  /**
   * @inheritdoc
   */
  public function flush(): bool
  {
    return $this->flushMemory();
  }

  /**
   * @inheritdoc
   */
  public function forget(string $key): bool
  {
    return $this->forgetFromMemory($key);
  }

  /**
   * Clears all stored records in memory.
   */
  final protected function flushMemory(): bool
  {
    $this->records = [];

    return true;
  }

   /**
   * Deletes the selected item from memory.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   */
  final protected function forgetFromMemory(string $key): bool
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount && isset($this->records[$keyArray[0]])) {
      unset($this->records[$keyArray[0]]);

      return true;
    }

    if (2 === $keyCount && isset($this->records[$keyArray[0]][$keyArray[1]])) {
      unset($this->records[$keyArray[0]][$keyArray[1]]);

      return true;
    }

    if (3 === $keyCount && isset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]])) {
      unset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]]);

      return true;
    }

    if (4 === $keyCount && isset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]])) {
      unset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]]);

      return true;
    }

    return false;
  }

  /**
   * Checks that the record array contains a key.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   */
  final protected function memoryHas(string $key): bool
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount) {
      return isset($this->records[$keyArray[0]]);
    }

    if (2 === $keyCount) {
      return isset($this->records[$keyArray[0]][$keyArray[1]]);
    }

    if (3 === $keyCount) {
      return isset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]]);
    }

    if (4 === $keyCount) {
      return isset($this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]]);
    }

    return false;
  }

  /**
   * Checks whether the record array contains a key. If so, it returns the saved value.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param mixed $default Any default value returned if the key does not exist.
   */
  final protected function memoryGet(string $key, mixed $default): mixed
  {
    if (!$this->memoryHas($key)) {
      return $default;
    }

    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount) {
      return $this->records[$keyArray[0]];
    }

    if (2 === $keyCount) {
      return $this->records[$keyArray[0]][$keyArray[1]];
    }

    if (3 === $keyCount) {
      return $this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]];
    }

    if (4 === $keyCount) {
      return $this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]];
    }

    return $default;
  }

  /**
   * Writes the value under the saved key to the records array.
   *
   * @param string $key Key in abc.def.ghi format, separated by dot.
   * @param mixed $value Value to be written under the given key.
   * @param \DateTimeInterface|\DateInterval|int $ttl Time after which the value is to be cleared.
   */
  final protected function memoryPut(string $key, mixed $value, \DateTimeInterface|\DateInterval|int $ttl = -1): bool
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if ($ttl > -1) {
      $this->timeout[] = [
        'key' => $key,
        'ttl' => $ttl
      ];
    }

    if (1 === $keyCount) {
      $this->records[$keyArray[0]] = $value;

      return true;
    }

    if (2 === $keyCount) {
      $this->records[$keyArray[0]][$keyArray[1]] = $value;

      return true;
    }

    if (3 === $keyCount) {
      $this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]] = $value;

      return true;
    }

    if (4 === $keyCount) {
      $this->records[$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]] = $value;

      return true;
    }

    return false;
  }
}
