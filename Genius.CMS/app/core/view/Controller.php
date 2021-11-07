<?php

namespace App\Core\View;

use App\Core\Facades\{Config, Response, Session, Request, Statistics};
use App\Core\View\Blade\{Factory, Directives};
use App\Core\Utils\Cast;
use Illuminate\Support\Str;

/**
 * Contains global logic for all views. If you want to add data for single view, use Composer.
 *
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
final class Controller extends Factory implements \App\Core\Schema\Controller
{
  public function __construct()
  {
    parent::__construct();

    $this
      ->setupBlade()
      ->setupDirectives(new Directives());
  }

  public function print(): self
  {
    $this->controllerData();

    // If we want to redirect the user to the previous page, it's worth not including those responsible for logging in.
    if (!in_array($this->namespace, ['NotFound', 'SignIn', 'Register', 'Installer'])) {
      Session::put('_previous_url', Request::url());
    }

    Statistics::push(\App\Core\Data\Statistics::TYPE_PAGE, 'PAGE:' . $this->namespace);

    Response::setContent($this->render(Cast::namespaceToBlade($this->namespace)));

    return $this;
  }

  private function controllerData(): void
  {
    $this->data->set('pagenow', Cast::namespaceToBlade($this->namespace));

    $this->data->set('body_classes', [
      'app',
      Str::lower(Config::get('app.name', 'application')),
      'page-' . Cast::namespaceToBlade($this->namespace)
    ]);

    $this->data->set('auth', [
      'loggedIn' => false
    ]);

    $this->data->append('js_data.props.view', Cast::namespaceToBlade($this->namespace));
  }
}
