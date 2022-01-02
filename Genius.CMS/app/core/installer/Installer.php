<?php

namespace App\Core\Installer;

use App\Core\Data\ErrorBag;
use App\Core\Facades\Logs;

/**
 * Automatic directory and database installer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Installer
{
  private ErrorBag $errorBag;

  private ConfigInstaller $configInstaller;

  private DatabaseInstaller $databaseInstaller;

  private UserInstaller $userInstaller;

  private array $installerData = [];

  /**
   * Creates a new installer instance and initializes internal classes.
   */
  public function __construct()
  {
    $this->errorBag = new ErrorBag();

    $this->configInstaller = new ConfigInstaller();
    $this->databaseInstaller = new DatabaseInstaller();
    $this->userInstaller = new UserInstaller();
  }

  /**
   * Goes through the entire installation process.
   */
  public function run(): bool
  {
    if (!$this->verify()) {
      $this->mergeErrorBags();

      return false;
    }

    if (!$this->setup()) {
      $this->mergeErrorBags();

      return false;
    }

    // First, we update local settings on the fly.
    if (!$this->configInstaller->update()) {
      $this->mergeErrorBags();

      return false;
    }

    // We are trying to connect to the database
    if (!$this->databaseInstaller->tryConnect()) {
      $this->mergeErrorBags();

      return false;
    }

    // We make an actual connection to the database and try to fill it with data.
    if (!$this->databaseInstaller->run()) {
      $this->mergeErrorBags();

      return false;
    }

    // We register the user
    if (!$this->userInstaller->run()) {
      $this->mergeErrorBags();

      return false;
    }

    // Finally, we save the settings to a file.
    if (!$this->configInstaller->run()) {
      $this->mergeErrorBags();

      return false;
    }

    $this->mergeErrorBags();

    return !$this->errorBag->hasErrors();
  }

  /**
   * Adds data to the information pool.
   */
  public function addData(string $key, mixed $data): void
  {
    $this->installerData[$key] = $data;
  }

  /**
   * Checks if information about the given key is in the pool.
   */
  public function hasData(string $key): bool
  {
    return isset($this->installerData[$key]);
  }

  /**
   * Gets information with the specified key from the pool.
   */
  public function getData(string $key): mixed
  {
    if (!$this->hasData($key)) {
      return null;
    }

    return $this->installerData[$key];
  }

  /**
   * Returns information whether errors occurs during installation.
   */
  public function hasErrors(): bool
  {
    return $this->errorBag->hasErrors();
  }

  /**
   * Gets an array with errors.
   */
  public function getErrors(): array
  {
    return $this->errorBag->getErrors();
  }

  /**
   * Concatenates bug packages from components.
   */
  private function mergeErrorBags(): bool
  {
    $this->errorBag->merge($this->configInstaller->getErrorBag());
    $this->errorBag->merge($this->databaseInstaller->getErrorBag());
    $this->errorBag->merge($this->userInstaller->getErrorBag());

    return true;
  }

  /**
   * Prepares components for installation.
   */
  private function setup(): bool
  {
    $this->configInstaller->setup(
      $this->getData('database.host'),
      $this->getData('database.user'),
      $this->getData('database.password'),
      $this->getData('database.table')
    );

    $this->databaseInstaller->setup(
      $this->getData('database.host'),
      $this->getData('database.user'),
      $this->getData('database.password'),
      $this->getData('database.table')
    );

    $this->userInstaller->setup(
      $this->getData('user.email'),
      $this->getData('user.password')
    );

    return true;
  }

  /**
   * Verifies if the system meets the installer requirements.
   */
  private function verify(): bool
  {
    if (!defined('PHP_MAJOR_VERSION') || PHP_MAJOR_VERSION < 8) {
      $this->errorBag->addError('installer.class.encryption', 'PHP ' . phpversion() . ' version is unsupported. The minimum version is 8.0');
    }

    if (!extension_loaded('openssl')) {
      $this->errorBag->addError('installer.extension.openssl', 'Extension OpenSSL has not been installed.');
    }

    if (!class_exists('\\App\\Core\\Data\\Encryption')) {
      $this->errorBag->addError('installer.class.encryption', 'Class \\App\\Core\\Data\\Encryption was not found or does not exist.');
    }

    if (!class_exists('\\App\\Common\\Config')) {
      $this->errorBag->addError('installer.class.config', 'Class \\App\\Common\\Config was not found or does not exist.');
    }

    if (!class_exists('\\App\\Common\\Database\\Prefill')) {
      $this->errorBag->addError('installer.database.prefill', 'Class \\App\\Common\\Database\\Prefill was not found or does not exist.');
    }

    if (!class_exists('\\App\\Common\\Database\\Schema')) {
      $this->errorBag->addError('installer.database.schema', 'Class \\App\\Common\\Database\\Schema was not found or does not exist.');
    }

    if (!$this->hasData('database.host')) {
      $this->errorBag->addError('installer.data.databaseHost', 'The information about database.host was not defined.');
    }

    if (!$this->hasData('database.user')) {
      $this->errorBag->addError('installer.data.databaseUser', 'The information about database.user was not defined.');
    }

    if (!$this->hasData('database.password')) {
      $this->errorBag->addError('installer.data.databasePassword', 'The information about database.password was not defined.');
    }

    if (!$this->hasData('database.table')) {
      $this->errorBag->addError('installer.data.databaseTable', 'The information about database.table was not defined.');
    }

    if (!$this->hasData('user.email')) {
      $this->errorBag->addError('installer.data.userEmail', 'The information about user.email was not defined.');
    }

    if (!$this->hasData('user.password')) {
      $this->errorBag->addError('installer.data.userPassword', 'The information about user.password was not defined.');
    }

    return !$this->hasErrors();
  }
}
