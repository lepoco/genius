<?php

namespace App\Core\Data;

/**
 * Represents an object retrieved from the database containing the ID and allowing itself to be created from an array.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
abstract class DatabaseObject
{
  protected int $id = 0;

  /**
   * Creates a new instance based on the given parameters.
   */
  abstract public static function build(array $properties);

  /**
   * Verifies whether the object retrieved from the database is real.
   */
  public function isValid(): bool
  {
    return 0 !== $this->id;
  }

  /**
   * Just a shorter wrapper for \App\Core\Data\DatabaseObject::getId()
   */
  final public function id(): int
  {
    return $this->getId();
  }

  /**
   * Gets an identifier that corresponds to the object in the database.
   */
  final public function getId(): int
  {
    return $this->id;
  }

  /**
   * Defines an identifier from the database, should not be changed by the user.
   */
  final protected function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }
}
