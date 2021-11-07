<?php

namespace App\Core\Facades;

use App\Core\Facades\Abstract\Facade;

/**
 * Contains necessary logic to send e-mails.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 *
 * @method static bool send(array|string $recipients, array $options = []) Send the message to the selected recipient.
 */
final class Email extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor(): string
  {
    return 'mailer';
  }
}
