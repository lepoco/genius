<?php

namespace App\Common\Composers\Dashboard;

use Engine\Genius;
use App\Core\Auth\Account;
use App\Core\View\Blade\Composer;
use App\Core\Facades\Request;
use App\Core\Http\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Str;

/**
 * Additional logic for the views/dashboard/sys.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class SysComposer extends Composer implements \App\Core\Schema\Composer
{
  public function compose(View $view): void
  {
    $segments = Request::segments();

    if (isset($segments[2])) {
      Redirect::url('dashboard');
    }

    $uuid = $segments[2];

    if (!Str::isUuid($uuid)) {
      Redirect::url('dashboard');
    }

    $system = Genius::getSystemBy('uuid', $uuid);

    if (empty($system)) {
      // TODO: Fix, display error message
      Redirect::url('404');
    }

    $conditions = Genius::getSystemConditions($system);
    $products = Genius::getSystemProducts($system);
    $relations = Genius::getSystemRelations($system);

    $view->with('user', Account::current());
    $view->with('system', $system);
    $view->with('question', $system->getQuestion());
    $view->with('is_question_has_pattern', !(false === strpos($system->getQuestion(), '{condition}')));
    $view->with('conditions', $conditions);
    $view->with('products', $products);
    $view->with('relations', $relations);
    $view->with('delete_url', Redirect::url('dashboard/delete/' . $system->getUUID()));

    $view->with('currentCondition', "con##");

    $view->with('systemSession', [123]);
  }
}
