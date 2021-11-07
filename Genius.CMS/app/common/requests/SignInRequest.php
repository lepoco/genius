<?php

namespace App\Common\Requests;

use App\Core\View\Request;
use App\Core\Http\Status;
use App\Core\Auth\Account;

/**
 * Action triggered during signing in.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class SignInRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'SignIn';
  }

  public function process(): void
  {
    $this->isSet([
      'email',
      'password'
    ]);

    $this->isEmpty([
      'email',
      'password'
    ]);

    $this->validate([
      ['email', FILTER_VALIDATE_EMAIL],
      ['password', FILTER_UNSAFE_RAW]
    ]);

    $user = Account::getBy('email', $this->getData('email'));

    if (empty($user)) {
      $this->unauthorizedRequest();

      return;
    }

    if (!$user->isActive()) {
      $this->addContent('fields', ['email', 'password']);
      $this->addContent('message', 'Your account has been blocked. Please contact the supprot for more information.');
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);
    }

    if (!$user->comparePassword($this->getData('password'))) {
      $this->unauthorizedRequest();

      return;
    }

    // TODO: If account verified via email

    if (!Account::signIn($user)) {
      $this->finish(self::ERROR_INTERNAL_ERROR, Status::IM_A_TEAPOT);

      return;
    }

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }

  private function unauthorizedRequest(): void
  {
    $this->addContent('fields', ['email', 'password']);
    $this->addContent('message', 'Incorrect login details.');
    $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);
  }
}
