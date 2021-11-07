<?php

namespace App\Core\i18n;

use App\Core\i18n\StringManager;

/**
 * Allows to translate text strings based on a defined domain.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Translate
{
  private StringManager $manager;

  public function __construct()
  {
    $this->manager = new StringManager();
  }

  /**
   * Translates a text string.
   */
  public function string(string $text, ...$arguments): string
  {
    // TODO: Implement plural and sprintf

    if (!$this->manager->has($text)) {
      return $text;
    }

    return $this->manager->get($text);
  }

  public function domain(): string
  {
    return $this->manager->getDomain();
  }

  /**
   * Sets the currently used language domain.
   */
  public function setDomain(string $domain): self
  {
    $this->manager->setDomain($domain);

    return $this;
  }

  /**
   * Sets the path where the translations are located.
   */
  public function setPath(string $path): self
  {
    $this->manager->setPath($path);

    return $this;
  }

  /**
   * Loads the determined domain.
   */
  public function initialize(): self
  {
    $this->manager->load();

    return $this;
  }
}
