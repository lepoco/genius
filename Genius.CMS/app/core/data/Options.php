<?php

namespace App\Core\Data;

use App\Core\Facades\{App, DB, Cache};

/**
 * Allows to retrieve and save options from the database, stored in memory using the Cache.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Options
{
  public function remember(string $name, \Closure $callback): mixed
  {
    if (Cache::has('options.' . $name)) {
      return Cache::get('options.' . $name, 'CACHE_ERROR');
    }

    $db = $this->getFromDatabase($name, $callback());

    Cache::put('options.' . $name, $db);

    return $db;
  }

  public function get(string $name, mixed $default = null): mixed
  {
    if (Cache::has('options.' . $name)) {
      return Cache::get('options.' . $name, 'CACHE_ERROR');
    }

    $db = $this->getFromDatabase($name, $default);

    // TODO: This is where we may have problems, we can do FORCE REFRESH or something
    Cache::put('options.' . $name, $db);

    return $db;
  }

  public function set(string $name, $value): bool
  {
    Cache::put('options.' . $name, $value);

    return $this->setInDatabase($name, $value);
  }

  private function getFromDatabase(string $key, mixed $default): mixed
  {
    if (!App::isConnected()) {
      return $default;
    }

    $query = DB::table('options')->where('name', $key)->first();

    if (isset($query->value)) {
      return self::decodeType($query->value);
    }

    return $default;
  }

  private function setInDatabase(string $key, mixed $value): bool
  {
    if (!App::isConnected()) {
      return false;
    }

    $query = DB::table('options')->where('name', $key)->first();

    if (isset($query->value)) {
      return DB::table('options')->where('name', $key)->update([
        'value' => self::encodeType($value),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }

    return DB::table('options')->insert([
      'name' => $key,
      'value' => self::encodeType($value),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
  }

  private static function decodeType(mixed $value): mixed
  {
    if ('true' === $value || true === $value) {
      return true;
    }

    if ('false' === $value || false === $value) {
      return false;
    }

    if (is_int($value)) {
      return (int) $value;
    }

    if (is_float($value)) {
      return (float) $value;
    }

    if (ctype_digit($value)) {
      return (int) $value;
    }

    return $value;
  }

  private static function encodeType(mixed $value): mixed
  {
    if ('true' === $value || true === $value) {
      return 'true';
    }

    if ('false' === $value || false === $value) {
      return 'false';
    }

    return $value;
  }
}
