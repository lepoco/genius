<?php

namespace App\Core\Installer;

use App\Core\Data\{Encryption, ErrorBag};
use App\Core\Utils\{Path, ClassInjector};
use App\Core\Facades\{App, Logs, Config};

/**
 * Automatic config file installer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ConfigInstaller implements InstallerComponent
{
  private array $salts = [];

  private string $passwordAlgorithm = '2y';

  private string $host;

  private string $user;

  private string $password;

  private string $table;

  private ErrorBag $errorBag;

  private ClassInjector $injector;

  /**
   * Creates a new config installer instance and initializes internal classes.
   */
  public function __construct()
  {
    $this->errorBag = new ErrorBag();
  }

  /**
   * Assigns class variables and creates application salts.
   */
  public function setup(string $host, string $user, string $password, string $table): bool
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->table = $table;

    $this->setupSalts();
    $this->setupAlgorithm();

    return $this->errorBag->hasErrors();
  }

  /**
   * Updates the application settings stored in memory.
   */
  public function update(): bool
  {
    try {
      Config::set('database.connections.default.host', $this->host);
      Config::set('database.connections.default.database', $this->table);
      Config::set('database.connections.default.username', $this->user);
      Config::set('database.connections.default.password', $this->password);

      Config::set('encryption.algorithm', $this->passwordAlgorithm);

      Config::set('salts.session', $this->salts['session']);
      Config::set('salts.cookie', $this->salts['cookie']);
      Config::set('salts.password', $this->salts['password']);
      Config::set('salts.nonce', $this->salts['nonce']);
      Config::set('salts.token', $this->salts['token']);
      Config::set('salts.webauth', $this->salts['webauth']);
      Config::set('salts.passphrase', $this->salts['passphrase']);
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.update',
        'Application settings failed to update.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error when updating application config.', ['exception' => $th]);
    }

    try {
      App::rebind('config');

      return true;
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.update',
        'Application failed to rebind config.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error when rebinding application config.', ['exception' => $th]);

      return false;
    }
  }

  /**
   * Updates the Config.php file.
   */
  public function run(): bool
  {
    $this->injector = new ClassInjector();
    $this->injector->setPath(Path::getAppPath('common/Config.php'));

    if (!$this->injector->isValid()) {
      $this->errorBag->addError('config.path.injector', 'Incorrect application configuration. Configuration data injection failed.');

      return false;
    }

    try {
      $this->injector->inject('ENCRYPTION_ALGO', $this->passwordAlgorithm, 'const');

      $this->injector->inject('SALT_SESSION', $this->salts['session'], 'const');
      $this->injector->inject('SALT_COOKIE', $this->salts['cookie'], 'const');
      $this->injector->inject('SALT_PASSWORD', $this->salts['password'], 'const');
      $this->injector->inject('SALT_NONCE', $this->salts['nonce'], 'const');
      $this->injector->inject('SALT_TOKEN', $this->salts['token'], 'const');
      $this->injector->inject('SALT_WEBAUTH', $this->salts['webauth'], 'const');
      $this->injector->inject('SALT_PASSPHRASE', $this->salts['passphrase'], 'const');

      $this->injector->inject('DATABASE_NAME', $this->table, 'const');
      $this->injector->inject('DATABASE_USER', $this->user, 'const');
      $this->injector->inject('DATABASE_PASS', $this->password, 'const');
      $this->injector->inject('DATABASE_HOST', $this->host, 'const');

      $this->injector->save();
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.inject',
        'Error updating config file.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error updating config file.', ['exception' => $th]);

      return false;
    }

    return $this->update();
  }

  /**
   * @inheritDoc
   */
  public function getErrorBag(): ErrorBag
  {
    return $this->errorBag;
  }

  /**
   * Creates random salts.
   */
  private function setupSalts(): bool
  {
    try {
      $this->salts = [
        'session' => Encryption::salter(64),
        'cookie' => Encryption::salter(64),
        'password' => Encryption::salter(64),
        'nonce' => Encryption::salter(64),
        'token' => Encryption::salter(64),
        'webauth' => Encryption::salter(64),
        'passphrase' => Encryption::salter(64)
      ];

      return true;
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.salts.setup',
        'Encryption salts could not be created.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error when creating salts.', ['exception' => $th]);

      return false;
    }
  }

  /**
   * Selects which algorithm will be used to encrypt passwords and tokens.
   */
  private function setupAlgorithm(): string
  {
    try {
      if (defined('PASSWORD_ARGON2ID')) {
        $this->passwordAlgorithm = PASSWORD_ARGON2ID;
      } elseif (defined('PASSWORD_ARGON2I')) {
        $this->passwordAlgorithm = PASSWORD_ARGON2I;
      } elseif (defined('PASSWORD_BCRYPT')) {
        $this->passwordAlgorithm = PASSWORD_BCRYPT;
      } elseif (defined('PASSWORD_DEFAULT')) {
        $this->passwordAlgorithm = PASSWORD_DEFAULT;
      } else {
        $this->errorBag->addError('config.algorithm.select', 'There is no known password hashing method.');

        return false;
      }

      return true;
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.algorithm.setup',
        'Password hash algorithm could not be selected.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error when creating algorithm.', ['exception' => $th]);

      return false;
    }
  }
}
