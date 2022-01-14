<?php

namespace App\Core\Cache;

use App\Core\Cache\Memory;
use App\Core\Facades\App;
use App\Core\Facades\Logs;
use App\Core\Facades\Option;

/**
 * Responsible for storing information in the Redis database.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Redis extends Memory implements \App\Core\Schema\Cache
{
  private const DATABASE_ID = 0;

  private const MAX_RETRIES = 5;

  private int $queries = 0;

  private bool $redisLoaded = false;

  private bool $redisConnected = false;

  private array $configuration = [];

  /**
   * @var \Redis $connection A Redis instance.
   * We do not define the class in advance, because we have no guarantee that the Redis library will be installed on the target server.
   */
  private $connection = null;

  /**
   * Initializes memory cache and connects to Redis.
   */
  public function __construct(bool $isInstalled)
  {
    if ($isInstalled && extension_loaded('redis')) {
      $this->redisLoaded = true;

      if (true === Option::get('redis_enable', false, false)) {
        $this->initializeRedis();
      }
    }
  }

  /**
   * Get an item from the cache, or execute the given Closure and store the result for a given time.
   */
  public function remember(string $key, \DateTimeInterface|\DateInterval|int $ttl, \Closure $callback): mixed
  {
    if (!$this->redisConnected) {
      if ($this->has($key)) {
        return $this->get($key, null);
      }

      $return = $callback();

      $this->put($key, $return);

      return $return;
    }

    $this->queries += 2;

    if ($this->connection->exists($key)) {
      return $this->connection->get($key);
    }

    $callbackResult = $callback();

    $this->connection->set($key, $callbackResult);
    $this->connection->expire($key, $this->convertTtl($ttl));

    return $callbackResult;
  }

  /**
   * Get an item from the cache, or execute the given Closure and store the result forever.
   */
  public function forever(string $key, \Closure $callback): mixed
  {
    if (!$this->redisConnected) {
      if ($this->has($key)) {
        return $this->get($key, null);
      }

      $callbackResult = $callback();

      $this->put($key, $callbackResult);

      return $callbackResult;
    }

    $this->queries += 2;

    if ($this->connection->exists($key)) {
      return $this->connection->get($key);
    }

    $callbackResult = $callback();

    $this->connection->set($key, $callbackResult);

    return $callbackResult;
  }

  /**
   * Store an item in the cache for a given number of seconds.
   */
  public function put(string $key, mixed $value, int $seconds = 0): bool
  {
    if (!$this->redisConnected) {
      return $this->memoryPut($key, $value);
    }

    $this->queries += 1;

    $this->connection->set($key, $value);

    if ($seconds > 0) {
      $this->queries += 1;

      $this->connection->expire($key, $seconds);
    }

    return true;
  }

  /**
   * Retrieve an item from the cache by key.
   */
  public function get(string $key, mixed $default): mixed
  {
    if (!$this->redisConnected) {
      if ($this->memoryHas($key)) {
        return $this->memoryGet($key, $default);
      }

      return $default;
    }

    if (!$this->connection->exists($key)) {
      return $default;
    }

    $this->queries += 2;

    return $this->connection->get($key);
  }

  /**
   * Indicates whether a given key is saved in the cache.
   */
  public function has(string $key): bool
  {
    if (!$this->redisConnected) {
      return $this->memoryHas($key);
    }

    $this->queries += 1;

    return $this->connection->exists($key);
  }

  /**
   * Remove an item from the cache.
   */
  public function forget(string $key): bool
  {
    if ($this->redisConnected) {
      $this->queries += 1;

      $this->connection->del($key);
    }

    return $this->forgetFromMemory($key);
  }

  /**
   * Remove all items from the cache.
   */
  public function flush(): bool
  {
    if ($this->redisConnected) {
      $this->queries += 1;

      $this->connection->flushDb();
    }

    return $this->flushMemory();
  }

  /**
   * Save and close connection.
   */
  public function close(): bool
  {
    if (!$this->redisConnected) {
      return false;
    }

    $this->connection->save();

    $this->redisConnected = false;

    return $this->connection->close();
  }

  /**
   * Gets a value inidicating whether the app is capable of connecting to Redis.
   */
  public function isLoaded(): bool
  {
    return $this->redisLoaded;
  }

  /**
   * Gets a value indicating whether the app is connected to the Redis server.
   */
  public function isConnected(): bool
  {
    return $this->redisConnected;
  }

  /**
   * Gets number of queries to the Redis server in this request.
   */
  public function queries(): int
  {
    return $this->queries;
  }

  /**
   * Returns the number of items in the cache.
   */
  public function count(): int
  {
    if (!$this->redisConnected) {
      $allRecords = count($this->records, COUNT_RECURSIVE);
      $parentRecords = count($this->records);

      return $allRecords - $parentRecords;
    }

    return $this->connection->dbSize();
  }

  private function getConfig(): void
  {
    $this->configuration = [
      'host' => Option::get('redis_host', '127.0.0.1', false),
      'port' => (int)Option::get('redis_port', 6379, false),
      'timeout' => (float)Option::get('redis_timeout', 1.0, false),
      'prefix' => Option::get('redis_prefix', 'app', false),
      'username' => Option::get('redis_username', '', false),
      'password' => Option::get('redis_password', '', false),
    ];

    $this->configuration['prefix'] .= ':';
  }

  private function tryAuth(): void
  {
    $authorizationArray = [];

    if (!empty($this->configuration['username'])) {
      $authorizationArray['user'] = $this->configuration['username'];
    }

    if (!empty($this->configuration['password'])) {
      $authorizationArray['pass'] = $this->configuration['password'];
    }

    if (!empty($authorizationArray) && $this) {
      $this->connection->auth($authorizationArray);
    }
  }

  private function initializeRedis(): void
  {
    $this->getConfig();

    $this->connection = new \Redis();

    $this->tryAuth();

    try {
      if (substr($this->configuration['host'], 0, 1) === "/") {
        // Connect by Unix socket
        $this->connection->connect($this->configuration['host']);
      } else {
        // Connect by ip
        $this->connection->connect(
          $this->configuration['host'],
          $this->configuration['port'],
          $this->configuration['timeout'],
          null,
          100
        );
      }
    } catch (\Throwable $th) {
      Logs::error($th->getMessage(), ['exception' => $th]);

      return;
    }

    $this->connection->select(self::DATABASE_ID);

    $this->redisConnected = true;

    if (defined('\\Redis::OPT_PREFIX')) {
      $this->connection->setOption(\Redis::OPT_PREFIX, strtolower(trim($this->configuration['prefix'])));
    }

    if (defined('\\Redis::OPT_SERIALIZER') && defined('\\Redis::SERIALIZER_PHP')) {
      $this->connection->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
    }

    if (defined('\\Redis::OPT_MAX_RETRIES')) {
      $this->connection->setOption(\Redis::OPT_MAX_RETRIES, self::MAX_RETRIES);
    }
  }

  /**
   * Converts TTL to seconds.
   */
  private function convertTtl(\DateTimeInterface|\DateInterval|int $ttl): int
  {
    if (is_int($ttl)) {
      return $ttl;
    }

    // TODO: Convert DateInterval \ DateTimeInterface

    return 100;
  }
}
