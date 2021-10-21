<?php

namespace App\Core\View\Blade;

use App\Core\Facades\{App, Response, Request, Option};
use Illuminate\View\View;

/**
 * Abstraction for new view's Composers.
 *
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class Composer implements \App\Core\Schema\Composer
{
  abstract public function compose(View $view): void;
}
