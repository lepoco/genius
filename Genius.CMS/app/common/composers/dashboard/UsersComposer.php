<?php

namespace App\Common\Composers\Dashboard;

use App\Core\Facades\DB;
use App\Core\Auth\{Account, User};
use App\Core\View\Blade\Composer;
use Illuminate\View\View;

/**
 * Additional logic for the views/dashboard/users.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class UsersComposer extends Composer implements \App\Core\Schema\Composer
{
  public function compose(View $view): void
  {
    $view->with('user', Account::current());
    $view->with('users', $this->getUsers());
  }

  private function getUsers(): array
  {
    $users = [];
    $dbUsers = DB::table('users')->get(['id'])->all();

    if (empty($dbUsers)) {
      return [];
    }

    foreach ($dbUsers as $singleUser) {
      if (isset($singleUser->id) && !empty($singleUser->id)) {
        $users[] = new User($singleUser->id);
      }
    }

    return $users;
  }
}
