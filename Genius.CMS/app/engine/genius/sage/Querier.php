<?php

namespace Engine\Genius\Sage;

use Engine\Genius\{System};
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
    return 0;
  }

  public static function getSystemObject(int $id): mixed
  {
    return DB::table(self::SYSTEMS_TABLE)->where(['id' => $id])->get()->first();
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

  /**
   * @return object[]
   */
  public static function getSystemsId(): array
  {
    return DB::table(self::SYSTEMS_TABLE)->get()->all();
  }
}
