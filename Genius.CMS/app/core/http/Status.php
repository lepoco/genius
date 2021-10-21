<?php

namespace App\Core\Http;

/**
 * HTTP Response codes.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Status
{
  public const OK                   = 200;
  public const CREATED              = 201;
  public const ACCEPTED             = 202;
  public const NO_CONTENT           = 204;
  public const MOVED_PERMANENTLY    = 301;
  public const FOUND                = 302;
  public const NOT_MODIFIED         = 304;
  public const TEMPORARY_REDIRECT   = 307;
  public const PERMANENT_REDIRECT   = 308;
  public const BAD_REQUEST          = 400;
  public const UNAUTHORIZED         = 401;
  public const FORBIDDEN            = 403;
  public const NOT_FOUND            = 404;
  public const REQUEST_TIMEOUT      = 408;
  public const GONE                 = 410;
  public const UNSUPPORTED_MEDIA    = 415;
  public const IM_A_TEAPOT          = 418;
  public const UNPROCESSABLE_ENTITY = 422;
  public const INTERNAL_ERROR       = 500;
  public const NOT_IMPLEMENTED      = 501;
  public const BAD_GATEWAY          = 502;
  public const SERVICE_UNAVAILABLE  = 503;
  public const GATEWAY_TIMEOUT      = 504;
}
