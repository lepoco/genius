<?php

namespace App\Common;

use App\Core\Http\Router;

/**
 * Abstraction for the router. Contains information about paths.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Routes extends Router
{
  /**
   * @var array $routes HTTP paths and Namespaces. The namespace should be in the Pascal Case (Studly Case).
   */
  protected array $routes = [

    // Public
    [
      'path' => '',
      'namespace' => 'Home',
      'redirect_logged' => true
    ],
    [
      'path' => '/register',
      'namespace' => 'Register',
      'redirect_logged' => true
    ],
    [
      'path' => '/signin',
      'namespace' => 'SignIn',
      'redirect_logged' => true
    ],

    // Dashboard | User
    [
      'path' => '/dashboard',
      'namespace' => 'Dashboard\\Main',
      'require_login' => true
    ],
    [
      'path' => '/dashboard/password',
      'namespace' => 'Dashboard\\Password',
      'require_login' => true
    ],
    [
      'path' => '/dashboard/account',
      'namespace' => 'Dashboard\\Account',
      'require_login' => true
    ],
    [
      'path' => '/dashboard/create',
      'namespace' => 'Dashboard\\Create',
      'require_login' => true
    ],

    // Admin panel
    [
      'path' => '/panel',
      'namespace' => 'Panel\\Main',
      'require_login' => true,
      'permissions' => ['all'],
      'redirect_no_permission' => 'dashboard'
    ],
    [
      'path' => '/panel/statistics',
      'namespace' => 'Panel\\Statistics',
      'require_login' => true,
      'permissions' => ['all'],
      'redirect_no_permission' => 'dashboard'
    ],
    [
      'path' => '/panel/users',
      'namespace' => 'Panel\\Users',
      'require_login' => true,
      'permissions' => ['all'],
      'redirect_no_permission' => 'dashboard'
    ],
    [
      'path' => '/panel/tools',
      'namespace' => 'Panel\\Tools',
      'require_login' => true,
      'permissions' => ['all'],
      'redirect_no_permission' => 'dashboard'
    ],
    [
      'path' => '/panel/settings',
      'namespace' => 'Panel\\Settings',
      'require_login' => true,
      'permissions' => ['all'],
      'redirect_no_permission' => 'dashboard'
    ]
  ];
}
