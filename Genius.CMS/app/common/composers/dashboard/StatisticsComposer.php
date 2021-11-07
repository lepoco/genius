<?php

namespace App\Common\Composers\Dashboard;

use App\Core\Facades\{DB, Statistics};
use App\Core\Auth\{Account, User};
use App\Core\View\Blade\Composer;
use Illuminate\View\View;

/**
 * Additional logic for the views/dashboard/statistics.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class StatisticsComposer extends Composer implements \App\Core\Schema\Composer
{
  public function compose(View $view): void
  {
    $users = $this->getUsers();

    $view->with('user', Account::current());
    $view->with('users', $users);
    $view->with('users_count', count($users));
    $view->with('reqests_today', $this->getRequestsCount());
    $view->with('views_today', $this->getViewsCount());
    $view->with('systems_count', $this->getSystemsCount());
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

  private function getRequestsCount(): int
  {
    return count(Statistics::get('request', 'lastHour'));
  }

  private function getViewsCount(): int
  {
    return count(Statistics::get('page', 'today'));
  }

  private function getSystemsCount(): int
  {
    return 1;
  }
}
