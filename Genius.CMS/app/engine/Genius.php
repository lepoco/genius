<?php

namespace Engine;

use Engine\Genius\{System, Condition, Product, Relation};
use Engine\Genius\Sage\Querier;

/**
 * Genius base.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Genius
{
  public static function getSystem(int $id): System
  {
    return new System($id);
  }

  public static function addSystem(System $system): int
  {
    return Querier::insertSystem($system);
  }

  public static function getTypeId(string $key): int
  {
    return Querier::getTypeId($key);
  }

  public static function getSystemBy(string $key, mixed $value): ?System
  {
    $id = Querier::getSystemIdBy($key, $value);

    if ($id < 1) {
      return null;
    }

    return new System($id);
  }

  /**
   * @return System[]
   */
  public static function getAllSystems(): array
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

  public static function dropSystem(System $system, bool $confirm = false): bool
  {
    if (!$confirm) {
      return false;
    }

    Querier::dropSystem($system);

    return false;
  }

  /**
   * @return Condition[]
   */
  public static function getSystemConditions(System $system): array
  {
    $conditions = [];
    $dbObjects = Querier::getAllConditions($system->getPrefix());

    foreach ($dbObjects as $singleObject) {
      if (isset($singleObject->id)) {
        $conditions[] = new Condition($singleObject->id, $system->getPrefix());
      }
    }

    return $conditions;
  }

  /**
   * @return Product[]
   */
  public static function getSystemProducts(System $system): array
  {
    $products = [];
    $dbObjects = Querier::getAllProducts($system->getPrefix());

    foreach ($dbObjects as $singleObject) {
      if (isset($singleObject->id)) {
        $products[] = new Product($singleObject->id, $system->getPrefix());
      }
    }

    return $products;
  }

  /**
   * @return Relation[]
   */
  public static function getSystemRelations(System $system): array
  {
    $relations = [];
    $dbObjects = Querier::getAllRelations($system->getPrefix());

    foreach ($dbObjects as $singleObject) {
      if (isset($singleObject->id)) {
        $relations[] = new Relation($singleObject->id, $system->getPrefix());
      }
    }

    return $relations;
  }


  public static function addCondition(System $system, Condition $condition): bool
  {
    return Querier::insertCondition($condition, $system->getPrefix());
  }

  public static function addRelation(System $system, Relation $relation): bool
  {
    return Querier::insertRelation($relation, $system->getPrefix());
  }

  public static function addProduct(System $system, Product $product): bool
  {
    return Querier::insertProduct($product, $system->getPrefix());
  }
}
