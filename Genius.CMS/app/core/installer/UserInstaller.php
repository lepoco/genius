<?php

namespace App\Core\Installer;

use App\Core\Data\ErrorBag;
use App\Core\Facades\Logs;
use App\Core\Data\Encryption;
use App\Core\Auth\{Account, User, Permission};

/**
 * Automatic user installer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class UserInstaller implements InstallerComponent
{
  private string $email;

  private string $password;

  private ErrorBag $errorBag;

  /**
   * Creates a new user installer instance and initializes internal classes.
   */
  public function __construct()
  {
    $this->errorBag = new ErrorBag();
  }

  /**
   * Prepares local variables.
   */
  public function setup(string $email, string $password): bool
  {
    $this->email = $email;
    $this->password = $password;

    return true;
  }

  /**
   * Tries to create an administrator account.
   */
  public function run(): bool
  {
    try {
      $encryptedPassword = Encryption::hash(
        $this->password,
        'password'
      );
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.user.hash',
        'Error while hashing administrator password.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error while hashing administrator password.', ['exception' => $th]);

      return false;
    }

    try {
      $adminUser = (User::build([
        'display_name' => 'Admin',
        'email' => $this->email,
        'password' => $encryptedPassword,
        'role' => Permission::getRoleId('admin')
      ]))
        ->markAsActive()
        ->markAsConfirmed();
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.user.build',
        'Error while creating user instance.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error while creating user instance.', ['exception' => $th]);

      return false;
    }

    try {
      Account::register($adminUser, $encryptedPassword);

      return true;
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'config.user.build',
        'Error while registering user.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error while registering user.', ['exception' => $th]);

      return false;
    }

    return true;
  }

  /**
   * @inheritDoc
   */
  public function getErrorBag(): ErrorBag
  {
    return $this->errorBag;
  }
}
