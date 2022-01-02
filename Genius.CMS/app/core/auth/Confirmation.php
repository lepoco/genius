<?php

namespace App\Core\Auth;

use App\Core\Facades\{App, Session, DB, Response, Cache};
use App\Core\Auth\User;
use App\Core\Data\Encryption;
use Illuminate\Support\Str;

/**
 * Allows to create confirmation keys for various actions related to users.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Confirmation
{
  /**
   * Returns the token encrypted in BASE64. If it already exists, the token will be overwritten.
   */
  public static function add(string $type, User $user): string
  {
    $confirmationId = self::getConfirmationId($type);
    $token = Encryption::salter(64);
    $encrypted = Encryption::hash($token, 'token');

    if (!empty(self::get($type, $user))) {
      $query = DB::table('user_confirmations')->where(['confirmation_id' => $confirmationId, 'user_id' => $user->id()])->update([
        'token' => $encrypted,
        'is_confirmed' => false,
        'updated_at' => date('Y-m-d H:i:s')
      ]);

      if (!(bool) $query) {
        // This scenario should never occur, but who knows.
        return '';
      }

      return base64_encode($token);
    }

    $query = DB::table('user_confirmations')->insert([
      'user_id' => $user->id(),
      'confirmation_id' => $confirmationId,
      'token' => $encrypted,
      'is_confirmed' => false,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    if (!(bool) $query) {
      // This scenario should never occur, but who knows.
      return '';
    }

    return base64_encode($token);
  }

  /**
   * Gets an array of token information.
   */
  public static function get(string $type, User $user): array
  {
    $confirmationId = self::getConfirmationId($type);

    $query = DB::table('user_confirmations')->where(['confirmation_id' => $confirmationId, 'user_id' => $user->id()])->first();

    if (!isset($query->id)) {
      return [];
    }

    return [
      'id' => $query->id,
      'type' => $type,
      'type_id' => $confirmationId,
      'user' => $user,
      'token' => $query->token ?? '',
      'created_at' => $query->created_at ?? '',
      'is_confirmed' => (bool) ($query->is_confirmed ?? false)
    ];
  }

  /**
   * Validates if the token is valid.
   */
  public static function isValid(string $type, User $user, string $base64Token): bool
  {
    $decodedToken = base64_decode($base64Token);

    $entry = self::get($type, $user);

    if (empty($entry)) {
      return false;
    }

    $databaseToken = $entry['token'] ?? '';

    return Encryption::compare($decodedToken, $databaseToken, 'token', true);
  }

  /**
   * Marks the token as compliant and validated against the database.
   */
  public static function markConfirmed(string $type, User $user)
  {
    $entry = self::get($type, $user);

    return DB::table('user_confirmations')->where(['confirmation_id' => $entry['type_id'], 'user_id' => $user->id()])->update([
      'is_confirmed' => true
    ]);
  }

  /**
   * Gets the ID based on the name. If it does not exist, it creates a new one.
   */
  private static function getConfirmationId(string $confirmationName): int
  {
    return Cache::forever('user.confirmation_id.' . $confirmationName, function () use ($confirmationName) {
      $dbKey = DB::table('confirmation_types')->where(['name' => $confirmationName])->get(['id'])->first();

      if (isset($dbKey->id)) {
        return $dbKey->id;
      }

      return DB::table('confirmation_types')->insertGetId([
        'name' => $confirmationName
      ]);
    });
  }

  /**
   * Gets the name based on the ID.
   */
  private static function getConfirmationName(int $confirmationId): string
  {
    return Cache::remember('user.confirmation_name.' . $confirmationId, 120, function () use ($confirmationId) {
      $confirmation = DB::table('confirmation_types')->where('id', $confirmationId)->first();

      return (string) ($confirmation->name ?? '');
    });
  }
}
