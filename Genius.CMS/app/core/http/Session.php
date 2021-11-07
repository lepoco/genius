<?php

namespace App\Core\Http;

/**
 * Manages the PHP session.
 *
 * @author  Drak <drak@zikula.org> / Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 * @see     Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage
 */
final class Session implements \App\Core\Schema\Session
{
  private const VALUES_KEY = '_values';

  private const TOKEN_KEY = '_token';

  private bool $started = false;

  private int $status = 0;

  private string $id = '';

  private array $sessionData = [];

  public function __construct()
  {
    if (!\extension_loaded('session')) {
      throw new \LogicException('PHP extension "session" is required.');
    }

    session_register_shutdown();
  }

  /**
   * Get an item from the session, or store the default value.
   */
  public function get(string $key, mixed $default = null): mixed
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount) {
      return $this->sessionData[self::VALUES_KEY][$keyArray[0]] ?? $default;
    }

    if (2 === $keyCount) {
      return $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]] ?? $default;
    }

    if (3 === $keyCount) {
      return $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]] ?? $default;
    }

    if (4 === $keyCount) {
      return $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]] ?? $default;
    }

    return $default;
  }

  /**
   * Put a key / value pair or array of key / value pairs in the session.
   */
  public function put(string $key, mixed $value): self
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount) {
      $this->sessionData[self::VALUES_KEY][$keyArray[0]] = $value;

      return $this;
    }

    if (2 === $keyCount) {
      $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]] = $value;

      return $this;
    }

    if (3 === $keyCount) {
      $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]] = $value;

      return $this;
    }

    if (4 === $keyCount) {
      $this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]] = $value;

      return $this;
    }

    return $this;
  }

  /**
   * Checks if a key is present.
   */
  public function has(string $key): bool
  {
    $keyArray = explode('.', $key);
    $keyCount = count($keyArray);

    if (1 === $keyCount) {
      return isset($this->sessionData[self::VALUES_KEY][$keyArray[0]]);
    }

    if (2 === $keyCount) {
      return isset($this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]]);
    }

    if (3 === $keyCount) {
      return isset($this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]]);
    }

    if (4 === $keyCount) {
      return isset($this->sessionData[self::VALUES_KEY][$keyArray[0]][$keyArray[1]][$keyArray[2]][$keyArray[3]]);
    }

    return false;
  }

  /**
   * Checks if all keys are present.
   */
  public function hasAny(array $keys): bool
  {
    foreach ($keys as $key) {
      if (!$this->has($key)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Get an item from the session, or store the default value.
   */
  public function remember(string $key, \Closure $callback): mixed
  {
    if ($this->has($key)) {
      return $this->get($key);
    }

    return $this->put($key, $callback());
  }

  /**
   * Retrieves all session data.
   */
  public function all(): array
  {
    return $this->sessionData ?? [];
  }

  /**
   * Clear ALL session data.
   */
  public function clear(): self
  {
    $this->sessionData = $_SESSION[self::VALUES_KEY] ?? [];

    // Clear out the session
    $_SESSION = [];

    return $this;
  }

  public function invalidate(): self
  {
    session_regenerate_id();

    $this->sessionData[self::TOKEN_KEY] = hash('sha3-512', time());

    return $this;
  }

  /**
   * Start new session.
   */
  public function start(): self
  {
    if ($this->started) {
      return true;
    }

    $this->status = session_status();

    if (\PHP_SESSION_ACTIVE === session_status()) {
      throw new \RuntimeException('Failed to start the session: already started by PHP.');
    }

    if (filter_var(ini_get('session.use_cookies'), \FILTER_VALIDATE_BOOLEAN) && headers_sent($file, $line)) {
      throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
    }

    // ok to try and start the session
    if (!session_start()) {
      throw new \RuntimeException('Failed to start the session.');
    }

    $this->id = session_id();

    $this->sessionData = &$_SESSION;

    if (!isset($this->sessionData[self::TOKEN_KEY])) {
      $this->regenerateToken();
    }

    return $this;
  }

  /**
   * @deprecated It's a bad practice to close a session while the application is running.
   */
  public function close(): bool
  {
    if (!$this->started) {
      return false;
    }

    ini_set('session.use_only_cookies', false);
    ini_set('session.use_cookies', false);
    ini_set('session.use_trans_sid', false);
    ini_set('session.cache_limiter', '');

    if (array_key_exists('PHPSESSID', $_COOKIE)) {
      session_id($_COOKIE['PHPSESSID']);
    } else {
      setcookie('PHPSESSID', session_id());
    }

    return session_write_close();
  }

  /**
   * Save used values in the session.
   */
  public function save(): self
  {
    if (!$this->started) {
      // TODO: Should we throw an error?
      return $this;
    }

    // TODO: Should we allow multiple saves?
    // if (!$this->started) {
    //   throw new \RuntimeException('Failed to save closed session.');
    // }

    $_SESSION = $this->sessionData;

    return $this;
  }

  /**
   * Regenerate the CSRF token value.
   */
  public function regenerate(bool $destroy = false, int $lifetime = null): bool
  {
    // Cannot regenerate the session ID for non-active sessions.
    if (\PHP_SESSION_ACTIVE !== session_status()) {
      return false;
    }

    if (headers_sent()) {
      return false;
    }

    if (null !== $lifetime && $lifetime != ini_get('session.cookie_lifetime')) {
      $this->save();
      ini_set('session.cookie_lifetime', $lifetime);
      $this->start();
    }

    if ($destroy) {
      $this->sessionData = [];
      session_destroy();
    }

    $isRegenerated = session_regenerate_id($destroy);

    return $isRegenerated;
  }

  /**
   * Get the current session ID.
   */
  public function getId(): string
  {
    return $this->id;
  }

  /**
   * Set the session ID.
   */
  public function setId(string $id): self
  {
    session_id($id);

    $this->id = $id;

    return $this;
  }

  /**
   * Get the current session ID.
   */
  public function token(): string
  {
    return $this->sessionData[self::TOKEN_KEY] ?? '';
  }

  /**
   * Set the session ID.
   */
  public function regenerateToken(): self
  {
    $this->sessionData[self::TOKEN_KEY] = hash('sha3-512', time());

    return $this;
  }

  /**
   * Get information about whether a session is currently opened.
   */
  public function isStarted(): bool
  {
    return $this->started;
  }
}
