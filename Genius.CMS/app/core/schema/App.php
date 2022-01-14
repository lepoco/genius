<?php

namespace App\Core\Schema;

/**
 * Base interface for App.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface App
{
  public function init(): void;
}
