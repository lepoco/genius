<?php

namespace App\Core\Factories;

use App\Core\Cron\Endpoint;

/**
 * Cron endpoints factory.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class CronFactory implements \App\Core\Schema\Factory
{
  /**
   * @return \App\Core\Schema\Request
   */
  public static function make(string $property = '')
  {
    return new Endpoint();
  }
}
