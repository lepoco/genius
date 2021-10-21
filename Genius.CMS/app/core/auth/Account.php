<?php

namespace App\Core\Auth;

use App\Core\Facades\{App, Session, DB};
use App\Core\Auth\User;
use App\Core\Data\Encryption;
use Illuminate\Support\Str;

/**
 * Used to manage user accounts
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Account
{
  public static function current(): ?User
  {
    if (!Session::get('auth.logged', false)) {
      return null;
    }

    $userId = Session::get('auth.id', 0);

    if ($userId < 1) {
      return null;
    }

    $user = new User($userId);

    if (!$user->isValid()) {
      return null;
    }

    if ($user->getRole() !== Session::get('auth.role', 0)) {
      return null;
    }

    if ($user->getUUID() !== Session::get('auth.uuid', '')) {
      return null;
    }

    if (!$user->compareCookieToken(Session::get('_token', ''))) {
      return null;
    }

    if (!$user->compareSessionToken(Session::get('auth.token', ''))) {
      return null;
    }

    return $user;
  }

  public static function signIn(User $user): bool
  {
    $token = Encryption::salter(32);

    Session::put('auth.id', $user->getId());
    Session::put('auth.uuid', $user->getUUID());
    Session::put('auth.role', $user->getRole());
    Session::put('auth.logged', true);
    Session::put('auth.token', $token);

    Session::passwordConfirmed();

    $user->updateTokens($token, Session::get('_token', ''));

    return true;
  }

  public static function signOut(): bool
  {
    App::destroy();

    return true;
  }

  public static function hasPermission(string $permission = 'read', ?User $user = null): bool
  {
    $user = $user ?? self::current();

    if (empty($user)) {
      return false;
    }

    return true;
  }

  public static function isRegistered(string $data, string $type = 'email'): bool
  {
    if ('email' === $type && DB::table('users')->get(['*'])->where('email', $data)->count() > 0) {
      return true;
    }

    return false;
  }

  public static function getBy(string $type = 'email', int|string $data = ''): ?User
  {
    $query = null;

    if ('id' === $type) {
      return new User((int) $data);
    }

    switch ($type) {
      case 'name':
        $query = DB::table('users')->where('name', $data)->first();
        break;

      case 'display_name':
        $query = DB::table('users')->where('display_name', $data)->first();
        break;

      default:
        $query = DB::table('users')->where('email', $data)->first();
        break;
    }

    if (empty($query)) {
      return null;
    }

    return new User($query->id);
  }

  public static function getRoleId(string $roleName): int
  {
    $role = DB::table('user_roles')->where('name', $roleName)->first();

    if (empty($role)) {
      return 1;
    }

    return (int) $role->id;
  }

  public static function getPlanId(string $planName): int
  {
    $plan = DB::table('plans')->where('name', $planName)->first();

    if (empty($plan)) {
      return 1;
    }

    return (int) $plan->id;
  }

  public static function register(User $user, string $encryptedPassword): bool
  {
    $users = DB::table('users')->get(['*'])->where('email', $user->getEmail());

    if ($users->count() > 0) {
      return false;
    }

    return (bool) DB::table('users')->insert([
      'email' => $user->getEmail(),
      'name' => $user->getName(),
      'display_name' => $user->getDisplayName(),
      'role_id' => $user->getRole(),
      'uuid' => Str::uuid(),
      'is_confirmed' => $user->isConfirmed(),
      'password' => $encryptedPassword,
      'timezone' => 'UTC',
      'is_active' => true
    ]);
  }
}
