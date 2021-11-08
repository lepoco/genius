<?php

namespace Engine\Genius;

use Engine\Genius\Sage\Querier;
use Engine\Genius\{Condition, Product};

/**
 * ES Relation.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Relation extends \Engine\Database\DBO
{
  private string $prefix = '';

  private int $typeId = 1;

  private int $conditionId = 0;

  private int $productId = 0;

  private Condition $condition;

  private Product $product;

  public function __construct(int $id = 0, string $prefix = '')
  {
    $this->id = $id;
    $this->prefix = $prefix;
    $this->condition = new Condition(0, $prefix);
    $this->product = new Product(0, $prefix);

    $this->fetch($id, $prefix);
  }

  public static function build(array $parameters): self
  {
    return (new self())
      ->setPrefix($parameters['prefix'] ?? '')
      ->setTypeId($parameters['type_id'] ?? 1)
      ->setConditionId($parameters['condition_id'] ?? 0)
      ->setProductId($parameters['product_id'] ?? 0)
      ->setId($parameters['id'] ?? 0);
  }

  private function fetch(int $id, string $prefix): void
  {
    if ($id < 1 || empty($prefix)) {
      return;
    }

    $sysObject = Querier::getRelationObject($id, $prefix);

    $this->id = $sysObject->id ?? $id;
    $this->typeId = $sysObject->type_id ?? '';

    $this->setConditionId($sysObject->condition_id ?? 0);
    $this->setProductId($sysObject->product_id ?? 0);
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

  public function setTypeId(int $typeId): self
  {
    $this->typeId = $typeId;

    return $this;
  }

  public function getTypeId(): int
  {
    return $this->typeId;
  }

  public function setConditionId(int $conditionId): self
  {
    $this->conditionId = $conditionId;
    $this->condition = new Condition($conditionId, $this->prefix);

    return $this;
  }

  public function getConditionId(): int
  {
    return $this->conditionId;
  }

  public function getCondition(): Condition
  {
    return $this->condition;
  }

  public function setProductId(int $productId): self
  {
    $this->productId = $productId;
    $this->product = new Product($productId, $this->prefix);

    return $this;
  }

  public function getProductId(): int
  {
    return $this->productId;
  }

  public function getProduct(): Product
  {
    return $this->product;
  }
}
