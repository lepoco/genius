<?php

namespace App\Core\View\Rest;

use App\Core\View\Request;
use App\Core\Facades\Config;
use App\Core\Facades\Request as IlluminateRequest;
use Illuminate\Support\Str;

/**
 * Collection of endpoints for the application's communication with the world
 * 
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Rest extends Request
{
  public function __construct()
  {
    $this->initialize();

    $this->addResponseData('kind', $this->getKind());
    $this->addResponseData('generator', Config::get('app.name', 'Application'));
    $this->addResponseData('items', []);
  }

  public function getAction(): string
  {
    return 'Rest';
  }

  public function process(): void
  {
    // void
  }

  public function print(): void
  {
    $this->finish(self::CODE_SUCCESS);
  }

  protected function setItems(array $items): void
  {
    $this->addResponseData('items', $items);
  }

  protected function getKind(): string
  {
    //"kind": "youtube#channelListResponse"
    $path = Str::lower(Str::after(IlluminateRequest::path(), 'rest'));

    if (empty($path)) {
      $path = 'unknown';
    }

    return 'rest#' . $path;
  }

  protected function addItem($value): self
  {
    $this->responseData['items'][] = $value;

    return $this;
  }
}
