<?php

namespace App\Core;

use App\Core\Http\{Router, Response};
use App\Core\Data\Options;
use App\Core\Facades\App;
use App\Core\Data\Container;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Cookie\CookieJar;
use Illuminate\Cache\CacheManager;
use Illuminate\Log\LogManager;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/**
 * Creates all connections and application objects.
 * Should be an abstraction to the App class.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
abstract class Bootstrap implements \App\Core\Schema\App
{
  protected int $status;

  protected bool $connected;

  protected Container $container;

  protected Router $router;

  protected Repository $configuration;

  protected Request $request;

  protected Response $response;

  protected NativeSessionStorage $nativeSession;

  protected CacheManager $cache;

  protected LogManager $logs;

  protected Manager $database;

  protected Options $options;

  protected FileSystem $filesystem;

  abstract public function init(): void;

  /**
   * Application specific constructor. Creates instances of base objects and assigns them to Facades.
   */
  final public function setup(): self
  {
    $this
      ->setStatus(0)
      ->init();

    $this
      ->setupRequest()
      ->setupContainer();

    App::set($this);

    return $this;
  }

  /**
   * Attempts to connect to the database and creates the Database, Options, and Cache objects.
   */
  public function connect(): self
  {
    $this
      ->setDatabase(new Manager($this->container))
      ->setCache(new CacheManager($this->container))
      ->setOptions(new Options());

    return $this;
  }

  /**
   * Triggers Router and tries to create the view to display or execute the Request.
   */
  public function print(): self
  {
    $this
      ->router
      ->setup()
      ->run();

    $this->close();

    return $this;
  }

  /**
   * Closes session and triggers the Garbage Collector.
   */
  public function close(bool $exit = true): void
  {
    $this->request->session()->put('_rendered', time());

    $this->request->session()->save();

    $this->response->send();

    if ($exit) {
      exit($this->status);
    }
  }

  /**
   * Flush the session data and regenerate the ID.
   */
  public function destroy(): void
  {
    $sessionId = $this->request->session()->getId();
    $sessionCookie = $this->response->getCookie($this->configuration->get('session.cookie', 'pkx_session'));

    $this->nativeSession->clear();
    $this->request->session()->invalidate();

    $this->response->removeCookie($sessionId);

    if (!empty($sessionCookie)) {
      $this->response->removeCookie($sessionCookie->getName());
    }
  }

  /**
   * Generate a new session identifier and token.
   */
  public function regenerate(): void
  {
    $this->request->session()->regenerateToken();
    $this->request->session()->regenerate();
    $this->nativeSession->regenerate();
  }

  /**
   * Gets an application object that is an instance of one of the logic elements.
   * @return mixed
   */
  final public function getProperty(string $property): object
  {
    $objects = get_object_vars($this);

    if (!isset($objects[$property])) {
      return $this;
    }

    return $objects[$property];
  }

  /**
   * Reassign the objects to the controller.
   */
  final public function rebind(string $abstract = ''): bool
  {
    if (empty($abstract) || 'config' === $abstract) {
      $this->container->bind('config', fn () => $this->configuration, true);
    }

    if (empty($abstract) || 'files' === $abstract) {
      $this->container->bind('files', fn () => $this->filesystem, true);
    }

    if (empty($abstract) || 'events' === $abstract) {
      $this->container->bind('events', fn () => new Dispatcher(), true);
    }

    if (empty($abstract) || 'cookie' === $abstract) {
      $this->container->bind('cookie', fn () => (new CookieJar())->setDefaultPathAndDomain(
        $this->configuration->get('session.path'),
        $this->configuration->get('session.domain'),
        $this->configuration->get('session.secure'),
        $this->configuration->get('session.same_site')
      ), true);
    }

    if (empty($abstract) || 'session' === $abstract) {
      $this->setSession(new SessionManager($this->container));
    }

    if (empty($abstract) || 'logs' === $abstract) {
      $this->setLogs(new LogManager($this->container));
    }

    return $this->isConnected(true);
  }

  /**
   * Checks whether the database is connected.
   */
  public function isConnected(bool $forceReCheck = false): bool
  {
    if (!$forceReCheck && isset($this->connected)) {
      return $this->connected;
    }

    $this->connected = false;

    try {
      if (!isset($this->database)) {
        return false;
      }

      $pdo = $this->database->connection()->getPdo();

      if ($pdo) {
        $this->connected = true;
      }
    } catch (\Throwable $th) {
      if (isset($this->logs)) {
        $this->logs->critical('Connection to the database failed.', ['error' => $th]);
      }
    }

    return $this->connected;
  }

  final protected function setStatus(int $status): self
  {
    $this->status = $status;

    return $this;
  }

  final protected function setupContainer(): self
  {
    $this->filesystem = new Filesystem();
    $this->container = new Container();

    $this->generateDirectories();
    $this->rebind();

    \Illuminate\Support\Facades\Facade::setFacadeApplication($this->container);

    return $this;
  }

  final protected function setupRequest(): self
  {
    $this->request = Request::capture();
    $this->response = new Response('', 200, $this->request->headers->all());

    return $this;
  }

  protected function setLogs(LogManager $logManager): self
  {
    $this->logs = $logManager;

    return $this;
  }

  protected function setSession(SessionManager $session): self
  {
    $id = $this->request->cookies->get($session->getName());

    if (!empty($id)) {
      $session->setId($id);
    }

    $session->setRequestOnHandler($this->request);

    $this->request->setLaravelSession($session);

    $this->nativeSession = new NativeSessionStorage([], $this->request->session()->getHandler());

    $this->nativeSession->start();

    $this->request->session()->start();

    $attributes = $this->request->session()->all();

    // Native session allows you to restore data from cookie sessions
    // and use the application on browsers that do not support them.
    if (!$this->request->session()->has('_rendered')) {
      foreach ($_SESSION as $key => $value) {
        $this->request->session()->put($key, $value);
      }
    }

    foreach ($attributes as $key => $value) {
      $_SESSION[$key] = $value;
    }

    return $this;
  }

  protected function setRouter(Router $router): self
  {
    $this->router = $router;

    return $this;
  }

  protected function setConfig(Repository $configuration): self
  {
    $this->configuration = $configuration;

    return $this;
  }

  protected function setDatabase(Manager $database): self
  {
    $this->database = $database;

    $this->database->setAsGlobal();
    $this->database->bootEloquent();

    return $this;
  }

  protected function setCache(CacheManager $cache): self
  {
    // FIXME:: Cache needs garbage collector.
    $this->cache = $cache;

    return $this;
  }

  protected function setOptions(Options $options): self
  {
    $this->options = $options;

    return $this;
  }

  private function generateDirectories(): void
  {
    if (!$this->filesystem->isDirectory($this->configuration->get('view.compiled', 'storage/blade'))) {
      $this->filesystem->makeDirectory($this->configuration->get('view.compiled', 'storage/blade'), 0755, true);
    }

    if (!$this->filesystem->isDirectory($this->configuration->get('storage.logs', 'storage/logs'))) {
      $this->filesystem->makeDirectory($this->configuration->get('storage.logs', 'storage/logs'), 0755, true);
    }

    if (!$this->filesystem->isDirectory($this->configuration->get('cache.stores.file.path', 'storage/cache'))) {
      $this->filesystem->makeDirectory($this->configuration->get('cache.stores.file.path', 'storage/cache'), 0755, true);
    }
  }
}
