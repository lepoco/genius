<?php

namespace App\Core\Schema;

/**
 * Base interface for Session.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Session
{
  public function start();

  public function save();

  public function clear();

  public function regenerate(bool $destroy = false, int $lifetime = null);

  public function getId(): string;

  public function setId(string $id);

  public function getName(): string;

  public function setName(string $name);
}
