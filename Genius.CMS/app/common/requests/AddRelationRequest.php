<?php

namespace App\Common\Requests;

use Engine\Genius;
use Engine\Utils\Converter;
use App\Core\View\Request;
use App\Core\Http\{Status, Redirect};
use App\Core\Facades\Translate;
use Engine\Genius\Condition;
use Engine\Genius\Product;
use Engine\Genius\Relation;

/**
 * Action triggered during adding relations of ES.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class AddRelationRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'AddRelation';
  }

  public function process(): void
  {
    $this->isSet([
      'system_id',
      'system_uuid',
      'product_id',
      'relation_id',
      'condition_id'
    ]);

    $this->isEmpty([
      'system_id',
      'system_uuid',
      'product_id',
      'relation_id',
      'condition_id'
    ]);

    $this->validate([
      ['system_id', FILTER_VALIDATE_INT],
      ['system_uuid', FILTER_SANITIZE_STRING],
      ['product_id', FILTER_VALIDATE_INT],
      ['relation_id', FILTER_VALIDATE_INT],
      ['condition_id', FILTER_VALIDATE_INT]
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

    Genius::addRelation($system, Relation::build([
      'prefix' => $system->getPrefix(),
      'product_id' => (int) $this->getData('product_id'),
      'condition_id' => (int) $this->getData('condition_id'),
      'type_id' => (int) $this->getData('relation_id')
    ]));

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
