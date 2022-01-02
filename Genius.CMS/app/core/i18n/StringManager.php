<?php

namespace App\Core\i18n;

use RuntimeException;
use App\Core\Facades\File;
use App\Core\Data\Hash;
use Illuminate\Support\Str;

/**
 * Manages text strings pairs written in YAML.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class StringManager
{
  private string $domain = '';

  private string $path = '';

  private string $recentSearch = '';

  private array $recentResult = [];

  private array $translatable = [];

  public function has(string $text): bool
  {
    // TODO: Refactor

    $hashedKey = $this->hashKey($text);

    $this->recentSearch = $text;

    foreach ($this->translatable as $pair) {
      if ($hashedKey == $pair['hash']) {
        $this->recentResult = $pair;

        return true;
      }
    }

    return false;
  }

  public function get(string $text): string
  {
    if ($text == $this->recentSearch) {
      return $this->recentResult['value'] ?? '';
    }

    $hashedText = $this->hashKey($text);

    $this->recentSearch = $text;

    foreach ($this->translatable as $pair) {
      if ($hashedText == $pair['hash']) {
        $this->recentResult = $pair;

        return $pair['value'] ?? '';
      }
    }

    return '';
  }

  public function push(string $key, string $value): void
  {
    $this->translatable[] = [
      'hash' => $this->hashKey($key),
      'key' => $key,
      'value' => $value
    ];
  }

  public function setPath(string $path): self
  {
    $this->path = $path;

    return $this;
  }

  public function getPath(): string
  {
    return $this->path ?? '';
  }

  public function setDomain(string $domain): self
  {
    $this->domain = $domain;

    return $this;
  }

  public function getDomain(): string
  {
    return $this->domain ?? '';
  }

  public function load(): self
  {
    if (empty($this->path)) {
      throw new RuntimeException('Path for translations must be defined');
    }

    if (empty($this->domain)) {
      throw new RuntimeException('Domain for translations must be defined');
    }

    $this->getStrings();

    return $this;
  }

  private function hashKey(string $key): mixed
  {
    // TODO: CRC32 may generate non unique value
    // Hash must be numeric type

    //hash('crc32', $key);
    return Hash::crc64($key, '%u');
  }

  private function getStrings(): bool
  {
    $domainPath = $this->path . '/' . $this->domain;

    if (!File::exists($domainPath)) {
      return false;
    }

    $files = scandir($domainPath);

    foreach ($files as $singleFile) {
      if (Str::endsWith($singleFile, '.yaml')) {
        $this->parseYaml(File::get($domainPath . '/' . $singleFile));
      }
    }

    return !empty($this->translatable);
  }

  private function parseYaml(?string $contents = ''): void
  {
    if (empty($this->translatable)) {
      $this->translatable = [];
    }

    $eContents = explode(PHP_EOL, $contents);

    foreach ($eContents as $singleLine) {
      if (Str::startsWith($singleLine, '#')) {
        continue;
      }

      $key = Str::before($singleLine, ': ');

      if ($this->has($key)) {
        continue;
      }

      if (!empty($singleLine) && Str::contains($singleLine, ': ')) {
        $this->push($key, Str::after($singleLine, ': '));
      }
    }
  }
}
