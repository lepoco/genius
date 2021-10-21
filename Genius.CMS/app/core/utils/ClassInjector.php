<?php

namespace App\Core\Utils;

use LogicException;
use App\Core\Facades\File;
use Illuminate\Support\Str;

/**
 * A small tool for editing PHP classes using... PHP.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class ClassInjector
{
  private string $absolutePath = '';

  private string $fileContent = '';

  public function save(): bool
  {
    if (!File::exists($this->absolutePath)) {
      return false;
    }

    File::put($this->absolutePath, $this->fileContent);

    return true;
  }

  public function inject(string $key, mixed $value, string $type = 'array', bool $isString = true): bool
  {
    switch ($type) {
      case 'array':
        return $this->injectToArray($key, $value, $isString);

      case 'const':
        return $this->injectToConst($key, $value, $isString);

      default:
        throw new LogicException('Unknown injection type');
        return false;
    }

    return false;
  }

  public function isValid(): bool
  {
    return File::exists($this->absolutePath);
  }

  public function setPath(string $path): self
  {
    $this->absolutePath = $path;

    $this->readContent();

    return $this;
  }

  private function readContent(): void
  {
    if (!File::exists($this->absolutePath)) {
      return;
    }

    $this->fileContent = File::get($this->absolutePath);
  }

  private function injectToArray(string $key, mixed $value, bool $isString = true): bool
  {
    return false;
  }

  private function injectToConst(string $key, mixed $value, bool $isString = true): bool
  {
    $replacer = $isString ? 'const ' . $key . ' = \'' . $value . '\';' : 'const ' . $key . ' = ' . $value . ';';

    $this->fileContent = preg_replace(
      '/const( ?)' . $key . '.+/',
      $replacer,
      $this->fileContent
    );

    return true;
  }
}
