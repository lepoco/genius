<?php

namespace App\Common\Database;

use App\Core\Facades\{Config, Request, DB};

/**
 * Populates the database with basic values.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 * @see https://laravel.com/docs/5.0/schema
 * @see https://laravel.com/docs/8.x/queries
 */
final class Prefill
{
  public static function fill(): void
  {
    self::fillOptions();

    // ROLES
    DB::table('user_roles')->insert([
      'name' => 'default',
      'permissions' => '{"p":["read","billing"]}'
    ]);

    DB::table('user_roles')->insert([
      'name' => 'manager',
      'permissions' => '{"p":["read","billing","admin_panel","admin_statistics"]}'
    ]);

    DB::table('user_roles')->insert([
      'name' => 'analyst',
      'permissions' => '{"p":["read","billing","admin_panel","admin_statistics"]}'
    ]);

    DB::table('user_roles')->insert([
      'name' => 'admin',
      'permissions' => '{"p":["all"]}'
    ]);

    // DUMMY USER
    DB::table('users')->insert([
      'name' => 'dummy',
      'display_name' => 'dummy',
      'email' => 'dummy@genius.lepo.co',
      'password' => '$cW4yTWs0djAwbTRjTi40VA$lQcuXoa/0y3FNdjrwOtxaJvxJ+GS2WHxAUC1qbk/EQg',
      'role_id' => 1
    ]);

    // Statistics
    DB::table('statistics_ips')->insert([
      'ip' => ''
    ]);
  }

  private static function fillOptions(): void
  {
    DB::table('options')->insert([
      'name' => 'app_version',
      'value' => Config::get('app.version', '2.0.0')
    ]);

    DB::table('options')->insert([
      'name' => 'app_name',
      'value' => Config::get('app.name', 'Genius')
    ]);

    DB::table('options')->insert([
      'name' => 'site_name',
      'value' => Config::get('app.name', 'Genius')
    ]);

    DB::table('options')->insert([
      'name' => 'language',
      'value' => 'en_us'
    ]);

    DB::table('options')->insert([
      'name' => 'base_url',
      'value' => rtrim(Request::root(), '/') . '/'
    ]);

    DB::table('options')->insert([
      'name' => 'home_url',
      'value' => rtrim(Request::root(), '/') . '/'
    ]);

    DB::table('options')->insert([
      'name' => 'signout_time',
      'value' => 15
    ]);

    DB::table('options')->insert([
      'name' => 'cookie_name',
      'value' => 'pkx_cookie'
    ]);

    DB::table('options')->insert([
      'name' => 'service_worker_enabled',
      'value' => 'false'
    ]);

    DB::table('options')->insert([
      'name' => 'stastistics_keep_ip',
      'value' => 'true'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_sendfrom',
      'value' => 'no-reply@example.com'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_replyto',
      'value' => 'smtp@example.com'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_sendname',
      'value' => 'Website'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_legal',
      'value' => 'Website Corporation, Street Name, Redmond, WA 98000 USA'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_enabled',
      'value' => 'false'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_auth',
      'value' => 'false'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_host',
      'value' => 'smtp.example.com'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_user',
      'value' => 'user@example.com'
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_password',
      'value' => ''
    ]);

    DB::table('options')->insert([
      'name' => 'mail_smtp_port',
      'value' => 465
    ]);
  }
}
