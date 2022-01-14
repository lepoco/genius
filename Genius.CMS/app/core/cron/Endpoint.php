<?php

namespace App\Core\Cron;

use App\Core\Facades\Option;
use App\Core\View\Request;
use App\Core\Cron\Cron;
use App\Core\Data\Encryption;
use App\Core\Http\Status;

/**
 * Extends the Request class for CRON endpoint.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Endpoint extends Request
{
  /**
   * Gets the request name.
   */
  public function getAction(): string
  {
    return 'CronEndpoint';
  }

  /**
   * Processes the query.
   */
  public function process(): void
  {
    $this->validateCronKey();

    Cron::run();
  }

  /**
   * Triggers actions and processes the default response.
   */
  public function print(): void
  {
    if (method_exists($this, 'process')) {
      $this->{'process'}();
    } else {
      $this->addContent('error', 'Non-existent action');
      $this->finish(self::ERROR_ACTION_INVALID, Status::BAD_REQUEST);
    }

    $this->finish(self::CODE_SUCCESS);
  }

  /**
   * Checks if the CRON key is valid.
   */
  private function validateCronKey(): void
  {
    $segments = \App\Core\Facades\Request::segments();

    if (!isset($segments[2])) {
      $this->addContent('error', 'Invalid token');
      $this->finish(self::ERROR_PASSWORD_INVALID, Status::BAD_REQUEST);
    }

    $key = strtolower(htmlspecialchars(trim((string)$segments[2])));
    $cronSecret = strtolower(trim(Option::get('cron_secret', Encryption::salter(8, 'LN'))));

    if ($cronSecret != $key) {
      $this->addContent('error', 'Invalid token');
      $this->finish(self::ERROR_PASSWORD_INVALID, Status::BAD_REQUEST);
    }
  }
}
