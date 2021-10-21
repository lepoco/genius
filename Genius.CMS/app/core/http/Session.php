<?php

namespace App\Core\Http;

use App\Core\Http\Redirect;
use phpDocumentor\Reflection\Types\Callable_;

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
  private bool $started = false;

  private array $sessionData = [];

  /**
   * Get an item from the session, or store the default value.
   */
  public function remember(string $key, \Closure $callback): mixed
  {
    if (isset($this->sessionData[$key])) {
      return $this->sessionData[$key];
    }

    return $this->sessionData[$key] = $callback;
  }

  public function start()
  {
    if ($this->started) {
      return true;
    }

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

    $this->sessionData = &$_SESSION;

    return $this;
  }

  public function regenerate(bool $destroy = false, int $lifetime = null)
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
}
