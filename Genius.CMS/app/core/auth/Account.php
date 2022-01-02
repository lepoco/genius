<?php

namespace App\Core\Auth;

use App\Core\Facades\{App, Session, DB, Response, Cache};
use App\Core\Auth\User;
use App\Core\Data\Encryption;
use Illuminate\Support\Str;
use App\Core\Utils\Cast;

/**
 * Used to manage user accounts
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Account
{
  /**
   * If exists, returns an instance of the currently logged on user.
   */
  public static function current(): ?User
  {
    // We could keep the user in a static instance, but for security reasons
    // it seems to me that at the cost of performance it is better to check its consistency each time.

    if (!App::isConnected()) {
      return null;
    }

    if (!Session::get('auth.logged', false)) {
      return null;
    }

    $userId = Session::get('auth.id', 0);

    if ($userId < 1) {
      return null;
    }

    $user = new User($userId);

    // How to Attack Unauthenticated Encryption
    // https://paragonie.com/blog/2015/05/using-encryption-and-authentication-correctly#title.2.1

    if (!$user->isValid()) {
      return null;
    }

    if ($user->getRole() !== Session::get('auth.role', 0)) {
      return null;
    }

    if ($user->getUUID() !== Session::get('auth.uuid', '')) {
      return null;
    }

    if (!$user->compareCookieToken(Session::get('auth.token', ''))) {
      return null;
    }

    if (!$user->compareSessionToken(Session::token())) {
      return null;
    }

    return $user;
  }

  /**
   * Logs the user in.
   */
  public static function signIn(User $user): bool
  {
    $token = Encryption::salter(32);

    Session::put('auth.id', $user->getId());
    Session::put('auth.uuid', $user->getUUID());
    Session::put('auth.role', $user->getRole());
    Session::put('auth.logged', true);
    Session::put('auth.token', $token);

    Session::put('auth.confirmed', true);

    $user->updateTokens(Session::token(), $token);

    return true;
  }

  /**
   * Logs out the user by destroying the session and replacing the data.
   * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Clear-Site-Data
   */
  public static function signOut(): bool
  {
    $user = self::current();

    if (!empty($user)) {
      // Overwrite the tokens
      $user->updateTokens(Encryption::salter(32), Encryption::salter(32));
    }

    App::destroy();

    return true;
  }

  /**
   * Checks whether the selected user has the indicated rights. If the user is not provided, checks the currently logged in user.
   */
  public static function hasPermission(string $permission = 'read', ?User $user = null): bool
  {
    if (empty($user)) {
      $user = self::current();
    }

    if (empty($user)) {
      return false;
    }

    return Permission::isRoleHasPermission($user->getRole(), $permission);
  }

  /**
   * Checks whether the current user is logged in.
   */
  public static function isLoggedIn(): bool
  {
    return !empty(self::current());
  }

  /**
   * Checks whether the user with the given e-mail address exists.
   */
  public static function isRegistered(string $data, string $type = 'email'): bool
  {
    if ('email' === $type && DB::table('users')->get(['*'])->where('email', $data)->count() > 0) {
      return true;
    }

    return false;
  }

  /**
   * Retrieves the user by the specified key.
   */
  public static function getBy(string $type = 'email', int|string $data = ''): ?User
  {
    // TODO: User cache with flush if new
    $userId = Cache::remember('user.getby.' . $type . '_' . $data, 120, function () use ($type, $data) {
      if ('id' === $type) {
        return (int) $data;
      }

      $query = null;

      switch ($type) {
        case 'name':
          $query = DB::table('users')->where('name', $data)->first();
          break;

        case 'uuid':
          $query = DB::table('users')->where('uuid', $data)->first();
          break;

        case 'display_name':
          $query = DB::table('users')->where('display_name', $data)->first();
          break;

        default:
          $query = DB::table('users')->where('email', $data)->first();
          break;
      }

      if (!isset($query->id)) {
        return 0;
      }

      return $query->id;
    });

    if (0 === $userId) {
      return null;
    }

    return new User($userId);
  }

  /**
   * Registers the specified user object.
   */
  public static function register(User $user, string $encryptedPassword): bool
  {
    $users = DB::table('users')->get(['*'])->where('email', $user->getEmail());

    if ($users->count() > 0) {
      return false;
    }

    $userName = Cast::emailToUsername($user->getEmail());

    // TODO: Verify if salted name does not exist
    if (!empty(self::getBy('name', $userName))) {
      $userName .= '#' . Encryption::salter(4, 'N');
    }

    $user->setName($userName);

    return (bool) DB::table('users')->insert([
      'email' => $user->getEmail(),
      'name' => $user->getName(),
      'display_name' => $user->getDisplayName(),
      'role_id' => $user->getRole(),
      'uuid' => Str::uuid(),
      'password' => $encryptedPassword,
      'timezone' => 'UTC',
      'is_active' => true, // $user->isActive()
      'is_confirmed' => $user->isConfirmed(),
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
  }
}
