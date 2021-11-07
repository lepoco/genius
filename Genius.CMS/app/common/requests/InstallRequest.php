<?php

namespace App\Common\Requests;

use PDO;
use PDOException;
use App\Core\View\Request;
use App\Core\Http\Status;
use App\Core\Auth\{Account, User};
use App\Core\Facades\{App, Logs, Config};
use App\Core\Data\Encryption;
use App\Core\Utils\{Path, ClassInjector};
use App\Common\Database\{Schema, Prefill};

/**
 * Action triggered during app installation.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class InstallRequest extends Request implements \App\Core\Schema\Request
{
  private string $passwordAlgo = '2y';

  private array $salts = [];

  public function getAction(): string
  {
    return 'Install';
  }

  public function process(): void
  {
    $this->isSet([
      'user',
      'password',
      'host',
      'database',
      'admin_email',
      'admin_password'
    ]);

    $this->isEmpty([
      'user',
      'host',
      'database',
      'admin_email',
      'admin_password'
    ]);

    $this->validate([
      ['user', FILTER_SANITIZE_STRING],
      ['password', FILTER_UNSAFE_RAW],
      ['host', FILTER_SANITIZE_STRING],
      ['database', FILTER_SANITIZE_STRING],
      ['admin_email', FILTER_VALIDATE_EMAIL],
      ['admin_password', FILTER_UNSAFE_RAW]
    ]);

    if (!empty(Config::get('database.connections.default.database', ''))) {
      $this->addContent('message', 'Unauthorized access.');
      $this->finish(self::ERROR_ENTRY_EXISTS, Status::UNAUTHORIZED);

      return;
    }

    if (App::isConnected()) {
      $this->addContent('message', 'Unauthorized access.');
      $this->finish(self::ERROR_ENTRY_EXISTS, Status::UNAUTHORIZED);

      return;
    }

    $this->tryConnectDB();
    $this->injectConfig();
    $this->createDatabases();
    $this->registerAdmin();

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }

  private function tryConnectDB(): void
  {
    $pdoDSN = 'mysql:host=' . $this->getData('host') . ';dbname=' . $this->getData('database');

    try {
      $connection = new PDO($pdoDSN, $this->getData('user'), $this->getData('password'));
      // set the PDO error mode to exception
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      Logs::error('Installation failed - error connecting with database', ['exception' => $e]);

      $this->addContent('fields', ['user', 'password', 'host', 'database']);
      $this->addContent('message', 'Failed to connect to the database.');
      $this->finish(self::ERROR_MYSQL_UNKNOWN, Status::GONE);
    }
  }

  private function injectConfig(): void
  {
    $this->setupSalts();
    $this->setupAlgorithm();

    $injector = new ClassInjector();
    $injector->setPath(Path::getAppPath('common/Config.php'));

    if (!$injector->isValid()) {
      $this->addContent('message', 'Incorrect application configuration. Configuration data injection failed.');
      $this->finish(self::ERROR_INTERNAL_ERROR, Status::UNPROCESSABLE_ENTITY);
    }

    $injector->inject('ENCRYPTION_ALGO', $this->passwordAlgo, 'const');

    $injector->inject('SALT_SESSION', $this->salts['session'], 'const');
    $injector->inject('SALT_COOKIE', $this->salts['cookie'], 'const');
    $injector->inject('SALT_PASSWORD', $this->salts['password'], 'const');
    $injector->inject('SALT_NONCE', $this->salts['nonce'], 'const');
    $injector->inject('SALT_TOKEN', $this->salts['token'], 'const');
    $injector->inject('SALT_WEBAUTH', $this->salts['webauth'], 'const');

    $injector->inject('DATABASE_NAME', $this->getData('database'), 'const');
    $injector->inject('DATABASE_USER', $this->getData('user'), 'const');
    $injector->inject('DATABASE_PASS', $this->getData('password'), 'const');
    $injector->inject('DATABASE_HOST', $this->getData('host'), 'const');

    $injector->save();

    Config::set('database.connections.default.host', $this->getData('host'));
    Config::set('database.connections.default.database', $this->getData('database'));
    Config::set('database.connections.default.username', $this->getData('user'));
    Config::set('database.connections.default.password', $this->getData('password'));

    Config::set('salts.session', $this->salts['session']);
    Config::set('salts.cookie', $this->salts['cookie']);
    Config::set('salts.password', $this->salts['password']);
    Config::set('salts.nonce', $this->salts['nonce']);
    Config::set('salts.token', $this->salts['token']);
    Config::set('salts.webauth', $this->salts['webauth']);

    App::rebind('config');
  }

  private function setupSalts(): array
  {
    $this->salts = [
      'session' => Encryption::salter(64),
      'cookie' => Encryption::salter(64),
      'password' => Encryption::salter(64),
      'nonce' => Encryption::salter(64),
      'token' => Encryption::salter(64),
      'webauth' => Encryption::salter(64)
    ];

    return $this->salts;
  }

  private function setupAlgorithm(): string
  {
    /** Password hash type */
    if (defined('PASSWORD_ARGON2ID')) {
      $this->passwordAlgo = PASSWORD_ARGON2ID;
    } elseif (defined('PASSWORD_ARGON2I')) {
      $this->passwordAlgo = PASSWORD_ARGON2I;
    } elseif (defined('PASSWORD_BCRYPT')) {
      $this->passwordAlgo = PASSWORD_BCRYPT;
    } elseif (defined('PASSWORD_DEFAULT')) {
      $this->passwordAlgo = PASSWORD_DEFAULT;
    }

    return $this->passwordAlgo;
  }

  private function createDatabases(): void
  {
    /** If the data has been entered correctly, a connection to the database will be established. */
    App::connect(true);
    /** After a successful connection, the tables in the database will be created. */
    Schema::build(true);
    /** Fill database with default values. */
    Prefill::fill();
  }

  private function registerAdmin(): void
  {
    $encryptedPassword = Encryption::encrypt(
      $this->getData('admin_password'),
      'password',
      $this->salts['password'],
      $this->passwordAlgo
    );

    $adminUser = (User::build([
      'display_name' => 'Admin',
      'email' => $this->getData('admin_email'),
      'password' => $encryptedPassword,
      'role' => Account::getRoleId('admin')
    ]))
    ->markAsActive()
    ->markAsConfirmed();

    Account::register($adminUser, $encryptedPassword);
  }
}
