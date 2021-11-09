<?php

namespace Engine\Genius\Sage;

use Engine\Genius\{System, Condition, Product, Relation};
use Engine\Database\Schema;
use App\Core\Facades\{DB, Cache};
use Illuminate\Support\Str;

/**
 * ES querier.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Querier
{
  private const SYSTEMS_TABLE = 'es_systems';

  private const TYPES_TABLE = 'es_system_types';

  public static function getTypeId(string $name): int
  {
    return Cache::forever('expert.querier.typeid' . $name, function () use ($name) {
      $dbKey = DB::table(self::TYPES_TABLE)->where(['name' => $name])->get(['id'])->first();

      if (isset($dbKey->id)) {
        return $dbKey->id;
      }

      $insertedId = DB::table(self::TYPES_TABLE)->insertGetId([
        'name' => $name
      ]);

      return $insertedId;
    });
  }

  public static function getSystemIdBy(string $key, mixed $value): int
  {
    $dbKey = DB::table(self::SYSTEMS_TABLE)->where($key, 'LIKE', $value)->get(['id'])->first();

    if (!isset($dbKey->id)) {
      return 0;
    }

    return $dbKey->id;
  }

  public static function getSystemObject(int $id): mixed
  {
    return DB::table(self::SYSTEMS_TABLE)->where(['id' => $id])->get()->first();
  }

  public static function getConditionObject(int $id, string $prefix): mixed
  {
    return DB::table('es_' . $prefix . '_conditions')->where(['id' => $id])->get()->first();
  }

  public static function getProductObject(int $id, string $prefix): mixed
  {
    return DB::table('es_' . $prefix . '_products')->where(['id' => $id])->get()->first();
  }

  public static function getRelationObject(int $id, string $prefix): mixed
  {
    return DB::table('es_' . $prefix . '_relations')->where(['id' => $id])->get()->first();
  }

  public static function insertSystem(System $system): int
  {
    $uuid = Str::uuid();
    $prefix = crc32(time());

    $insertedId = DB::table(self::SYSTEMS_TABLE)->insertGetId([
      'uuid' => $uuid,
      'prefix' => $prefix,
      'type_id' => $system->getTypeId(),
      'name' => $system->getName(),
      'description' => $system->getDescription(),
      'question' => $system->getQuestion(),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    if (0 < $insertedId) {
      Schema::buildForSystem($prefix);
    }

    return $insertedId;
  }

  public static function insertCondition(Condition $condition, string $prefix): int
  {
    return DB::table('es_' . $prefix . '_conditions')->insertGetId([
      'name' => $condition->getName(),
      'simplified_name' => $condition->getSimplifiedName(),
      'description' => $condition->getDescription(),
      'created_at' => $condition->getCreatedAt()
    ]);
  }

  public static function insertProduct(Product $product, string $prefix): int
  {
    return DB::table('es_' . $prefix . '_products')->insertGetId([
      'name' => $product->getName(),
      'simplified_name' => $product->getSimplifiedName(),
      'description' => $product->getDescription(),
      'created_at' => $product->getCreatedAt()
    ]);
  }

  public static function insertRelation(Relation $relation, string $prefix): int
  {
    return DB::table('es_' . $prefix . '_relations')->insertGetId([
      'condition_id' => $relation->getConditionId(),
      'product_id' => $relation->getProductId(),
      'type_id' => $relation->getTypeId()
    ]);
  }

  /**
   * @return object[]
   */
  public static function getSystemsId(): array
  {
    return DB::table(self::SYSTEMS_TABLE)->get()->all();
  }

  /**
   * @return object[]
   */
  public static function getAllConditions(string $prefix): array
  {
    return DB::table('es_' . $prefix . '_conditions')->get(['id'])->all();
  }

  /**
   * @return object[]
   */
  public static function getAllProducts(string $prefix): array
  {
    return DB::table('es_' . $prefix . '_products')->get(['id'])->all();
  }

  /**
   * @return object[]
   */
  public static function getAllRelations(string $prefix): array
  {
    return DB::table('es_' . $prefix . '_relations')->get(['id'])->all();
  }

  public static function getCommon(string $type, System $system, int $limit = 15, int $shift = 0): array
  {
    $prefix = 'es_' . $system->getPrefix() . '_';
    $groupBy = '';

    switch ($type) {
      case 'condition':
      case 'conditions':
        $prefix .= 'conditions';
        $groupBy = 'weight';
        break;

      case 'product':
      case 'products':
        $prefix .= 'products';
        $groupBy = 'weight';
        break;

      case 'relation':
      case 'relations':
        $prefix .= 'relations';
        $groupBy = 'condition_id';
        break;

      default:
        throw new \RuntimeException('Unknown element type in ' . __METHOD__);
    }

    $query = DB::table($prefix)
      ->groupBy($groupBy)
      ->orderByRaw('COUNT(*) DESC')
      ->get(['id'])
      ->skip($shift)
      ->take($limit);
  }

  public static function dropSystem(System $system): bool
  {
    if (!$system->isValid()) {
      return false;
    }

    Schema::removeForSystem($system->getPrefix());

    return DB::table(self::SYSTEMS_TABLE)->where(['id' => $system->getId()])->delete();
  }
}
