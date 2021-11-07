<?php

namespace App\Common\Composers;

use App\Core\Facades\Response;
use App\Core\View\Blade\Composer;
use Illuminate\View\View;

/**
 * Additional logic for the views/not-found.blade view.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class NotFoundComposer extends Composer implements \App\Core\Schema\Composer
{
  public function compose(View $view): void
  {
    Response::setStatusCode(\App\Core\Http\Status::NOT_FOUND);
  }
}
