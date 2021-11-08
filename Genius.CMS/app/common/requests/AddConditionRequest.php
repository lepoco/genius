<?php

namespace App\Common\Requests;

use Engine\Genius;
use Engine\Utils\Converter;
use App\Core\View\Request;
use App\Core\Http\{Status, Redirect};
use App\Core\Facades\Translate;
use Engine\Genius\Condition;
use Engine\Genius\Product;

/**
 * Action triggered during adding conditions of ES.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class AddConditionRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'AddCondition';
  }

  public function process(): void
  {
    $this->isSet([
      'system_id',
      'system_uuid',
      'condition_name',
      'condition_description'
    ]);

    $this->isEmpty([
      'system_id',
      'system_uuid',
      'condition_name',
    ]);

    $this->validate([
      ['system_id', FILTER_VALIDATE_INT],
      ['system_uuid', FILTER_SANITIZE_STRING],
      ['condition_name', FILTER_SANITIZE_STRING],
      ['condition_description', FILTER_SANITIZE_STRING]
    ]);

    $system = Genius::getSystem((int) $this->getData('system_id'));

    if (!$system->isValid()) {
      $this->addContent('fields', ['condition_name', 'condition_description']);
      $this->addContent('message', Translate::string('Selected system does not exist.'));
      $this->finish(self::ERROR_ENTRY_DONT_EXISTS, Status::OK);
    }

    if ($this->getData('system_uuid') !== $system->getUUID()) {
      $this->addContent('fields', ['condition_name', 'condition_description']);
      $this->addContent('message', Translate::string('Selected is invalid.'));
      $this->finish(self::ERROR_UNKNOWN, Status::OK);
    }

    Genius::addCondition($system, Condition::build([
      'prefix' => $system->getPrefix(),
      'simplified_name' => Converter::simplifyString($this->getData('condition_name')),
      'name' => $this->getData('condition_name'),
      'description' => $this->getData('condition_description')
    ]));

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
