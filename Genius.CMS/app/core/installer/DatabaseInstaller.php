<?php

namespace App\Core\Installer;

use PDO;
use PDOException;
use App\Core\Data\ErrorBag;
use App\Core\Facades\{App, Logs};

/**
 * Automatic database installer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class DatabaseInstaller implements InstallerComponent
{
  private string $host;

  private string $user;

  private string $password;

  private string $table;

  private ErrorBag $errorBag;

  /**
   * Creates a new database installer instance and initializes internal classes.
   */
  public function __construct()
  {
    $this->errorBag = new ErrorBag();
  }

  /**
   * Prepares local variables.
   */
  public function setup(string $host, string $user, string $password, string $table): bool
  {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->table = $table;

    return true;
  }

  /**
   * Tries to establish a PDO connection to verify that the entered data is correct.
   */
  public function tryConnect(): bool
  {
    $pdoDSN = 'mysql:host=' . $this->host . ';dbname=' . $this->table;

    try {
      $connection = new PDO($pdoDSN, $this->user, $this->password);
      // set the PDO error mode to exception
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return true;
    } catch (PDOException $e) {
      $this->errorBag->addError(
        'database.connection',
        'Encryption salts could not be created.',
        [
          'throwable_message' => $e->getMessage() ?? 'UNKNOWN',
          'file' => $e->getFile() ?? 'UNKNOWN',
          'code' => $e->getCode() ?? 'UNKNOWN',
          'line' => $e->getLine() ?? 'UNKNOWN',
          'trace' => $e->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error connecting with database.', ['exception' => $e]);

      return false;
    }
  }

  /**
   * Forces a connection to the database and starts the table creation process.
   */
  public function run(): bool
  {
    /** If the data has been entered correctly, a connection to the database will be established. */
    App::connect(true);

    /** After a successful connection, the tables in the database will be created. */
    \App\Common\Database\Schema::build(true);

    /** Fill database with default values. */
    \App\Common\Database\Prefill::fill();

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
