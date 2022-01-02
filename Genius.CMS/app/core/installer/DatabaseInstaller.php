<?php

namespace App\Core\Installer;

use PDO;
use PDOException;
use App\Core\Data\ErrorBag;
use App\Core\Facades\{App, Config, Logs};

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
        'Error connecting with database.',
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
    try {
      Config::set('database.connections.default.options', [
        \PDO::ATTR_EMULATE_PREPARES => true,
        \PDO::MYSQL_ATTR_COMPRESS => true
      ]);

      App::rebind('config');
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'database.reconnection',
        'Error occurred while changing additional database options.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error occurred while changing additional database options.', ['exception' => $th]);
    }

    /** If the data has been entered correctly, a connection to the database will be established. */
    try {
      App::connect(true);
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'database.reconnection',
        'Error reconnecting to the database.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error reconnecting to the database.', ['exception' => $th]);

      return false;
    }

    /** After a successful connection, the tables in the database will be created. */
    try {
      \App\Common\Database\Schema::build(true);
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'database.schema',
        'Error creating database tables.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error creating database tables.', ['exception' => $th]);

      return false;
    }

    /** Fill database with default values. */
    try {
      \App\Common\Database\Prefill::fill();
    } catch (\Throwable $th) {
      $this->errorBag->addError(
        'database.fill',
        'Error filling tables with basic data.',
        [
          'throwable_message' => $th->getMessage() ?? 'UNKNOWN',
          'file' => $th->getFile() ?? 'UNKNOWN',
          'code' => $th->getCode() ?? 'UNKNOWN',
          'line' => $th->getLine() ?? 'UNKNOWN',
          'trace' => $th->getTrace() ?? 'UNKNOWN'
        ]
      );

      Logs::error('Installation failed - error filling tables with basic data.', ['exception' => $th]);

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
