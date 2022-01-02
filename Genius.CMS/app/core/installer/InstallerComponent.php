<?php

namespace App\Core\Installer;

use App\Core\Data\ErrorBag;

/**
 * Interface for installer components.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface InstallerComponent
{
  /**
   * Prepares the component for installation.
   */
  //public function setup(): bool; // setup can have a variable number of parameters.

  /**
   * Performs component installation process.
   */
  public function run(): bool;

  /**
   * Returns the container with errors.
   */
  public function getErrorBag(): ErrorBag;
}
