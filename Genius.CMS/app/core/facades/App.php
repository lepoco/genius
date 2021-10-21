<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;
use App\Core\Bootstrap;

/**
 * App facade
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static \App\Core\Bootstrap connect() Attempts to connect to the database and creates the Database, Options, and Cache objects.
 * @method static \App\Core\Bootstrap close() Closes session and triggers the Garbage Collector.
 * @method static \App\Core\Bootstrap print() Triggers Router and tries to create the view to display or execute the Request.
 * @method static \App\Core\Bootstrap setup() Application specific constructor. Creates instances of base objects and assigns them to Facades.
 * @method static bool rebind(string $abstract = '') Reassign the objects to the controller.
 * @method static bool isConnected() Checks whether the database is connected.
 * @method static bool beforePrint() Performs actions before printing the page.
 * @method static object getProperty(string $property) Gets an application object that is an instance of one of the logic elements.
 * @method static void destroy() Flush the session data and regenerate the ID.
 * @method static void regenerate() Generate a new session identifier and token.
 */
final class App extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'self';
  }

  /**
   * Defines a global reference to an application instance.
   */
  public static function set(Bootstrap &$app): void
  {
    static::$app = $app;
  }

  /**
   * Responds to a static call to the Facade class and then tries to execute a method with the same name on the application object.
   * @param mixed|array $arguments
   * @return mixed
   */
  public static function __callStatic(string $name, $arguments)
  {
    return static::$app->{$name}(...$arguments);
  }
}
