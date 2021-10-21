<?php

namespace App\Core\View\Blade;

use App\Core\Facades\{Option, Request};
use App\Core\Data\Encryption;

/**
 * Dynamically creates directives for Blade.
 * Static methods are triggered every time, dynamic only during rendering.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Directives
{
  /**
   * Creates media url.
   * Triggered once.
   */
  public function media(string $path = ''): string
  {
    if (empty($path)) {
      return self::getAssetsUrl() . 'img/';
    }

    return self::getAssetsUrl() . 'img/' . $path;
  }

  /**
   * Creates media url.
   * Triggered once.
   */
  public function asset(string $path = ''): string
  {
    $assetsUrl = rtrim(Option::get('base_url', Request::root()), '/') . '/';

    if (empty($path)) {
      return self::getAssetsUrl();
    }

    return self::getAssetsUrl() . $path;
  }

  /**
   * Creates url.
   * Triggered once.
   */
  public function url(string $path = ''): string
  {
    if (empty($path)) {
      return rtrim(Option::get('base_url', Request::root()), '/') . '/';
    }

    return rtrim(Option::get('base_url', Request::root()), '/') . '/' . $path;
  }

  /**
   * Translates text.
   * Triggered every time.
   */
  public static function translate(string $text = ''): string
  {
    if (empty($text)) {
      return 'translator_string';
    }

    $text = str_replace(
      [
        '\n'
      ],
      [
        '<br>'
      ],
      $text
    );

    return $text;
  }

  /**
   * Creates a time-dependent verification nonce.
   * Triggered every time.
   */
  public static function nonce(string $action = ''): string
  {
    if (empty($action)) {
      return Encryption::encrypt('ajax_nonce', 'nonce');
    }

    return Encryption::encrypt('ajax_' . $action . '_nonce', 'nonce');
  }

  /**
   * Asks for options value to the database or the cache.
   * Triggered every time.
   */
  public static function option(string $name, $default = '')
  {
    return Option::get($name, $default);
  }

  /**
   * Asks for options value to the database or the cache.
   * Triggered every time.
   */
  // public function permission(string $permission)
  // {
  //   return 'if(\App\Core\Application::Account()->hasPermission(' . $permission . ')):';
  // }

  /**
   * Asks for options value to the database or the cache.
   * Triggered every time.
   */
  // public function endpermission()
  // {
  //   return 'endif';
  // }

  private static function getAssetsUrl(): string
  {
    return $assetsUrl = rtrim(Option::get('base_url', Request::root()), '/') . '/';
  }
}
