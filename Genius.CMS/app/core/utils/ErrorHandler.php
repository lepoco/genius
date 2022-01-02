<?php

namespace App\Core\Utils;

use App\Core\Utils\Path;
use Illuminate\Http\Request;

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

  private const CONTACT_EMAIL = 'webmaster@example.com';

  private static bool $errorPagePrinted = false;

  /**
   * Registers globally a custom error handler.
   */
  public static function register(): bool
  {
    if (defined('APPDEBUG') && APPDEBUG === true) {
      ini_set('display_errors', '1');
      ini_set('display_startup_errors', '1');
    }

    ini_set('error_reporting', (string) E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', Path::getAppPath('storage/logs/errors-' . date('Y-m-d') . '.log'));

    set_error_handler([self::class, 'error'], E_ALL);
    set_exception_handler([self::class, 'exception']);

    return true;
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

    if (!defined('APPDEBUG') || APPDEBUG !== true) {
      return true;
    }

    if (class_exists('\Symfony\Component\VarDumper\VarDumper')) {
      \Symfony\Component\VarDumper\VarDumper::dump([
        'ERROR' => [
          'errno' => $errno,
          'errstr' => $errstr,
          'errfile' => $errfile,
          'errline' => $errline
        ]
      ]);
    }

    return true;
  }

  /**
   * Static method that fires when an exception occurs in code.
   * @var \Exception|\TypeError $exception
   */
  public static function exception($exception): bool
  {
    self::log(self::TYPE_EXCEPTION, 'Exception', [$exception]);

    self::printErrorPage($exception->getMessage());

    if (!defined('APPDEBUG') || APPDEBUG !== true) {
      return true;
    }

    $request = Request::capture();

    if ($request->has('action')) {
      return true;
    }

    if (class_exists('\Symfony\Component\VarDumper\VarDumper')) {
      \Symfony\Component\VarDumper\VarDumper::dump($exception);
    }

    return true;
  }

  private static function log(string $type = 'ERROR', string $message = '', array $context = []): void
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

  private static function printErrorPage(string $message = ''): void
  {
    if (self::$errorPagePrinted) {
      return;
    }

    $contactEmail = self::CONTACT_EMAIL;

    if (defined('SUPPORTEMAIL')) {
      $contactEmail = SUPPORTEMAIL;
    }

    $html = '<!doctype html><html lang="en" style="height: 100%;"><head>';
    $html .= '<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">';
    $html .= '<title>Error</title></head>';

    $html .= '<body style="min-height: 100%;margin: 0;display:flex;justify-content:center;align-items:center;font-family: system-ui, -apple-system, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, \'Noto Sans\', \'Liberation Sans\', sans-serif">';
    $html .= '<div>'; //Non ending div

    // Card
    $html .= '<div style="margin: 50px;padding:30px;background:#eee;border-radius:10px">';
    $html .= '<h2 style="font-size: 25px;margin:0;margin-bottom:10px;">Something has gone very wrong!</h2>';
    $html .= '<p style="margin:0; padding:0">As a result of the error, the site stopped working, we are working on fixing the problem.</p>';
    $html .= '<p style="margin:0; padding:0">Please contact us at <a href="mailto:' . $contactEmail . '">' . $contactEmail . '</a></p>';

    if (!empty($message)) {
      $html .= '<code style="display:block;margin:0; margin-top:20px;">' . $message . '</code>';
      $html .= '<code>' . time() . '</code>';
    }

    $html .= '</div>';

    echo $html;

    self::$errorPagePrinted = true;
  }
}
