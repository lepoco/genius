<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Allows to retrieve and save options from the database, stored in memory using the Cache.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static mixed remember(string $key, \Closure $callback) Get an option from the database, or store the default value.
 * @method static mixed get(string $key, mixed $default = '', $cache = true) Get option from the database.
 * @method static mixed set(string $key, mixed $value) Set and save option in the database.
 */
final class Option extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'options';
  }
}
