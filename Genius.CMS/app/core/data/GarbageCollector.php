<?php

namespace App\Core\Data;

use App\Core\Facades\Cache;
use App\Core\Facades\DB;

/**
 * Cleans unnecessary garbage.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class GarbageCollector
{
  public function cache(): bool
  {
    //$this->cache->flush();
    return true;
  }

  public function logs(): bool
  {
    return true;
  }
}
