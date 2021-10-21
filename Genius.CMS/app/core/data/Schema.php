<?php

namespace App\Core\Data;

use App\Core\Facades\{Config, Request, DB};
use Illuminate\Database\Schema\Blueprint;

/**
 * Builds a database
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
    self::tableWallets();
    self::tableStatistics();

    self::fill();
  }

  private static function fill(): void
  {
    DB::table('options')->insert([
      'name' => 'app_version',
      'value' => Config::get('app.version', '1.0.0')
    ]);

    DB::table('options')->insert([
      'name' => 'app_name',
      'value' => Config::get('app.name', '1.0.0')
    ]);

    DB::table('options')->insert([
      'name' => 'base_url',
      'value' => rtrim(Request::root(), '/') . '/'
    ]);

    DB::table('options')->insert([
      'name' => 'home_url',
      'value' => rtrim(Request::root(), '/') . '/'
    ]);

    DB::table('plans')->insert([
      'name' => 'trader',
      'capabilities' => '{c:[]}'
    ]);

    DB::table('user_roles')->insert([
      'name' => 'default',
      'permissions' => '{p:[]}'
    ]);

    DB::table('user_roles')->insert([
      'name' => 'manager',
      'permissions' => '{p:[]}'
    ]);

    DB::table('statistics_types')->insert([
      'name' => 'page'
    ]);

    DB::table('statistics_types')->insert([
      'name' => 'request'
    ]);

    DB::table('statistics_types')->insert([
      'name' => 'transaction'
    ]);

    DB::table('statistics_types')->insert([
      'name' => 'user'
    ]);

    DB::table('plans')->insert([
      'name' => 'standard',
      'capabilities' => '{c:[]}'
    ]);

    DB::table('plans')->insert([
      'name' => 'plus',
      'capabilities' => '{c:[]}'
    ]);

    DB::table('plans')->insert([
      'name' => 'premium',
      'capabilities' => '{c:[]}'
    ]);

    DB::table('plans')->insert([
      'name' => 'trader',
      'capabilities' => '{c:[]}'
    ]);

    DB::table('users')->insert([
      'name' => 'dummy',
      'display_name' => 'dummy',
      'email' => 'dummy@genius.pl',
      'password' => '$cW4yTWs0djAwbTRjTi40VA$lQcuXoa/0y3FNdjrwOtxaJvxJ+GS2WHxAUC1qbk/EQg',
      'role_id' => 1
    ]);

    DB::table('currencies')->insert([
      'rate' => 1,
      'iso_number' => 840,
      'iso_code' => 'USD',
      'name' => 'US Dollar',
      'sign' => '$',
      'decimal_sign' => '¢',
      'decimal_name' => 'cent',
      'is_crypto' => false,
      'is_master' => true,
    ]);

    DB::table('currencies')->insert([
      'rate' => 0.86572246,
      'iso_number' => 978,
      'iso_code' => 'EUR',
      'name' => 'Euro',
      'sign' => '€',
      'decimal_sign' => 'c',
      'decimal_name' => 'cent',
      'is_crypto' => false,
      'is_master' => false,
    ]);

    DB::table('currencies')->insert([
      'rate' => 0.73666607,
      'iso_number' => 826,
      'iso_code' => 'GBP',
      'name' => 'Pound sterling',
      'sign' => '£',
      'decimal_sign' => 'p',
      'decimal_name' => 'pence',
      'is_crypto' => false,
      'is_master' => false,
    ]);
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

    DB::schema()->dropIfExists('transactions');
    DB::schema()->dropIfExists('wallets');
    DB::schema()->dropIfExists('currencies');

    DB::schema()->dropIfExists('user_billings');
    DB::schema()->dropIfExists('user_newsletters');
    DB::schema()->dropIfExists('user_plans');

    DB::schema()->dropIfExists('users');

    DB::schema()->dropIfExists('user_roles');
    DB::schema()->dropIfExists('plans');
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

    if (!DB::schema()->hasTable('plans')) {
      DB::schema()->create('plans', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('capabilities');
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
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
        $table->string('timezone')->default('UTC');
        $table->timestamp('time_last_login')->nullable();
        $table->boolean('is_active')->default(false);
        $table->boolean('is_confirmed')->default(false);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('user_plans')) {
      DB::schema()->create('user_plans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->foreignId('plan_id')->references('id')->on('plans');
        $table->timestamp('expiers_at')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('user_billings')) {
      DB::schema()->create('user_billings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('street')->nullable();
        $table->string('postal')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->nullable();
        $table->string('province')->nullable();
        $table->text('phone')->nullable();
        $table->text('email')->nullable();
        $table->text('timezone')->nullable();
        $table->timestamp('created_at')->useCurrent();
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

  private static function tableWallets(): void
  {
    // https://en.wikipedia.org/wiki/ISO_4217
    if (!DB::schema()->hasTable('currencies')) {
      DB::schema()->create('currencies', function (Blueprint $table) {
        $table->id();
        $table->float('rate', 12, 8, false)->default(1);
        $table->integer('iso_number')->nullable();
        $table->string('iso_code')->nullable();
        $table->string('sign')->nullable();
        $table->string('name')->nullable();
        $table->string('decimal_sign')->nullable();
        $table->string('decimal_name')->nullable();
        $table->boolean('is_crypto')->default(false);
        $table->boolean('is_master')->default(false);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('wallets')) {
      DB::schema()->create('wallets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->foreignId('currency_id')->references('id')->on('currencies')->default(1);
        $table->float('virtual_balance')->default(0);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }

    if (!DB::schema()->hasTable('transactions')) {
      DB::schema()->create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->references('id')->on('users');
        $table->foreignId('wallet_from')->references('id')->on('wallets');
        $table->foreignId('wallet_to')->references('id')->on('wallets');
        $table->float('amount')->default(0);
        $table->string('uuid')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }
  }

  private static function tableStatistics(): void
  {
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
        $table->string('ip')->nullable();
        $table->text('ua')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->nullable()->useCurrent();
      });
    }
  }
}
