<?php

namespace App\Core\Data;

use App\Core\Facades\Config;

/**
 * Contains the methods used for encryption and salt formation.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.0.0
 */
final class Encryption
{
  /**
   * Gets the application salts.
   */
  private static function getSalts(): array
  {
    return [
      'algo'       => Config::get('encryption.algorithm', PASSWORD_BCRYPT),
      'session'    => Config::get('salts.session', ''),
      'cookie'     => Config::get('salts.cookie', ''),
      'password'   => Config::get('salts.password', ''),
      'token'      => Config::get('salts.token', ''),
      'webauth'    => Config::get('salts.token', ''),
      'nonce'      => Config::get('salts.nonce', ''),
      'passphrase' => Config::get('salts.passphrase', '')
    ];
  }

  /**
   * Creates hashes of data depending on the selected method, default password.
   */
  public static function hash(
    string $text,
    string $type = 'password',
    string $customSalt = '',
    $customAlgo = ''
  ): string {

    $salts = self::getSalts();

    if (empty($salts['password'])) {
      $salts['password'] = $customSalt;
    }

    if (empty($salts['nonce'])) {
      $salts['nonce'] = $customSalt;
    }

    if (empty($salts['session'])) {
      $salts['session'] = $customSalt;
    }

    if (empty($salts['cookie'])) {
      $salts['cookie'] = $customSalt;
    }

    if (empty($salts['token'])) {
      $salts['token'] = $customSalt;
    }

    if (empty($salts['webauth'])) {
      $salts['webauth'] = $customSalt;
    }

    if (empty($salts['algo'])) {
      $salts['algo'] = $customAlgo;
    }

    switch ($type) {
      case 'password':
        return (!empty($salts['password']) ? password_hash(hash_hmac('sha256', $text, $salts['password']), $salts['algo']) : '');

      case 'nonce':
        return (!empty($salts['nonce']) ? hash_hmac('sha1', $text . date('Y-m-d h'), $salts['nonce']) : '');
        break;

      case 'session':
        return (!empty($salts['session']) ? hash_hmac('sha256', $text, $salts['session']) : '');
        break;

      case 'token':
        return (!empty($salts['token']) ? password_hash(hash_hmac('sha256', $text, $salts['token']), $salts['algo']) : '');
        break;

      case 'webauth':
        return (!empty($salts['webauth']) ? password_hash(hash_hmac('sha256', $text, $salts['webauth']), $salts['algo']) : '');
        break;

      case 'cookie':
        return (!empty($salts['cookie']) ? hash_hmac('sha256', $text, $salts['cookie']) : '');
        break;

      default:
        return hash_hmac('sha256', $text, self::salter(60));
    }
  }

  /**
   * Compares hashed data with raw input.
   *
   * @link https://php.net/manual/en/function.hash-hmac.php
   * @link https://secure.php.net/manual/en/function.password-verify.php
   */
  public static function compare(
    string $text,
    string $compare_text,
    string $type = 'password',
    bool $plain = true,
    string $customSalt = ''
  ): bool {

    $salts = self::getSalts();

    if (empty($salts['password'])) {
      $salts['password'] = $customSalt;
    }

    if (empty($salts['nonce'])) {
      $salts['nonce'] = $customSalt;
    }

    if (empty($salts['session'])) {
      $salts['session'] = $customSalt;
    }

    if (empty($salts['token'])) {
      $salts['token'] = $customSalt;
    }

    if (empty($salts['webauth'])) {
      $salts['webauth'] = $customSalt;
    }

    if (empty($salts['cookie'])) {
      $salts['cookie'] = $customSalt;
    }

    switch ($type) {
      case 'password':
        return password_verify(($plain ? hash_hmac('sha256', $text, $salts['password']) : $text), $compare_text);

      case 'nonce':
        // We define the validity of the nonce for about two hours,
        // a solution for minutes would be better, but it would require more acrobatics.
        if (($plain ? hash_hmac('sha1', $text . date('Y-m-d h'), $salts['nonce']) : $text) == $compare_text) {
          return true;
        } elseif (($plain ? hash_hmac('sha1', $text . date('Y-m-d h', time() - 3600), $salts['nonce']) : $text) == $compare_text) {
          return true;
        } else {
          return false;
        }

      case 'session':
        return ($plain ? hash_hmac('sha256', $text, $salts['session']) : $text) == $compare_text;

      case 'cookie':
        return ($plain ? hash_hmac('sha256', $text, $salts['cookie']) : $text) == $compare_text;

      case 'token':
        return password_verify(($plain ? hash_hmac('sha256', $text, $salts['token']) : $text), $compare_text);

      case 'webauth':
        return password_verify(($plain ? hash_hmac('sha256', $text, $salts['webauth']) : $text), $compare_text);

      default:
        return false;
    }
  }

  /**
   * Generate a pseudo-random string of bytes.
   */
  public static function generateVector(string $cipherAlgo = 'aes-256-cbc'): string
  {
    if (!(function_exists('openssl_cipher_iv_length') && function_exists('openssl_random_pseudo_bytes'))) {
      return null;
    }

    return openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipherAlgo));
  }

  /**
   * Encrypt data using Open SSL.
   */
  public static function encrypt(string $data, string $iv, string $passphrase = null, string $cipherAlgo = 'aes-256-cbc'): string|bool
  {
    if (!function_exists('openssl_encrypt')) {
      return null;
    }

    $salts = self::getSalts();

    if (empty($passphrase)) {
      $passphrase = $salts['passphrase'] ?? '';
    }

    $options = 0; //OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING

    return openssl_encrypt($data, $cipherAlgo, $passphrase, $options, $iv);
  }

  /**
   * Decrypts encrypted data using Open SSL.
   */
  public static function decrypt(string $data, string $iv, string $passphrase = null, string $cipherAlgo = 'aes-256-cbc'): string|bool
  {
    if (!function_exists('openssl_decrypt')) {
      return null;
    }

    $salts = self::getSalts();

    if (empty($passphrase)) {
      $passphrase = $salts['passphrase'] ?? '';
    }

    $options = 0; //OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING

    return openssl_decrypt($data, $cipherAlgo, $passphrase, $options, $iv);
  }

  /**
   * Generates a pseudo-random string.
   * @see https://php.net/manual/en/function.mt-rand.php
   */
  public static function salter(int $length, string $pattern = 'ULNS'): string
  {
    self::sRandSeed();

    if (empty($pattern)) {
      $pattern = 'ULNS';
    }
    $pattern = strtoupper($pattern);

    $characters = '';
    if (strpos($pattern, 'U') !== false) {
      $characters .= 'GHIJKLMNOPQRSTUVWXYZABCDEF';
    }

    if (strpos($pattern, 'L') !== false) {
      $characters .= 'abcdefghijklmnopqrstuvwxyz';
    }

    if (strpos($pattern, 'N') !== false) {
      $characters .= '0123456789';
    }

    if (strpos($pattern, 'S') !== false) {
      $characters .= '!@#%^&*_+-=;:,.?~';
    }

    $rand = '';
    for ($i = 0; $i < $length; $i++) {
      $rand .= $characters[mt_rand(0, strlen($characters) - 1)]; //Mersenne Twist
    }

    return $rand;
  }

  /**
   * Generates a pseudo-random seed.
   * @see https://php.net/manual/en/function.mt-srand.php
   */
  private static function sRandSeed(): void
  {
    $characters = '+MNT%#aefbcklmnQRSX67D*&^YZ_oJKLUVWpqijP-=@.z012345EFrstuvdg,?!ABChwxy89GHIO';
    $crx = '';
    for ($i = 0; $i < 50; $i++) {
      $crx .= $characters[mt_rand(0, 75)];
    }

    $rand = intval(crc32(self::buildSeed() . '@' . $crx) * 2147483647);
    mt_srand($rand);
  }

  /**
   * Flips a random seed based on the timecode.
   */
  private static function buildSeed(): int
  {
    list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
  }
}
