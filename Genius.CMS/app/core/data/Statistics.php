<?php

namespace App\Core\Data;

use App\Core\Facades\{DB, Request, Cache, Option};
use App\Core\Auth\Account;

/**
 * Allows to save and read statistical data from the database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Statistics
{
  public const TYPE_PAGE = 'page';

  public const TYPE_REQUEST = 'request';

  public const TYPE_TRANSACTION = 'transaction';

  public const TYPE_USER = 'user';

  private bool $opened = false;

  private array $tags = [];

  private array $types = [];

  public function __construct(bool $opened = false)
  {
    $this->opened = $opened;

    if (!$this->opened) {
      return;
    }

    // TODO: FIX CACHE
    $this->fetchTags(DB::table('statistics_tags')->get()->all() ?? []);
    $this->fetchTypes(DB::table('statistics_types')->get()->all() ?? []);
  }

  /**
   * Writes a new record to the database.
   */
  public function push(string $type, string $tag, string $ua = null): bool
  {
    if (!$this->opened) {
      return false;
    }

    $user = Account::current();

    return (bool) DB::table('statistics')->insert([
      'statistic_tag' => $this->getTagId($tag),
      'statistic_type' => $this->getTypeId($type),
      'ua' => $ua,
      'user_id' => !empty($user) ? $user->getId() : null,
      'ip_id' => Option::get('stastistics_keep_ip', true) ? $this->getIpId(Request::ip()) : 1,
      'created_at' => date('Y-m-d H:i:s')
    ]);
  }

  /**
   * Gets selected type of statistics type from the database and limits them in time.
   */
  public function get(string $type, string $when): array
  {
    $typeId = $this->getTypeId($type, true);

    if (0 === $typeId) {
      return [];
    }

    $dbResults = DB::table('statistics')->where(['statistic_type' => $typeId]);

    switch ($when) {
      case 'today':
        $dbResults->whereDate('created_at', '>=', date('Y-m-d'));
        break;

      case 'lastHour':
        $dbResults->whereDate('created_at', '>=', date('Y-m-d'))->whereTime('created_at', '>=', date('H:i:s', time() - (60 * 60)));
        break;
    }

    return $dbResults->get()->all();
  }

  /**
   * Gets information about whether statistics are currently being saved.
   */
  public function isOpened(): bool
  {
    return $this->opened;
  }

  public function getIpId(string $ip): int
  {
    return Cache::forever('statistics.ip.' . $ip, function () use ($ip) {
      $dbKey = DB::table('statistics_ips')->where(['ip' => $ip])->get(['id'])->first();

      if (isset($dbKey->id)) {
        return $dbKey->id;
      }

      $insertedId = DB::table('statistics_ips')->insertGetId([
        'ip' => $ip
      ]);

      return $insertedId;
    });
  }

  public function getTypeId(string $type, bool $nullCheck = false): int
  {
    foreach ($this->types as $singleType) {
      if ($singleType['name'] === $type) {
        return $singleType['id'];
      }
    }

    if ($nullCheck) {
      return 0;
    }

    $insertedId = Cache::forever('statistics.type_id.' . $type, function () use ($type) {
      return DB::table('statistics_types')->insertGetId([
        'name' => $type
      ]);
    });

    $this->types[] = [
      'id' => $insertedId,
      'name' => $type
    ];

    return $insertedId;
  }

  public function getTagId(string $tag, bool $nullCheck = false): int
  {
    foreach ($this->tags as $singleTag) {
      if ($singleTag['name'] === $tag) {
        return $singleTag['id'];
      }
    }

    if ($nullCheck) {
      return 0;
    }

    $insertedId = Cache::forever('statistics.tag_id.' . $tag, function () use ($tag) {
      return DB::table('statistics_tags')->insertGetId([
        'name' => $tag
      ]);
    });

    $this->tags[] = [
      'id' => $insertedId,
      'name' => $tag
    ];

    return $insertedId;
  }

  private function fetchTags(array $tags = []): self
  {
    foreach ($tags as $tag) {
      if (isset($tag->id) && isset($tag->name)) {
        $this->tags[] = [
          'id' => $tag->id,
          'name' => $tag->name
        ];
      }
    }

    return $this;
  }

  private function fetchTypes(array $types = []): self
  {
    foreach ($types as $type) {
      if (isset($type->id) && isset($type->name)) {
        $this->types[] = [
          'id' => $type->id,
          'name' => $type->name
        ];
      }
    }

    return $this;
  }
}
