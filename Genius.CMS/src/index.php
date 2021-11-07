<?php

/**
 * This is where the request made by the browser begins.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */

use App\Common\App;
use App\Core\Utils\ErrorHandler;

define('APPSTART', microtime(true));
define('ABSPATH', __DIR__ . '/../');
define('APPDIR', 'app/');

date_default_timezone_set('UTC');

require __DIR__ . '/../vendor/autoload.php';

ErrorHandler::register();

$app = new App();

$app
  ->setup()
  ->connect()
  ->print()
  ->close();
