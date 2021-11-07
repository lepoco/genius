<?php

namespace App\Core\Email;

use App\Core\Facades\App;
use Illuminate\View\Factory as BladeFactory;
use Illuminate\View\ViewServiceProvider;
use Illuminate\Contracts\View\View;

/**
 * Contains logic for building blade themes for emails.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Template
{
  private BladeFactory $factory;

  public function __construct()
  {
    (new ViewServiceProvider(App::getProperty('container')))->register();
    $this->factory = App::getProperty('container')->get('view');
  }

  public function build(string $view, array $data = []): string
  {
    return $this->make('emails.' . $view, $data)->render();
  }

  public function exists(string $view): bool
  {
    return $this->factory->exists('emails.' . $view);
  }

  protected function make(string $view, $data): View
  {
    return $this->factory->make($view, $data, []);
  }
}
