<?php

namespace App\Core\Data;

/**
 * Contains a collection of error information.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ErrorBag
{
  private array $errorBag = [];

  /**
   * Adds an error to the list.
   */
  public function addError(string $title, string $message, array $parameters = []): bool
  {
    $this->errorBag[] = [
      'title' => $title,
      'message' => $message,
      'parameters' => $parameters
    ];

    return true;
  }

  /**
   * Takes array of all errors.
   */
  public function getErrors(): array
  {
    return $this->errorBag;
  }

  /**
   * Takes information if there are any bugs.
   */
  public function hasErrors(): bool
  {
    return count($this->errorBag) > 0;
  }

  /**
   * Combines two error bags into one.
   */
  public function merge(ErrorBag $errors): bool
  {
    if (!$errors->hasErrors()) {
      return true;
    }

    $mergableErrors = $errors->getErrors();

    foreach ($mergableErrors as $singleError) {
      $this->errorBag[] = [
        'title' => $singleError['title'] ?? 'UNKNOWN_ERROR_TITLE',
        'message' => $singleError['message'] ?? 'UNKNOWN_ERROR_MESSAGE',
        'parameters' => $singleError['parameters'] ?? []
      ];
    }

    return true;
  }
}
