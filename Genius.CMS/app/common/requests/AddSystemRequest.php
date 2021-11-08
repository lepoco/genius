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
final class AddSystemRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'AddSystem';
  }

  public function process(): void
  {
    $this->isSet([
      'system_name',
      'system_description',
      'system_type',
      'system_question'
    ]);

    $this->isEmpty([
      'system_name',
      'system_description',
      'system_type',
      'system_question'
    ]);

    $this->validate([
      ['system_name', FILTER_SANITIZE_STRING],
      ['system_description', FILTER_SANITIZE_STRING],
      ['system_type', FILTER_SANITIZE_STRING],
      ['system_question', FILTER_SANITIZE_STRING]
    ]);

    // Validate system type
    $systemType = '';

    switch ($this->getData('system_type')) {
      case 'relational':
        $systemType = 'relational';

        break;

      case 'fuzzy':
        $systemType = 'fuzzy';

        break;

      default:
        $this->addContent('message', Translate::string('Incorrect system type selected.'));
        $this->addContent('fields', ['system_type']);
        $this->finish(self::ERROR_ENTRY_DONT_EXISTS, Status::OK);

        break;
    }

    if (!Schema::isBuilt()) {
      Schema::build();
    }

    $typeId = Genius::getTypeId($systemType);

    $insertedSystemId = Genius::addSystem(System::build([
      'type_id' => $typeId,
      'name' => $this->getData('system_name'),
      'description' => $this->getData('system_description'),
      'question' => $this->getData('system_question'),
    ]));

    if ($insertedSystemId < 1) {
      $this->addContent('message', Translate::string('Something went wrong... We don\'t know what.'));
      $this->finish(self::ERROR_INTERNAL_ERROR, Status::OK);
    }

    $newSystem = Genius::getSystem($insertedSystemId);

    $this->addContent('redirect', Redirect::url('dashboard/edit/' . $newSystem->getUUID()));
    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
