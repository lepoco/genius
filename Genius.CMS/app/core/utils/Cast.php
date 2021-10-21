<?php

namespace App\Core\Utils;

use Illuminate\Support\Str;

/**
 * Provides static name casting methods.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Cast
{
  public static function namespaceToBlade(string $namespace): string
  {
    $view = '';
    $path = explode('\\', $namespace);

    for ($i = 0; $i < count($path); $i++) {
      $view .= ($i > 0 ? '.' : '') . Str::kebab($path[$i]);
    }

    return $view;
  }

  public static function emailToUsername(string $email): string
  {
    return preg_replace("/[^a-zA-Z0-9]+/", "", trim(strtolower(Str::beforeLast($email, '@'))));
  }
}
