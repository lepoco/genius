<?php

namespace App\Core\Auth;

use App\Core\Facades\{App, Session, DB, Response, Cache};
use App\Core\Auth\User;
use App\Core\Data\Encryption;
use Illuminate\Support\Str;

/**
 * Used to manage user roles and permissions.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Permission
{
  /**
   * Checks whether a given role is authorized to perform actions.
   */
  public static function isRoleHasPermission(int $roleId, string $permission): bool
  {
    $rawPermissions = self::getRolePermissions($roleId);

    if (empty($rawPermissions)) {
      return false;
    }

    $decodedRoles = json_decode($rawPermissions, true);

    if (!isset($decodedRoles['p'])) {
      return false;
    }

    if (in_array('all', $decodedRoles['p'])) {
      return true;
    }

    return in_array($permission, $decodedRoles['p']);
  }

  /**
   * Gets the ID of a role based on its name.
   */
  public static function getRoleId(string $roleName): int
  {
    return Cache::remember('user.role_id.' . $roleName, 120, function () use ($roleName) {
      $role = DB::table('user_roles')->where('name', $roleName)->first();

      return (int) ($role->id ?? 1);
    });
  }

  /**
   * Gets the Name of a role based on its ID.
   */
  public static function getRoleName(int $roleId): string
  {
    return Cache::remember('user.role_id.' . $roleId, 120, function () use ($roleId) {
      $role = DB::table('user_roles')->where('id', $roleId)->first();

      return (string) ($role->name ?? 'default');
    });
  }

  /**
   * Gets list of permissions for selected role.
   */
  public static function getRolePermissions(int $roleId): string
  {
    return Cache::remember('user.role_permissions.' . $roleId, 120, function () use ($roleId) {
      $role = DB::table('user_roles')->where('id', $roleId)->first();

      return (string) ($role->permissions ?? '{"p":[]}');
    });
  }
}
