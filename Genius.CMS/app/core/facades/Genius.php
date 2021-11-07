<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * SE.
 */
final class Genius extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'genius';
  }
}
