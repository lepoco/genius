<?php

namespace Engine\Genius;

use Engine\Genius\Sage\Querier;

/**
 * ES system representation.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class System
{
  private int $id = 0;

  private int $typeId = 0;

  private string $uuid = '';

  private string $name = '';

  private string $prefix = '';

  private string $description = '';

  private string $question = '';

  private string $createdAt = '';

  private string $updatedAt = '';

  public function __construct(int $id = 0)
  {
    $this->id = $id;

    $this->fetch($id);
  }

  /**
   * Verifies whether the object retrieved from the database is real.
   */
  public function isValid(): bool
  {
    return 0 !== $this->id;
  }

  public static function build(array $parameters): self
  {
    return (new self())
      ->setId($parameters['id'] ?? 0)
      ->setTypeId($parameters['type_id'] ?? 0)
      ->setUUID($parameters['uuid'] ?? '')
      ->setName($parameters['name'] ?? '')
      ->setPrefix($parameters['prefix'] ?? '')
      ->setDescription($parameters['description'] ?? '')
      ->setQuestion($parameters['question'] ?? '')
      ->setCreatedAt($parameters['created_at'] ?? date('Y-m-d H:i:s'))
      ->setUpdatedAt($parameters['updated_at'] ?? date('Y-m-d H:i:s'));
  }

  private function fetch(int $id): void
  {
    if ($id < 1) {
      return;
    }

    $sysObject = Querier::getSystemObject($id);

    $this->id = $sysObject->id ?? $id;
    $this->typeId = $sysObject->type_id ?? 1;
    $this->uuid = $sysObject->uuid ?? '';
    $this->name = $sysObject->name ?? '';
    $this->prefix = $sysObject->prefix ?? '';
    $this->description = $sysObject->description ?? '';
    $this->question = $sysObject->question ?? '';
    $this->createdAt = $sysObject->created_at ?? '';
    $this->updatedAt = $sysObject->updated_at ?? '';
  }

  /**
   * Just a shorter wrapper for getId()
   */
  final public function id(): int
  {
    return $this->getId();
  }

  /**
   * Gets an identifier that corresponds to the object in the database.
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * Defines an identifier from the database, should not be changed by the user.
   */
  private function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getUUID(): string
  {
    return $this->uuid;
  }

  public function setUUID(string $uuid): self
  {
    $this->uuid = $uuid;

    return $this;
  }

  public function getPrefix(): string
  {
    return $this->prefix;
  }

  public function setPrefix(string $prefix): self
  {
    $this->prefix = $prefix;

    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getQuestion(): string
  {
    return $this->question;
  }

  public function setQuestion(string $question): self
  {
    $this->question = $question;

    return $this;
  }

  public function getTypeId(): int
  {
    return $this->typeId;
  }

  public function setTypeId(int $typeId): self
  {
    $this->typeId = $typeId;

    return $this;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function setCreatedAt(string $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }

  public function setUpdatedAt(string $updatedAt): self
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
