<?php

namespace App\Core\View\Blade;

use ReflectionMethod;
use App\Core\View\Renderable;
use App\Core\View\Blade\Data;
use App\Core\Factories\ComposerFactory;
use App\Core\Facades\App;
use Illuminate\Contracts\View\View;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Factory as BladeFactory;
use Illuminate\View\ViewServiceProvider;
use Illuminate\Support\Str;

/**
 * Abstract for Laravel Blade.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
abstract class Factory extends Renderable
{
  private BladeFactory $factory;

  private BladeCompiler $compiler;

  protected Data $data;

  public function __construct()
  {
    parent::__construct();

    $this->data = new Data;
  }

  protected function setupBlade(): self
  {
    (new ViewServiceProvider(App::getProperty('container')))->register();

    $this->factory = App::getProperty('container')->get('view');
    $this->compiler = App::getProperty('container')->get('blade.compiler');

    return $this;
  }

  protected function setupDirectives(object $directivesClass): self
  {
    $directives = get_class_methods($directivesClass);

    foreach ($directives as $directive) {
      $this->directive($directive, function ($expression) use (&$directivesClass, $directive) {
        $reflection = new ReflectionMethod($directivesClass, $directive);

        /** @var array $variables */
        $variables = explode(',', $expression);

        if ($reflection->isStatic()) {
          $result = '\\' . get_class($directivesClass) . '::' . $directive . '(' . $expression . ')';
        } else if (is_array($variables) && count($variables) > 0) {
          $variablesMixed = $variables;

          array_walk($variablesMixed, function (&$element) {
            if (Str::startsWith($element, '\'')) {
              $element = substr($element, 1, -1);
            }
          });

          $result = "'" . call_user_func_array([$directivesClass, $directive], $variablesMixed) . "'";
        } else {
          $result = "'" . call_user_func([$directivesClass, $directive]) . "'";
        }

        return "<?php echo " . $result . "; ?>";
      });
    }

    $this->directive('ifpermission', function (string $permission) {
      return '<?php if(\App\Core\Auth\Account::hasPermission(' . $permission . ')): ?>';
    });

    $this->directive('iflogged', function () {
      return '<?php if(null !== \App\Core\Auth\Account::current()): ?>';
    });

    return $this;
  }

  protected function exists(string $view): bool
  {
    return $this->factory->exists($view);
  }

  protected function render(string $view): string
  {
    return $this->make($view)->render();
  }

  protected function make(string $view): View
  {
    $composer = ComposerFactory::make($this->namespace);

    if (!empty($composer)) {
      $this->factory->composer($view, $composer);
    }
  
    return $this->factory->make($view, $this->data->get(), []);
  }

  protected function directive(string $name, callable $handler): void
  {
    $this->compiler->directive($name, $handler);
  }
}
