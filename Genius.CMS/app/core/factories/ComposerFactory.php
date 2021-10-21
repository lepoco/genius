<?php

namespace App\Core\Factories;

/**
 * Blade composers factory.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ComposerFactory implements \App\Core\Schema\Factory
{
  private const NAMESPACE = '\\App\\Common\\Composers\\';

  /**
   * @return string
   */
  public static function make(string $property = '')
  {
    $composerClass = self::NAMESPACE . $property . 'Composer';

    if (!class_exists($composerClass)) {
      return '';
    }

    return $composerClass;
  }
}
