<?php

namespace App\Core\Schema;

use Symfony\Component\HttpFoundation\Cookie;

/**
 * Alter response interface.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
interface Response
{
  public function setHeader(string $name, string $value): self;

  public function setCookie(Cookie $cookie): self;
}
