<?php

namespace Engine\Genius;

use Engine\Genius\Sage\Querier;

/**
 * ES product.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Product extends \Engine\Database\DBO
{
  private float $weight = 1;

  private string $prefix = '';

  private string $name = '';

  private string $simplifiedName = '';

  private string $description = '';

  private string $createdAt = '';

  public function __construct(int $id = 0, string $prefix = '')
  {
    $this->id = $id;
    $this->prefix = $prefix;

    $this->fetch($id, $prefix);
  }

  public static function build(array $parameters): self
  {
    return (new self())
      ->setWeight($parameters['weight'] ?? 1)
      ->setPrefix($parameters['prefix'] ?? '')
      ->setName($parameters['name'] ?? '')
      ->setSimplifiedName($parameters['simplified_name'] ?? '')
      ->setDescription($parameters['description'] ?? '')
      ->setCreatedAt($parameters['created_at'] ?? date('Y-m-d H:i:s'))
      ->setId($parameters['id'] ?? 0);
  }

  private function fetch(int $id, string $prefix): void
  {
    if ($id < 1 || empty($prefix)) {
      return;
    }

    $sysObject = Querier::getProductObject($id, $prefix);

    $this->id = $sysObject->id ?? $id;
    $this->weight = $sysObject->weight ?? 1;
    $this->name = $sysObject->name ?? '';
    $this->simplifiedName = $sysObject->simplified_name ?? '';
    $this->description = $sysObject->description ?? '';
    $this->createdAt = $sysObject->created_at ?? '';
  }

  public function setWeight(float $weight): self
  {
    $this->weight = $weight;

    return $this;
  }

  public function getWeight(): float
  {
    return $this->weight;
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

  public function getSimplifiedName(): string
  {
    return $this->simplifiedName;
  }

  public function setSimplifiedName(string $simplifiedName): self
  {
    $this->simplifiedName = $simplifiedName;

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

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function setCreatedAt(string $createdAt): self
  {
    $this->createdAt = $createdAt;

    return $this;
  }
}
