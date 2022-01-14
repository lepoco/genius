<?php

namespace App\Common\Database;

use App\Core\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

/**
 * Builds a database.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 * @see https://laravel.com/docs/5.0/schema
 * @see https://laravel.com/docs/8.x/queries
 */
final class Schema
{
  public static function build(bool $dropIfExists = false): void
  {
    if ($dropIfExists) {
      self::drop();
    }

    self::tableOptions();
    self::tableUsers();
    self::tableStatistics();
  }

  private static function drop(): void
  {
    /**
     * Drop must be in the correct order regarding the foreign keys.
     */
    DB::schema()->dropIfExists('options');

    DB::schema()->dropIfExists('statistics');
    DB::schema()->dropIfExists('statistics_tags');
    DB::schema()->dropIfExists('statistics_types');
    DB::schema()->dropIfExists('statistics_ips');

    DB::schema()->dropIfExists('user_newsletters');
    DB::schema()->dropIfExists('user_confirmations');

    DB::schema()->dropIfExists('users');

    DB::schema()->dropIfExists('user_roles');
    DB::schema()->dropIfExists('confirmation_types');
  }

  private static function tableOptions(): void
  {
    if (!DB::schema()->hasTable('options')) {
      DB::schema()->create('options', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->longText('value')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }
  }

  private static function tableUsers(): void
  {
    if (!DB::schema()->hasTable('user_roles')) {
      DB::schema()->create('user_roles', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('permissions');
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('confirmation_types')) {
      DB::schema()->create('confirmation_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamp('created_at')->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('users')) {
      DB::schema()->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->string('name');
        $table->string('display_name')->nullable();
        $table->string('uuid')->nullable();
        $table->text('image')->nullable();
        $table->text('password')->nullable();
        $table->text('session_token')->nullable();
        $table->text('cookie_token')->nullable();
        $table->foreignId('role_id')->references('id')->on('user_roles');
        $table->string('language')->default('en_US');
        $table->string('timezone')->nullable()->default('UTC');
        $table->timestamp('time_last_login')->nullable();
        $table->boolean('is_active')->default(false);
        $table->boolean('is_confirmed')->default(false);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('user_confirmations')) {
      DB::schema()->create('user_confirmations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->foreignId('confirmation_id')->references('id')->on('confirmation_types');
        $table->text('token')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->boolean('is_confirmed')->default(false);
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('user_newsletters')) {
      DB::schema()->create('user_newsletters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->string('unsubscribe_token');
        $table->boolean('active')->default(false);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }
  }

  private static function tableStatistics(): void
  {
    if (!DB::schema()->hasTable('statistics_ips')) {
      DB::schema()->create('statistics_ips', function (Blueprint $table) {
        $table->id();
        $table->string('ip')->nullable();
      });
    }

    if (!DB::schema()->hasTable('statistics_tags')) {
      DB::schema()->create('statistics_tags', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
      });
    }

    if (!DB::schema()->hasTable('statistics_types')) {
      DB::schema()->create('statistics_types', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable();
      });
    }

    if (!DB::schema()->hasTable('statistics')) {
      DB::schema()->create('statistics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('statistic_tag')->references('id')->on('statistics_tags');
        $table->foreignId('statistic_type')->references('id')->on('statistics_types');
        $table->foreignId('user_id')->nullable()->references('id')->on('users');
        $table->foreignId('ip_id')->nullable()->references('id')->on('statistics_ips');
        $table->text('ua')->nullable();
        $table->timestamp('created_at')->useCurrent();
      });
    }
  }
}
