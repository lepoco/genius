<?php

namespace App\Core\Http;

use App\Core\Facades\Request;
use App\Core\Data\Encryption;

/**
 * Creates a set of rules to protect the site from XSS attacks.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ContentSecurityPolicy
{
  private bool $allowObjects;

  private bool $allowFrames;

  private bool $allowExternalImages;

  private bool $allowInlineScripts;

  private bool $allowInlineStyles;

  private string $root;

  private string $cspNonce = '';

  private string $nonceAlgo = 'sha512';

  private array $scriptSources = [];

  private array $styleSources = [];

  private array $fontSources = [];

  public function __construct()
  {
    $this->root = Request::root();

    $this->ruleset();
  }

  public function ruleset(bool $inlineScripts = false, bool $inlineStyles = true, bool $frames = false, bool $objects = false, bool $externalImages = false): self
  {
    $this->allowInlineScripts = $inlineScripts;
    $this->allowInlineStyles = $inlineStyles;
    $this->allowFrames = $frames;
    $this->allowObjects = $objects;
    $this->allowExternalImages = $externalImages;

    return $this;
  }

  /**
   * Gets a cryptographic nonce (only used once) to allow scripts.
   * The server must generate a unique nonce value each time it transmits a policy.
   *
   * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy#other_values
   */
  public function nonce(): string
  {
    if (empty($this->cspNonce)) {
      $this->cspNonce = base64_encode(hash($this->nonceAlgo, Encryption::salter(16) . time()));
    }

    return $this->cspNonce;
  }

  /**
   * Creates a text string which is the security policy. It can be used in the header or in the meta.
   */
  public function build(): string
  {
    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/default-src */
    $csp = 'default-src ' . $this->root . ';';

    $csp .= $this->generateStyleSrc();
    $csp .= $this->generateScriptSrc();
    $csp .= $this->generateFontSrc();
    $csp .= $this->generatePrefetchSrc();

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/img-src */
    $csp .= ' connect-src ' . $this->root . ';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/manifest-src */
    $csp .= ' manifest-src ' . $this->root . ';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/worker-src */
    $csp .= ' worker-src ' . $this->root . ';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/img-src */
    $csp .= ' img-src ' . ($this->allowExternalImages ? 'https://*' : $this->root) . ' data:;';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/frame-src */
    $csp .= ' frame-src ' . ($this->allowFrames ? $this->root : '\'none\'') . ';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/object-src */
    $csp .= ' object-src ' . ($this->allowObjects ? $this->root : '\'none\'') . ';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/child-src */
    $csp .= ' child-src \'none\';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/trusted-types */
    $csp .= ' trusted-types \'none\';';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests */
    $csp .= ' upgrade-insecure-requests;';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/require-sri-for */
    //$csp .= ' require-sri-for script style;';

    /** @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/require-trusted-types-for */
    //$csp .= ' require-trusted-types-for \'script\';';

    return $csp;
  }

  /**
   * Adds the URL to the list of trusted scripts vendors.
   */
  public function addScriptSource(string $source): self
  {
    $this->scriptSources[] = $source;

    return $this;
  }

  /**
   * Adds the URL to the list of trusted styles vendors.
   */
  public function addStyleSource(string $source): self
  {
    $this->styleSources[] = $source;

    return $this;
  }

  /**
   * Adds the URL to the list of trusted fonts vendors.
   */
  public function addFontSource(string $source): self
  {
    $this->fontSources[] = $source;

    return $this;
  }

  /**
   * Defines the algorithm with which the nonce will be created.
   */
  public function setAlgo(string $algo = 'sha512'): self
  {
    $this->nonceAlgo = $algo;

    return $this;
  }

  /**
   * Specifies valid sources for JavaScript.
   * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src
   */
  private function generateScriptSrc(): string
  {
    $source = $this->root;

    foreach ($this->scriptSources as $script) {
      $source .= ' ' . $script;
    }

    $source .= ' \'nonce-' . $this->nonce() . '\'';

    if ($this->allowInlineScripts) {
      $source .= ' \'unsafe-inline\'';
    }

    return ' script-src ' . $source . ';';
  }

  /**
   * Specifies valid sources for CSS.
   * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src
   */
  private function generateStyleSrc(): string
  {
    $source = $this->root;

    foreach ($this->styleSources as $style) {
      $source .= ' ' . $style;
    }

    if ($this->allowInlineStyles) {
      $source .= ' \'unsafe-inline\'';
    }

    return ' style-src ' . $source . ';';
  }

  /**
   * Specifies valid sources for fonts.
   * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/font-src
   */
  private function generateFontSrc(): string
  {
    $source = $this->root;

    foreach ($this->fontSources as $font) {
      $source .= ' ' . $font;
    }

    return ' font-src ' . $source . ';';
  }

  private function generatePrefetchSrc(): string
  {
    $source = $this->root;

    $prefetchSources = [];

    foreach ($this->scriptSources as $script) {
      $prefetchSources[] = $script;
    }

    foreach ($this->styleSources as $style) {
      if (!in_array($style, $prefetchSources)) {
        $prefetchSources[] = $style;
      }
    }

    foreach ($this->fontSources as $font) {
      if (!in_array($font, $prefetchSources)) {
        $prefetchSources[] = $font;
      }
    }

    foreach ($prefetchSources as $singleSource) {
      $source .= ' ' . $singleSource;
    }

    return ' prefetch-src ' . $source . ';';
  }
}
