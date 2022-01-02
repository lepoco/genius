<?php

namespace App\Core\Email;

use App\Core\Email\Template;
use App\Core\Facades\{Config, Option};
use App\Core\Http\Redirect;
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

  private Template $template;

  private array $data = [];

  public function __construct()
  {
    $this->mailer = new PHPMailer(true);
    $this->mailer->SMTPDebug = false;

    $this->template = new Template();

    $this
      ->setupSMTP()
      ->setupMailer();
  }

  /**
   * @param Array|String $recipient
   * */
  public function send(array|string $recipients, array $options = []): bool
  {
    if (!isset($options['template'])) {
      $options['template'] = 'default';
    }

    if (!isset($options['subject'])) {
      $options['subject'] = Option::get('site_name', 'Website');
    }

    $this->prepareData($options);

    try {
      if (is_array($recipients)) {
        foreach ($recipients as $recipient) {
          $this->mailer->addAddress($recipient);
        }
      }

      if (!is_array($recipients)) {
        $this->mailer->addAddress($recipients);
      }


      $this->mailer->Subject = $options['subject'];
      $this->mailer->Body = $this->getTemplate($options['template']);
      //$this->mailer->AltBody = null === $altMessage ? $this->plainBody($message) : $altMessage;

      $this->mailer->send();

      return true;
    } catch (Exception $e) {
      //echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
      return false;
    }
  }

  private function prepareData(array $options = []): void
  {
    $this->addData('logo', Redirect::url('img/logo.png'));
    $this->addData('base_url', Redirect::url());
    $this->addData('privacy_url', Redirect::url('privacy'));
    $this->addData('site_name', Option::get('site_name', 'Website'));
    $this->addData('legal', Option::get('mail_legal', 'Website Corporation, Street Name, Redmond, WA 98000 USA'));

    foreach ($options as $key => $value) {
      $this->addData($key, $value);
    }
  }

  private function getTemplate(string $name): string
  {
    if (!$this->template->exists($name)) {
      return '';
    }
    return $this->template->build($name, $this->data);
  }

  private function setupSMTP(): self
  {
    if (!(bool) Option::get('mail_smtp_enabled', false)) {
      return $this;
    }

    $smtpUser = Option::get('mail_smtp_user', '');

    if (!filter_var($smtpUser, FILTER_VALIDATE_EMAIL)) {
      return $this;
    }

    // TODO: Work with SMTP, optionally Google 0Auth
    $this->mailer->isSMTP();                                                 //Send using SMTP

    $this->mailer->Username   = $smtpUser;                                   //SMTP username
    $this->mailer->Host       = Option::get('mail_smtp_host', '');           //Set the SMTP server to send through
    $this->mailer->SMTPAuth   = (bool) Option::get('mail_smtp_auth', false); //Enable SMTP authentication
    $this->mailer->Password   = Option::get('mail_smtp_password', '');       //SMTP password
    $this->mailer->Port       = (int) Option::get('mail_smtp_port', 465);    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    if (Option::get('mail_smtp_encryption', 'smtps') === 'starttls') {
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } else {
      $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    return $this;
  }

  private function setupMailer(): self
  {
    $sendFrom = Option::get('mail_sendfrom', '');

    if (!filter_var($sendFrom, FILTER_VALIDATE_EMAIL)) {
      $sendFrom = 'mail@example.com';
    }

    $replyTo = Option::get('mail_replyto', '');

    if (!filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
      $replyTo = 'mail@example.com';
    }

    $this->mailer->setFrom($sendFrom, Option::get('mail_sendname', 'Website'));
    $this->mailer->addReplyTo($replyTo, Option::get('mail_sendname', 'Website'));
    $this->mailer->isHTML(true);

    $this->mailer->CharSet = 'UTF-8';
    $this->mailer->XMailer = 'Microsoft Mailer';

    return $this;
  }

  private function addData(string $key, mixed $value): self
  {
    $this->data[$key] = $value;

    return $this;
  }
}
