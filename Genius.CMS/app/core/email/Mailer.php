<?php

namespace App\Core\Email;

use App\Core\Facades\Config;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Contains necessary logic to send e-mails.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Mailer
{
  private PHPMailer $mailer;

  public function __construct()
  {
    $this->mailer = new PHPMailer(true);
    $this->mailer->SMTPDebug = Config::get('app.debug', true);

    $this
      ->setupSMTP()
      ->setupMailer();
  }

  private function setupSMTP(): self
  {
    return $this;
  }

  private function setupMailer(): self
  {
    return $this;
  }
}
