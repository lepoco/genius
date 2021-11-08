<?php

namespace App\Common\Requests;

use Engine\Database\Schema;
use Engine\Genius;
use Engine\Genius\System;
use Engine\Utils\Converter;
use App\Core\View\Request;
use App\Core\Http\{Status, Redirect};
use App\Core\Facades\Translate;
use Engine\Genius\Condition;
use Engine\Genius\Product;
use Illuminate\Support\Str;

/**
 * Action triggered during adding products of ES.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class AddProductRequest extends Request implements \App\Core\Schema\Request
{
  public function getAction(): string
  {
    return 'AddProduct';
  }

  public function process(): void
  {
    $this->isSet([
      'system_id',
      'system_uuid',
      'product_name',
      'product_description'
    ]);

    $this->isEmpty([
      'system_id',
      'system_uuid',
      'product_name',
    ]);

    $this->validate([
      ['system_id', FILTER_VALIDATE_INT],
      ['system_uuid', FILTER_SANITIZE_STRING],
      ['product_name', FILTER_SANITIZE_STRING],
      ['product_description', FILTER_SANITIZE_STRING]
    ]);

    $system = Genius::getSystem((int) $this->getData('system_id'));

    if (!$system->isValid()) {
      $this->addContent('fields', ['product_name', 'product_description']);
      $this->addContent('message', Translate::string('Selected system does not exist.'));
      $this->finish(self::ERROR_ENTRY_DONT_EXISTS, Status::OK);
    }

    if ($this->getData('system_uuid') !== $system->getUUID()) {
      $this->addContent('fields', ['product_name', 'product_description']);
      $this->addContent('message', Translate::string('Selected is invalid.'));
      $this->finish(self::ERROR_UNKNOWN, Status::OK);
    }

    Genius::addProduct($system, Product::build([
      'prefix' => $system->getPrefix(),
      'simplified_name' => Converter::simplifyString($this->getData('product_name')),
      'name' => $this->getData('product_name'),
      'description' => $this->getData('product_description')
    ]));

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
