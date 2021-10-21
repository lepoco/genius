<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Allows queries to the database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static \Illuminate\Database\Connection connection(string|null $connection = null) Get a connection instance from the global manager.
 * @method static \Illuminate\Database\Query\Builder table(\Closure|\Illuminate\Database\Query\Builder|string $table, string|null $as = null, string|null $connection = null) Get a fluent query builder instance.
 * @method static \Illuminate\Database\Schema\Builder schema(string|null $connection = null) Get a schema builder instance.
 */
final class DB extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'database';
  }
}
