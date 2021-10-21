<?php

namespace App\Core\Utils;

use App\Core\Utils\Path;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Provides static error/exception triggers for Symfony VarDumper.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ErrorHandler
{
  private const TYPE_ERROR = 'error';

  private const TYPE_EXCEPTION = 'exception';

  /**
   * Registers globally a custom error handler.
   */
  public static function register(): void
  {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    ini_set('error_reporting', (string) E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', Path::getAppPath('storage/logs/errors-' . date('Y-m-d') . '.log'));

    set_error_handler([self::class, 'error'], E_ALL);
    set_exception_handler([self::class, 'exception']);
  }

  /**
   * Static method that fires when an error occurs in code.
   */
  public static function error($errno, $errstr, $errfile, $errline): bool
  {
    self::log(self::TYPE_ERROR, 'Error', [$errno, $errstr, $errfile, $errline]);

    $request = Request::capture();

    if ($request->has('action')) {
      return true;
    }

    VarDumper::dump([
      'ERROR' => [
        'errno' => $errno,
        'errstr' => $errstr,
        'errfile' => $errfile,
        'errline' => $errline
      ]
    ]);

    return true;
  }

  /**
   * Static method that fires when an exception occurs in code.
   * @var \Exception|\TypeError $exception
   */
  public static function exception($exception): bool
  {
    self::log(self::TYPE_EXCEPTION, 'Exception', [$exception]);

    $request = Request::capture();

    if ($request->has('action')) {
      return true;
    }

    VarDumper::dump($exception);

    return true;
  }

  private static function log(string $type = 'ERROR', string $message, array $context = []): void
  {
    $rawMessage = '';

    switch ($type) {
      case self::TYPE_ERROR:
        $rawMessage .= 'Error: ' . $context[1];
        $rawMessage .= ' [\'path\' => \'' . $context[2] . '\', \'line\' => \'' . $context[3] . '\']';
        break;

      case self::TYPE_EXCEPTION:
        $rawMessage .= 'Exception: ' . $context[0]->getMessage();
        $rawMessage .= ' [\'file\' => \'' . $context[0]->getFile() . '\', \'line\' => \'' . $context[0]->getLine() . '\']';
        $rawMessage .= PHP_EOL . $context[0]->getTraceAsString();
        break;

      default:
        $rawMessage .= $message;
        $rawMessage .= ' ' . str_replace(array("\r", "\n"), '', var_export($context, true));
        break;
    }

    error_log($rawMessage, 0);
  }
}
