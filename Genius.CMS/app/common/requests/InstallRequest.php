<?php

namespace App\Common\Requests;

use App\Core\View\Request;
use App\Core\Http\Status;
use App\Core\Facades\{App, Config};
use App\Core\Installer\Installer;

/**
 * Action triggered during app installation.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class InstallRequest extends Request implements \App\Core\Schema\Request
{
  private Installer $installer;

  public function getAction(): string
  {
    return 'Install';
  }

  public function process(): void
  {
    $this->isSet([
      'user',
      'password',
      'host',
      'database',
      'admin_email',
      'admin_password'
    ]);

    $this->isEmpty([
      'user',
      'host',
      'database',
      'admin_email',
      'admin_password'
    ]);

    $this->validate([
      ['user', self::SANITIZE_STRING],
      ['password', FILTER_UNSAFE_RAW],
      ['host', self::SANITIZE_STRING],
      ['database', self::SANITIZE_STRING],
      ['admin_email', FILTER_VALIDATE_EMAIL],
      ['admin_password', FILTER_UNSAFE_RAW]
    ]);

    if (!empty(Config::get('database.connections.default.database', ''))) {
      $this->addContent('message', 'Unauthorized access.');
      $this->finish(self::ERROR_ENTRY_EXISTS, Status::UNAUTHORIZED);

      return;
    }

    if (App::isConnected()) {
      $this->addContent('message', 'Unauthorized access.');
      $this->finish(self::ERROR_ENTRY_EXISTS, Status::UNAUTHORIZED);

      return;
    }

    $this->installer = new Installer();

    $this->installer->addData('database.host', $this->get('host'));
    $this->installer->addData('database.user', $this->get('user'));
    $this->installer->addData('database.password', $this->get('password'));
    $this->installer->addData('database.table', $this->get('database'));

    $this->installer->addData('user.email', $this->get('admin_email'));
    $this->installer->addData('user.password', $this->get('admin_password'));

    if (!$this->installer->run()) {
      $this->addContent('errors', $this->installer->getErrors());
      $this->addContent('message', 'Installation failed');

      $this->finish(self::ERROR_INTERNAL_ERROR, Status::UNPROCESSABLE_ENTITY);
    }

    $this->finish(self::CODE_SUCCESS, Status::OK);
  }
}
