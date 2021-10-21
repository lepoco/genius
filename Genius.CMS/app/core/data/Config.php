<?php

namespace App\Core\Data;

use Illuminate\Config\Repository;

/**
 * Extends the Repository class containing the application's configuration to the name Config. Uses the Laravel scheme.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Config extends Repository
{
  protected static $instance;
}
