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
 * Additional logic for the views/dashboard/delete.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class DeleteComposer extends Composer implements \App\Core\Schema\Composer
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

    $view->with('user', Account::current());
    $view->with('system', $system);
    $view->with('edit_url', Redirect::url('dashboard/edit/' . $system->getUUID()));
  }
}
