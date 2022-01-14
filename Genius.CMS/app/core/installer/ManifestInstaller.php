<?php

namespace App\Core\Installer;

use App\Core\Data\ErrorBag;
use App\Core\Facades\{Logs, Config, Request};
use App\Core\Data\Encryption;
use App\Core\Utils\Path;

/**
 * Automatic manifest creator.
 *
 * @author  Pomianowski Leszek <pomian@student.ukw.edu.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ManifestInstaller implements InstallerComponent
{
  private const MANIFEST_NAME = 'm.webmanifest';

  private array $manifestData = [];

  private ErrorBag $errorBag;

  /**
   * Creates a new manifest creator instance and initializes internal classes.
   */
  public function __construct()
  {
    $this->errorBag = new ErrorBag();
  }
  /**
   * Prepares local variables.
   */
  public function setup(): bool
  {
    $appVersion = Config::get('app.version', '1.0.0');
    $baseUrl = rtrim(Request::root(), '/');
    $locale = str_replace('_', '-', Config::get('i18n.default', 'en_US'));

    $this->manifestData = [
      'icons' => $this->generateIcons($baseUrl, $appVersion),
      'shortcuts' => $this->generateShortcuts($baseUrl, $appVersion),
      'name' => Config::get('app.name', ''),
      'short_name' => Config::get('app.short_name', ''),
      'description' => Config::get('app.description', ''),
      'background_color' => Config::get('app.color', ''),
      'theme_color' => Config::get('app.color', ''),
      'default_locale' => substr($locale, 0, 2),
      'lang' => $locale,
      'start_url' => $baseUrl,
      'id' => $baseUrl,
      'incognito' => 'split',
      'dir' => 'rtl',
      'display' => 'standalone',
      'orientation' => 'any',
      'prefer_related_applications' => false,
      'scope' => '/',
      // 'developer' => [
      //   'name' => 'Genius',
      //   'url' => 'https://github.com/lepoco/genius'
      // ],
      //"gcm_sender_id" => "71562645621",
      //"gcm_user_visible_only" => true
    ];

    return true;
  }

  /**
   * Tries to create an administrator account.
   */
  public function run(): bool
  {
    $path = Path::getAbsolutePath('public');

    if (!is_dir($path)) {
      return false;
    }

    $path .= '/' . self::MANIFEST_NAME;

    return false !== file_put_contents($path, json_encode($this->manifestData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
  }

  /**
   * @inheritDoc
   */
  public function getErrorBag(): ErrorBag
  {
    return $this->errorBag;
  }

  private function generateIcons(string $baseUrl, string $appVersion): array
  {
    $baseUrl = $baseUrl . '/';
    $sizes = [192, 256, 384, 512];
    $maskableSizes = [48, 72, 96, 128, 192, 384, 512];

    $icons = [];

    foreach ($sizes as $singleSize) {
      $icons[] = [
        'sizes' => $singleSize . 'x' . $singleSize,
        'src' => $baseUrl . 'img/icons/' . $singleSize . '.png?v=' . $appVersion,
        'type' => 'image/png'
      ];
    }

    foreach ($maskableSizes as $singleSize) {
      $icons[] = [
        'sizes' => $singleSize . 'x' . $singleSize,
        'src' => $baseUrl . 'img/icons/maskable_icon_x' . $singleSize . '.png?v=' . $appVersion,
        'type' => 'image/png',
        'purpose' => 'maskable'
      ];
    }

    return $icons;
  }

  private function generateShortcuts(string $baseUrl, string $appVersion): array
  {
    $baseUrl = $baseUrl . '/';

    $defaultIcon = [
      'sizes' => '96x96',
      'src' => $baseUrl . 'img/icons/maskable_icon_x96.png?v=' . $appVersion,
      'type' => 'image/png',
      'purpose' => 'maskable'
    ];

    return [
      // [
      //   'name' => 'CRON',
      //   'url' => '/cron/run/',
      //   'description' => 'Tasks'
      // ],
      [
        'name' => 'Dashboard',
        'url' => '/dashboard',
        'description' => 'Main account page',
        'icons' => [$defaultIcon]
      ],
      [
        'name' => 'Sign In',
        'url' => '/signin',
        'icons' => [$defaultIcon]
      ],
      [
        'name' => 'Registration',
        'url' => '/register',
        'icons' => [$defaultIcon]
      ]
    ];
  }
}
