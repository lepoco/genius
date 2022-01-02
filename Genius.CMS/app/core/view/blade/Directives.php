<?php

namespace App\Core\View\Blade;

use App\Core\Facades\{Config, Option, Translate, Response, Request};
use App\Core\Data\Encryption;
use App\Core\Http\Redirect;
use Illuminate\Support\Str;

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
   * Creates url.
   * Triggered once.
   */
  public function url(string $path = ''): string
  {
    return Redirect::url($path);
  }

  /**
   * Creates media url.
   * Triggered once.
   */
  public function media(string $asset = ''): string
  {
    $url = '#';

    if (!empty($asset)) {
      $url = Redirect::url('img/' . $asset . '?v=' . Option::remember('app_version', fn () => Config::get('app.version', '0.0.0')));
    }

    return '<img lazy src="' . $url . '" alt="' . Str::before($asset, '.') . ' icon" />';
  }

  /**
   * Creates media url.
   * Triggered once.
   */
  public function asset(string $path = ''): string
  {
    if (empty($path)) {
      return Redirect::url();
    }

    return Redirect::url($path . '?v=' . Option::remember('app_version', fn () => Config::get('app.version', '0.0.0')));
  }

  /**
   * Creates url.
   * Triggered once.
   */
  public static function csp(): string
  {
    return Response::getNonce();
  }

  /**
   * Returns the current language domain.
   * Triggered every time.
   */
  public static function domain(bool $short = false): string
  {
    $domain = Translate::domain();
    $domain = !empty($domain) ? $domain : 'en_US';

    return $short ? substr($domain, 0, 2) : $domain;
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

    $text = Translate::string($text);

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
      return Encryption::hash('ajax_nonce', 'nonce');
    }

    return Encryption::hash('ajax_' . $action . '_nonce', 'nonce');
  }

  /**
   * Print current URL without query.
   * Triggered every time.
   */
  public static function currenturl()
  {
    return Request::url();
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
}
