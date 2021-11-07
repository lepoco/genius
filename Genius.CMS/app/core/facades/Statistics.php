<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Allows to save and read statistical data from the database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static bool push(string $type, string $tag, string $ua = null) Writes a new record to the database.
 * @method static array get(string $type, string $when) Gets selected type of statistics type from the database and limits them in time.
 * @method static bool isOpened() Gets information about whether statistics are currently being saved.
 */
final class Statistics extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'statistics';
  }
}
