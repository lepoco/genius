<?php

namespace Engine;

use Engine\Genius\System;
use Engine\Genius\Sage\Querier;

/**
 * Genius base.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Genius
{
  public function getSystem(int $id): System
  {
    return new System($id);
  }

  public function addSystem(System $system): int
  {
    return Querier::insertSystem($system);
  }

  public function getTypeId(string $key): int
  {
    return Querier::getTypeId($key);
  }

  /**
   * @return System[]
   */
  public function getAllSystems(): array
  {
    $systems = [];
    $ids = Querier::getSystemsId();

    foreach ($ids as $singleId) {
      if (isset($singleId->id)) {
        $systems[] = new System($singleId->id);
      }
    }

    return $systems;
  }
}
