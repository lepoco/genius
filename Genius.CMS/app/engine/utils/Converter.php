<?php

namespace Engine\Utils;

/**
 * Converts things.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Converter
{
  public static function simplifyString(string $haystack): string
  {
    return preg_replace("/[^a-zA-Z0-9]+/", '', strtolower($haystack));
  }
}
