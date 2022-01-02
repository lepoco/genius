<?php

namespace App\Core\Data;

use App\Core\Facades\Config;

/**
 * Implementation crc64() in php 64bit.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class Hash
{
  /**
   * @return array
   */
  public static function crc64Table(): mixed
  {
    $crc64tab = [];

    // ECMA polynomial
    $poly64rev = (0xC96C5795 << 32) | 0xD7870F42;

    // ISO polynomial
    // $poly64rev = (0xD8 << 56);

    for ($i = 0; $i < 256; $i++) {
      for ($part = $i, $bit = 0; $bit < 8; $bit++) {
        if ($part & 1) {
          $part = (($part >> 1) & ~(0x8 << 60)) ^ $poly64rev;
        } else {
          $part = ($part >> 1) & ~(0x8 << 60);
        }
      }

      $crc64tab[$i] = $part;
    }

    return $crc64tab;
  }

  /**
   * @param string $string
   * @param string $format
   * @return mixed
   */
  /*
   *  Formats:
   *  crc64('php'); // afe4e823e7cef190
   *  crc64('php', '0x%x'); // 0xafe4e823e7cef190
   *  crc64('php', '0x%X'); // 0xAFE4E823E7CEF190
   *  crc64('php', '%d'); // -5772233581471534704 signed int
   *  crc64('php', '%u'); // 12674510492238016912 unsigned int
   */
  public static function crc64($string, $format = '%x'): mixed
  {
    static $crc64tab;

    if ($crc64tab === null) {
      $crc64tab = self::crc64Table();
    }

    $crc = 0;

    for ($i = 0; $i < strlen($string); $i++) {
      $crc = $crc64tab[($crc ^ ord($string[$i])) & 0xff] ^ (($crc >> 8) & ~(0xff << 56));
    }

    return sprintf($format, $crc);
  }
}
