<?php

namespace App\Common\Requests;

use Engine\Database\Schema;
use Engine\Genius;
use Engine\Genius\System;
use App\Core\View\Request;
use App\Core\Http\{Status, Redirect};
use App\Core\Facades\Translate;

/**
 * Action triggered during adding ES.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class DeleteSystemRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'DeleteSystem';
  }

  public function process(): void
  {
    $this->isSet([
      'system_id',
      'system_uuid',
      'accept_delete'
    ]);

    $this->isEmpty([
      'system_id',
      'system_uuid',
      'accept_delete'
    ]);

    $this->validate([
      ['system_id', FILTER_VALIDATE_INT],
      ['system_uuid', FILTER_SANITIZE_STRING],
      ['accept_delete', FILTER_SANITIZE_STRING]
    ]);

    if ('accept_delete' !== $this->getData('accept_delete')) {
      $this->addContent('fields', ['accept_delete']);
      $this->addContent('message', Translate::string('You must accept the deletion.'));
      $this->finish(self::ERROR_EMPTY_ARGUMENTS, Status::OK);
    }

    $system = Genius::getSystem((int) $this->getData('system_id'));

    if (!$system->isValid()) {
      $this->addContent('fields', ['accept_delete']);
      $this->addContent('message', Translate::string('Selected system does not exist.'));
      $this->finish(self::ERROR_ENTRY_DONT_EXISTS, Status::OK);
    }

    if ($this->getData('system_uuid') !== $system->getUUID()) {
      $this->addContent('fields', ['accept_delete']);
      $this->addContent('message', Translate::string('Selected is invalid.'));
      $this->finish(self::ERROR_UNKNOWN, Status::OK);
    }

    Genius::dropSystem($system, true);

    $this->addContent('redirect', Redirect::url('dashboard'));
    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
