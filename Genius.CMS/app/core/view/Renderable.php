<?php

namespace App\Core\View;

use App\Core\Facades\{Request, Response, Option};
use App\Core\Data\Encryption;
use App\Core\Http\Redirect;

/**
 * Abstraction for a view/request, contains the basic logic for all kinds of returned data in the browser.
 *
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class Renderable
{
  protected string $namespace;

  protected string $baseUrl;

  public function __construct()
  {
    Response::setPrivate()
      ->setImmutable()
      ->setMaxAge(0)
      ->setHeader('Content-Type', 'text/html; charset=UTF-8')
      ->setHeader('X-Content-Type-Options', 'nosniff');

    $this->setBaseUrl(Redirect::url());

    /**
     * Website contains items related to money transfers and is not suitable for children.
     *
     * @see https://tools.ietf.org/html/rfc8674
     */
    Response::setContentSafe(false);
  }

  public function setNamespace(string $namespace = ''): self
  {
    $this->namespace = $namespace;

    return $this;
  }

  final protected function nonce(string $key): string
  {
    return Encryption::hash($key, 'nonce');
  }

  final protected function validateNonce(string $baseKey, string $rawInput): bool
  {
    return Encryption::compare($baseKey, $rawInput, 'nonce');
  }

  final protected function setBaseUrl(string $url = ''): self
  {
    $this->baseUrl = $url;

    return $this;
  }

  final protected function isAjax(): bool
  {
    //return 'XMLHttpRequest' === $this->request->getHeader('x-requested-with');
    return Request::ajax();
  }
}
