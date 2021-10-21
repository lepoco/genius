<?php

namespace App\Common;

use App\Core\Bootstrap;
use App\Common\Routes;
use App\Core\Data\Config;
use App\Core\Utils\Path;

/**
 * Main class of the application. Contains all logic.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class App extends Bootstrap implements \App\Core\Schema\App
{
  /**
   * Method triggered in setup.
   */
  public function init(): void
  {
    $this
      ->setRouter(new Routes())
      ->setConfig(
        new Config([
          'app' => [
            'name' => 'Genius',
            'version' => '1.1.0',
            'log_level' => 'debug',
            'debug' => true
          ],
          'database' => [
            'default' => 'default',
            'connections' => [
              'default' => [
                'driver' => 'mysql',
                'host' => \App\Common\Config::DATABASE_HOST,
                'port' => '3306',
                'database' => \App\Common\Config::DATABASE_NAME,
                'username' => \App\Common\Config::DATABASE_USER,
                'password' => \App\Common\Config::DATABASE_PASS,
                'collation' => 'utf8_general_ci',
                'charset' => 'utf8',
                'strict' => false,
              ]
            ]
          ],
          'encryption' => [
            'algorithm' => \App\Common\Config::ENCRYPTION_ALGO
          ],
          'salts' => [
            'session' => \App\Common\Config::SALT_SESSION,
            'cookie' => \App\Common\Config::SALT_COOKIE,
            'password' => \App\Common\Config::SALT_PASSWORD,
            'nonce' => \App\Common\Config::SALT_NONCE,
            'token' => \App\Common\Config::SALT_TOKEN,
            'webauth' => \App\Common\Config::SALT_WEBAUTH
          ],
          'storage' => [
            'logs' => Path::getAppPath('storage/logs'),
            'uploads' => Path::getAbsolutePath('public/assets/uploads')
          ],
          'logging' => [
            'default' => 'daily',
            'channels' => [
              'daily' => [
                'name' => 'GENIUS',
                'driver' => 'daily',
                'path' => Path::getAppPath('storage/logs/daily.log'),
                'level' => 'debug',
                'days' => 31
              ]
            ]
          ],
          'session' => [
            'driver' => 'cookie',
            'path' => '/',
            'cookie' => 'pkx_session',
            'lifetime' => 60,
            'same_site' => 'Lax',
            'encrypt' => false,
            'expire_on_close' => false,
            'secure' => true
          ],
          'view' => [
            'paths' => [Path::getAppPath('common/views')],
            'emails' => [Path::getAppPath('common/views/emails')],
            'compiled' => Path::getAppPath('storage/blade'),
            'composers' => Path::getAppPath('common/composers')
          ],
          'cache' => [
            'default' => 'file',
            'stores' => [
              'file' => [
                'driver' => 'file',
                'path' => Path::getAppPath('storage/cache'),
              ]
            ]
          ]
        ])
      );
  }
}
