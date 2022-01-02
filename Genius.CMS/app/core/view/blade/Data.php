<?php

namespace App\Core\View\Blade;

use App\Core\Utils\Cast;
use App\Core\Facades\{Config, Option, Request};
use App\Core\Auth\Account;
use App\Core\Http\Redirect;

/**
 * Container of information passed to blade views.
 * 
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Data
{
  private array $data = [];

  public function __construct()
  {
    $this->setDefault();
  }

  public function get(): array
  {
    return $this->data;
  }

  public function set(string $key, $value): self
  {
    $this->data[$key] = $value;

    return $this;
  }

  public function append(string $keys, $value): self
  {
    $keysArray = explode('.', $keys);
    $keysCount = count($keysArray);

    switch ($keysCount) {
      case 1:
        $this->data[$keysArray[0]] = $value;
        break;

      case 2:
        $this->data[$keysArray[0]][$keysArray[1]] = $value;
        break;

      case 3:
        $this->data[$keysArray[0]][$keysArray[1]][$keysArray[2]] = $value;
        break;

      case 4:
        $this->data[$keysArray[0]][$keysArray[1]][$keysArray[2]][$keysArray[3]] = $value;
        break;
    }

    return $this;
  }

  private function setDefault(): void
  {
    $currentUser = Account::current();
    $defaultUrl = Redirect::url();

    $this->set('version', Config::get('app.version', '0.0.0'));

    $this->set('debug', Config::get('app.debug', true));

    $this->set('base_url', $defaultUrl);

    $this->set('is_logged', null !== $currentUser);

    $this->set('js_data', [
      'props' => [
        'baseUrl' => $defaultUrl,
        'ajax' => $defaultUrl . 'request/',
        'dashboard' => 'dashboard',
        'secured' => Request::secure(),
        'debug' => Config::get('app.debug', true),
        'cookieName' => Option::get('cookie_name', 'access_cookie'),
        'serviceWorkerEnabled' => Option::get('service_worker_enabled', true),
        'signoutTime' => (int) Option::get('signout_time', 15),
        'version' => Config::get('app.version', '0.0.0')
      ],
      'auth' => [
        'loggedIn' => null !== $currentUser,
        'uuid' => null !== $currentUser ? $currentUser->getUUID() : ''
      ]
    ]);
  }
}
