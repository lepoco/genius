<?php

namespace App\Common\Composers\Dashboard;

use Engine\Genius;
use App\Core\Auth\Account;
use App\Core\View\Blade\Composer;
use Illuminate\View\View;

/**
 * Additional logic for the views/dashboard/main.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class MainComposer extends Composer implements \App\Core\Schema\Composer
{
  public function compose(View $view): void
  {
    $systems = Genius::getAllSystems();

    $view->with('user', Account::current());
    $view->with('systems', $systems);
  }
}
