<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Stores the application's static, unchanging configuration.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static mixed get(string $key, mixed $default = null) Get the specified configuration value.
 * @method static mixed getMany(array $keys) Get many configuration values.
 * @method static bool has(string $key) Determine if the given configuration value exists.
 * @method static void set($key, $value = null) Set a given configuration value.
 * @method static void prepend(string $key, mixed $value) Prepend a value onto an array configuration value.
 * @method static void push(string $key, mixed $value) Push a value onto an array configuration value.
 * @method static array all() Get all of the configuration items for the application.
 */
final class Config extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'configuration';
  }
}
