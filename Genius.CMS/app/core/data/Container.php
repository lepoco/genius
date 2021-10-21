<?php

namespace App\Core\Data;

use Illuminate\Container\Container as IlluminateContainer;

/**
 * Extends the Container class.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Container extends IlluminateContainer
{
  /**
   * @deprecated Since 1.1.0
   */
  public function runningUnitTests(): bool
  {
    // FIXME: This is a very bad way of managing Laravel, it needs to be corrected
    return false;
  }
}
