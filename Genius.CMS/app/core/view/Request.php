<?php

namespace App\Core\View;

use App\Core\Facades\{App, Logs, Response, Translate, Statistics};
use App\Core\Facades\Request as IlluminateRequest;
use App\Core\Http\Status;
use App\Core\View\Renderable;
use Illuminate\Support\Str;

/**
 * Abstraction for the request, contains the necessary underlying methods.
 *
 * @since 1.0.0
 * @author Pomianowski
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class Request extends Renderable implements \App\Core\Schema\Request
{
  protected const ERROR_UNKNOWN                  = 'E00';
  protected const ERROR_INTERNAL_ERROR           = 'E01';
  protected const ERROR_MISSING_ARGUMENTS        = 'E02';
  protected const ERROR_EMPTY_ARGUMENTS          = 'E03';
  protected const ERROR_ACTION_MISSING           = 'E04';
  protected const ERROR_ACTION_INVALID           = 'E05';
  protected const ERROR_NONCE_MISSING            = 'E06';
  protected const ERROR_NONCE_INVALID            = 'E07';

  protected const ERROR_MYSQL_UNKNOWN            = 'E08';
  protected const ERROR_SPECIAL_CHARACTERS       = 'E09';
  protected const ERROR_ENTRY_EXISTS             = 'E10';
  protected const ERROR_ENTRY_DONT_EXISTS        = 'E11';
  protected const ERROR_INSUFFICIENT_PERMISSIONS = 'E12';
  protected const ERROR_INVALID_URL              = 'E13';
  protected const ERROR_INVALID_EMAIL            = 'E14';
  protected const ERROR_USER_EMAIL_EXISTS        = 'E15';
  protected const ERROR_USER_NAME_EXISTS         = 'E16';
  protected const ERROR_USER_INVALID             = 'E17';
  protected const ERROR_PASSWORD_INVALID         = 'E18';
  protected const ERROR_PASSWORDS_DONT_MATCH     = 'E19';
  protected const ERROR_PASSWORD_TOO_SHORT       = 'E20';
  protected const ERROR_PASSWORD_TOO_SIMPLE      = 'E21';
  protected const ERROR_PASSWORD_CANNOT_BE_SAME  = 'E22';
  protected const ERROR_FILE_INVALID             = 'E23';
  protected const ERROR_FILE_INVALID_MIME_TYPE   = 'E24';
  protected const ERROR_FILE_TOO_LARGE           = 'E25';
  protected const ERROR_VALUE_TOO_BIG            = 'E26';
  protected const ERROR_VALUE_TOO_LOW            = 'E27';
  protected const ERROR_VALUE_INVALID            = 'E28';
  protected const ERROR_CARD_INVALID             = 'E29';

  protected const CODE_SUCCESS                   = 'S01';

  /**
   * Workaround for depracted way of sanitizing.
   */
  protected const SANITIZE_STRING = 513;

  protected bool $finished = false;

  protected string $method = '';

  private array $responseData = [];

  private array $incomeData = [];

  private array $requestData = [];

  /**
   * Validates the correctness of the class implementation.
   */
  abstract public function getAction(): string;

  /**
   * Responsible for processing the entire request.
   */
  abstract public function process(): void;

  /**
   * Prints the JSON reply in body of the reply.
   */
  public function print(): void
  {
    Logs::info('New request.', ['action' => $this->getAction(), 'ip' => IlluminateRequest::ip()]);

    $this->initialize();
    $this->validateRequestNonce();

    $this->addContent('hash', Str::random(32));

    Statistics::push(\App\Core\Data\Statistics::TYPE_REQUEST, 'REQUEST:' . $this->getAction());

    if (method_exists($this, 'process')) {
      $this->{'process'}();
    } else {
      $this->addContent('error', 'Non-existent action');
      $this->finish(self::ERROR_ACTION_INVALID, Status::BAD_REQUEST);
    }

    if (!$this->isFinished()) {
      $this->finish(self::ERROR_INTERNAL_ERROR, Status::IM_A_TEAPOT);
    }
  }

  /**
   * Checks if the given field exists in request. If not, the script aborts.
   */
  protected function isSet(array $fields): self
  {
    $notSetField = [];

    foreach ($fields as $field) {
      if (!isset($this->incomeData[$field])) {
        $notSetField[] = $field;
      }
    }

    if (count($notSetField) > 0) {
      $this->addContent('error', 'Non-existent field');
      $this->addContent('notice', 'not-exists');
      $this->addContent('fields', $notSetField);

      $this->addContent('message', Translate::string('Some fields are missing.'));
      $this->finish(self::ERROR_MISSING_ARGUMENTS, Status::UNPROCESSABLE_ENTITY);
    }

    return $this;
  }

  /**
   * Checks if the given field exists in request and is not empty. If not, the script aborts.
   */
  protected function isEmpty(array $fields): self
  {
    $emptyField = [];

    foreach ($fields as $field) {
      if (!isset($this->incomeData[$field]) || empty($this->incomeData[$field])) {
        $emptyField[] = $field;
      }
    }

    if (count($emptyField) > 0) {
      $this->addContent('error', 'Empty field');
      $this->addContent('notice', 'empty');
      $this->addContent('fields', $emptyField);
      $this->addContent('message', Translate::string('Not all fields are correctly filled.'));
      $this->finish(self::ERROR_EMPTY_ARGUMENTS, Status::UNPROCESSABLE_ENTITY);
    }

    return $this;
  }

  /**
   * Adds a field to a validated set.
   */
  protected function validate(array $fields): self
  {
    $incorrectFields = [];

    foreach ($fields as $field) {
      if (!isset($field[1])) {
        $field[1] = FILTER_UNSAFE_RAW;
      }

      $value = isset($this->incomeData[$field[0]]) ? $this->incomeData[$field[0]] : '';

      if ($field[1] == self::SANITIZE_STRING) {
        $filteredValue = htmlspecialchars($value, ENT_QUOTES);
      } else {
        $filteredValue = filter_var($value, $field[1]);
      }

      if (false === $filteredValue) {
        $incorrectFields[] = $field;

        continue;
      }

      $this->addData($field[0], $filteredValue);
    }

    if (count($incorrectFields) > 0) {
      $this->addContent('error', 'Incorrect field');
      $this->addContent('notice', 'non_filtered');
      $this->addContent('fields', $incorrectFields);
      $this->addContent('message', Translate::string('Some fields contain characters that are not allowed.'));
      $this->finish(self::ERROR_SPECIAL_CHARACTERS, Status::UNPROCESSABLE_ENTITY);
    }

    return $this;
  }

  /**
   * Quits request.
   */
  protected function finish(?string $status = '', int $responseCode = 200, bool $exit = true): void
  {
    if (!empty($status)) {
      $this->setStatus($status);
    }

    $this->setAsFinished();

    Response::setStatusCode($responseCode);
    Response::setHeader('Content-Type', 'application/json; charset=utf-8');
    Response::setContent(json_encode($this->responseData, JSON_UNESCAPED_UNICODE));

    if ($exit) {
      App::close();
    }
  }

  protected function initialize(): void
  {
    $this->method = IlluminateRequest::method();
    $this->incomeData = IlluminateRequest::toArray();

    $this->responseData = [
      'status' => self::ERROR_UNKNOWN,
      'type' => $this->method,
      'uuid' => (string) Str::uuid(),
      'time' => [
        'timestamp' => time(),
        'timezone' => date_default_timezone_get(),
        'generated' => date('Y-m-d H:i:s')
      ]
    ];
  }

  private function validateRequestNonce(): void
  {
    if ($this->getAction() == 'Install') {
      return;
    }

    if (!IlluminateRequest::has('nonce')) {
      $this->addContent('error', 'Missing nonce');
      $this->finish(self::ERROR_NONCE_MISSING, Status::BAD_REQUEST);
    }

    if (!$this->validateNonce('ajax_' . strtolower($this->getAction()) . '_nonce', IlluminateRequest::get('nonce'))) {
      $this->addContent('error', 'Invalid nonce');
      $this->addContent('message', Translate::string('The time verification key does not match, please try refreshing the page.'));
      $this->finish(self::ERROR_NONCE_INVALID, Status::BAD_REQUEST);
    }
  }

  /**
   * Adds new content to be returned in the JSON response.
   */
  protected function addContent(string $id, $content): void
  {
    $this->responseData['content'][$id] = $content;
  }

  /**
   * Determines what status is to be returned in JSON.
   */
  protected function setStatus(string $status): void
  {
    $this->responseData['status'] = $status;
  }

  /**
   * Adds raw data to be returned in the JSON response.
   */
  protected function addResponseData(string $id, $content): void
  {
    $this->responseData[$id] = $content;
  }

  /**
   * Adds a new entry to the validated pool.
   */
  private function addData(string $name, $value): void
  {
    $this->requestData[$name] = $value;
  }

  /**
   * Gets request field.
   */
  protected function get(string $name): mixed
  {
    return $this->getData($name);
  }

  /**
   * Returns an optional option that does not need to be validated.
   */
  protected function optional(string $name, bool $escape = true): mixed
  {
    if ($escape) {
      return trim(htmlspecialchars($this->incomeData[$name] ?? '', ENT_QUOTES));
    } else {
      return trim($this->incomeData[$name] ?? '');
    }
  }

  protected function isTrue(string $name): bool
  {
    return isset($this->incomeData[$name]) && !empty($this->incomeData[$name]);
  }

  /**
   * Gets request field.
   */
  protected function getData(string $name): mixed
  {
    return $this->requestData[$name] ?? null;
  }

  protected function getIncomeData(): array
  {
    return $this->incomeData;
  }

  protected function getValidatedData(): array
  {
    return $this->requestData;
  }

  protected function isFinished(): bool
  {
    return $this->finished;
  }

  protected function setAsFinished(): void
  {
    $this->finished = true;
  }
}
