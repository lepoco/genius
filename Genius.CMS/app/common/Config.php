<?php

namespace App\Common;

/**
 * Dynamic configuration.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Config implements \App\Core\Schema\Config
{
  public const ENCRYPTION_ALGO = 'argon2id';

  public const SALT_SESSION = '';
  public const SALT_COOKIE = '';
  public const SALT_PASSWORD = '';
  public const SALT_NONCE = '';
  public const SALT_TOKEN = '';
  public const SALT_WEBAUTH = '';

  public const DATABASE_NAME = '';
  public const DATABASE_USER = '';
  public const DATABASE_PASS = '';
  public const DATABASE_HOST = '127.0.0.1';
}
