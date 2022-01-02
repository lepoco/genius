<?php

namespace Engine\Genius\Predictor;

/**
 * ES Solver.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Solver
{
  public static function fromSession(array $data): self
  {
    return new self();
  }

  public function findNext(int $currentCondition, int $response): void
  {
    //123
  }
}
