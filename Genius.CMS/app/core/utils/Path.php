<?php

namespace App\Core\Utils;

/**
 * Provides static path casting methods.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Path
{
  public static function getAbsolutePath(string $path): string
  {
    return ABSPATH . $path;
  }

  public static function getAppPath(string $path): string
  {
    return ABSPATH . APPDIR . $path;
  }
}
