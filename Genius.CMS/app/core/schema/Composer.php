<?php

namespace App\Core\Schema;

use Illuminate\View\View;

/**
 * Base interface for Composer.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Composer
{
  public function compose(View $view): void;
}
