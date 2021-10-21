<?php

namespace App\Core\Http;

use App\Core\Facades\{App, Option, Logs, Config, Request, Session};
use App\Core\Factories\{ControllerFactory, RequestFactory, RestFactory};
use App\Core\Auth\Account;
use App\Core\Http\Redirect;
use Bramus\Router\Router as BramusRouter;

/**
 * Redirects traffic to views or requests.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
abstract class Router implements \App\Core\Schema\Router
{
  protected BramusRouter $router;

  protected array $routes = [];

  public function __construct()
  {
    $this->router = new BramusRouter();
  }

  public function setup(): self
  {
    $this->router->post(
      '/request',
      fn () => $this->handleRequest()
    );

    $this->router->get(
      '/request',
      fn () => $this->handleRequest()
    );

    $this->router->post(
      '/rest/(.*)?',
      fn () => $this->handleRest()
    );

    $this->router->get(
      '/rest/(.*)?',
      fn () => $this->handleRest()
    );

    $this->router->set404(
      fn () => $this->handleView('NotFound')
    );

    if (empty(Config::get('database.connections.default.database', ''))) {
      $this->router->get(
        '',
        fn () => $this->handleView('Installer')
      );

      return $this;
    }

    $this->router->get(
      '/signout',
      fn () => $this->handleLogout()
    );

    foreach ($this->routes as $route) {
      $this->router->get(
        $route['path'],
        fn () => $this->handleView($route['namespace'])
      );
    }

    return $this;
  }

  public function run(): bool
  {
    return $this->router->run();
  }

  protected function handleRequest(): void
  {
    $request = RequestFactory::make();

    if (is_object($request)) {
      $request->print();
    } else {
      Logs::error('Desired REQUEST does not exist', ['request' => Request::all()]);
    }
  }

  protected function handleView(string $namespace): void
  {
    $this->validateAccess($namespace);

    $controller = ControllerFactory::make($namespace);

    if (is_object($controller)) {
      $controller->print();
    } else {
      Logs::error('Desired CONTROLLER does not exist', ['namespace' => $namespace]);
    }
  }

  protected function handleRest(): void
  {
    $rest = RestFactory::make();

    if (is_object($rest)) {
      $rest->print();
    } else {
      Logs::error('Desired REST endpoint not exist', ['request' => Request::all()]);
    }
  }

  protected function validateAccess(string $namespace): void
  {
    $routeData = array_filter($this->routes, fn ($route) => isset($route['namespace']) && $namespace == $route['namespace']) ?? [];
    $routeData = array_shift($routeData);
    $isSignedIn = !empty(Account::current());

    if (isset($routeData['require_login']) && true === $routeData['require_login'] && !$isSignedIn) {
      Redirect::to('signin');
    }

    if (isset($routeData['redirect_logged']) && true === $routeData['redirect_logged'] && $isSignedIn) {
      Redirect::to('dashboard');
    }
  }

  protected function handleLogout(): void
  {
    Account::signOut();

    Redirect::to('signin');
  }
}
