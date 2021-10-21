<?php

namespace App\Core\Schema;

/**
 * Base interface for Request / REST.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Request
{
  public function getAction(): string;

  public function print(): void;
}
