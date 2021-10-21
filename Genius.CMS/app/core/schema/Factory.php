<?php

namespace App\Core\Schema;

/**
 * Base interface for Factory.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Factory
{
  public static function make(string $property = '');
}
