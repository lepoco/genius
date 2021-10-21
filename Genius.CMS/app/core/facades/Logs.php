<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Allows to save application logs.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static string getDefaultDriver() Get the default log driver name.
 * @method static void emergency(string $message, array $context = []) System is unusable.
 * @method static void alert(string $message, array $context = []) Action must be taken immediately.
 * @method static void critical(string $message, array $context = []) Critical conditions.
 * @method static void error(string $message, array $context = []) Runtime errors that do not require immediate action but should typically be logged and monitored.
 * @method static void warning(string $message, array $context = []) Exceptional occurrences that are not errors.
 * @method static void notice(string $message, array $context = []) Normal but significant events.
 * @method static void info(string $message, array $context = []) Interesting events.
 * @method static void debug(string $message, array $context = []) Detailed debug information.
 * @method static void log(mixed $level, string $message, array $context = []) Logs with an arbitrary level.
 */
final class Logs extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'logs';
  }
}
