<?php

namespace App\Core\Http;

use RuntimeException;
use DateTime;
use App\Core\Facades\Request;
use App\Core\Http\ContentSecurityPolicy;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Object that stores the HTTP response.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Response extends \Symfony\Component\HttpFoundation\Response implements \App\Core\Schema\Response
{
  private ContentSecurityPolicy $csp;

  /**
   * Sets a new HTTP header.
   */
  public function setHeader(string $key, $values, bool $replace = true): self
  {
    $this->headers->set($key, $values, $replace);

    return $this;
  }

  /**
   * Removes a HTTP header.
   */
  public function removeHeader(string $key): self
  {
    $this->headers->remove($key);

    return $this;
  }

  /**
   * Sets a new HTTP cookie header.
   */
  public function setCookie(Cookie $cookie): self
  {
    $this->headers->setCookie($cookie);

    return $this;
  }

  /**
   * Removes a HTTP cookie header.
   */
  public function removeCookie(string $key): self
  {
    $this->headers->removeCookie($key);

    return $this;
  }

  /**
   * Gets a HTTP cookie header by given name.
   */
  public function getCookie(string $key): ?Cookie
  {
    $cookies = $this->headers->getCookies();

    foreach ($cookies as $cookie) {
      if ($key === $cookie->getName()) {
        return $cookie;
      }
    }

    return null;
  }

  /**
   * Sends HTTP headers and content.
   */
  public function send(): self
  {
    if (!isset($this->csp)) {
      throw new RuntimeException('CSP was not prepared.');
    }

    $this->sessionCookie();
    $this->permanentHeaders();

    parent::send();

    return $this;
  }

  /**
   * Prepares Content Security Policy.
   */
  public function prepareCSP(array $scripts = [], array $styles = [], array $fonts = []): void
  {
    $this->csp = new ContentSecurityPolicy();

    foreach ($scripts as $script) {
      $this->csp->addScriptSource($script);
    }

    foreach ($styles as $style) {
      $this->csp->addStyleSource($style);
    }

    foreach ($fonts as $font) {
      $this->csp->addFontSource($font);
    }
  }

  public function getNonce(): string
  {
    if (!isset($this->csp)) {
      throw new RuntimeException('CSP was not prepared.');
    }

    return $this->csp->nonce();
  }

  private function permanentHeaders(): void
  {
    /**
     * We get the modification date of the last used file to generate the sum. Most often this will be the blade view file.
     */
    $generatorFiles = get_included_files();
    $lastModified = filemtime($generatorFiles[array_key_last($generatorFiles)]);

    /*
     * Here we are trying to remove the default server-defined headers.
     */

    $this->headers->remove('pragma');
    $this->headers->remove('host');
    $this->headers->remove('server');
    $this->headers->remove('expires');
    $this->headers->remove('authorization');
    $this->headers->remove('x-powered-by');

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control */
    $this->setCache([
      'last_modified' => ((new DateTime())->setTimestamp($lastModified)),
      'must_revalidate' => true,
      'no_store' => true,
      'no_cache' => true
    ]);

    $this->setEtag(sprintf('%s-%s', $lastModified, hash('crc32', $this->content)), true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Strict-Transport-Security */
    $this->headers->set('Strict-Transport-Security', 'max-age=31536000; preload', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Accept-Ranges */
    $this->headers->set('Accept-Ranges', 'bytes', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Connection */
    $this->headers->set('Connection', 'keep-alive, close', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Upgrade-Insecure-Requests */
    $this->headers->set('Upgrade-Insecure-Requests', 1, true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options */
    $this->headers->set('X-Frame-Options', 'DENY', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection */
    $this->headers->set('X-XSS-Protection', '1; mode=block', true);

    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    $this->headers->set('Access-Control-Allow-Origin', Request::root() . ', https://fonts.googleapis.com, https://fonts.gstatic.com', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Vary */
    $this->headers->set('Vary', 'Origin, Digest, Accept-Encoding', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Date */
    $this->headers->set('Date', date('r'), true); // RFC 2822 server time

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Want-Digest */
    $this->headers->set('Want-Digest', 'SHA-512', true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Digest */
    $this->headers->set('Digest', 'sha-512=' . base64_encode(hash('sha512', $this->content)), true);

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy */
    $this->headers->set('Content-Security-Policy', $this->csp->build(), true);

    // The RTT network client hint request header field provides the approximate round trip time on the application layer, in milliseconds
    if (defined('APPSTART')) {
      /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/RTT */
      $this->headers->set('RTT', ((int) (microtime(true) - APPSTART) * 1000), true);
    }
  }

  private function sessionCookie(): void
  {
    // TODO: Fix cookies saving
  }
}
