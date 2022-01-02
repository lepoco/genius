<?php

namespace App\Core;

use App\Core\Http\{Router, Response, Session};
use App\Core\Data\{Container, Statistics, Options};
use App\Core\Facades\App;
use App\Core\Email\Mailer;
use App\Core\i18n\Translate;
use App\Core\Cache\Redis;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Log\LogManager;

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
  protected int $queries = 0;

  protected int $status;

  protected bool $connected;

  protected bool $installed;

  protected Container $container;

  protected Router $router;

  protected Repository $configuration;

  protected Request $request;

  protected Response $response;

  protected Session $session;

  protected Redis $cache;

  protected LogManager $logs;

  protected Manager $database;

  protected Options $options;

  protected FileSystem $filesystem;

  protected Translate $translate;

  protected Statistics $statistics;

  protected Mailer $mailer;

  abstract public function init(): void;

  /**
   * Application specific constructor. Creates instances of base objects and assigns them to Facades.
   */
  public function setup(): self
  {
    $this->status = 0;

    $this->init();
    $this->setupContainer();

    App::set($this);

    return $this;
  }

  /**
   * Attempts to connect to the database and creates the Database, Options, and Cache objects.
   */
  public function connect(bool $soft = false): self
  {
    if (!$soft) {
      $this
        ->setCache(new Redis())
        ->setSession(new Session());
    }

    $this
      ->setDatabase(new Manager($this->container))
      ->setOptions(new Options());

    $this->isConnected(true);

    $timeNow = time();

    if ($this->isInstalled() && $this->session->has('last_opened')) {
      $maxDiff = (int) $this->options->get('signout_time', 15);
      $maxDiff = $maxDiff < 1 ? 60 : $maxDiff * 60;

      if (($timeNow - $this->session->get('last_opened')) > $maxDiff) {
        $this->destroy();
      }
    }

    if (!$this->session->has('language')) {
      $this->session->put('language', $this->configuration->get('i18n.default', 'en_US'));
    }

    $this->session->put('last_opened', $timeNow);

    return $this;
  }

  /**
   * Triggers Router and tries to create the view to display or execute the Request.
   */
  public function print(): self
  {
    $this->translate = new Translate();

    $langauge = $this->session->get('language', $this->configuration->get('i18n.default', 'en_US'));

    // TODO: Detect browser specific language
    // TODO: Or, set language on front via dropdown

    if ($this->isInstalled()) {
      $user = \App\Core\Auth\Account::current();

      if (!empty($user)) {
        $langauge = $user->getLanguage();
      }
    }

    $this->translate
      ->setDomain($langauge)
      ->setPath($this->configuration->get('i18n.path', ''))
      ->initialize();

    $this->statistics = new Statistics($this->isInstalled());
    $this->mailer = new Mailer();

    $this->response->prepareCSP(
      [],
      ['*.googleapis.com', '*.gstatic.com'],
      ['*.googleapis.com', '*.gstatic.com']
    );

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
    $this->session->put('_rendered', time());

    $this->session->save();

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
    $this->session->clear();
    $this->session->invalidate();
  }

  /**
   * Generate a new session identifier and token.
   */
  public function regenerate(): void
  {
    $this->session->regenerate();
  }

  /**
   * Gets an application object that is an instance of one of the logic elements.
   * @return mixed
   */
  final public function getProperty(string $property): object
  {
    $objects = get_object_vars($this);

    if ($property == 'database') {
      $this->queries++;
    }

    if (!isset($objects[$property])) {
      return $this;
    }

    return $objects[$property];
  }

  /**
   * Checks whether the database is connected.
   */
  final public function isConnected(bool $forceReCheck = false): bool
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

  /**
   * Checks whether the database is connected and initialized.
   */
  final public function isInstalled(bool $forceReCheck = false): bool
  {
    if (!$forceReCheck && isset($this->installed)) {
      return $this->installed;
    }

    if (!$this->isConnected()) {
      return false;
    }

    $this->installed = $this->database->schema()->hasTable('options') && $this->database->schema()->hasTable('users');
    $this->queries++;

    return $this->installed;
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

    if (empty($abstract) || 'logs' === $abstract) {
      $this->setLogs(new LogManager($this->container));
    }

    return true;
  }

  /**
   * Get the number of queries to the database for a given run.
   */
  final public function queries(): int
  {
    return $this->queries;
  }

  final protected function setupContainer(): self
  {
    $this->request = Request::capture();
    $this->response = new Response('', 200, $this->request->headers->all());

    $this->filesystem = new Filesystem();
    $this->container = new Container();

    $this->generateDirectories();
    $this->rebind();

    \Illuminate\Support\Facades\Facade::setFacadeApplication($this->container);

    return $this;
  }

  final protected function setLogs(LogManager $logManager): self
  {
    $this->logs = $logManager;

    return $this;
  }

  protected function setSession(Session $session): self
  {
    $this->session = $session;

    $this->session->start();

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

  protected function setOptions(Options $options): self
  {
    $this->options = $options;

    return $this;
  }

  protected function setCache(\App\Core\Schema\Cache $cache): self
  {
    $this->cache = $cache;

    return $this;
  }

  protected function generateDirectories(): void
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
