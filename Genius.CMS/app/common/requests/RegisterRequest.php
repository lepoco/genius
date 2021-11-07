<?php

namespace App\Common\Requests;

use App\Core\Facades\{Email, Translate};
use App\Core\View\Request;
use App\Core\Http\{Status, Redirect};
use App\Core\Auth\{Account, User};
use App\Core\Data\Encryption;
use App\Core\Utils\Cast;
use Illuminate\Support\Str;

/**
 * Action triggered during registration.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class RegisterRequest extends Request implements \App\Core\Schema\Request
{
  /** @see https://regex101.com/ */
  private const PASSWORD_PATTERN = "/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])/";

  private const PASSWORD_MIN_LENGTH = 20;

  private const PASSWORD_MAX_LENGTH = 128;

  public function getAction(): string
  {
    return 'Register';
  }

  public function process(): void
  {
    $this->isSet([
      'email',
      'password',
      'password_confirm',
    ]);

    $this->isEmpty([
      'email',
      'password',
      'password_confirm',
    ]);

    $this->validate([
      ['email', FILTER_VALIDATE_EMAIL],
      ['password', FILTER_UNSAFE_RAW],
      ['password_confirm', FILTER_UNSAFE_RAW]
    ]);

    if (Account::isRegistered($this->getData('email'))) {
      $this->addContent('fields', ['email']);
      $this->addContent('message', 'You cannot use this email address.');
      $this->finish(self::ERROR_ENTRY_EXISTS, Status::OK);
    }

    if (Str::length($this->getData('password')) < self::PASSWORD_MIN_LENGTH) {
      $this->addContent('fields', ['password']);
      $this->addContent('message', $this->passwordMessage());
      $this->finish(self::ERROR_PASSWORD_TOO_SHORT, Status::OK);
    }

    if (!preg_match(self::PASSWORD_PATTERN, $this->getData('password'))) {
      $this->addContent('fields', ['password']);
      $this->addContent('message', $this->passwordMessage());
      $this->finish(self::ERROR_PASSWORD_TOO_SIMPLE, Status::OK);
    }

    if (Str::length($this->getData('password')) > self::PASSWORD_MAX_LENGTH) {
      $this->addContent('fields', ['password']);
      $this->addContent('message', $this->passwordMessage());
      $this->finish(self::ERROR_PASSWORD_TOO_SHORT, Status::OK);
    }

    if ($this->getData('password') != $this->getData('password_confirm')) {
      $this->addContent('fields', ['password_confirm']);
      $this->addContent('message', 'Passwords must be the same.');
      $this->finish(self::ERROR_PASSWORDS_DONT_MATCH, Status::OK);
    }

    if (!$this->registerUser()) {
      $this->finish(self::ERROR_INTERNAL_ERROR, Status::IM_A_TEAPOT);
    }

    $this->addContent(
      'redirect',
      Redirect::url('register/confirmation', [
        'n' => $this->nonce('RegisterConfirmation'),
        'e' => $this->getData('email')
      ])
    );

    // This should never happen
    $this->finish(self::CODE_SUCCESS, Status::OK);
  }

  private function registerUser(): bool
  {
    $encryptedPassword = Encryption::encrypt($this->getData('password'), 'password');

    $newUser = User::build([
      'display_name' => Cast::emailToUsername($this->getData('email')),
      'email' => $this->getData('email'),
      'password' => $encryptedPassword,
      'role' => Account::getRoleId('default')
    ]);

    $registered = Account::register($newUser, $encryptedPassword);

    if (! $registered) {
      return false;
    }

    $registeredUser = Account::getBy('email', $this->getData('email'));

    if (empty($registeredUser)) {
      return false;
    }

    //$registeredUser

    Email::send($this->getData('email'), [
      'subject' => Translate::string('Thank you for your registration!'),
      'header' => Translate::string('Account confirmation'),
      'message' => Translate::string('Thank you for creating an account on our website. Click on the link below to activate your account.'),
      'action_title' => Translate::string('Confirm email'),
      'action_url' => \App\Core\Http\Redirect::url('registration', [
        'confirmation' => '123',
        'email' => $this->getData('email')
      ])
    ]);

    return true;
  }

  private function passwordMessage(): string
  {
    return sprintf(
      'The password provided is too simple. It should be %s to %s characters long and contain a lowercase letter, an uppercase letter, a number and a special character.',
      self::PASSWORD_MIN_LENGTH,
      self::PASSWORD_MAX_LENGTH
    );
  }
}
