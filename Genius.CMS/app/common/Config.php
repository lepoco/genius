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

  public const SALT_SESSION = 'OAzE~@Lg^WgO-g9%R5^O8I;5wSLw=IURaparExhV783t+3V*^?-9S3HGP=l?zg73';
  public const SALT_COOKIE = 'Fit@7CY-nudz01tC3nZ0#:tBslgSK-it&DV&D%N?I!#FK2V:v.:bQhI#Rg3f+4jV';
  public const SALT_PASSWORD = 'a48?*iKMT#%_URCCi@4w*7j&X&30Ec~j#PyCq_NnTea#.2v0J@mupD&%1q@vlYvo';
  public const SALT_NONCE = 'LaVm&16biuX&LNoInAG+TQg+1b?azK#a&,@c=!TFGMA?jb09fhVM5UxI219onB^A';
  public const SALT_TOKEN = 'XBQsGA~wGR!0LoCobBFd.dSca7WihqN_FUb^o?08pwLa-PHV#9II=isDtraL%.ak';
  public const SALT_WEBAUTH = ';Wz5S5IMAix&lg=J6bIh91r;;y6?1#kkg0W6j8Rp7eXEL@Q;-gRj&sWeH!Lw!+v:';

  public const DATABASE_NAME = 'genius';
  public const DATABASE_USER = 'root';
  public const DATABASE_PASS = 'root';
  public const DATABASE_HOST = '127.0.0.1';
}
