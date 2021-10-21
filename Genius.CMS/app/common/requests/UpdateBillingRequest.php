<?php

namespace App\Common\Requests;

use App\Core\View\Request;
use App\Core\Http\Status;
use App\Core\Auth\{Account, User, Billing};

/**
 * Action triggered during update of account billing.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class UpdateBillingRequest extends Request implements \App\Core\Schema\Request
{
  private User $user;

  private Billing $billing;

  public function getAction(): string
  {
    return 'UpdateBilling';
  }

  public function process(): void
  {
    $this->isSet([
      'id',
      'first_name',
      'last_name',
      'street',
      'postal_code',
      'city',
      'province',
      'country',
      'phone',
      'email'
    ]);

    $this->isEmpty([
      'id',
      'first_name',
      'last_name',
      'street',
      'postal_code',
      'city',
      'province',
      'country',
      'phone',
      'email'
    ]);

    $this->validate([
      ['id', FILTER_VALIDATE_INT],
      ['first_name', FILTER_SANITIZE_STRING],
      ['last_name', FILTER_SANITIZE_STRING],
      ['street', FILTER_SANITIZE_STRING],
      ['postal_code', FILTER_SANITIZE_STRING],
      ['city', FILTER_SANITIZE_STRING],
      ['province', FILTER_SANITIZE_STRING],
      ['country', FILTER_SANITIZE_STRING],
      ['phone', FILTER_SANITIZE_STRING],
      ['email', FILTER_VALIDATE_EMAIL]
    ]);

    if (empty(Account::current())) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    if ($this->getData('id') < 1) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    if ($this->getData('id') !== Account::current()->getId()) {
      $this->finish(self::ERROR_USER_INVALID, Status::UNAUTHORIZED);

      return;
    }

    // TODO: Validate input data

    $this->user = new User((int) $this->getData('id'));
    $this->billing = $this->user->getBilling();

    $this->billing
      ->setFirstName($this->getData('first_name'))
      ->setLastName($this->getData('last_name'))
      ->setStreet($this->getData('street'))
      ->setPostalCode($this->getData('postal_code'))
      ->setCity($this->getData('city'))
      ->setCountry($this->getData('country'))
      ->setProvince($this->getData('province'))
      ->setPhone($this->getData('phone'))
      ->setEmail($this->getData('email'))
      ->update();

    $this->addContent('message', 'Your billing details have been saved.');
    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
