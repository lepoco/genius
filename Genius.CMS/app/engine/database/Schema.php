<?php

namespace App\Common\Database;

use App\Core\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

/**
 * Builds a ES database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   2.0.0
 * @see https://laravel.com/docs/5.0/schema
 * @see https://laravel.com/docs/8.x/queries
 */
final class Schema
{
  public static function buildForSystem(string $prefix): void
  {
    // TODO: Build tables for new system
    // conditions relations result
  }

  public static function build(bool $dropIfExists = false): void
  {
    if ($dropIfExists) {
      self::drop();
    }

    self::tableSystems();
  }

  public static function isBuilt(): bool
  {
    return DB::schema()->hasTable('es_systems');
  }

  private static function drop(): void
  {
    /**
     * Drop must be in the correct order regarding the foreign keys.
     */
    DB::schema()->dropIfExists('es_systems');
    DB::schema()->dropIfExists('es_system_types');
  }

  private static function tableSystems(): void
  {
    if (!DB::schema()->hasTable('es_system_types')) {
      DB::schema()->create('es_system_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
      });

      DB::table('es_system_types')->insert([
        'name' => 'relational'
      ]);

      DB::table('es_system_types')->insert([
        'name' => 'fuzzy'
      ]);
    }

    if (!DB::schema()->hasTable('es_systems')) {
      DB::schema()->create('es_systems', function (Blueprint $table) {
        $table->id();
        $table->string('uuid');
        $table->string('name');
        $table->string('prefix')->nullable();
        $table->longText('description')->nullable();
        $table->string('question')->nullable();
        $table->foreignId('type_id')->nullable()->references('id')->on('es_system_types')->default(1);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }
  }
}
